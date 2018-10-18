<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<!--IF:first_page([[=page_no=]]==1)-->
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellspacing="0" width="100%">
			<tr valign="top">
			<td align="left" width="65%">
			<strong><?php echo [[=hotel_name=]];?></strong><br />
			<?php echo [[=hotel_address=]];?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
			</tr>	
		</table>
		<font class="report_title">
        <?php echo Portal::language('karaoke_order_revenue_less_than_200_report'); ?>
        </font>
        
		
        <br />
        <br />
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
        <?php Report::display_date_params();?><br />
        <!--IF:cond(![[=check=]])-->
		<?php echo URL::get('karaoke_id')?Portal::language('karaoke_id').' '.DB::fetch('select name from karaoke where id=\''.URL::get('karaoke_id').'\'','name'):'';?>
        <!--/IF:cond-->
        <?php echo Url::get('karaoke_name');?>
        </div>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if( isset( [[=start_shift_time=]] ) ) echo Portal::language('time').': '.[[=start_shift_time=]].' - '; if( isset( [[=end_shift_time=]] ) ) echo [[=end_shift_time=]]; ?>
        </div>
        <br />
        
		<table cellspacing="3px" cellpadding="3px" width="50%" border="0" style="float: left;">
			<tr valign="top">
                <td align="left">
    			<strong>[[.hotel_name.]] : </strong>
                </td>
    			<td align="left">
    			<strong><?php echo [[=hotel_name=]];?></strong>
                </td>
            </tr>
			<tr valign="top">
                <td align="left">
    			<strong>[[.hotel_address.]] : </strong>
                </td>
    			<td align="left">
    			<strong><?php echo HOTEL_ADDRESS ;?></strong>
                </td>
            </tr>
			<tr valign="top">
                <td align="left">
    			<strong>[[.tax_code.]] : </strong>
                </td>
    			<td align="left">
    			<strong><?php echo HOTEL_TAXCODE;?></strong>
                </td>
            </tr>
		</table>
		<!--/IF:first_page-->