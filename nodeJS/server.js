var http = require('http'),

socketIO = require('socket.io'),

port = 3000,

ip = '192.168.1.150',

server = http.createServer().listen(port, ip, function(){

console.log('Socket.IO server started at %s:%s!', ip, port);

}),

io = socketIO.listen(server);

//io.set('match origin protocol', true);

io.set('origins', '*:*');

//io.set('log level', 1);

var run = function(socket){
// Socket process here!!!
//socket.emit('greeting', 'Hello from Socket.IO');
//// 'user-join' event handler here
//socket.on('user-join', function(data){
//
//console.log('User %s have joined', data);
//socket.broadcast.emit('new-user', data);
//
//})
//socket.on('cal',function(data){
//    var result = eval(data);
//    console.log(result);
//    socket.broadcast.emit('result',result);
//    socket.emit('result',result);
//});
console.log(socket.id);
socket.on('call_eating',function(data){
    
    socket.broadcast.emit('recei_eating',data);
});
socket.on('move_eating',function(data){
    //console.log(data);
    socket.broadcast.emit('recei_move_eating',data);
});
socket.on('back_eating',function(data){
    //console.log(data);
    socket.broadcast.emit('recei_back_eating',data);
});
socket.on('remove_eating',function(data){
    socket.broadcast.emit('recei_remove_eating',data);
});
socket.on('checkout',function(data){
    console.log("checkout : "+data);
    socket.emit('recei_checkout',data);
    socket.broadcast.emit('recei_checkout',data);
});
}
console.log("dasd");
io.sockets.on('connection', run);