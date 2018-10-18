<style>
@media print
{
    .search{display:none;}
}
</style>
<div>
<div>
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css">

<!--/IF:first_page-->
<table cellpadding="2" cellspacing="0" width="98%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%">
		<tr valign="top">
			<td align="left" width="65%"><img src="<?php echo HOTEL_LOGO;?>" width="140px" /></td>
			<td align="right" nowrap width="35%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><br/>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
            <br />
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
            </td>
			</td>
		</tr>	
	</table>
   
		<div class="report_title" style="text-transform:uppercase; margin-bottom: 20px;margin-top: 20px;">[[.extra_service_revenue_report.]]</div>
        <div class="search">
        <form name="Search" method="post">
         <label>[[.from_date.]]: </label><input name="in_date" id="in_date"  value="[[|in_date|]]"  style="width: 80px;" onchange="changevalue()"/>
         &nbsp;&nbsp;
         <label>[[.to.]]: </label><input name="to_date" id="to_date" value="[[|to_date|]]" style="width: 80px;" onchange="changefromday()" />
        &nbsp;&nbsp;
        <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
        [[.hotel.]]: <select name="portal_id" id="portal_id" style="width:100px"></select>
        <?php }?>
         <input name="do_search" type="submit" class="do_search" value="[[.view_report.]]" />
         </form>
         </div>
 <br />
<script type="text/javascript">
		jQuery("#in_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
        jQuery("#to_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});

	$('in_date').value='<?php if(Url::get('in_date')){echo Url::get('in_date');}else{ echo (date('d/m/Y'));}?>';
	$('to_date').value='<?php if(Url::get('to_date')){echo Url::get('to_date');}else{ echo (date('d/m/Y'));}?>';

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
        [[.from_date.]] <?php echo Url::get('in_date')?Url::get('in_date'):date('d/m/Y'); ?> &nbsp;&nbsp;  [[.to.]] <?php echo Url::get('to_date')?Url::get('to_date'):date('d/m/Y'); ?>
        <!--[[.from_date.]] <?php echo $day.'/'.$month.'/'.$year.'     '; ?>
        [[.to.]]:<?php echo $end_day.'/'.$end_month.'/'.$end_year.'   ';?>-->
		<?php if(URL::get('minibar_id')){echo '<br />'.Portal::language('minibar').DB::fetch('select name from minibar where id=\''.URL::get('minibar_id').'\'','name');}?>
		<?php if(URL::get('category_id')){echo '<br />'.Portal::language('category').DB::fetch('select name from product_category where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('product_id')){echo '<br />'.Portal::language('product').DB::fetch('select concat(concat(id,\' - \'),name_'.Portal::language().') as name from product where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('reservation_type_id')){echo '<br />'.Portal::language('reservation_type').': '.DB::fetch('select id,name from reservation_type where id=\''.URL::iget('reservation_type_id').'\'','name');}?>
</td></tr></table>
		</div><br />
		<!--/IF:first_page-->
<script type="text/javascript">
//giap.ln thay doi gia tri tu ngay den ngay
    function changevalue()
    {
        var myfromdate = document.getElementById('in_date').value;//$('in_date').value.split("/");
        var from_dates = myfromdate.split('/');

        //new Date(year, month, day, hours, minutes, seconds, milliseconds)
        var to_date = document.getElementById('to_date').value;
        var to_dates = to_date.split('/');
       
        
        var from = new Date(from_dates[2],from_dates[1],from_dates[0],0,0,0,0);
        var to = new Date(to_dates[2],to_dates[1],to_dates[0],0,0,0,0);
        if(from>to)
        {
            document.getElementById('to_date').value = document.getElementById('in_date').value;
        }
    }
    function changefromday()
    {
    	var myfromdate = document.getElementById('in_date').value;
        var from_dates = myfromdate.split('/');

        //new Date(year, month, day, hours, minutes, seconds, milliseconds)
        var to_date = document.getElementById('to_date').value;
        var to_dates = to_date.split('/');
       
        console.log(from_dates[0] + '--' + to_dates[0]);
        var from = new Date(from_dates[2],from_dates[1],from_dates[0],0,0,0,0);
        var to = new Date(to_dates[2],to_dates[1],to_dates[0],0,0,0,0);
        
        if(from>to)
        {
            console.log('from > to');
        	document.getElementById('in_date').value = document.getElementById('to_date').value; 
        }
    }
    //giap.ln end 
</script>        
