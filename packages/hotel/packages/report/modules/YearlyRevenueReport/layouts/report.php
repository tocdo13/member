<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                    </td>
                    </tr>
                    <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.yearly_revenue_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.year.]]&nbsp;[[|year|]]
                            </span> 
                        </div>
                    </td>
                    
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>
<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <td>[[.year.]]</td>
                                    <td>
                                        <select name="year" id="year" style="width:60px;" class="by-year">
                                        <?php
                                        	for($i=date('Y')+1;$i>=BEGINNING_YEAR;$i--)
                                        	{
                                        		echo '<option value="'.$i.'"'.(($i==Url::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
                                        	}
                                        ?>
                                        </select>
                                    </td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td><input type="submit" name="do_search" value="[[.report.]]"/></td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
.table-bound{margin: 0 auto !important;}
.desc{text-align: left !important;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date').datepicker();
});
</script>



<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="100px" rowspan="2">[[.month.]]</th>
        <th class="report_table_header" width="100px" rowspan="2">[[.total_room.]]</th>
		<th class="report_table_header" width="400px" colspan="4">[[.num_guest.]]</th>
        <th class="report_table_header" width="400px" colspan="4">[[.day_guest.]]</th>
    </tr>
    
    <tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="100px" >[[.FIT.]]</th>
        <th class="report_table_header" width="100px" >[[.GIT.]]</th>
		<th class="report_table_header" width="100px" >[[.HK.]]</th>
        <th class="report_table_header" width="100px" >[[.total.]]</th>
        <th class="report_table_header" width="100px" >[[.FIT.]]</th>
        <th class="report_table_header" width="100px" >[[.GIT.]]</th>
		<th class="report_table_header" width="100px" >[[.HK.]]</th>
        <th class="report_table_header" width="100px" >[[.total.]]</th>
    </tr>
    
    <!--LIST:data-->
    <tr>
        <td class="report_table_column">[[|data.month|]]</td>
        <td class="report_table_column" align="center"><?php echo System::display_number([[=data.max_room=]]); ?></td>
        
        <td class="report_table_column" align="right"><?php echo System::display_number($this->map['data']['current']['num_guest']['FIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number($this->map['data']['current']['num_guest']['GIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number($this->map['data']['current']['num_guest']['HK']); ?></td>
        <td class="report_table_column" align="right" style="font-weight: bold;"><?php echo System::display_number($this->map['data']['current']['num_guest']['FIT']+$this->map['data']['current']['num_guest']['GIT']); ?></td>
        
        <td class="report_table_column" align="right"><?php echo System::display_number($this->map['data']['current']['day_guest']['FIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number($this->map['data']['current']['day_guest']['GIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number($this->map['data']['current']['day_guest']['HK']); ?></td>
        <td class="report_table_column" align="right" style="font-weight: bold;"><?php echo System::display_number($this->map['data']['current']['day_guest']['FIT']+$this->map['data']['current']['day_guest']['GIT']); ?></td>
        
    </tr>
    <!--/LIST:data-->
    
    <tr style="font-weight: bold;">
        <td class="report_table_header" align="center" colspan="2">[[.total.]]</td>
		<td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['GUEST_FIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['GUEST_GIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['GUEST_HK']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['GUEST_FIT']+[[=summary=]]['GUEST_GIT']); ?></td>
        
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['DAY_FIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['DAY_GIT']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['DAY_HK']); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=summary=]]['DAY_FIT']+[[=summary=]]['DAY_GIT']); ?></td>
    </tr>
    
</table>

<br />
<br />

<!--IF:check([[=plan=]])-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="float: left;">

    <tr bgcolor="#EFEFEF">
		<th class="report_table_header" colspan="3" width="400px">[[.plan.]] [[|year|]]</th>
        <th class="report_table_header" width="300px">[[.has.]]</th>
        <th class="report_table_header" width="100px">[[.achieve.]](%)</th>
    </tr>
    <!--LIST:plan-->
    <tr>
        <td class="report_table_column" align="left" width="200px"><strong>[[|plan.name|]]</strong></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=plan.value=]]); ?></td>
        <td class="report_table_column" align="right">[[|plan.currency_id|]]</td>
        <!--IF:cond([[=plan.code=]] == 'ROOM_REVENUE')-->
        <td class="report_table_column" align="right"><strong><?php echo System::display_number([[=total_room_revenue=]]); ?></strong></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_room_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'AVERAGE_OCCUPANCY')-->
        <td class="report_table_column" align="right"><?php echo System::display_number([[=average_occupancy=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=average_occupancy=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'AVERAGE_PRICE')-->
        <td class="report_table_column" align="right"><?php echo System::display_number([[=average_room_price_USD=]]).' (USD) - '.System::display_number([[=average_room_price=]]).' (VND)'; ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=average_room_price_USD=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'BAR_REVENUE')-->
        <td class="report_table_column" align="right"><strong><?php echo System::display_number([[=total_res_revenue=]]); ?></strong></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_res_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'TRANSPORT')-->
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_transport_revenue=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_transport_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'OTHER')-->
        <td class="report_table_column" align="right"><?php echo System::display_number([[=other_revenue=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=other_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'PHONE')-->
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_telephone_revenue=]]); ?></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_telephone_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'OTHER_REVENUE')-->
        <td class="report_table_column" align="right"><strong><?php echo System::display_number([[=total_other_revenue=]]); ?></strong></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_other_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        <!--IF:cond([[=plan.code=]] == 'REVENUE')-->
        <td class="report_table_column" align="right"><strong><?php echo System::display_number([[=total_revenue=]]); ?></strong></td>
        <td class="report_table_column" align="right"><?php echo System::display_number([[=total_revenue=]]*100/[[=plan.value=]]); ?></td>
        <!--/IF:cond-->
        
    </tr>
    <!--/LIST:plan-->
</table>
<!--/IF:check-->



<br/>


<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td></td>
	<td>[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >[[.general_accountant.]]</td>
	<td width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<div style="page-break-before:always;page-break-after:always;"></div>