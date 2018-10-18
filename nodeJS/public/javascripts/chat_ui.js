function divEscapedContentElement(message) {
    return $('<div></div>').text(message);
}

function divSystemContentElement(message) {
    return $('<div></div>').html('<a>' + message + '</a>');
    
}

function processUserInput(chatApp, socket) {
    var message = $('#send-message').val();
    var systemMessage;
    //console.log(message);
    //return false;
    if (message.charAt(0) == '/') { // Neu user ghi ky tu dau tien la '/' thi se coi no la command
        systemMessage = chatApp.processCommand(message);
        if (systemMessage) {
            $('#messages').append(divSystemContentElement(systemMessage));
        }
    } else {
        //
        
        
        chatApp.sendMessage($('#room').text(), message);
        $('#messages').append(divEscapedContentElement(message));
        $('#messages').scrollTop($('#messages').prop('scrollHeight'));
    }

    $('#send-message').val('');
}

var socket = io.connect();

$(document).ready(function () {
    var chatApp = new Chat(socket);

    socket.on('nameResult', function (result) { // Hi?n th? t�n khi d?i t�n
        var message;

        if (result.success) {
            message = 'You are now known as ' + result.name + '.';
        } else {
            message = result.message;
        }
        $('#messages').append(divSystemContentElement(message));
    });

    socket.on('joinResult', function (result) { // �?i ph�ng
        $('#room').text(result.room);
        $('#messages').append(divSystemContentElement('Room changed.'));
    });

    socket.on('message', function (message) { // Mesage nh?n du?c l� g�
        var newElement = $('<div></div>').text(message.text);
        $('#messages').append(newElement);
    });

    socket.on('rooms', function (rooms) { // Hi?n th? c�c ph�ng dang c�
        $('#room-list').empty();
        //console.log(rooms);
        for (var room in rooms) {
            room = room.substring(1, room.length);
            if (room != '') {
                $('#room-list').append(divEscapedContentElement(room));
            }
        }

        $('#room-list div').click(function () { // Khi click v�o room th� cho join v�o room n�y
            chatApp.processCommand('/join ' + $(this).text());
            $('#send-message').focus();
        });
    });

    setInterval(function () { // M?i v�o th� s? request danh s�ch phong dang c� tr�n server.
        socket.emit('rooms');
    }, 1000);

    $('#send-message').focus();

    $('#send-form').submit(function () { // Khi nh?n message th� submit n� t?i server.
        processUserInput(chatApp, socket);
        return false;
    });
});