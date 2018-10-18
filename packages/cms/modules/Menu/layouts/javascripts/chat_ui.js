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
    return jQuery('<div></div>').html('<a>' + message + '</a>');
    
}

function processUserInput(chatApp, socket) {
    var message = jQuery('#send-message').val();
    var systemMessage;
    //console.log(message);
    //return false;
    if (message.charAt(0) == '/') { // Neu user ghi ky tu dau tien la '/' thi se coi no la command
        systemMessage = chatApp.processCommand(message);
        if (systemMessage) {
            jQuery('#messages').append(divSystemContentElement(systemMessage));
        }
    } else {
        //    
        chatApp.sendMessage(jQuery('#room').text(), message);
        jQuery('#messages').append(divEscapedContentElement(message));
        jQuery('#messages').scrollTop(jQuery('#messages').prop('scrollHeight'));
    }

    jQuery('#send-message').val('');
}

var socket = io.connect("http://192.168.1.150:3000");

jQuery(document).ready(function () {

    var chatApp = new Chat(socket);

    socket.on('nameResult', function (result) { // Hi?n th? tên khi d?i tên
        var message;

        if (result.success) {
           // message = 'You are now known as ' + result.name + '.';
        } else {
            message = result.message;
        }
        //jQuery("#username").val(result.name);
        jQuery('#messages').append(divSystemContentElement(message));
    });

    socket.on('joinResult', function (result) { // Ð?i phòng
        jQuery('#room').text(result.room);
        //jQuery('#messages').append(divSystemContentElement('Room changed.'));
    });

    socket.on('message', function (message) { // Mesage nh?n du?c là gì
        var newElement = jQuery('<div></div>').text(message.text);
        jQuery('#messages').append(newElement);
    });

    socket.on('rooms', function (rooms) { // Hi?n th? các phòng dang có
        jQuery('#room-list').empty();
        //console.log(rooms);
        for (var room in rooms) {
            room = room.substring(1, room.length);
            if (room != '') {
                jQuery('#room-list').append("<div>"+room+"</div>");
            }
        }

        jQuery('#room-list div').click(function () { // Khi click vào room thì cho join vào room này
            chatApp.processCommand('/join ' + jQuery(this).text());
            jQuery('#send-message').focus();
        });
    });

    setInterval(function () { // M?i vào thì s? request danh sách phong dang có trên server.
        socket.emit('rooms');
    }, 1000);

    jQuery('#send-message').focus();

    jQuery('#send-form').submit(function () { // Khi nh?n message thì submit nó t?i server.
        //return false;
        //var message = jQuery('#send-message').val();
        event.preventDefault(); // Stops browser from navigating away from page
        //var data_temp = jQuery("#send-form").serialize();
        //data_temp["time_send"] = (Math.floor(Date.now() / 1000));
        jQuery.post("http://192.168.1.150:3000/", jQuery("#send-form").serialize(), function(data) {
        });
        processUserInput(chatApp, socket);
    });
});