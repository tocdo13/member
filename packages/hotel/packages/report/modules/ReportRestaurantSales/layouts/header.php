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
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			 [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
			</td>
			</tr>	
		</table>
        
		<font class="report_title">[[.report_restaurant_sales.]]</font>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"><?php Report::display_date_params();?><br />
        
        
        <table align="left">
            <tr>
                <th align="left">[[.creator.]]</th>
                <th align="left">:</th>
                <th align="left"><?php $user = Session::get('user_data'); echo $user['full_name'];?> </th>
            </tr>
            <tr>
                <th align="left">[[.department.]]</th>
                <th align="left">:</th>
                <th align="left"> [[.restaurant.]]</th>
            </tr>
            
        </table>
		<!--IF:cond(Url::get('customer_name'))-->[[.customer.]]: <?php echo Url::sget('customer_name');?><!--/IF:cond-->
		<!--/IF:first_page-->
        