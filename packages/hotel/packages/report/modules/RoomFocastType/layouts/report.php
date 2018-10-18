<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
 });
</script>
<div style="width:100%;overflow:auto">
<table cellSpacing=0 width="100%">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		[[.address.]]: <?php echo HOTEL_ADDRESS;?><br />
          </td>
		<td align="right" nowrap width="15%" style="padding-right:20px;">
		<strong>[[.template_code.]]</strong>
        <br />
                        Date: <?php echo date('d/m/Y H:i');?>
                        <br />
                        Printer: <?php $user_name =DB::fetch('select name_1 from party where user_id=\''.User::id().'\''); echo $user_name['name_1']  ;?>
        <br />
		<i>[[.promulgation.]]</i>		</td>
	</tr>	
	<tr>
	<td align="center" colspan="2" style="font-size:18px;"><strong>[[.room_focast_type.]]</strong></td>
    </tr>
    <tr>
    <td colspan="2" align="center">
	[[.from_date.]] : <input name="from_date" value="<?php echo Url::get('from_date',date('d/m/Y'));?>"  type="text" id="from_date" onchange="changevalue();" style="width:100px;">
	[[.to_date.]] : <input name="to_date" value="<?php echo Url::get('to_date',date('d/m/Y'));?>"  type="text" id="to_date" style="width:100px;" onchange="changefromday();" />
	<input name="view_result"  value="[[.view.]]" type="submit" id="view_result"/>
	</td>
	</tr>
	<tr>
		<td align="center" colspan="2">(<i>[[.nen_chon_khoang_thoi_gian_nho_hon_hai_muoi_ngay.]]</i>)</td>
	</tr>
       
</table>
</form>
</p>
<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="black" style="border-collapse:collapse" style="font-size:12px;">
    <tr>
    	<td width="100" align="center" style="background-color:#CCCCCC" nowrap="nowrap"><strong>[[.room_type.]]</strong></td>
    	<td align="center" width="50"><strong>[[.Sum.]]</strong></td>
    	<!--LIST:days-->
    	<td width="25" align="center"><strong>[[|days.id|]]</strong></td>
    	<!--/LIST:days-->
    </tr>
    <!--LIST:items-->
    <tr>
        <td width="100" align="center" style="background-color:#CCCCCC" nowrap="nowrap">[[|items.name|]]</td>
        <td align="center" width="50"><strong>[[|items.acount|]]</strong></td>
        <!--LIST:items.child-->
        <td width="25" align="center">[[|items.child.total|]]/<?php echo [[=items.child.acount=]]-[[=items.child.total_room=]]-[[=items.child.total_repair=]]; ?></td>
        <!--/LIST:items.child-->
    </tr>
    <!--/LIST:items-->
    <tr>
        <td width="100" align="center" style="background-color:#CCCCCC" nowrap="nowrap"><strong>[[.total.]]</strong></td>
        <td align="center" width="50"><strong>[[|total|]]</strong></td>
        <!--LIST:days-->
    	<td width="25" align="center"><strong>[[|days.total|]]/<?php echo [[=total=]]-[[=days.total_room=]]-[[=days.total_repair=]] ?><br />(<?php echo round([[=days.total=]]*100/([[=total=]]-[[=days.total_repair=]]),2) ?>%)</strong></td>
    	<!--/LIST:days-->
    </tr>
</table>
</td>
</tr>
</table>
<br />
</div>
<script>
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>