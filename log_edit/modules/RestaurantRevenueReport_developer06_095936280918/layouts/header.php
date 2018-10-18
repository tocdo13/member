<div class="report-bound" style=" page:land;">
<div >
<!--IF:first_page([[=page_no=]]==[[=start_page=]] or [[=page_no=]] == 0 )-->
<link rel="stylesheet" href="skins/default/report.css"/>
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-size:12px;">
			<tr>
            	<td align="left" width="60%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
            
            <td align="right">
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
            </td>
            </tr> 
		</table>
		<font class="report_title">[[.product_revenue_report.]]</font>
        <!--Luu Nguyen Giap add search nha hang end-->
        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
        <?php echo Url::get('bar_names');?>
        </div>
        <!--Luu Nguyen Giap add search nha hang end-->
        <?php 
        if(Url::get('search_time')){
         ?>
           	<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"> [[.from_b.]]: <?php 
echo (Url::get('from_time')).'-'.(Url::get('from_date_tan'));?>&nbsp;&nbsp;[[.to_b.]]:<?php echo (Url::get('to_time')).'-'.(Url::get('to_date_tan'));?><br />
		      
        <?php    
        }
        ?>
        <?php
         if(Url::get('search_invoice')){ ?>
        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
            <?php if([[=from_bill=]]!=''){ ?> [[.from_bill.]] <?php echo [[=from_bill=]]; } ?> <?php if([[=to_bill=]]!=''){ ?> [[.to_bill.]] <?php echo [[=to_bill=]]; } ?> 
        </div>
        <?php } ?>
        <!--IF:cond(Url::get('customer_name'))-->[[.customer.]]: <?php echo Url::sget('customer_name');?><!--/IF:cond-->
		<!--/IF:first_page-->