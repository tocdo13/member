<style type="text/css" media="print">	
	#search_bound{
		display:none;
	}
</style>
<?php 
$title = Portal::language('product_list');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_list'));
?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left">
			<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
            <br />
            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
			</td>
			</tr>	
		</table>
            <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC">
                <tr>
                    <td width="100%" align="center">
						<h2>B&#7842;NG K&Ecirc; H&Agrave;NG H&Oacute;A </h2>
						<div style="padding-bottom:5px;text-decoration:underline;">[[.date.]]: [[|from_arrival_time|]] - [[|to_arrival_time|]]</div>
                        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">[[.time.]] : 
                        <?php if( isset( [[=start_shift_time=]] ) ) echo [[=start_shift_time=]]; ?> - <?php if( isset( [[=end_shift_time=]] ) ) echo [[=end_shift_time=]]; ?>
                        </div>
                        [[.total.]]: [[|total|]] h&agrave;ng h&oacute;a
					</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
				<div id="search_bound">
				<form method="get" name="SearchKaraokeCatalogueForm">
					<input type="hidden" name="page" value="<?php echo URL::get('page');?>" />
                    <table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
                        <tr><td>
                        	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
                            	<table width="100%">
                                    <tr><td >
                                            <table style="margin: 0 auto;">
                                                <tr>
                            						<td>
                                                        [[.date_from.]]: <input name="from_arrival_time" type="text" id="from_arrival_time" onchange="changevalue();" size="8"/> 
                                                        [[.date_to.]]: <input name="to_arrival_time" type="text" id="to_arrival_time" onchange="changefromday();" size="8"/>
                                                         <script>
                                                                function changevalue(){
                                                                    var myfromdate = $('from_arrival_time').value.split("/");
                                                                    var mytodate = $('to_arrival_time').value.split("/");
                                                                    if(myfromdate[2] > mytodate[2]){
                                                                        $('to_arrival_time').value =$('from_arrival_time').value;
                                                                    }else{
                                                                        if(myfromdate[1] > mytodate[1]){
                                                                            $('to_arrival_time').value =$('from_arrival_time').value;
                                                                        }else{
                                                                            if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                                                $('to_arrival_time').value =$('from_arrival_time').value;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                function changefromday(){
                                                                    var myfromdate = $('from_arrival_time').value.split("/");
                                                                    var mytodate = $('to_arrival_time').value.split("/");
                                                                    if(myfromdate[2] > mytodate[2]){
                                                                        $('from_arrival_time').value= $('to_arrival_time').value;
                                                                    }else{
                                                                        if(myfromdate[1] > mytodate[1]){
                                                                            $('from_arrival_time').value = $('to_arrival_time').value;
                                                                        }else{
                                                                            if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                                                $('from_arrival_time').value =$('to_arrival_time').value;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            </script>
                                                    </td>
                                                    <td><strong>|</strong></td>
                                                    <td>
                                                        [[.by_time.]]
                                                        <input name="start_time" type="text" id="start_time" style="width:30px;" onblur="validate_time(this,this.value);" />
                                                        [[.to.]]
                                                        <input name="end_time" type="text" id="end_time" style="width:30px;" onblur="validate_time(this,this.value);" />
                                                    </td>
                                                    <td><strong>|</strong></td>
                            						<td>[[.code.]]</td>
                            						<td><input name="code" type="text" id="code" style="width:50px;"/></td>
                                                    <td><strong>|</strong></td>
                                                    <td>[[.name.]]</td>
                            						<td><input name="name" type="text" id="name" style="width:100px;"/></td>
                                                    <td><strong>|</strong></td>
                            						<td>[[.category_id.]]</td>
                            						<td>
                            							<select name="category_id" id="category_id" style="width:150px;"></select>
                            							<input name="action" type="hidden" id="action"/>
                                                    </td>
                            						<td><select name="order_by" id="order_by"></select></td>
                            						<td><input type="submit" value="  [[.search.]]  "/></td>
                                                </tr>
                                            </table>
                                    </td></tr>
                                </table>
                        	</div>
                        </td></tr>
                    </table>
				</form>
				</div>
				<form name="KaraokeCatalogueListForm" method="post">
                    <div style="border:2px solid #FFFFFF;">
					<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CECFCE">
						<tr valign="middle" style="text-transform:uppercase;" bgcolor="#EFEFEF">
							<th nowrap align="left" width="10%">[[.code.]]</th>
							<th nowrap align="left" width="60%">[[.name.]]</th>
							    <th align="left" nowrap="nowrap">[[.unit_id.]]</th>
							    <th align="right" nowrap="nowrap">[[.quantity.]] </th>
					    </tr>
						<!--LIST:items-->
						<tr>
							<td align="left" nowrap id="id_[[|items.id|]]">
									[[|items.id|]]								</td>
							<td align="left" nowrap id="name_[[|items.id|]]">
									[[|items.name|]]
								</td>
								    <td align="left" nowrap="nowrap" id="unit_[[|items.id|]]"> [[|items.unit_name|]] </td>
								    <td align="right" nowrap="nowrap" id="price_[[|items.id|]]"> [[|items.quantity|]] </td>
					    </tr>
						<!--/LIST:items-->
					</table>
                    </div>
            <input name="cmd" type="hidden" value="">
			</form>            
		</td>
		</tr>
	</table>
    <br />
	<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center">[[.day.]] <?php echo date('d');?>[[.month.]] <?php echo date('m');?>[[.year.]] <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center"><strong>[[.creator.]]</strong></td>
			<td width="33%" align="center"><strong>[[.general_accountant.]]</strong></td>
			<td width="33%" align="center"><strong>[[.director.]]</strong></td>
		</tr>
		</table>
		<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
 </td>
</tr>
</table>
<div style="page-break-before:always;page-break-after:always;"></div>
<script>
	jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    jQuery('#start_time').mask("99:99");
    jQuery('#end_time').mask("99:99");
    
    function validate_time(obj,value)
    {
        if(value != "__:__")
        {
            var arr = value.split(":")
            var h = arr[0];
            var m = arr[1];
            if(is_numeric(h.toString()))
            {
                if(h>23)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }
            if(is_numeric(m.toString()))
            {
                if(m>59)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }  
        }
    }
</script>