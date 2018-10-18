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
    				<td> 
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo User::id();?>
                    </td>
    			</tr>
    			<tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td align="left" width="35%">
                    </td>
    				<td> 
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" >[[.weekly_forecast.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[|from_date|]] - [[|to_date|]]
                            </span> 
                        </div>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
.sub_title_1{text-align:left; text-indent: 20px;}
.sub_title_2{text-align:left; text-indent: 40px; font-weight: normal!important; font-style: italic !important;}
.sub_title_3{text-align:left; text-indent: 60px;}
.arrow{font-size: 20px;}
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
                                    <td>[[.date_from.]]</td>
                                    <td>
                                    	<input name="from_date" type="text" id="from_date" onchange="changevalue();"/>
                                    </td>
                                    <td>[[.date_to.]]</td>
                                    <td>                             
                                    	<input name="to_date" type="text" id="to_date" onchange="changefromday();"/>
                                    </td>
                                    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php //}?>
                                    <td><input type="submit" name="do_search" value="[[.report.]]"/></td>
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
    #search{display:none;}
}
.table-bound{margin: 0 auto !important;}
.desc{text-align: left !important;}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date_from').datepicker();
	jQuery('#date_to').datepicker();

	jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
	jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
});

	function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
</script>


<div style="float: left;"><strong>1. [[.forecast.]]</strong></div>
<br />
<br />

<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
    	<th class="report_table_header" width="30px" rowspan="2">[[.date.]]</th>
    	<!--LIST:date-->
		<th style="background-color: #F4CCCC;" class="report_table_header" width="150px"  colspan="2"> <?php if(Portal::language()==1){
            echo [[=date.name_1=]];
        }
        else
        {
            echo [[=date.name_2=]] ;
        } ?></th>
        <!--/LIST:date-->
    </tr>
    <tr bgcolor="#EFEFEF">
    	<!--LIST:date-->
		<th class="report_table_header" width="150px" colspan="2">[[|date.day|]]</th>
        <!--/LIST:date-->
    </tr>
   
    <tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="30px" rowspan="2"></th>
    	<!--LIST:date-->
        <th style="font-size: 12px;font-weight:normal;">
        <?php
        if(Portal::language()==1)
        {
            echo [[=date.number_guest_1=]];
        }
        else
        {
            echo [[=date.number_guest_2=]];
        } 
        ?></th>
        <th  style="font-size: 12px; font-weight: normal;">
        <?php 
        if(Portal::language()==1)
        {
            echo [[=date.number_room_1=]];
        }
        else
        {
            echo [[=date.number_room_2=]];
        }
        ?></th>
        <!--/LIST:date-->
    </tr>
    <tr bgcolor="#EFEFEF">
        <!--LIST:date-->
        <th >[[|date.number_guest|]]</th>
        <th >[[|date.number_room|]]</th>
        <!--/LIST:date-->
    </tr>
  </table>   



<br />
<span style="float: left;">[[.note_room_than_10.]]</span>
<br />
<br />

<!--LIST:arr_note-->
<table style="margin-left: 10px;"  cellpadding="3" cellspacing="0" width="80%" bordercolor="#CCCCCC">
<tr>
<td style="width: 120px; font-weight: bold; text-align: left;">[[.recode.]]<span style="float: right;">:</span></td>
<td align="left"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=arr_note.id=]]));?>">#[[|arr_note.id|]]</a></td>
</tr>

<tr>
<td style=" font-weight: bold; text-align: left;">[[.company_name_tour.]]<span style="float: right;">:</span></td>
<td align="left">[[|arr_note.name|]]</td>
</tr>


<tr>
<td style=" font-weight: bold; text-align: left;">[[.arrival_time.]] <span style="float: right;">:</span></td>
<td align="left">[[|arr_note.arrival_time|]] &nbsp <b>[[.departure_time.]]:</b> [[|arr_note.departure_time|]]</td>
</tr>

<tr>
<td style=" font-weight: bold; text-align: left;">[[.num_room.]]<span style="float: right;">:</span></td>
<td align="left">[[|arr_note.num_room|]] &nbsp [[.room.]] &nbsp(
<?php
    foreach([[=arr_note.room_level=]] as $row) 
    {
        echo  $row['num_level'].': '.$row['name'];
        echo ' ';
    }
?>
) </td>
</tr>

<tr>
<td style=" font-weight: bold; text-align: left;">[[.num_guest.]]<span style="float: right;">:</span></td>
<td align="left">[[|arr_note.num_guest|]]</td>
</tr>
</table>
<br/>

<!--/LIST:arr_note-->

<script>full_screen();</script>
<div style="page-break-before:always;page-break-after:always;"></div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#no_of_breakfast').keyup(function(){
        var bf_revenue = to_numeric(jQuery('#no_of_breakfast').val()?jQuery('#no_of_breakfast').val():0) * 63000;
        jQuery('#bf_revenue').html(number_format(bf_revenue));
    })
    
})
</script>

<?php

function compare($x, $y)
{
    $x = round($x,2);
    $y = round($y,2);
    //echo 'x'.$x.'<br />';
    //echo 'y'.$y;
    $prefix = '';
    $result = 0;
    
    if($x == $y)
    {
        return $prefix.' '.System::display_number($result).' %';
    }
    
    if($y == 0)
        $y = 1;
    if($x < $y)
    {
        $prefix = '<strong class="arrow">&darr;</strong>';
        $result = 100 - ($x*100/$y);
    }
        
    else
        if($x > $y)
        {
            $prefix = '<strong class="arrow">&uarr;</strong>';
            $result = ($x*100/$y)-100;
        }
    return $prefix.' '.System::display_number($result).' %';
            
}
?>