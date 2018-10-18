var socketio = require('socket.io');
var io;
var guestNumber = 1;
var nickNames = {};
var namesUsed = [];
var currentRoom = {};

exports.listen = function (server) {
    io = socketio.listen(server); // Kh?i t?o socket IO tr�n server hi?n c� l�c n�y ta start
    io.set('log level', 1);
    io.sockets.on('connection', function (socket) {// Function c� ch?c nang handle connection
        guestNumber = assignGuestName(socket, guestNumber, nickNames, namesUsed); // G�n t�n cho ai d� khi h? truy c?p
        joinRoom(socket, 'Ha Long'); // Cho user d� v�o trong c�i ph�ng m?c
        handleMessageBroadcasting(socket, nickNames); // Handle user t?o nickname
        handleNameChangeAttempts(socket, nickNames, namesUsed); // Handle user d?i nickname
        handleRoomJoining(socket); // handle user t?o ph�ng chat
        socket.on('rooms', function () { // Cung c?p user danh s�ch ph�ng chat 
            //console.log(io.sockets.manager.rooms);
            socket.emit('rooms', io.sockets.manager.rooms);
        });
        handleClientDisconnection(socket, nickNames, namesUsed); // User kh�ng k?t n?i n?a 
    });
};

function assignGuestName(socket, guestNumber, nickNames, namesUsed) {
    var name = 'Guest' + guestNumber; // T?o t�n
    nickNames[socket.id] = name; // G�n t�n theo connection id cho m?t m?ng nickNames
    socket.emit('nameResult', { // Gui cho trinh duyet ten user moi join v�o chat.
        success: true,
        name: name
    });
    namesUsed.push(name); // Ghi chu user m?i du?c t?o l� user hi?n t?i du?c s? d?ng.
    return guestNumber + 1; // Tang 1 user 
}

function joinRoom(socket, room) {
    socket.join(room); // T?o user join v�o room chat.
    currentRoom[socket.id] = room; // Ghi ch� user dang ? trong ph�ng chat.
    socket.emit('joinResult', {room: room}); // Th�ng b�o cho user l� h? dang ? trong ph�ng chat
    socket.broadcast.to(room).emit('message', { // Th�ng b�o cho c�c user kh�c trong ph�ng l� m?i c� ngu?i joined v�o
        text: nickNames[socket.id] + ' has joined ' + room + '.'
    });

    var usersInRoom = io.sockets.clients(room); // L?y ra users dang ? trong ph�ng
    if (usersInRoom.length > 1) { // T?ng h?p th�ng tin user dang ? trong ph�ng
        var usersInRoomSummary = 'Users currently in ' + room + ': ';
        for (var index in usersInRoom) {
            var userSocketId = usersInRoom[index].id;
            if (userSocketId != socket.id) {
                if (index > 0) {
                    usersInRoomSummary += ', ';
                }
                usersInRoomSummary += nickNames[userSocketId];
            }
        }
        usersInRoomSummary += '.';
        socket.emit('message', {text: usersInRoomSummary}); // G?i t?i user m?i du?c join v? nh?ng user n�y.
    }
    
}

function handleNameChangeAttempts(socket, nickNames, namesUsed) {
    socket.on('nameAttempt', function (name) {
        if (name.indexOf('Guest') == 0) {
            socket.emit('nameResult', {
                success: false,
                message: 'Names cannot begin with "Guest".'
            });
        } else {
            if (namesUsed.indexOf(name) == -1) {
                var previousName = nickNames[socket.id];
                var previousNameIndex = namesUsed.indexOf(previousName);
                namesUsed.push(name);
                nickNames[socket.id] = name;
                delete namesUsed[previousNameIndex];
                socket.emit('nameResult', {
                    success: true,
                    name: name
                });
                socket.broadcast.to(currentRoom[socket.id]).emit('message', {
                    text: previousName + ' is now known as ' + name + '.'
                });
            } else {
                socket.emit('nameResult', {
                    success: false,
                    message: 'That name is already in use.'
                });
            }
        }
    });
}

function handleMessageBroadcasting(socket) {
    socket.on('message', function (message) {
        socket.broadcast.to(message.room).emit('message', {
            text: nickNames[socket.id] + ': ' + message.text
        });
    });
}

function handleRoomJoining(socket) {
    socket.on('join', function (room) {
        socket.leave(currentRoom[socket.id]);
        joinRoom(socket, room.newRoom);
    });
}

function handleClientDisconnection(socket) {
    socket.on('disconnect', function () {
        var nameIndex = namesUsed.indexOf(nickNames[socket.id]);
        delete namesUsed[nameIndex];
        delete nickNames[socket.id];
    });
}