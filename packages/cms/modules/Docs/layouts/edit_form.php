<style>
.simple-layout-middle{width:100%;}

.items_edit_module
{
    margin-left: 20px;
}
textarea,input {
    font-size: 16px;
}
</style>

<div class="warehouse-bound">
<form name="ViewFormForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="form-title"><?php if(Url::get('cmd')=='edit_form'){ echo 'Cập nhật ';} else { echo 'Thêm mới '; }?> Form <strong style="color: blue; font-weight: bold;"><?php if(isset($_REQUEST['form_name'])) { echo $_REQUEST['form_name']; }?></strong></td>
            <td class="form_title_button"><input class="button-medium-save" name="save" type="submit" value="[[.save.]]" /></td>
            
            <td>
            <a href="<?php echo Url::build_current(array('cmd'=>'view_module','module_id'=>$_REQUEST['module_parent']));?>" class="button-medium-back" style="margin-right: 30px;">Quay lại</a>
            </td>
        </tr>
    </table>
   
    <input name="module_id" type="hidden" id="module_id">
    <div class="content">
    <fieldset>
        <table width="100%" border="0" cellspacing="0" cellpadding="2" align="left">
            <tr>
            <td></td>
            <td>
                <span>Name:<input name="form_name" type="text" id="form_name" style="width: 250px;"/></span>
                <span class="items_edit_module">Nhân viên:<select name="staff_id_dev" id="staff_id_dev" style="width:150px;"></select></span>
                <span class="items_edit_module" >Trạng thái 1:<select name="status_1" id="status_1" style="width:80px;"></select></span>
                <span class="items_edit_module">Trạng thái 2:<select name="status_2" id="status_2" style="width:80px;"></select></span>
            </td>
            </tr>
            <tr>
                <td valign="middle" width="100px;"><span style="font-weight: bold;">Tiêu đề:</span></td>
                <td><input name="title_form" type="text"  id="title_form" style="padding:0px;margin:0px;width:80%; height: 18px;" /></td>                              
            </tr>
            <tr>
                <td valign="top"><span style="font-weight: bold;">Mô tả quy trình:</span></td>
                <td><textarea name="description_form"  id="description_form" rows="5"  style="padding:0px;margin:0px;width:98%; height: 250px;" ></textarea></td>                              
            </tr>
            
            <tr>
                <td valign="top"><span style="font-weight: bold;">Diễn giải code:</span></td>
                <td><textarea name="interpret_code"  id="interpret_code" rows="5"  style="padding:0px;margin:0px;width:98%; height: 250px;" ></textarea></td>                              
            </tr>
            
            <tr>
                <td valign="top"><span style="font-weight: bold;">Ghi chú:</span></td>
                <td><textarea name="note_form"  id="note_form" rows="5" style="padding:0px;margin:0px;width:80%; height: 100px;"></textarea></td>                              
            </tr>
        </table>
        </fieldset>
    </div>
</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
<?php
	if(isset($_REQUEST['status_1_form']))
	{
		?>
		document.getElementById('status_1').value ="<?php echo $_REQUEST['status_1_form']?'OK':'NOTOK';?>";
		<?php 
	}
	else
	{
		?>
		document.getElementById('status_1').value ="ALL";
		<?php 
	}

	if(isset($_REQUEST['status_2_form']))
	{
		?>
		document.getElementById('status_2').value ="<?php echo $_REQUEST['status_2_form']?'OK':'NOTOK';?>";
		<?php 
	}
	else
	{
		?>
		document.getElementById('status_2').value ="ALL";
		<?php 
	}
?>
document.getElementById('staff_id_dev').value=<?php echo isset($_REQUEST['staff_id_dev'])?$_REQUEST['staff_id_dev']:'ALL';?>;
})

/*START: non-is_developer04  status_1,status_2
*****************************************************************************/ 
var is_developer04=<?php echo (User::id()=='developer04' || User::id()=='giapln')?1:0;?>;
if(is_developer04==0)
{
    
    /*jQuery("#status_1 option").not(":selected").attr("disabled", "disabled");
    jQuery('#status_1').css({"background-color":"#CCCCCC"});
    
    jQuery("#status_2 option").not(":selected").attr("disabled", "disabled");
    jQuery('#status_2').css({"background-color":"#CCCCCC"});*/
}
//END

/*START: non-is_developer04, is non module_user_id
*******************************************************/
var is_module_user_id =0;
<?php
	if(isset($_REQUEST['module_user_id']))
	{
		?>
		var is_module_user_id=<?php echo (User::id()==$_REQUEST['module_user_id'])?1:0; ?>;
		<?php 
	}
?>

if(is_developer04==0 && is_module_user_id==0)
{
	/*jQuery("#staff_id_dev option").not(":selected").attr("disabled", "disabled");
    jQuery('#staff_id_dev').css({"background-color":"#CCCCCC"});*/
}

//END 
</script>
