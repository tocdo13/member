<div>
<div>
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css"/>

<!--/IF:first_page-->
<table cellpadding="2" cellspacing="0" width="98%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-size:11px;">
		<tr valign="top">
			<td align="left" width="25%"><img src="<?php echo HOTEL_LOGO;?>" width="100px" /></td>
			<td align="right" nowrap width="75%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><br/>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			</td>
		</tr>	
        </table>
		<div class="report_title" style="text-transform:uppercase; font-size:20px; margin-bottom: 5px; margin-top: 5px;">[[.daily_revenue_report.]]</div>
        <form name="Search" method="post">
        <input name="in_date" id="in_date"  value="[[|in_date|]]"  style="width: 100px;" onchange="Search.submit();" />
        <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
        [[.hotel.]]<select name="portal_id" id="portal_id"></select>
        <?php }?>
         <input name="do_search" type="submit" class="do_search" value="[[.view_report.]]" />
         </form>
         <script>
		$('in_date').value = '[[|in_date|]]';
		jQuery("#in_date").datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
        </script>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		<?php //Report::display_date_params();
		      $year = URL::get('from_year')?URL::get('from_year'):date('Y');
			$end_year = URL::get('from_year')?URL::get('from_year'):date('Y');
			$end_day = Date_Time::day_of_month(date('m'),date('Y'));
			if(URL::get('from_day'))
			{
				$day = URL::get('from_day');
				$end_day = URL::get('to_day');
			}else{
				$day = date('d');
				$end_day = date('d');
			}
			$month = URL::get('from_month')?URL::get('from_month'):date('m');
			$end_month = $month;//URL::get('to_month')?URL::get('to_month'):date('m');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			if(!checkdate($end_month,$end_day,$end_year))
			{
				$end_day = Date_time::day_of_month($end_month,$end_year);
			}
		?>
        [[.date.]]:<?php echo Url::get('in_date')?Url::get('in_date'):date('d/m/Y'); ?>	
        <!--[[.from_date.]]:<?php echo $day.'/'.$month.'/'.$year.'     '; ?>
        [[.to.]]:<?php echo $end_day.'/'.$end_month.'/'.$end_year.'   ';?>-->
		<?php if(URL::get('minibar_id')){echo '<br />'.Portal::language('minibar').DB::fetch('select name from minibar where id=\''.URL::get('minibar_id').'\'','name');}?>
		<?php if(URL::get('category_id')){echo '<br />'.Portal::language('category').DB::fetch('select name from product_category where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('product_id')){echo '<br />'.Portal::language('product').DB::fetch('select concat(concat(id,\' - \'),name_'.Portal::language().') as name from product where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('reservation_type_id')){echo '<br />'.Portal::language('reservation_type').': '.DB::fetch('select id,name from reservation_type where id=\''.URL::iget('reservation_type_id').'\'','name');}?>
</td></tr></table>
		</div>
		<!--/IF:first_page-->