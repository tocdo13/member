<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<!--IF:first_page([[=page_no=]]==Url::get('start_page') OR [[=page_no=]]==0)-->
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==Url::get('start_page') OR [[=page_no=]]==0)-->
		<table cellspacing="0" width="100%" style="font-size:11px;">
			<tr valign="top">
			<td align="left" width="60%">
			<strong><?php echo [[=hotel_name=]];?></strong><br />
			<?php echo [[=hotel_address=]];?></td>
            <td align="right">
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
            </td>

			</tr>	
		</table>
		<font class="report_title" style="font-size:16px;">
        <?php echo Portal::language('restaurant_order_revenue_less_than_200_report'); ?>
        </font>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
       [[.date_from.]]: <?php echo Url::get('date_from') ?> [[.date_to.]]: <?php echo Url::get('date_to') ?> <br />
        <!--IF:cond(![[=check=]])-->
		<?php echo URL::get('bar_id')?Portal::language('bar_id').' '.DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?>
        <!--/IF:cond-->
        <?php echo Url::get('bar_name');?>
        </div>
        
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if( isset( [[=start_shift_time=]] ) ) echo Portal::language('time').': '.[[=start_shift_time=]].' - '; if( isset( [[=end_shift_time=]] ) ) echo [[=end_shift_time=]]; ?>
        </div>
        <br />
        
		<table cellspacing="3px" cellpadding="3px" width="100%" border="0" style="float: left; font-size:11px;">
			<tr valign="top">
                <td align="left">
    			<strong>[[.hotel_name.]]</strong> :<?php echo [[=hotel_name=]];?> 
                </td>
    		
            </tr>
			<tr valign="top">
                <td align="left">
    			<strong>[[.hotel_address.]]</strong> :<?php echo HOTEL_ADDRESS ;?> 
                </td>
    			
            </tr>
			<tr valign="top">
                <td align="left">
    			<strong>[[.tax_code.]]</strong> :<?php echo HOTEL_TAXCODE;?> 
                </td>
    			
            </tr>
		</table>
		<!--/IF:first_page-->

<?php

if([[=total_page=]]==0)
{

?>
    <div style="padding:20px;">
    	<h3>[[.no_result_matchs.]]</h3>
    	<a href="<?php echo Url::build_current();?>">[[.back.]]</a>
    </div>
<?php        
    }
?>