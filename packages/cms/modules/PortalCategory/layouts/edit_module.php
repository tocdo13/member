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
<form name="ViewModuleForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="form-title"><?php if(Url::get('cmd')=='edit_module'){ echo 'Cập nhật '; }else { echo 'Thêm mới ';}?> module <strong style="color: blue; font-weight: bold;"><?php if(isset($_REQUEST['module_name'])) { echo $_REQUEST['module_name']; }?></strong></td>
            <td class="form_title_button"><input class="button-medium-save" name="save" type="submit" value="[[.save.]]"/></td>
            <td>
            <a href="<?php echo Url::build_current(array('cmd'=>'view_module','category_id'=>Url::get('category_id')));?>" class="button-medium-back" style="margin-right: 30px;">Quay lại</a>
            </td>
        </tr>
    </table>
    <div class="content">
    <span style="color: red; font-weight: bold; margin-left: 20px;"><?php if(isset($_REQUEST['error'])) { echo $_REQUEST['error']; }?></span>
    <fieldset>
        <table width="100%" border="0" cellspacing="0" cellpadding="2" align="left">
            <tr>
            <td></td>
            <td>
                <span><input name="name" type="hidden" id="name" style="width: 250px;"/></span>
                <span class="items_edit_module">Category:<select name="category" id="category" style="width:200px;"></select></span>
                <span class="items_edit_module">Nhân viên:<select name="staff_id_dev" id="staff_id_dev" style="width:150px;"></select></span>
                <span class="items_edit_module">Trạng thái 1:<select name="status_1" id="status_1" style="width:80px;"></select></span>
                <span class="items_edit_module">Trạng thái 2:<select name="status_2" id="status_2" style="width:80px;"></select></span>
            </td>
            </tr>
            <tr>
                <td valign="middle" width="100px;"><span style="font-weight: bold;">Tiêu đề:</span></td>
                <td><input name="title_module" type="text"  id="title_module" style="padding:0px;margin:0px;width:80%; height: 18px;" /></td>                              
            </tr>
            <tr>
                <td valign="top"><span style="font-weight: bold;">Mô tả quy trình:</span></td>
                <td><textarea name="description_module"  id="description_module" rows="5"  style="padding:0px;margin:0px;width:98%; height: 250px;" ></textarea></td>                              
            </tr>
            <tr>
                <td valign="top"><span style="font-weight: bold;">Ghi chú:</span></td>
                <td><textarea name="note_module"  id="note_module" rows="5" style="padding:0px;margin:0px;width:80%; height: 100px;"></textarea></td>                              
            </tr>
        </table>
    </fieldset>
    </div>
</form>
</div>

<script type="text/javascript">
document.getElementById('category').value ="<?php echo isset($_REQUEST['category_id'])?$_REQUEST['category_id']:1;?>";
document.getElementById('staff_id_dev').value="<?php echo isset($_REQUEST['user_id'])?$_REQUEST['user_id']:'ALL';?>";
<?php
	if(isset($_REQUEST['status_1_module']))
	{
		?>
		document.getElementById('status_1').value ="<?php echo $_REQUEST['status_1_module']?'OK':'NOTOK';?>";
		<?php 
	}
	else
	{
		?>
		document.getElementById('status_1').value ="ALL";
		<?php 
	}
?>
<?php
	if(isset($_REQUEST['status_2_module']))
	{
		?>
		document.getElementById('status_2').value ="<?php echo $_REQUEST['status_2_module']?'OK':'NOTOK';?>";
		<?php 
	}
	else
	{
		?>
		document.getElementById('status_2').value ="ALL";
		<?php 
	}
?>


/*START: giap.luunguyen non-is_developer04 disable staff, status_1,status_2
*****************************************************************************/ 
var is_developer04=<?php echo (User::id()=='developer04'|| User::id()=='giapln')?1:0;?>;
if(is_developer04==0)
{
    /**jQuery("#staff_id_dev option").not(":selected").attr("disabled", "disabled");
    jQuery('#staff_id_dev').css({"background-color":"#CCCCCC"});
    
    jQuery("#status_1 option").not(":selected").attr("disabled", "disabled");
    jQuery('#status_1').css({"background-color":"#CCCCCC"});
    
    jQuery("#status_2 option").not(":selected").attr("disabled", "disabled");
    jQuery('#status_2').css({"background-color":"#CCCCCC"});
    **/
}  
//END giap.luunguyen
</script>
