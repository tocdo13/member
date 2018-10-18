<form name="choose_customer" method="POST">
    
    <table style="width: 100%; padding-top:20px;">
        <tr>
            <td class="form-title" style="text-align:left; height: 40px; width:50%;">[[.group_event.]]</td>
            <td style="text-align:right;"><input name="save" class="button-medium-save"  type="submit"  value="[[.save.]]" />
            <input type="button" onclick="window.close();" class="button-medium-back" value="[[.close.]]" /></td>
        </tr>
    </table>    
    <br />
    
    <div>
        <label style="color: red;">[[.customer.]]:</label>
        <select name="group_customer" id="group_customer" style="width: 200px;"></select>
        <label style="color: #0066CC">[[.gender.]]: </label>
        <select name="gender" id="gender"></select>
        <label style="color: #0066CC">[[.guest_type.]]</label>
        <select name="traveller_level" id="traveller_level" style="width: 200px;">
        </select>
        <label style="color: #0066CC">[[.country.]]</label>
        <select name="country_option" id="country_option" style="width: 200px;">	
        </select>
        <input name="search" type="submit" value="search"  />
     </div>
    
    <div class="content-top" style="width: 100%; clear: both; overflow: hidden;">
        <div class="content-left" style="width: 50%;float: left;">
            <h3 class="title">[[.customer.]]</h3>
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
                <tr class="table-header">
                    <td>[[.stt.]]</td>
                    <td>
                        <input type="checkbox" name="check_all_customer" id="check_all_customer" />
                    </td>
                    
                    <td>[[.customer_name.]]</td>
                    <td>[[.customer_email.]]</td>
                </tr>
                <?php $k=1; ?>
                <!--LIST:list_customer-->
                <tr>
                    <td><?php echo $k++; ?></td>
                    <?php if([[=list_customer.check=]]!=1){ ?>
                    <td><input type="checkbox" name="customer_[[|list_customer.id|]]" value="[[|list_customer.id|]]" class="check_items_customer" /></td>
                    <?php }else{ ?>
                    <td><input type="checkbox" name="customer_[[|list_customer.id|]]" value="[[|list_customer.id|]]"  class="check_items_customer" checked="checked" /></td>
                    <?php } ?>
                    <td>[[|list_customer.name|]]</td>
                    <td>[[|list_customer.email|]]</td>
                </tr>
                <!--/LIST:list_customer-->
            </table>
        </div>
        
        
        <div class="content-left" style="width: 50%;float: left;">
            <h3 class="title">[[.traveller.]]</h3>
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
                <tr class="table-header">
                    <td>[[.stt.]]</td>
                    <td>
                        <input type="checkbox" name="check_all_traveller" id="check_all_traveller" />
                    </td>
                    
                    <td>[[.customer_name.]]</td>
                    <td>[[.customer_email.]]</td>
                </tr>
                <?php $k=1; ?>
                <!--LIST:list_traveller-->
                <tr>
                    <td><?php echo $k++; ?></td>
                    <?php if([[=list_traveller.check=]]!=1){ ?>
                    <td><input type="checkbox" name="traveller_[[|list_traveller.id|]]" value="[[|list_traveller.id|]]" class="check_items_traveller" /></td>
                    <?php }else{ ?>
                    <td><input type="checkbox" name="traveller_[[|list_traveller.id|]]" value="[[|list_traveller.id|]]"  class="check_items_traveller" checked="checked" /></td>
                    <?php } ?>
                    <td>[[|list_traveller.fullname|]]</td>
                    <td>[[|list_traveller.email|]]</td>
                </tr>
                <!--/LIST:list_traveller-->
            </table>
        </div>
    
   </div>
</form>
<script>
    
    jQuery("#check_all_customer").click(function (){
		var check  = this.checked;
		jQuery(".check_items_customer").each(function(){
			this.checked = check;
		});
	});
    jQuery("#check_all_traveller").click(function (){
		var check  = this.checked;
		jQuery(".check_items_traveller").each(function(){
			this.checked = check;
		});
	});
</script>