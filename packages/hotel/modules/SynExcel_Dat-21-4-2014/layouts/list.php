<style type="text/css">
.field{
	line-height:25px;
}
.success{
	font-weight:bold;
	padding:10px;
	padding-left:5px;
	font-size:16px;
	border:1px solid #006600;
	color:#009900;
}
</style>
<script>
jQuery(function(){
	jQuery('#date_from').datepicker();
	jQuery('#date_to').datepicker();
});
</script>

<div align="center">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="text-align:left;margin-top:3px;">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.Synchronyze_data.]]</td>
					<td width="" align="right"><a class="button-medium-save" style="padding-left:10px;" onclick="synchronize.submit();">[[.save.]]</a></td>
					<td width="" align="right"><a class="button-medium-back" style="padding-left:10px;" onclick="history.go(-1)">[[.back.]]</a></td>                    
                </tr>
            </table>
		</td>
	</tr>
</table>
<form name="synchronize" method="post">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="text-align:left;margin-top:3px;">
	<tr>
    	<td>[[.Date_from.]] : <input name="date_from" type="text" id="date_from" />&nbsp;&nbsp;&nbsp;&nbsp; [[.Date_to.]] :<input name="date_to" type="text" id="date_to" /></td>
    </tr>
    <tr>
        <td style="padding:10px;">
        	<div style="color:#FF0000;margin-bottom:10px;"><strong style="color:#000000;">Note</strong> : Qu&#225; tr&#236;nh chuy&#7875;n d&#7919; li&#7879;u c&#243; th&#7875; s&#7869; m&#7845;t nhi&#7873;u th&#7901;i gian. Vui l&#242;ng kh&#244;ng t&#7855;t tr&#236;nh duy&#7879;t cho &#273;&#7871;n khi d&#7919; li&#7879;u &#273;&#432;&#7907;c t&#7843;i xong</div>
        	<?php if(Url::get('cmd')=='success'){?>
            <div class="success">[[.Transfer_successfull.]] !</div>
            <?php }?>
        	<div class="title">[[.Choose_division.]]</div>
            <div class="field"><input name="check_all" type="checkbox" id="check_all" value="1" onclick="select_all();" /><label for="check_all"><strong>[[.Select_all.]]</strong></label></div>
        	<div class="field"><input name="reservation" type="checkbox" id="reservation" value="1" /><label for="reservation">[[.Reservation.]]</label></div>
        	<div class="field"><input name="housekeeping" type="checkbox" id="housekeeping" value="1" /><label for="housekeeping">[[.Housekeeping.]]</label></div>
        	<div class="field"><input name="restaurant" type="checkbox" id="restaurant" value="1" /><label for="restaurant">[[.Restaurant.]]</label></div>
			<div class="field"><input name="shop" type="checkbox" id="shop" value="1" /><label for="shop">[[.shop.]]</label></div>            
<!--        	<div class="field"><input name="karaoke" type="checkbox" id="karaoke" value="1" /><label for="karaoke">[[.Karaoke.]]</label></div>
        	<div class="field"><input name="massage" type="checkbox" id="massage" value="1" /><label for="massage">[[.Massage.]]</label></div>
        	<div class="field"><input name="warehouse" type="checkbox" id="warehouse" value="1" /><label for="warehouse">[[.Warehouse.]]</label></div>            
-->            <div>
                <input type="submit" value="[[.SynExcel.]]" />
            </div>
        </td>
    </tr>
</table>
</form>
</div>
<script type="text/javascript">
function select_all()
{
	if($('check_all').checked == true)
	{
		$('reservation').checked=true;
		$('housekeeping').checked=true;
		$('restaurant').checked=true;
		//$('karaoke').checked=true;
		//$('massage').checked=true;
		//$('warehouse').checked=true;
		$('shop').checked=true;
	}
	else
	{
		$('reservation').checked=false;
		$('housekeeping').checked=false;
		$('restaurant').checked=false;
		//$('karaoke').checked=false;
		//$('massage').checked=false;
		//$('warehouse').checked=false;	
		$('shop').checked=false;	
	}
}
</script>