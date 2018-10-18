<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css"/>
<!--IF:first_page([[=page_no=]]==1)-->
<?php echo Form::$current->error_messages();?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="300px">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%">
			<tr valign="top">
    			<td align="left" width="60%">
        			<strong><?php echo [[=hotel_name=]];?></strong><br />
        			<?php //echo [[=hotel_address=]];?>
                </td>
    			<td align="right" nowrap width="40%">
        			<strong>[[.template_code.]]</strong><br />
        			<i>[[.promulgation.]]</i>
    			</td>
			</tr>	
		</table>
		
        <font class="report_title" style="text-transform:uppercase; font-size: 15px;">
            <?php echo Portal::language('receipt_by_employee_report');?>
        </font>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><?php Report::display_date_params();?><br />
            <?php echo Url::get('karaoke_id')?Portal::language('karaoke_id').' '.DB::fetch('select name from karaoke where id=\''.Url::get('karaoke_id').'\'','name'):'';?>
        </div>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">[[.time.]] : 
            <?php if( isset( [[=start_shift_time=]] ) ) echo [[=start_shift_time=]]; ?> - <?php if( isset( [[=end_shift_time=]] ) ) echo [[=end_shift_time=]]; ?>
        </div>
        <br />        
		<!--/IF:first_page-->