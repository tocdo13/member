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
                        <strong><?php echo Portal::language('template_code');?></strong>
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                    </td>
    			</tr>
    			<tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td align="left" width="35%">
                    </td>
    				<td> 
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('weekly_forecast');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                <?php echo $this->map['from_date'];?> - <?php echo $this->map['to_date'];?>
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
                                    <td><?php echo Portal::language('date_from');?></td>
                                    <td>
                                    	<input  name="from_date" id="from_date" onchange="changevalue();"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                                    </td>
                                    <td><?php echo Portal::language('date_to');?></td>
                                    <td>                             
                                    	<input  name="to_date" id="to_date" onchange="changefromday();"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                                    </td>
                                    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td><?php echo Portal::language('hotel');?></td>
                                	<td><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <?php //}?>
                                    <td><input type="submit" name="do_search" value="<?php echo Portal::language('report');?>"/></td>
                                </tr>
                            </table>
                        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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


<div style="float: left;"><strong>1. <?php echo Portal::language('forecast');?></strong></div>
<br />
<br />

<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
    	<th class="report_table_header" width="30px" rowspan="2"><?php echo Portal::language('date');?></th>
    	<?php if(isset($this->map['date']) and is_array($this->map['date'])){ foreach($this->map['date'] as $key1=>&$item1){if($key1!='current'){$this->map['date']['current'] = &$item1;?>
		<th style="background-color: #F4CCCC;" class="report_table_header" width="150px"  colspan="2"> <?php if(Portal::language()==1){
            echo $this->map['date']['current']['name_1'];
        }
        else
        {
            echo $this->map['date']['current']['name_2'] ;
        } ?></th>
        <?php }}unset($this->map['date']['current']);} ?>
    </tr>
    <tr bgcolor="#EFEFEF">
    	<?php if(isset($this->map['date']) and is_array($this->map['date'])){ foreach($this->map['date'] as $key2=>&$item2){if($key2!='current'){$this->map['date']['current'] = &$item2;?>
		<th class="report_table_header" width="150px" colspan="2"><?php echo $this->map['date']['current']['day'];?></th>
        <?php }}unset($this->map['date']['current']);} ?>
    </tr>
   
    <tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="30px" rowspan="2"></th>
    	<?php if(isset($this->map['date']) and is_array($this->map['date'])){ foreach($this->map['date'] as $key3=>&$item3){if($key3!='current'){$this->map['date']['current'] = &$item3;?>
        <th style="font-size: 12px;font-weight:normal;">
        <?php
        if(Portal::language()==1)
        {
            echo $this->map['date']['current']['number_guest_1'];
        }
        else
        {
            echo $this->map['date']['current']['number_guest_2'];
        } 
        ?></th>
        <th  style="font-size: 12px; font-weight: normal;">
        <?php 
        if(Portal::language()==1)
        {
            echo $this->map['date']['current']['number_room_1'];
        }
        else
        {
            echo $this->map['date']['current']['number_room_2'];
        }
        ?></th>
        <?php }}unset($this->map['date']['current']);} ?>
    </tr>
    <tr bgcolor="#EFEFEF">
        <?php if(isset($this->map['date']) and is_array($this->map['date'])){ foreach($this->map['date'] as $key4=>&$item4){if($key4!='current'){$this->map['date']['current'] = &$item4;?>
        <th ><?php echo $this->map['date']['current']['number_guest'];?></th>
        <th ><?php echo $this->map['date']['current']['number_room'];?></th>
        <?php }}unset($this->map['date']['current']);} ?>
    </tr>
  </table>   



<br />
<span style="float: left;"><?php echo Portal::language('note_room_than_10');?></span>
<br />
<br />

<?php if(isset($this->map['arr_note']) and is_array($this->map['arr_note'])){ foreach($this->map['arr_note'] as $key5=>&$item5){if($key5!='current'){$this->map['arr_note']['current'] = &$item5;?>
<table style="margin-left: 10px;"  cellpadding="3" cellspacing="0" width="80%" bordercolor="#CCCCCC">
<tr>
<td style="width: 120px; font-weight: bold; text-align: left;"><?php echo Portal::language('recode');?><span style="float: right;">:</span></td>
<td align="left"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['arr_note']['current']['id']));?>">#<?php echo $this->map['arr_note']['current']['id'];?></a></td>
</tr>

<tr>
<td style=" font-weight: bold; text-align: left;"><?php echo Portal::language('company_name_tour');?><span style="float: right;">:</span></td>
<td align="left"><?php echo $this->map['arr_note']['current']['name'];?></td>
</tr>


<tr>
<td style=" font-weight: bold; text-align: left;"><?php echo Portal::language('arrival_time');?> <span style="float: right;">:</span></td>
<td align="left"><?php echo $this->map['arr_note']['current']['arrival_time'];?> &nbsp <b><?php echo Portal::language('departure_time');?>:</b> <?php echo $this->map['arr_note']['current']['departure_time'];?></td>
</tr>

<tr>
<td style=" font-weight: bold; text-align: left;"><?php echo Portal::language('num_room');?><span style="float: right;">:</span></td>
<td align="left"><?php echo $this->map['arr_note']['current']['num_room'];?> &nbsp <?php echo Portal::language('room');?> &nbsp(
<?php
    foreach($this->map['arr_note']['current']['room_level'] as $row) 
    {
        echo  $row['num_level'].': '.$row['name'];
        echo ' ';
    }
?>
) </td>
</tr>

<tr>
<td style=" font-weight: bold; text-align: left;"><?php echo Portal::language('num_guest');?><span style="float: right;">:</span></td>
<td align="left"><?php echo $this->map['arr_note']['current']['num_guest'];?></td>
</tr>
</table>
<br/>

<?php }}unset($this->map['arr_note']['current']);} ?>

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