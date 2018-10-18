<style>
.error{display:none;}
</style>
<div class="ExtraServiceAdmin_type-bound">
<form name="EditExtraServiceAdminForm" method="post" id="EditExtraServiceAdminForm">
    <input  name="deleted_ids" type="hidden"  id="deleted_ids" value="<?php echo Url::get('deleted_ids');?>"/>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
           	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" align="right" style="padding-right: 30px;"><input name="save" type="button" id="save" class="w3-btn w3-orange w3-text-white" value="[[.Save_and_close.]]" onclick="savefrom();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
			
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br>
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td class="label">[[.code.]](*):</td>
                  <td><input name="code" type="text" id="code" style="height: 24px;" />
                      <span class="error" id="error"></span>  
                  </td>
                </tr>
                <tr>
                  <td class="label">[[.name.]](*):</td>
                  <td><input name="name" type="text" id="name" style="height: 24px;"/></td>
                </tr>
                <tr>
                  <td class="label">[[.price.]]</td>
                  <td><input name="price" type="text" id="price" oninput="change_priceFc();" style="height: 24px;"/></td>
              </tr>
                <tr>
                  <td class="label">[[.unit.]]</td>
                  <td><input name="unit" type="text" id="unit" style="height: 24px;"/></td>
                </tr>
                <tr>
                  <td class="label">[[.department.]]</td>
                  <td>
                      <select name="department_id" id="department_id" style="height: 24px;"></select>
                  </td>
                </tr>
                <tr>
                  <td class="label">[[.use.]]</td>
                  <td>
                     <input name="used" type="checkbox" id="used" <?php if(isset([[=used=]]) && [[=used=]] !='') echo 'checked="checked"'; ?> value="1"/>
                  </td>
                </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>

<script>
var service_exist=<?php echo isset($_REQUEST['service_exist'])?String::array2js($_REQUEST['service_exist']):'{}';?>;
    var id_service = '';
<?php
if (Url::get('cmd') == 'edit') {
    $id_service = Url::get('id');
    ?>
        id_service = <?php echo $id_service; ?>;
    <?php
}
?>
 /*
 function check_code() {
        var code = jQuery("#code").val();

        jQuery.ajax({
            type: "POST",
            data: "code=" + code + "&id_service="+id_service,
            url: "get_package_service.php",
            success: function (data) {
                if (data == false)
                {  
                    return false;
                }
                else
                {    alert('OK');
                    return true;
                }
            }
        });
    }
  */  
  
    function savefrom() {
        var check = true;
        var code = jQuery("#code").val();
        if (code =='') {
            alert('Mã code không được rỗng');
        } else {
              console.log(service_exist);
            for(var i in service_exist)
                {
                if(code.trim() == service_exist[i]['code'].trim()){
                    check =false;break;
                } 
                }
             if(check==true)
                jQuery("#EditExtraServiceAdminForm").submit();
             else{
                jQuery("#code").val('');
                jQuery("#error").css("display", "inline-block");
                jQuery("#error").text('Mã '+code+' đã tồn tại');
                return false;
             }
                  
            /*
            jQuery.ajax({
                type: "POST",
                data: "code="+code,
                url: "get_package_service.php",
                success: function (result) {
                    if (result == 2)
                        jQuery("#EditExtraServiceAdminForm").submit();
                    else{
                        alert('Trùng mã dịch vụ');return false;
                    }
                        
                }
            });
            
            */
        }
    }   
</script>