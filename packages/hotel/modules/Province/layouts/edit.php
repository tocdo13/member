<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('edit_province'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[|title|]]</td>
					<td width="" align="right"><a class="button-medium-save" onClick="EditProvinceForm.submit();">[[.save.]]</a></td>
					<td width="" align="right"><a class="button-medium-back" onClick="history.go(-1)">[[.back.]]</a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	
    <tr valign="top">
    	<td>
        	<form name="EditProvinceForm" method="post" >
                
                
                
                <table width="100%" cellpadding="5">
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
            						<span class="multi-input-header"><span style="width:100px;">[[.code.]]</span></span>
            						<span class="multi-input-header"><span style="width:300px;">[[.name.]]</span></span>
            					</span>
            				</span>
                		</td>
                    </tr>
                    <tr>
                        <td>
                			<span class="multi_input">
                				<input name="code" type="text" id="code" style="width:100px;text-transform: uppercase;"/>
                			</span>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    jQuery('#code').attr('readonly', <?php if(Url::get('cmd')=='edit') echo '\'readonly\''; else echo '\'\''; ?>)
                                    
                                })
                            </script>
                			<span class="multi_input">
                				<input name="name" type="text" id="name" style="width:300px;"  />
                			</span>     
                        </td>
                	</tr>
            	</table>
        	</form>
    	</td>
    </tr>
</table>
</div>
