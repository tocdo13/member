<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_department'));?>

<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />


<div class="form_bound">
<form name="ListActiveDepartmentForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="form-title" width="100%">[[.list_department.]]</td>
        </tr>
    </table>
    
    <div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
    			<tr>
    				<td width="100%">
    					<div style="border:2px solid #FFFFFF;">
                            <a name="top_anchor"></a>
                            <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                            	
                                <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                            		
                                    <th width="14%" align="left">[[.portal_id.]]</th>
                            		
                            		<th width="13%" align="left">[[.portal_name.]]</th>
                                    
                                    <th width="13%" align="left">[[.department.]]</th>
                            		
                            		
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<th width="1%" align="center" nowrap>[[.select_warehouse.]]</th>
                            		<?php }?>
                            		
                            	</tr>
                                
                            	<!--LIST:portal-->
                            	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                            		
                                    <td align="left">[[|portal.id|]]</td>
                                   
                                    <td align="left">[[|portal.name|]]</td>
                                    
                                    
                                    <td nowrap align="left">
                                    <!--LIST:portal.department-->
                                    [[|portal.department.name|]],&nbsp;
                                    <!--/LIST:portal.department-->
                                    </td>
                            		
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a href="<?php echo URL::build_current(array('cmd'=>'edit','portal_id'=>[[=portal.id=]]));?>">
                                            <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Sua"/>
                                        </a>
                                    </td>
                            		<?php }?>
                            		
                            	</tr>
                            	<!--/LIST:portal-->
                            </table>
    				    </div>
    				</td>
    			</tr>
			</table>
			
            
            <table width="100%">
                <tr>
        			<td width="100%">
        				&nbsp;
        			</td>
        			<td>
        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
        			</td>
    			</tr>
            </table>
		</td>
	</tr>
	</table>	
	</div>
    
                                                                
</form>
</div>
