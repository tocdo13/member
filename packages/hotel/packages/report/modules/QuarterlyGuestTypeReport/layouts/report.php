<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="35%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
    				<td></td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                    </td>
    			</tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td></td>
                    <td>
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" >[[.guest_type_report.]] [[.by.]] [[.quarter.]]<br /></font>
                            <br />
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                            [[|from_date|]] - [[|to_date|]] 
                            </span> 
                        </div>
                    </td>
                    <td></td>
                </tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>


<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.from_date.]]</td>
                                    <td>
                                        <input name="from_date" type="text" id="from_date" style="width:100px;" onchange="changevalue();" class="by-year"/>
                                    </td>
                                    <td>[[.to_date.]]</td>
                                    <td>
                                        <input name="to_date" type="text" id="to_date" style="width:100px;" onchange="changefromday();" class="by-year"/>
                                    </td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
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
.input{width: 30px; text-align: center;}
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
    .description{display:none}
    .input{display:none}
    .lbl_total{color: black !important;}
}
.total{background-color:#FFC}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
    }
);
//style="background-color:#FFC"
</script>

<?php /*oanh' stt */$count_group_name = 0;$count_group_name_1 = 0;?>
<!--LIST:group_name-->
<?php 
    $count_group_name += [[=group_name.colspan=]];
    if(!$count_group_name_1) $count_group_name_1 += [[=group_name.colspan=]];
?>
<!--/LIST:group_name-->
<?php //echo $count_group_name;?>
<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" rowspan="3" width="80px">[[.nationality.]]<br />(1)</th>
        <th class="report_table_header" colspan="<?php echo count([[=guest_type=]])+1;?>">[[.guest_checkin.]] <?php //echo Url::get('quarter').'/'.Url::get('year',date('Y')); ?></th>
        <th class="report_table_header total" rowspan="3">[[.total.]] [[.travel.]]<br /> (<?php echo (2+1+$count_group_name); ?>)</th>
        <th class="report_table_header total" rowspan="3">[[.total.]] [[.online.]]<br /> (<?php echo (3+1+$count_group_name); ?>)</th>
        <th class="report_table_header total" rowspan="3">[[.total.]]<br /> (<?php echo (4+1+$count_group_name); ?>)</th>
        <th class="report_table_header" colspan="<?php echo count([[=guest_type=]])+1;?>">[[.guest_old_and_checkin.]]<?php //echo Url::get('quarter').'/'.Url::get('year',date('Y')); ?></th>
        <th class="report_table_header total" rowspan="3">[[.total.]] [[.travel.]]<br /> (<?php echo (5+1+$count_group_name+1+$count_group_name); ?>)</th>
        <th class="report_table_header total" rowspan="3">[[.total.]] [[.online.]]<br /> (<?php echo (5+1+$count_group_name+2+$count_group_name); ?>)</th>
        <th class="report_table_header total" rowspan="3">[[.total.]] [[.night.]] [[.guest.]]<br /> (<?php echo (5+1+$count_group_name+3+$count_group_name); ?>)</th>
        <th class="report_table_header total" rowspan="3" width="60px">[[.total.]][[.night.]][[.room.]]<br /> (<?php echo (5+1+$count_group_name+4+$count_group_name); ?>)</th>
    </tr>
    <?php $i=2; ?>
    <tr>
        <?php $group_name = '';?>
        <!--LIST:group_name-->
        <?php  
        if($group_name != [[=group_name.group_name=]] and $group_name != '' )
        {
            echo '<th class="report_table_header total" rowspan="2">'.Portal::language('total').' '.Portal::language('walk_in').'<br />('.($i+[[=group_name.colspan=]]).')'.'</th>';
        }
        $group_name = [[=group_name.group_name=]];
        ?>
        <th class="report_table_header" colspan="[[|group_name.colspan|]]"><?php echo Portal::language(strtolower([[=group_name.group_name=]]));?></th>
        <!--/LIST:group_name-->
        
        <?php $group_name = '';$colspan=0;?>
        <!--LIST:group_name-->
        <?php  
        if($group_name != [[=group_name.group_name=]] and $group_name != '' )
        {
            echo '<th class="report_table_header total" rowspan="2">'.Portal::language('total').' '.Portal::language('walk_in').'<br />('.((5+1+$count_group_name)+[[=group_name.colspan=]]).')'.'</th>';
        }
        $group_name = [[=group_name.group_name=]];
        if(!$colspan) $colspan = [[=group_name.colspan=]];
        ?>
        
        <th class="report_table_header" colspan="[[|group_name.colspan|]]"><?php echo Portal::language(strtolower([[=group_name.group_name=]]));?></th>
        <!--/LIST:group_name-->
    </tr>
    <tr>
        <!--LIST:guest_type-->
        <th class="report_table_header">
            [[|guest_type.name|]]
            <br />
            <?php
                if($i == (2+$count_group_name_1) )
                    $i++; 
                echo '('.$i++.')';
            ?>
        </th>
        <!--/LIST:guest_type-->
        <?php $i = (4+1+$count_group_name+1); ?>
        <!--LIST:guest_type-->
        <th class="report_table_header">
        [[|guest_type.name|]]
        <br />
        <?php
            if($i == ((4+1+$count_group_name+1)+$count_group_name_1) )
                $i++; 
            echo '('.$i++.')';
        ?>
        </th>
        <!--/LIST:guest_type-->    
    </tr>
    
    
    <?php
        foreach($this->map['nationality'] as $key=>$value)
        {
            echo '<tr>';
            echo '<td>'.$value['nationality'].'</td>';
            $count = 0;
            foreach($this->map['guest_type'] as $k=>$v)
            {
                if($count==$colspan)
                {
                    echo '<td class="total">'.($value['WALK_IN today']?$value['WALK_IN today']:'').'</td>';
                }
                echo '<td>'.($value[$v['name'].' today']?$value[$v['name'].' today']:'').'</td>';
                $count++;
            }
            echo '<td class="total">'.($value['TRAVEL today']?$value['TRAVEL today']:'').'</td>';
            echo '<td class="total">'.($value['IS_ONLINE today']?$value['IS_ONLINE today']:'').'</td>';
            echo '<td class="total">'.($value['TOTAL today']?$value['TOTAL today']:'').'</td>';
            
            $count = 0;
            foreach($this->map['guest_type'] as $k=>$v)
            {
                if($count==$colspan)
                {
                    echo '<td class="total">'.($value['WALK_IN']?$value['WALK_IN']:'').'</td>';
                }
                echo '<td>'.($value[$v['name']]?$value[$v['name']]:'').'</td>';
                $count++;
            }

            echo '<td class="total">'.($value['TRAVEL']?$value['TRAVEL']:'').'</td>';
            echo '<td class="total">'.($value['IS_ONLINE']?$value['IS_ONLINE']:'').'</td>';
            echo '<td class="total">'.($value['TOTAL']?$value['TOTAL']:'').'</td>';
            //echo '<td class="total">'.($value['TOTAL NIGHT GUEST']?$value['TOTAL NIGHT GUEST']:'').'</td>';
            echo 
            '<td class="total">
                '.($value['total_room']?$value['total_room']:'').'
            </td>';
            echo '</tr>';
        }
        
        echo '<tr style="font-weight:bold;">';
        echo '<td>'.Portal::language('total').'</td>';
        $count = 0;
        foreach($this->map['guest_type'] as $k=>$v)
        {
            if($count==$colspan)
            {
                echo'<td class="total">'.([[=total=]]['WALK_IN today']?[[=total=]]['WALK_IN today']:'').'</td>';
            }
            echo '<td>'.([[=total=]][$v['name'].' today']?[[=total=]][$v['name'].' today']:'').'</td>';
            $count++;
        }
        echo'<td class="total">'.([[=total=]]['TRAVEL today']?[[=total=]]['TRAVEL today']:'').'</td>';
        echo'<td class="total">'.([[=total=]]['IS_ONLINE today']?[[=total=]]['IS_ONLINE today']:'').'</td>';
        echo'<td class="total">'.([[=total=]]['TOTAL today']?[[=total=]]['TOTAL today']:'').'</td>';
        
        $count = 0;
        foreach($this->map['guest_type'] as $k=>$v)
        {
            if($count==$colspan)
            {
                echo'<td class="total">'.([[=total=]]['WALK_IN']?[[=total=]]['WALK_IN']:'').'</td>';
            }
            echo '<td>'.([[=total=]][$v['name']]?[[=total=]][$v['name']]:'').'</td>';
            $count++;
        }
        echo'<td class="total">'.([[=total=]]['TRAVEL']?[[=total=]]['TRAVEL']:'').'</td>';
        echo'<td class="total">'.([[=total=]]['IS_ONLINE']?[[=total=]]['IS_ONLINE']:'').'</td>';
        echo'<td class="total">'.([[=total=]]['TOTAL']?[[=total=]]['TOTAL']:'').'</td>';
        echo'<td class="total">'.([[=total=]]['total_room']?[[=total=]]['total_room']:'').'</td>';
        /*       
        echo
        '<td class="total">
            <input id="total" type="text" class="input input_number" onkeyup="assign(this.id,this.value);" />
            <label id="lbl_total" class="lbl_total" style="color: white;" ></label>
        </td>';
        echo '</tr>';
        */
        //echo
        //'<td class="total">
        //    <label id="lbl_total" ></label>
        //</td>';
        echo '</tr>';
        
    ?>
</table>


<!---------FOOTER----------->

<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td ></td>
	<td > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >[[.general_accountant.]]</td>
	<td width="33%" >[[.director.]]</td>
</tr>
</table>
<br />
<br />
<p class="description" style="margin-left:20px;" align="left"><b>Diễn giải:</b><br />
- Từ cột (2) đến cột (13) đếm số khách mới check-in trong ngày<br />
- Từ cột (14) đến cột (23) đếm số khách ở qua đêm tại khách sạn trong thời gian báo cáo<br />
- (6) = (2) + (3) + (4) + (5)<br />
- (11) = (7) + (8) + (9) + (10)<br />
- (12) = (5) + (10)<br />
- (13) = (6) + (11)<br />
- (18) = (14) + (15) + (16) + (17)<br />
- (23) = (19) + (20) + (21) + (22)<br />
- (24): số phòng tương ứng với số khách (17) + (22)<br />
- (25): Số phòng tương ứng với số khách ở cột (26)<br />
- (26) = (18) + (23)
</p>
<script>
full_screen();
function assign(id, value)
{
    //alert(value);
    jQuery("#lbl_"+id).html(value);
}


function calc_total()
{
    var total = 0;
    jQuery(".lbl_total").each(function(){
        if(jQuery(this).html() != '')
        {
            total += to_numeric(jQuery(this).html());
            
        }
    });
    jQuery("#lbl_total").html(total);
    
}
</script>

<!---<div style="page-break-before:always;page-break-after:always;"></div>--->
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#from_date').datepicker();
    jQuery('#to_date').datepicker();
});
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