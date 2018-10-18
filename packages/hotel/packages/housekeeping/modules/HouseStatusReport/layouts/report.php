<script>
function handleKeyPress(evt) {  
	var nbr;  
	var nbr = (window.event)?event.keyCode:evt.which;
	if(nbr==27){
		window.location = '<?php echo Url::build_current();?>';
	}
	return true;
}
document.onkeydown= handleKeyPress;
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
 });
</script>
<div style="width:100%;overflow:auto">
<table cellSpacing=0 width="100%">
<tr>
<td>
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		ADD: <?php echo HOTEL_ADDRESS;?><BR>
		Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
		Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
		<td align="right" nowrap width="50%">[[.department.]]: ............................</td>
	</tr>
    <tr>
	<td align="center" colspan="2" class="report_title">[[.house_status_report.]]<br></td></tr>
</table><br />
<?php if(Url::get('view_printable_result')){?>
<table cellSpacing=0 width="100%">
	<tr>
	<td align="center">
	[[.from_date.]]: <?php echo Url::get('from_date',date('d/m/Y'));?>
	[[.to_date.]]: <?php echo Url::get('to_date',date('d/m/Y'));?></td>
	</tr>
</table>
<?php }else{?>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%" bgcolor="#EFEFEF" style="border:1px solid #CCC;">
	<tr valign="top">
	<td align="center">
        <table border="0" cellspacing="0" cellpadding="5">
            <tr valign="top">
	            <td>
                	[[.from_date.]]<br /><input name="from_date" value="<?php echo Url::get('from_date',date('d/m/Y'));?>"  type="text" id="from_date" style="width:100px;">
                </td>
                <td>
                	[[.to_date.]]<br /><input name="to_date" value="<?php echo Url::get('to_date',date('d/m/Y'));?>"  type="text" id="to_date" style="width:100px;">
                </td>
                <td>
                	[[.hotel.]]<br />
                	<select name="portal_id[]" multiple="multiple">[[|portal_options|]]</select>
                </td>
                <td><br />
                	<input name="view_printable_result"  value="[[.view_printable_result.]]" type="submit" id="view_printable_result">
                </td>
            </tr>
        </table>
   	</td>
	</tr>
</table>
</form></p><?php }?>
<!--LIST:selected_portals-->
<br /><h3>[[|selected_portals.name|]]</h3>
<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999">
<!--LIST:selected_portals.dates-->
<tr bgcolor="[[|selected_portals.dates.color|]]">
	<td width="50" align="center" nowrap="nowrap">[[|selected_portals.dates.name|]]</td>
    <td width="40" align="center" nowrap="nowrap"><strong>[[|selected_portals.dates.total|]]</strong></td>
    <!--LIST:selected_portals.dates.room_levels-->
    <td width="<?php echo 100/sizeof([[=selected_portals.dates.room_levels=]]);?>%" align="center" nowrap="nowrap">[[|selected_portals.dates.room_levels.value|]]</td>	
    <!--/LIST:selected_portals.dates.room_levels-->
</tr>
<!--/LIST:selected_portals.dates-->
</table>
<!--/LIST:selected_portals-->
</td>
</tr>
</table>
<br />
</div>