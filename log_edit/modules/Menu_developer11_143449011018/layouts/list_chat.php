<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div id='chatting'>
                <div id='room'></div>
                <div id='room-list' style="display: none;"></div>
                <div id='messages'></div>
                <form id='send-form' action="/" method="POST">
                    <div class="form-group">
                      <label for="comment">Comment:</label>
                      <textarea class="form-control" style="resize: none;" rows="5" id='send-message' name="sendmessage"></textarea>
                    </div>
                    <input id='send-button' type='submit' value='Send'/>
                    <input type="hidden" value="<?php echo User::id(); ?>" name="username" id="username" />
                    <input type="hidden" value="<?php echo User::id(); ?>" name="nickname" id="nickname" value="[[|nickname|]]" />
                    <div id='help' style="display: none;">
                        Chat commands:
                        <ul>
                            <li>Change nickname: <code>/nick [username]</code></li>
                            <li>Join/create room: <code>/join [room name]</code></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src='http://192.168.1.150:3000/socket.io/socket.io.js' type='text/javascript'></script>
<script src='packages/hotel/modules/Chatting/layouts/javascripts/chat.js' type='text/javascript'></script>
<script src='packages/hotel/modules/Chatting/layouts/javascripts/chat_ui.js' type='text/javascript'></script>
<script>
    var temp = {};
    setTimeout(function(){
        jQuery.when(
            jQuery.ajax({
              url: 'http://192.168.1.150:3000/test',
              dataType: 'jsonp'
            })
        ).then(function(result){
                temp = result;
                var str = "";
                var username = jQuery("#username").val();
                for(var i in temp)
                {            
                    if(username==temp[i]['username'])
                    {
                        str += "<div style='width:100%; height:30px; line-height:30px;'><span style='float:left;padding-left:10px;'>"+temp[i]['sendmessage']+"</span><span style='float:right; padding-right:10px;'>"+timeConverter(temp[i]['time'])+"</span></div>";
                    }
                    else
                    {
                        str += "<div style='width:100%; height:30px; line-height:30px;'><span style='float:left;padding-left:10px;'>"+temp[i]['username']+" : "+temp[i]['sendmessage']+"</span><span style='float:right; padding-right:10px;'>"+timeConverter(temp[i]['time'])+"</span></div>";
                    }
                    
                }
                jQuery("#messages").append(str);
            
        },function(xhr,status,error){
        });
        
    }, 500);
    function timeConverter(UNIX_timestamp){
      var a = new Date(UNIX_timestamp * 1000);
      var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      var year = a.getFullYear();
      var month = months[a.getMonth()];
      var date = a.getDate();
      var hour = a.getHours();
      var min = a.getMinutes();
      var sec = a.getSeconds();
      var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
      return time;
    }
</script>