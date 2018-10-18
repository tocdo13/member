<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_ticket_type'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[|title|]]</td>
					<td width="" align="right"><a class="button-medium-save" onClick="AddActiveDepartmentForm.submit();">[[.save.]]</a></td>
					<td width="" align="right"><a class="button-medium-back" onClick="window.location='<?php echo Url::build_current();?>'">[[.back.]]</a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	
    <tr valign="top">
    	<td>
        	<form name="AddActiveDepartmentForm" method="post" >
                <fieldset>
                    <div class="form_content">
                	<table cellspacing="0" width="100%">
                	<tr bgcolor="#EFEFEF" valign="top">
                		<td width="100%">
        					<div style="border:2px solid #FFFFFF;">
                                <a name="top_anchor"></a>
                                <input name="portal_id" type="hidden" id="portal_id"/>
                                <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                                    <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                                		<th width="40px" title="[[.check_all.]]">
                                            <input type="checkbox" value="1" id="ManagePortal_all_checkbox" onclick="select_all_checkbox(this.form, 'ManagePortal',this.checked,'#FFFFEC','white');"/>
                                        </th>
                                		<th width="300px" align="left" >[[.name.]]</th>
                                        <th width="300px" align="left" >[[.description.]]</th>
                                	</tr>
             
                                	<!--LIST:ticket-->
                                	<tr bgcolor="#FFF" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                                		<td>
                                            <input name="selected_ids[]" type="checkbox" id="check_box_[[|ticket.id|]]" value="[[|ticket.id|]]"/>
                                        </td>
                                        <td nowrap align="left">[[|ticket.name|]]</td>
                                        <td nowrap align="left">[[|ticket.desc|]]</td>                        
                                	</tr>
                                    
                                	<!--/LIST:ticket-->
                                </table>
        				    </div>
                			
                            
                            <table width="100%">
                                <tr>
                        			<td width="100%">
                        				[[.select.]]:&nbsp;
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',false,'#FFFFEC','white');">[[.select_none.]]</a>
                        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.AddActiveDepartmentForm,'ManagePortal',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
                        			</td>
                        			<td>
                        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"/></a>
                        			</td>
                    			</tr>
                            </table>
                		</td>
                	</tr>
                	</table>	
                	</div>                                                                                                                                                                                                                                              
                </fieldset>
        	</form>
    	</td>
    </tr>
</table>
</div>

<script>
 jQuery(document).ready(function(){
    
    ticket_area_type_js = [[|ticket_area_type_js|]];
    for(var j in ticket_area_type_js){
        if(jQuery('#check_box_'+ticket_area_type_js[j]['ticket_id']))
        {
            jQuery('#check_box_'+ticket_area_type_js[j]['ticket_id']).attr('checked','checked');
        }
    }    
 })

</script> 