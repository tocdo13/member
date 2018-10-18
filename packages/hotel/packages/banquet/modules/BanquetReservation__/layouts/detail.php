<!--?php echo"tet6";?-->
<style type="text/css">
.form-label{
	font-weight:bold;
}
</style>
<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center">
				<table cellpadding="0" width="100%" border="0">
				<td align="center" colspan="3"><img src="<?php echo HOTEL_LOGO;?>" width="120px" style="margin-bottom:10px;" /></td>
				<tr>
					<td align="center">
						<font style="font-size:20px;text-transform:uppercase;"><b>[[.banquet_info.]]</b></font><br />
                        <font style="font-size:14px;"><strong>[[|date|]]</strong></font>				
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr bgcolor="#F4F4F4">
			<td bgcolor="#FFFFFF">
				<table width="100%" cellpadding="5">
                    <tr>
                    	<td></td>
                    </tr>
					<tr>
					  	<td width="15%" class="form-label">[[.agent_name.]]:</td>
                      	<td width="1%" >:</td>
					  	<td style="border-bottom:1px dotted #CCCCCC;">[[|full_name|]]</td>
				  	</tr>
					<tr>
					  	<td  class="form-label" >[[.agent_address.]]:</td>
                      	<td  >:</td>
					  	<td  style="border-bottom:1px dotted #CCCCCC;">[[|address|]]</td>
				  	</tr>                    
					<tr>
					  	<td  class="form-label">[[.agent_email.]]:</td>
                      	<td  >:</td>
					 	<td style="border-bottom:1px dotted #CCCCCC;">[[|email|]]</td>
				  	</tr>
					<tr>
					  <td  class="form-label">[[.agent_phone.]]</td>
                      <td  >:</td>
					  <td style="border-bottom:1px dotted #CCCCCC;">[[|home_phone|]]</td>
				  </tr>
                  <?php 
                        if([[=num_people=]] != '')
                            echo '<tr>
                					  <td   class="form-label">'.Portal::language('banquet_time').'</td>
                                      <td  >:</td>
                					  <td  style="border-bottom:1px dotted #CCCCCC;">
                                       '.Portal::language('from').' '.date('H\h:i',[[=checkin_time=]]).' '.Portal::language('to').' '.date('H\h:i',[[=checkout_time=]]).' '.Portal::language('date').' '.date('d',[[=checkin_time=]]).' '.Portal::language('month').' '.date('m',[[=checkin_time=]]).' '.Portal::language('year').' '.date('Y',[[=checkin_time=]]).' '.'</td>
                				  </tr>';
                        if([[=meeting_num_people=]] != '')
                            echo '<tr>
                					  <td   class="form-label">'.Portal::language('meeting__time').'</td>
                                      <td  >:</td>
                					  <td  style="border-bottom:1px dotted #CCCCCC;">
                                       '.Portal::language('from').' '.date('H\h:i',[[=meeting_checkin_hour=]]).' '.Portal::language('to').' '.date('H\h:i',[[=meeting_checkout_hour=]]).' '.Portal::language('date').' '.date('d',[[=meeting_checkin_hour=]]).' '.Portal::language('month').' '.date('m',[[=meeting_checkin_hour=]]).' '.Portal::language('year').' '.date('Y',[[=meeting_checkin_hour=]]).' '.'</td>
                				  </tr>';
                    ?>
					<tr>
					  <td   class="form-label">[[.banquet_location.]]</td>
                      <td  >:</td>
					  <td  style="border-bottom:1px dotted #CCCCCC;">[[|banquet_room|]]</td>
				    </tr>
                    <?php 
                        if([[=num_people=]] != '')
                            echo '<tr>
            					  <td   class="form-label">'.Portal::language('number_guest').'</td>
                                  <td  >:</td>
            					  <td  style="border-bottom:1px dotted #CCCCCC;">'.[[=num_people=]].'</td>
                                  </tr>';
                        if([[=meeting_num_people=]] != '')
                            echo '<tr>
            					  <td   class="form-label">'.Portal::language('number_meeting_guest').'</td>
                                  <td  >:</td>
            					  <td  style="border-bottom:1px dotted #CCCCCC;">'.[[=meeting_num_people=]].'</td>
                                  </tr>';
                    ?> 
                    <tr>
					  <td  class="form-label">[[.num_table.]]</td>
                      <td  >:</td>
					  <td  style="border-bottom:1px dotted #CCCCCC;">[[|num_table|]]</td>
				    </tr> 
					<tr>
					  <td  class="form-label">[[.deposit.]]</td>
                      <td  >:</td>
                      <td  style="border-bottom:1px dotted #CCCCCC;">[[|deposit|]] [[|deposit_date|]]</td>
				    </tr>
                    <tr>
                    	<td class="form-label">[[.banquet_price.]]</td>
                        <td  >:</td>
                        <td style="border-bottom:1px dotted #CCCCCC;">[[|total|]]</td>
                    </tr>                    
					<tr>
					  <td  class="form-label">[[.note.]]</td>
                      <td  >:</td>
                      <td  style="border-bottom:1px dotted #CCCCCC;">[[|note|]]</td>
				  </tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<br />
<div style="border:1px solid #CCCCCC;width:980px;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%">
			<table cellSpacing=0 cellPadding=5 border=0 width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center">
				<table cellpadding="0" width="100%" border="0">
				<tr>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
						<font style="font-size:20px;text-transform:uppercase;"><b>[[.banquet_detail.]]</b></font><br />		
					</td>
				</tr>
				</table>
			  </td>
			</tr>
			<tr bgcolor="#F4F4F4">
			<td bgcolor="#FFFFFF">
				<table width="100%" cellpadding="5">
                    <tr>
                    	<td></td>
                    </tr>
					<tr>
					  	<td width="15%" class="form-label">[[.banquet_type.]]:</td>
                      	<td width="1%" >:</td>
					  	<td style="border-bottom:1px dotted #CCCCCC;">[[|party_type_name|]]</td>
				  	</tr>
                    <!--IF:cond([[=groom_name=]])-->
					<tr>
					  	<td  class="form-label" >[[.groom_name.]]:</td>
                      	<td  >:</td>
					  	<td  style="border-bottom:1px dotted #CCCCCC;">[[|groom_name|]]</td>
				  	</tr>
                    <!--/IF:cond-->      
                    <!--IF:cond([[=bride_name=]])-->
					<tr>
					  	<td  class="form-label" >[[.bride_name.]]:</td>
                      	<td  >:</td>
					  	<td  style="border-bottom:1px dotted #CCCCCC;">[[|bride_name|]]</td>
				  	</tr>
                    <!--/IF:cond-->  
                    <!--IF:cond_menu([[=banquet_menu=]])-->
					<tr>
						<td class="form-label" valign="top">[[.menu.]]</td>
                        <td valign="top">:</td>
                        <td>
                            <fieldset>
                        	<table cellpadding="0" width="100%">
                            <tr>
                                    <td>[[.food.]] :</td>
                                    <td>[[.drinks.]] :</td>
                                    <td>[[.service_menu.]] :</td>
                                </tr>
                                <tr>
                        
                        <td>
                                
                              <?php 
                                    for($j = 1; $j < [[=num_eating=]]; $j++)
                                    {
                                  ?>
                                    <li>
                                        <?php 
                                            if(isset([[=eating=]][$j])){ echo [[=eating=]][$j]; }
                                        ?>
                                         
                                    </li>
                                  <?php 
                                    }
                              ?>
                        </td>
                        
                        <td>
                              <?php 
                                    for($i = 1; $i < [[=num_drinking=]]; $i++)
                                    {
                                  ?>
                                    <li>
                                        <?php 
                                            if(isset([[=drinking=]][$i])){ echo [[=drinking=]][$i];}
                                        ?>
                                        
                                    </li>
                              <?php 
                                }
                              ?>
                        </td>
                        <td>
                              <?php 
                                for($k = 1; $k < [[=num_service=]]; $k++)
                                {
                              ?>
                                <li>
                                    <?php 
                                        if(isset([[=service=]][$k])){ echo [[=service=]][$k];}
                                    ?>
                                    
                                </li>
                              <?php 
                                }
                              ?>
                        </td>
                    </tr>
                            </table>
                            </fieldset>
                        </td>
					</tr>
                    <!--/IF:cond_menu-->   
                    <!--IF:cond_room([[=banquet_rooms=]])-->
					<tr>
						<td class="form-label" valign="top">[[.banquet_rooms.]]</td>
                        <td valign="top">:</td>
                        <td>
                            <fieldset>
                        	<table cellpadding="2" width="100%">
                            	<?php $i=1;?>
								<!--LIST:banquet_rooms-->
                                <tr>                                
                                	<td>
                                        <strong><?php echo $i;?>.</strong> [[|banquet_rooms.name|]] - [[|banquet_rooms.group_name|]]
                                        <!--IF:cond_note([[=note=]])--><em>([[|banquet_rooms.note|]])</em><!--/IF:cond_note-->
                                    </td>                                   
                                    <?php $i++;?>
                                </tr>
                                <!--/LIST:banquet_rooms-->
                            </table>
                            </fieldset>
                        </td>
					</tr>
                    <!--/IF:cond_room--> 
                    <tr>
                    	<td class="form-label">[[.party_category.]]</td>
                        <td  >:</td>
                        <td style="border-bottom:1px dotted #CCCCCC;">[[|party_category|]]</td>
                    </tr> 
                    <!--IF:cond_promotion(isset([[=promotion=]]))-->
                    <tr>
                    	<td class="form-label">[[.promotions.]]</td>
                        <td  >:</td>
                        <td>
                            <fieldset>
                        	<table cellpadding="2" width="100%">
                            	<?php $i=1;?>
								<!--LIST:promotion-->
                                <!--IF:cond([[=promotion.name=]] != '')-->
                                    <tr>                                
                                    	<td>
                                            <strong><?php echo $i;?>.</strong> [[|promotion.name|]]
                                        </td>                                   
                                        <?php $i++;?>
                                    </tr>
                                <!--/IF:cond-->    
                                <!--/LIST:promotion-->
                            </table>
                            </fieldset>
                        </td> 
                    </tr> 
                    <!--/IF:cond_promotion-->              
					<tr>
					  <td  class="form-label">[[.detail_price.]]</td>
                      <td  >:</td>
                      <td >
                      
                            <fieldset>
                        	<table cellpadding="2" width="100%">
                                <tr>                                
                                	<td>[[.total_before_tax.]] : [[|total_before_tax|]] <em>([[|description|]])</em></td>                                   
                                </tr>
                                <tr>  
                                	<td>[[.service_fee.]] : [[|service_fee|]]</td>                                                                
                                </tr>
                                <tr>
                                	<td>[[.tax_fee.]] : [[|tax_fee|]]</td>                                                                    
                                </tr>
                            </table>
                            </fieldset>
                      </td>
				  </tr>
				</table>
			</td>
			</tr>
            
			</table>
		</td>
	</tr>
</table>
</div>
<div>
<p align=center>
<?php if(User::can_edit(false,ANY_CATEGORY) ) {?><input  type="button" value="[[.edit.]]" onclick="window.location='<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>Url::get('party_type'),'action'=>'edit','id'=>Url::iget('id'))); ?>'" /><?php } ?>
</p>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>