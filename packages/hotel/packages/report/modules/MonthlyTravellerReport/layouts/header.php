<style>
@media print{
    .search{
        display: none;
    }
}
</style>
<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><?php $input_count = 0;?>
<table border="0" cellspacing="0" style="width: 98%; margin: 0px auto;">
<tr>
	<td align="center">
		<table cellSpacing=0 cellPadding=0 width="100%" border="0" style="border-collapse:collapse; font-size:12px;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center">
					<table width="100%"><tr align="center" valign="top">
                        <tr>
                            <td align="left">
        					<div>
        						<a href="#" onclick="if(document.getElementById('search_box').style.display=='none'){document.getElementById('search_box').style.display='';}else{document.getElementById('search_box').style.display='none';}"><img src="<?php echo HOTEL_LOGO;?>" width="75"></a><br />
        					</div>
        					</td>
                            <td align="right">
                            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                            <br />
                            [[.user_print.]]:<?php echo User::id();?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
        					<div>
        					<font style="font-size:20px" class="report-title"><br/><br/>[[.guest_by_countries.]]</font>
                            <br />
        					<span class="report-sub-title">[[.month.]] [[|month|]] [[.year.]] [[|year|]]</span>
        					</div>
        					</td>
                        </tr>
                    
                    </table>
				</td>
			</tr>
			<tr>
			  <td>
<!---------PARAMERTERS----------->
			<!---------SEARCH----------->
			<div class="search">
            <form method="post" name="SearchMonthlyTravellerReportForm">
            <span id="search_box">

			<link href="skins/default/datetime.css" rel="stylesheet" type="text/css" />
            <br />
            <div style="margin: 0 auto; width:550px"><span>[[.Report_options.]]: </span><select name="option" id="option"></select><span>[[.hotel.]]: </span><select name="portal_id" id="portal_id"></select><span><input type="button" value="  [[.view_report.]]  " onclick="SearchMonthlyTravellerReportForm.submit();" /></span></div>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F0F6DE">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:years-->
					<td nowrap><a class="datetime_button[[|years.selected|]]" href="<?php echo URL::build_current(array('month','day'));?>&year=[[|years.year|]]">[[|years.year|]]</a></td>
					<!--/LIST:years-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F0F6DE">
				<tr>
					<td width="50%">&nbsp;</td>
					<!--LIST:months-->
					<td><a class="datetime_button[[|months.selected|]]" href="<?php echo URL::build_current(array('year','day'));?>&month=[[|months.month|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','day'=>[[=day=]]));?>&month=',[[|month|]],[[|months.month|]]); event.returnValue=false;}">[[|months.month|]]</a></td>
					<!--/LIST:months-->
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<!--
            [[.nationality.]] : 
			<select  name="country_id">
			<?php
				foreach([[=country_id_list=]] as $id=>$name)
				{
					echo '<option value="'.$id.'" '.((URL::get('country_id')==$id)?'selected':'').'>'.$name.'</option>';
				}
			?></select>
			<input type="submit" value="[[.search.]]"/>
            -->
            </span>
			</form>
            </div>
          </td>
        </tr>
       </table>
      </td>
    </tr>
  </table>    
			
