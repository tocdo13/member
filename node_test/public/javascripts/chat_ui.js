function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function divEscapedContentElement(message) {
    var d = new Date();
    var h = addZero(d.getHours());
    var m = addZero(d.getMinutes());
    var str = "<div style='width:100%; height:30px; line-height:30px;'><span style='float:left;padding-left:10px;'>"+message+"</span><span style='float:right; padding-right:10px;'>"+h+":"+m+"</span></div>";
    return str;
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

    socket.on('nameResult', function (result) { // Hi?n th? tên khi d?i tên
        var message;

        if (result.success) {
            message = 'You are now known as ' + result.name + '.';
        } else {
            message = result.message;
        }
        $("#username").val(result.name);
        $('#messages').append(divSystemContentElement(message));
    });

    socket.on('joinResult', function (result) { // Ð?i phòng
        $('#room').text(result.room);
        $('#messages').append(divSystemContentElement('Room changed.'));
    });

    socket.on('message', function (message) { // Mesage nh?n du?c là gì
        var newElement = $('<div></div>').text(message.text);
        $('#messages').append(newElement);
    });

    socket.on('rooms', function (rooms) { // Hi?n th? các phòng dang có
        $('#room-list').empty();
        //console.log(rooms);
        for (var room in rooms) {
            room = room.substring(1, room.length);
            if (room != '') {
                $('#room-list').append("<div>"+room+"</div>");
            }
        }

        $('#room-list div').click(function () { // Khi click vào room thì cho join vào room này
            chatApp.processCommand('/join ' + $(this).text());
            $('#send-message').focus();
        });
    });

    setInterval(function () { // M?i vào thì s? request danh sách phong dang có trên server.
        socket.emit('rooms');
    }, 1000);

    $('#send-message').focus();

    $('#send-form').submit(function () { // Khi nh?n message thì submit nó t?i server.
        //return false;
        //var message = $('#send-message').val();
        event.preventDefault(); // Stops browser from navigating away from page
        //var data_temp = $("#send-form").serialize();
        //data_temp["time_send"] = (Math.floor(Date.now() / 1000));
        $.post("/", $("#send-form").serialize(), function(data) {
            //alert(data);
            //data
        });
        processUserInput(chatApp, socket);
    });
});