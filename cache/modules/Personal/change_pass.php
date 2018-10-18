<div class="personal-bound">
	<div class="personal-content" style="width:980px;margin-left:auto;margin-right:auto;">
        <div class="setting-tab">
            <div class="bound normal"><div><div><a href="<?php echo Url::build_current()?>"><?php echo Portal::language('Account_information');?></a></div></div></div>
            <div class="bound selected"><div><div><a href="<?php echo Url::build_current(array('cmd'=>'change_pass'))?>"><?php echo Portal::language('Change_password');?></a></div></div></div>
        </div>
        <div class="setting-bound">    
        <?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
        <form name="ChangePassword" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>" id="ChangePassword">
            <table cellpadding="4" cellspacing="0" width="100%" style="#width:99%;margin-top:8px;" border="0" bordercolor="#E7E7E7" align="center">
                 <tr align="center">
                    <td colspan="3" style="height:10px"></td>
                </tr>
                <tr class="change_pass_text">
                    <td align="right" class="change_pass_text"><?php echo Portal::language('old_password');?></td>
                    <td align=left><input  name="old_password" id="old_password" class="input-large" type ="password" value="<?php echo String::html_normalize(URL::get('old_password'));?>"></td>
                </tr>
                <tr class="change_pass_text">
                    <td width=37% align="right" class="change_pass_text"><?php echo Portal::language('new_password');?></td>
                    <td width=63% align=left><input  name="new_password" id="new_password" class="input-large" type ="password" value="<?php echo String::html_normalize(URL::get('new_password'));?>"> <span style="color:red;font-style:italic;"><?php echo Portal::language('notify_pass');?></span></td>
                    
                </tr>
                <tr class="change_pass_text">
                    <td width=37% align="right"><?php echo Portal::language('retype_new_password');?></td>
                    <td width=63% align=left><input  name="retype_new_password" id="retype_new_password" class="input-large" type ="password" value="<?php echo String::html_normalize(URL::get('retype_new_password'));?>"></td>
                </tr>
            </table>
            <div class="personal-button" align="center"><input name="save" type="button" id="save" value="<?php echo Portal::language('Save');?>" onclick="savefrom();" />&nbsp;&nbsp;&nbsp;<input name="reset" type="reset" value="<?php echo Portal::language('Reset');?>" /></div>
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
        </div>
	</div>        
</div>
<script type="text/javascript">

jQuery('#new_password').on('change', function() {
    var newpass=this.value;
   //savefrom(newpass);
});

function savefrom(){
     var newpass= jQuery('#new_password').val();
     var typenewpass = jQuery('#retype_new_password').val();
        jQuery.ajax({
                type: "POST",
                data: "password="+newpass,
                url: "get_customer_search.php",
                success: function(result){ 
                    if(result == 2)
                    { 
                      alert('<?php echo Portal::language('notify_pass');?>');
                      return false;
                    }else
                    {
                        if(newpass==typenewpass){
                            document.getElementById("ChangePassword").submit();
                        }else{
                            alert('<?php echo Portal::language('diff_pass');?>'); return false;
                        }  
                    }    
                }                 
            });
}
    
/*
function checkPass(newpass){
     if(newpass==123456){
        alert('Bạn không được nhập password là 123456');
             // or $(this).val()
             // $("#yourTextBox").val('');
             this.value='';
            // jQuery(this).val('')
            return false;
    }else if(newpass.length<6){ 
        alert('Password phải chứa từ 8 ký tự trở nên.');
        return false;
      }else{
       
        jQuery.ajax({
                type: "POST",
                data: "password="+newpass,
                url: "get_customer_search.php",
                success: function(result){ 
                    if(result == 2)
                    { 
                      alert('Password phải bao gồm ký tự và chữ số.');
                      return false;
                    }else
                    {
                        return true;
                    }    
                }                 
            });
      }
}
*/
</script>