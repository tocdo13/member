<script language="javascript" src="packages/hotel/includes/js/ajax.js"></script>
<div class="chat-bound" id="chat-bound">
	<div class="chat-title">[[.Chat.]]<img src="skins/default/images/down_arrow.gif" id="imgClose" onclick="toggleChat();" width="12px" alt="[[.Close.]]"/></div>
	<div class="chat-content-bound">
    <div id="message_box" style="width:95%;height:100px;overflow-y:scroll;text-align:left;border:1px solid black;padding:5px;"></div>
    <table cellpadding="0" width="100%" style="margin-top:5px;">
    	<tr>
        	<td width="100%"><input type="text" id="message" style="width:80px;" onkeydown="if(event.keyCode==13){send_message($('message').value);$('message').value='';event.returnValue=false;}" /></td>
            <td><input type="button" onclick="send_message($('message').value);$('message').value='';" value="  Send  " english="1"/></td>
        </tr>
    </table>
    </div>
</div>
<script type="text/javascript">
	current_time = <?php echo time();?>;
	last_title = document.title;
	opened = true;
	function toggleChat()
	{
		jQuery('.chat-content-bound').slideToggle(500);
		if(opened){
			jQuery('#imgClose').attr('src','skins/default/images/up_arrow.gif');
			jQuery('#imgClose').attr('alt','[[.expand.]]');
			opened = false;
		}
		else{
			jQuery('#imgClose').attr('src','skins/default/images/down_arrow.gif');
			jQuery('#imgClose').attr('alt','[[.close.]]');
			opened = true;
		}
	}
	function send_message(message)
	{
		if(message)
		{
			<?php if(Session::is_set('user_id')){?>ajax.get_text('r_send_message.php?user_id=<?php echo Session::get('user_id');?>&message='+encodeMyHtml(message).replace(/%/g,';percent;'), set_message);
			//document.title=last_title;
			<?php }?>
			receive_message();
			$('message_box').scrollTop = $('message_box').scrollHeight-10;
		}
		else
		{
			alert('[[.message_is_required.]]!');
			$('message').focus();
		}
		return false;
	}
	function receive_message()
	{
		ajax.get_text('r_get_messages.php?time='+current_time, set_messages);
		window.setTimeout('receive_message();',10000);
		//current_time += 1;
		
	}
	last_messages = '';
	function set_messages(messages)
	{
		var temp = messages;
		messages = unescape(messages);
		if(messages.length>last_messages.length)
		{
			//document.title=messages.substr(last_messages.length).replace(/<br>/g,'').replace(/<strong>/g,'').replace(/<\/strong>/g,'').replace(/<\/font>/g,'').replace(/<font color="blue"/g,'').replace(/<font color="#999999">/g,'');
			last_messages = messages;
			$('message_box').innerHTML = messages;
			$('message_box').scrollTop = $('message_box').scrollHeight;
			$('message').focus();
		}
	}
	function set_message(message)
	{
		$('message_box').innerHTML += unescape(message.replace(';percent;','%'));
		last_messages += message;
	}
	window.setTimeout('receive_message();',1000);
	function encodeMyHtml(st) {
		return escape(st).replace(/\//g,"%2F").replace(/\?/g,"%3F").replace(/=/g,"%3D").replace(/@/g,"%40");
   }
</script>