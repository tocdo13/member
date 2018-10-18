<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}

</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.departure_room_list_new.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.day.]]&nbsp;[[|day|]]
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>
<!---------SEARCH----------->
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id" style="width: 150px;"></select></td>
                                    <?php }?>
                                    <td>[[.date.]]</td>
                                	<td><input name="date" type="text" id="date" style="width: 70px;" /></td>
                                    <td>[[.status.]]</td>
                                	<td><select name="status" id="status" style="width: 50px;"></select></td>
                                    <td>[[.customer.]]</td>
                                    <td><select name="customer_id" id="customer_id" style="width: 150px;"></select></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                    <td><button id="export">[[.export.]]</button></td>
                                
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
    }
);
</script>
<!---------REPORT----------->	
<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
    <tr bgcolor="#EFEFEF">
        <th width="10px" style="text-align: center;">[[.stt.]]</th>
        <th width="20px" style="text-align: center;">[[.reservation_room_code.]]</th>
        <th width="100px" style="text-align: center;">[[.source.]]</th> 
        <th width="100px" style="text-align: center;">[[.note.]]</th> 
        <th width="30px" style="text-align: center;">[[.room.]]</th>
        <th width="50px" style="text-align: center;">[[.room_level.]]</th>
        <th width="60px" style="text-align: center;">[[.arrival_date.]]</th>
        <th width="60px" style="text-align: center;">[[.departure_date.]]</th>
        <th width="60px" style="text-align: center;">[[.status.]]</th>
        <th width="20px" style="text-align: center;">[[.night.]]</th>
        <th width="150px" style="text-align: center;">[[.guest_name.]]</th>
        <th width="60px" style="text-align: center;">[[.arrival_date.]]</th>
        <th width="60px" style="text-align: center;">[[.departure_date.]]</th>
    </tr>
    <?php $r_id = '';$rr_id='';
    ?>
    <!--LIST:items-->
        <!--LIST:items.room-->
            <!--LIST:items.room.traveller-->
                <tr>
                    <?php if([[=items.id=]]!=$r_id){ $r_id=[[=items.id=]];?>
                        <td rowspan="<?php echo [[=items.count=]];?>">[[|items.stt|]]</td>
                        <td rowspan="<?php echo [[=items.count=]];?>" style="text-align: center;"><b><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.id=]]));?>" target="_blank">[[|items.id|]]</a></b></td>
                        <td rowspan="<?php echo [[=items.count=]];?>" style="text-align: left;"><div style="font-size:13px;"><b>[[|items.note|]]</b><br/>[[|items.reservation_note|]]</div></td>
                    <?php }?>
                    <?php if([[=items.room.id=]]!=$rr_id){ $rr_id=[[=items.room.id=]];?>
                    <td rowspan="<?php echo [[=items.room.count=]];?>" style="text-align: left;"><i>[[|items.room.note_room|]]</i></td>
                        <td rowspan="<?php echo [[=items.room.count=]];?>" style="text-align: center;"><b><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.id=]],'r_r_id'=>[[=items.room.id=]]));?>" target="_blank">[[|items.room.room_name|]]</a></b></td>
                        <td rowspan="<?php echo [[=items.room.count=]];?>">[[|items.room.room_level|]]</td>
                        <td rowspan="<?php echo [[=items.room.count=]];?>" style="text-align: center;"><?php echo date('d/m/Y H:i',[[=items.room.time_in_room=]]);?></td>
                        <td rowspan="<?php echo [[=items.room.count=]];?>" style="text-align: center;"><?php echo date('d/m/Y H:i',[[=items.room.time_out_room=]]);?></td>
                        <td rowspan="<?php echo [[=items.room.count=]];?>">[[|items.room.status|]]</td>
                        <td rowspan="<?php echo [[=items.room.count=]];?>" style="text-align: center;">[[|items.room.night|]]</td>
                    <?php }?>
                    <td><a href="<?php echo Url::build('traveller',array('id'=>[[=items.room.traveller.traveller_id=]]));?>" target="_blank">[[|items.room.traveller.fullname|]]</a></td>
                    <td style="text-align: center;"><?php echo ([[=items.room.traveller.time_in=]]!='')?date('d/m/Y H:i',[[=items.room.traveller.time_in=]]):'';?></td>
                    <td style="text-align: center;"><?php echo ([[=items.room.traveller.time_out=]]!='')?date('d/m/Y H:i',[[=items.room.traveller.time_out=]]):'';?></td>
                </tr>
            <!--/LIST:items.room.traveller-->
        <!--/LIST:items.room-->
    <!--/LIST:items-->
    <tr>
        <td colspan="4"><b>[[.total.]]</b></td>
        <td style="text-align: center;"><b>[[|total_room|]]</b></td>
        <td colspan="4"></td>
        <td style="text-align: center;"><b>[[|total_night|]]</b></td>
        <td colspan="12"></td>
    </tr>
</table>
<!---------FOOTER----------->
<br/>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
        <td></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    });
</script>
