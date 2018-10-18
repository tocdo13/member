var socketio = require('socket.io');
var io;


var handle = {};
var socket_rooms = {};

exports.listen = function (server) {
    io = socketio.listen(server); // Kh?i t?o socket IO trên server hi?n có lúc nãy ta start
    io.set('log level', 1);
    io.sockets.on('connection', function (socket) {// Function có ch?c nang handle connection

           socket.on('open_session', function (data) {
                socket.join(data.user_id);
                if(typeof handle[data.user_id] === 'undefined')
                {
                    handle[data.user_id] = {};
                    handle[data.user_id]['count_session'] = 1;
                    handle[data.user_id]['status_chat'] = 1; // Hien tat ca thanh vien
                }
                else
                {
                    handle[data.user_id]['count_session']++;
                    socket.emit('open_old_room', handle[data.user_id]);
                }
                
                socket.broadcast.emit('online', {user_on : data.user_id,stt_chat :  handle[data.user_id]['status_chat']});
                
                socket.to(socket.id).emit('get_online', {socket_rooms : handle});
                //socket.to(socket.id).emit('getUserStt_temp', {stt_chat :  handle[data.user_id]['status_chat']});
                
                socket_rooms[socket.id] = data.user_id;
           });
           
           socket.on('open_room', function (data) {    
                if(typeof handle[data.user_id] !== 'undefined')
                handle[data.user_id]['open_room'] =  data.open_room;
                
                if(typeof handle[data.user_id]['open_chat'] !== 'undefined')
                {
                    if(data.open_room==0)
                    {
                        for(var item_user in handle[data.user_id]['open_chat'])
                        {
                            handle[data.user_id]['open_chat'][item_user] = 0;
                        }
                    }
                }
                
                socket.broadcast.to(data.user_id).emit('open_room', {});              
           });
           
           socket.on('open_chat', function (data) {
                if(typeof handle[data.user_id] === 'undefined')
                {
                    handle[data.user_id] = {};
                    handle[data.user_id]['open_chat'] = {};
                    handle[data.user_id]['open_chat'][data.to_user] = 1;
                }
                else
                {
                    if(typeof handle[data.user_id]['open_chat'] === 'undefined')
                    {
                        handle[data.user_id]['open_chat'] = {};
                    }
                    else
                    handle[data.user_id]['open_chat'][data.to_user] = 1;
                }

                socket.broadcast.to(data.user_id).emit('open_chat', data);
           });  
           
           socket.on('close_chat', function (data) {
                if(typeof handle[data.user_id]['open_chat'] !== 'undefined')
                {
                    //handle[data.user_id]['open_chat'] = {};
                    handle[data.user_id]['open_chat'][data.to_user] = 0;
                }

                socket.broadcast.to(data.user_id).emit('close_chat', data);
           });
           
           socket.on('send_mes', function (data) {
            //console.log(handle[data.to_user]);
                data['action'] = 'current';
                socket.broadcast.to(data.user_id).emit('send_mes', data);
                data['action'] = 'from_user';
                data['from_user'] = data.user_id;
                
                if(typeof handle[data.to_user] === 'undefined')
                {
                    handle[data.to_user] = {};
                    handle[data.to_user]['open_room'] =  1;
                    handle[data.to_user]['open_chat'] = {};
                    handle[data.to_user]['open_chat'][data.user_id] = 1;
                }
                else
                {
                    handle[data.to_user]['open_room'] =  1;
                    if(typeof handle[data.to_user]['open_chat'] === 'undefined')
                    {
                        handle[data.to_user]['open_chat'] = {};
                        handle[data.to_user]['open_chat'][data.user_id] = 1;
                    }
                    else
                    {
                        handle[data.to_user]['open_chat'][data.user_id] = 1;
                    }
                }
                socket.broadcast.to(data.to_user).emit('send_mes', data);
           }); 
           
           
           socket.on('getUserStt', function(data) {
              
              if(typeof handle[data.user_id]!== 'undefined')
              {
                    //handle[data.user_id]['open_chat'] = {};
                    handle[data.user_id]['status_chat'] = data.status_chat;
              }

              socket.broadcast.to(data.user_id).emit('getUserStt_temp', {user_id:data.user_id,stt_chat : data.status_chat});
              
           });
                        
           socket.on('disconnect', function() {
              
              var current_room = socket_rooms[socket.id];
              if(typeof handle[current_room] !== 'undefined')
              {
                  handle[current_room]['count_session']--;
                  //console.log(handle[current_room]['count_session']);
                  if(handle[current_room]['count_session']<=0)
                  {
                     socket.broadcast.emit('offline', {user_off : current_room,stt_chat :  handle[current_room]['status_chat']});
                  }
              }
           });                      
    });

};
