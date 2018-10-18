<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
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
                        <strong><?php echo Portal::language('template_code');?></strong>
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('housekeeping_report');?><br /></font><br />
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo $this->map['from_time'];?>&nbsp;<?php echo Portal::language('date');?>&nbsp;<?php echo $this->map['from_date'];?><!--&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>-->
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
                        <form name="HousekeepingReportForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td style="width:80px;"><?php echo Portal::language('line_per_page');?></td>
                                	<td ><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="2" style="text-align:right;width:72px;"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="2" style="text-align:right; width: 100px;"/></td>
                                    <td><?php echo Portal::language('from_page');?></td>
                                	<td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right; width: 80px;"/></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
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
                                    <?php }?>
                                    <td><?php echo Portal::language('floor');?></td>
                                	<td><select  name="floor_id" id="floor_id"><?php
					if(isset($this->map['floor_id_list']))
					{
						foreach($this->map['floor_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('floor_id',isset($this->map['floor_id'])?$this->map['floor_id']:''))
                    echo "<script>$('floor_id').value = \"".addslashes(URL::get('floor_id',isset($this->map['floor_id'])?$this->map['floor_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('room');?></td>
                                	<td><select  name="room_id" id="room_id"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  " onclick="return check_search();"/></td>
                                </tr>
                                <tr>    
                                    <td style="width:85px;"><?php echo Portal::language('HK_status');?></td>
                                	<td><select  name="house_status" id="house_status" style="width:70px;"><?php
					if(isset($this->map['house_status_list']))
					{
						foreach($this->map['house_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('house_status',isset($this->map['house_status'])?$this->map['house_status']:''))
                    echo "<script>$('house_status').value = \"".addslashes(URL::get('house_status',isset($this->map['house_status'])?$this->map['house_status']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('fo_status');?></td>
                                	<td><select  name="fo_status" id="fo_status"><?php
					if(isset($this->map['fo_status_list']))
					{
						foreach($this->map['fo_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('fo_status',isset($this->map['fo_status'])?$this->map['fo_status']:''))
                    echo "<script>$('fo_status').value = \"".addslashes(URL::get('fo_status',isset($this->map['fo_status'])?$this->map['fo_status']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('date');?></td>
                                	<td><input  name="from_date" id="from_date" style="width: 80px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <!--<td><?php echo Portal::language('to');?></td>
                                	<td><input  name="to_date" id="to_date" style="width: 80px;" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>-->
                                    <td><?php echo Portal::language('time');?></td>
                                    <td><input  name="from_time" id="from_time" style="width: 85px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>"></td>
                                    <!--<td><?php echo Portal::language('to_time');?></td>
                                    <td><input  name="to_time" id="to_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>"></td>-->
                                   
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
    #search{display:none}
}
</style>


				<?php
				}
				?>
<!---------REPORT----------->	
<?php 
				if((isset($this->map['items'])))
				{?>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('guest_name');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('nationality');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('HK_status');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('FO_status');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('arrival_date');?></th>                        
        <th class="report_table_header" width="100px"><?php echo Portal::language('departure_date');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('special_request');?></th>
    </tr>   
    <?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	        
    <!--<tr>
        <td colspan="4" class="report_sub_title" align="center"><b><?php echo Portal::language('last_page_summary');?></b></td>
		<td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['total_before_tax']);?></strong></td>
        <td align="center" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['total']);?></strong></td>
        
	</tr>-->
	
				<?php
				}
				?>
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr bgcolor="white">
        <?php
            $k = $this->map['count_traveller'][$this->map['items']['current']['code']]['num'];
            if($is_rowspan == false)
            {
        ?>
		<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['room_name'];?></td>
        <?php
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['guest_name'];?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['nationality'];?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['hk_status'];?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['fo_status'];?></td>
        <td align="center" class="report_table_column" ><?php echo($this->map['items']['current']['time_in']>0?(Date('d/m/Y',$this->map['items']['current']['time_in'])):'') ?></td>
        <td align="center" class="report_table_column" ><?php echo($this->map['items']['current']['time_out']>0?(Date('d/m/Y',$this->map['items']['current']['time_out'])):'') ?></td>
	    <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['special_request'];?></td>
        <?php
           $i++ ;}
        ?>
        <?php
            if($is_rowspan == false)
            {
                $is_rowspan = true;
            } 
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>  
    </tr>
	<?php }}unset($this->map['items']['current']);} ?>
    <!--<tr>
        <td align="center" colspan="4" class="report_sub_title" ><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_before_tax']);?></strong></td>
        <td align="center" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total']);?></strong></td>
    </tr>-->
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">
  <?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></div>
</center>
<br/>

<?php 
				if((($this->map['real_page_no']==$this->map['real_total_page'])))
				{?>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
</tr>
</table>
<p>&nbsp;</p>

				<?php
				}
				?>

<div style="page-break-before:always;page-break-after:always;"></div>

 <?php }else{ ?>
<strong><?php echo Portal::language('no_data');?></strong>

				<?php
				}
				?>
<script>
    jQuery(document).ready(function(){
        jQuery("#from_date").datepicker();
    	jQuery("#to_date").datepicker();
    });
    full_screen();
    function changevalue(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('to_date').value =$('from_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('to_date').value =$('from_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('to_date').value =$('from_date').value;
                }
            }
        }
    }
    function changefromday(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('from_date').value= $('to_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('from_date').value = $('to_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('from_date').value =$('to_date').value;
                }
            }
        }
    }
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));  
        if(to_numeric(hour_from[0]) >23 ||  to_numeric(hour_from[1]) >59)
        {
            alert('<?php echo Portal::language('the_max_time_is_2359_try_again');?>');
            return false;
        }
        else
        { 
            return true;       
        }   
    }
    //end:KID them ham check dieu kien search
</script>