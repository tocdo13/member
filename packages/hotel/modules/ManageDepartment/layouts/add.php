<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_department'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="60%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
					<td width="40%" align="right"><a class="w3-orange w3-text-white w3-btn" onClick="AddManageDepartmentForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.save.]]</a>
					<a class="w3-btn w3-green" onClick="history.go(-1)" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	
    <tr valign="top">
    	<td>
        	<form name="AddManageDepartmentForm" method="post" >
            	<div class="search-box">
                	<fieldset>
                    	<legend class="title">[[.select.]]</legend>
                        <span>[[.parent_name.]]:</span> 
                		<select name="parent_id" id="parent_id"></select>
                    </fieldset>
                </div>
                <table width="100%" cellpadding="0">
                    <?php 
                    if(Form::$current->is_error())
                	{
                	?>
                    <tr valign="top">
                        <td bgcolor="#C8E1C3"><?php echo Form::$current->error_messages();?></td>
                	</tr>
                	<?php
                	}
                	?>
                    
                	<tr valign="top">
                		<td>
            				<span id="mi_product_all_elems">
            					<span>
            						<span class="multi-input-header" style="width:51px;">[[.code.]]</span>
            						<!--LIST:languages-->
            						<span class="multi-input-header" style="width:152px;">[[.name.]]([[|languages.code|]])</span>
            						<!--/LIST:languages-->
                                    <span class="multi-input-header" style="width:82px;">[[.area_id.]]</span>
                                    <?php if(User::is_admin()){?>
            						<span class="multi-input-header" style="width:81px;">[[.account_revenue_code.]]</span>
            						<span class="multi-input-header" style="width:80px;">[[.account_deposit_code.]]</span>
                                    <?php }?>
                                    <span class="multi-input-header" style="width:100px;">[[.mice_use.]]</span>
            					</span>
            				</span>
                		</td>
                    </tr>
                    <tr>
                        <td>
                			<span class="multi_input">
                				<input name="code" type="text" id="code" style="width:50px;text-transform: uppercase; height: 24px;"/>
                			</span>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    <?php if(Url::get('cmd')=='edit') {?>
                                    jQuery('#code').attr('readonly','readonly');
                                    <?php }?>      
                                })
                            </script>
                            <!--LIST:languages-->
                			<span class="multi_input">
                				<input name="name_[[|languages.id|]]" type="text" id="name_[[|languages.id|]]" style="width:148px; height: 24px;"  />
                			</span>
                			<!--/LIST:languages-->
                            <span class="multi_input">
                				<select name="area_id" id="area_id" style="width: 80px; height: 24px;">
                					[[|area_options|]]
                				</select>
                			</span>
                            <?php if(User::is_admin()){?>
                            <span class="multi_input">
                				<input name="acc_revenue_code" type="text" id="acc_revenue_code" style="width:80px; height: 24px;"/>
                			</span>
                            <span class="multi_input">
                				<input name="acc_deposit_code" type="text" id="acc_deposit_code" style="width:80px; height: 24px;"/>
                			</span>
                            <?php }?>
                            <span class="multi_input">
                				<select name="mice_use" id="mice_use" style="width: 100px; height: 24px;">
                					[[|mice_use_option|]]
                				</select>
                                <script>
                                    jQuery("#mice_use").val('<?php echo $_REQUEST['mice_use'] ?>');
                                </script>
                			</span>
                        </td>
                	</tr>
            	</table>
        	</form>
    	</td>
    </tr>
</table>
</div>
