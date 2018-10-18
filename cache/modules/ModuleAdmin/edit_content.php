<script src="packages/core/modules/ModuleAdmin/edit_module.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit_code')?Portal::language('edit_code_title'):Portal::language('edit_code_title');
$action = (URL::get('cmd')=='edit_code')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title);
?>
<br />
<div class="form-bound">
<table cellpadding="0" width="100%">
	<tr>
    	<td class="form-title"><?php echo $title;?> module <?php echo $this->map['name'];?></td>
        <td class="form_title_button"><a  onclick="EditModuleContentAdminForm.submit();"><img src="<?php echo Portal::template('core').'/images/buttons/';?>save_button.gif" style="text-align:center"/><br /><?php echo Portal::language('save');?></a></td>
        <td>&nbsp;</td>
    <td>
   		<a  onclick="location='<?php echo URL::build_current();?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>go_back_button.gif"/><br /><?php echo Portal::language('back');?></a></td>
    <?php if($action=='edit'){?><td class="form_title_button">
		<a  onclick="location='<?php echo URL::build_current(array('cmd'=>'edit','id'));?>';"><img src="<?php echo Portal::template('core').'/images/buttons/';?>edit_button.gif"/><br /><?php echo Portal::language('Edit');?></a></td><?php }?>
    <td class="form_title_button">
		<a target="_blank" href="<?php echo URL::build('room_map');?>"><img src="<?php echo Portal::template('core').'/images/buttons/';?>frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td></tr></table>
		<div>
			<?php if(Form::$current->is_error()){?><strong>B&#225;o l&#7895;i</strong><br><?php echo Form::$current->error_messages();?><br><?php }?>		
          	<form name="EditModuleContentAdminForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>" enctype="multipart/form-data">
			<br />
            <table cellspacing="0" width="100%">
                <tr>
                    <td>
	                    Upload File from <input  name="from_file" id="from_file" type ="file" value="<?php echo String::html_normalize(URL::get('from_file'));?>"> to folder <input  name="to_folder" id="to_folder" style="width:230px;border:1px solid #CCCCCC;background:#CFF" type ="text" value="<?php echo String::html_normalize(URL::get('to_folder'));?>"> <input  name="upload_file" value="Copy FIle" type ="submit" value="<?php echo String::html_normalize(URL::get('upload_file'));?>"><br />
                        Create File &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  name="file" id="file" style="width:515px;border:1px solid #CCCCCC;background:#CFC" type ="text" value="<?php echo String::html_normalize(URL::get('file'));?>"> <input  name="create_file" value="Create FIle" type ="submit" value="<?php echo String::html_normalize(URL::get('create_file'));?>"><br />
                    	File path &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  name="path" id="path" style="width:600px;border:1px solid #CCCCCC;background:#FFFF99" onchange="updateContent(this.value);" type ="text" value="<?php echo String::html_normalize(URL::get('path'));?>">
                        <div style="padding:10px;">
                        	<a href="#" onclick="$('path').value=this.innerHTML" style="font-weight:bold;text-decoration:underline;">index.php</a> |
                        	<a href="#" onclick="$('path').value=this.innerHTML" style="font-weight:bold;text-decoration:underline;">packages/core/includes/system/</a> |
                            <a href="#" onclick="$('path').value=this.innerHTML" style="font-weight:bold;text-decoration:underline;">packages/hotel/packages/</a>
                        </div>
                   	</td>
                </tr>
                <tr>
                    <td>
                        <textarea  name="content" id="content" style="width:970px;height:300px;" onkeydown="if(edit_code_keypress(this)){ if(document.all)event.returnValue=false;else return false;}"><?php echo String::html_normalize(URL::get('content',''.$this->map['content']));?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                    	SQL STATEMENT <input  name="execute_sql" value="Execute" / type ="submit" value="<?php echo String::html_normalize(URL::get('execute_sql'));?>"><br />
                        <textarea  name="sql_statement" id="sql_statement" style="width:970px;height:50px;background-color:#E3E4C9;color:#03C;border:1px solid #CCC;" onkeydown="if(edit_code_keypress(this)){ if(document.all)event.returnValue=false;else return false;}"><?php echo String::html_normalize(URL::get('sql_statement',''));?></textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="1" name="confirm_edit"/>
        	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
		</div>
</div>
<script>
	function updateContent(path){
		jQuery.ajax({
			type: "POST",
			url: "form.php?block_id=<?php echo Module::block_id();?>",
			data: ({
					'block_id':'<?php echo Module::block_id();?>'
					,'path':path
					,'cmd':'get_path_content'
				}),
			success: function(msg){
				jQuery('#content').html(msg);
			}
		});
	}
</script>