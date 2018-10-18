<?php //System::debug($this->map['items']); ?>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<?php 
				if(($this->map['page_no']==$this->map['start_page']))
				{?>
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
                        <strong><?php echo Portal::language('template_code');?></strong>
                        <br />
                        
                        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>

                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('arrival_day_used_room_list');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('from_date');?>&nbsp;<?php echo $this->map['day'];?>&nbsp; <?php echo Portal::language('to');?>&nbsp; <?php 
                                if(isset($this->map['to_date']))
                                {
                                    echo $this->map['to_date'];
                                } 
                                else 
                                {
                                    echo date('d/m/Y');
                                } ?>
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
                                	<td><?php echo Portal::language('line_per_page');?></td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="4" style="text-align:right; width: 50px;"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="4" style="text-align:right;width: 30px;"/></td>
                                    <td><?php echo Portal::language('from_page');?></td>
                                	<td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right;width: 30px;"/></td>
                                    <td>|</td>
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
                                    <td><?php echo Portal::language('from_date');?> &nbsp;<input  name="date" id="date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
                                	<td><?php echo Portal::language('to');?>&nbsp;<input  name="to_date" id="to_date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><?php echo Portal::language('status');?></td>
                                	<td><select  name="status" id="status"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('early_late');?></td>
                                	<td><select  name="early_late" id="early_late"><?php
					if(isset($this->map['early_late_list']))
					{
						foreach($this->map['early_late_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('early_late',isset($this->map['early_late'])?$this->map['early_late']:''))
                    echo "<script>$('early_late').value = \"".addslashes(URL::get('early_late',isset($this->map['early_late'])?$this->map['early_late']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/></td>
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
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
        
        jQuery('#to_date').datepicker();
    }
);
</script>

				<?php
				}
				?>

<!---------REPORT----------->	
<?php 
				if(($this->map['real_room_count']))
				{?>


<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="20px"><?php echo Portal::language('reservation_room_code');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('room_level');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('price');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('tour');?>,<?php echo Portal::language('company');?></th>                        
        <th class="report_table_header" width="150px"><?php echo Portal::language('guest_name');?></th>
        <th class="report_table_header" width="20px"><?php echo Portal::language('countries');?></th>
        <th class="report_table_header" width="20px"><?php echo Portal::language('A/c');?></th>
        <th class="report_table_header" width="60px"><?php echo Portal::language('arrival_date');?></th>
        <th class="report_table_header" width="60px"><?php echo Portal::language('departure_date');?></th>
        <th class="report_table_header" width="20px"><?php echo Portal::language('eililodayuse_qty');?></th>
        <th class="report_table_header" width="40px"><?php echo Portal::language('flight_code');?></th>
        <th class="report_table_header" width="60px"><?php echo Portal::language('flight_arrival_time');?></th>
        <th class="report_table_header" width="70px"><?php echo Portal::language('car_note_arrival');?></th>
    </tr>
    
    <?php
        $i=1;
        $is_rowspan = false; 
        $total_money=0;
        $total_adult = 0;
        $total_child = 0;
        $total_night = 0;
        $total_room = 0;
        
        $total_money2=0;
        $total_adult2 = 0;
        $total_child2 = 0;
        $total_night2 = 0;
        $total_room2 = 0;
        $total_room_4 = 0;
        //System::debug($this->map['count_traveller'][$this->map['items']['current']['reservation_room_code']]['num']);
    ?>
    <?php
    if($this->map['page_no']!=1){
    ?>
        <tr>
           <th><?php echo Portal::language('last_page_summary');?></th>
           <th><?php echo System::display_number($this->map['last_total_sammary']['total_room']); ?></th>
           <th></th>
           <th><?php echo System::display_number($this->map['last_total_sammary']['total_price']); ?></th>
           <th colspan="3"></th>
           <th><?php echo System::display_number($this->map['last_total_sammary']['total_adult'])."/".System::display_number($this->map['last_total_sammary']['total_child']); ?></th>
           <th colspan="2"></th>
           <th><?php echo System::display_number($this->map['last_total_sammary']['total_night']); ?></th>
           <th colspan="3"></th>
        </tr>
    <?php } ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr bgcolor="white">
		
        <?php 
            $k = $this->map['count_traveller'][$this->map['items']['current']['reservation_room_code']]['num'];
            //echo $k.'-'.$this->map['items']['current']['reservation_room_code'].'-'.$this->map['items']['current']['reservation_id'];
            if($is_rowspan == false)
            {
        ?>
        <td class="report_table_column" rowspan="<?php echo $k; $total_room += 1;?>"  align="center">
            <div style="font-size:11px;">
                <a href="<?php 
                $reservation_room_code = explode('_',$this->map['items']['current']['reservation_room_code']);
                $reservation_room_code = $reservation_room_code[0];
                echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$reservation_room_code));
                ?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a>
            </div>
        </td>
        <td class="report_table_column" rowspan="<?php echo $k; $total_room_4 += $this->map['items']['current']['room_num'];?>" align="center"><div style="font-size:11px;">
            <strong><?php echo $this->map['items']['current']['room_name'];?></strong><?php 
				if(($this->map['items']['current']['early_checkin'] != 0))
				{?> - E.I
				<?php
				}
				?>
                                <?php 
				if(($this->map['items']['current']['late_checkout'] != 0))
				{?>- L.O
				<?php
				}
				?>
                                <?php 
				if(($this->map['items']['current']['late_checkin'] != 0))
				{?>- L.I
				<?php
				}
				?>
                                <?php 
				if(($this->map['items']['current']['day_use'] != 0))
				{?>- D.U
				<?php
				}
				?>
        </div></td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>"  align="center"><div style="font-size:11px;"><?php echo $this->map['items']['current']['brief_name'];?></div></td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>" align="right"><div style="font-size:11px;"><?php echo System::display_number($this->map['items']['current']['price']); $total_money += $this->map['items']['current']['price'];?></div></td>
        <td class="report_table_column" style="white-space: normal; text-align: left; font-size:10px;" rowspan="<?php echo $k; ?>"><div style="font-size:13px;"><b><?php echo $this->map['items']['current']['note'];?></b></div><i><?php echo $this->map['items']['current']['reservation_note'];?></i></td> 
        <?php
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>                           
		<td class="report_table_column" style="text-align: left;">
            <div style="font-size:11px;">
                <a href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['traveller_id']));?>" target="_blank"><?php echo $this->map['items']['current']['fullname'];?></a>
            </div>        
        </td>
        <td class="report_table_column" style="white-space: normal;" align="center"><div style="font-size:11px;"><?php echo $this->map['items']['current']['country_code'];?></div></td>
        
        <?php
            $i++;
            }
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" align="center"><div style="font-size:11px;">
            <!--Neu phong co khach thi cho ra so khach, neu khong thi cho ra so adult trong csdl-->
            <?php echo $this->map['items']['current']['adult']; $total_adult += $this->map['items']['current']['adult'];?>/<?php echo ($this->map['items']['current']['child']?$this->map['items']['current']['child']:0); if ($this->map['items']['current']['child'] > 0) $total_child += $this->map['items']['current']['child']; ?></div>        </td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" align="center"><div style="font-size:11px;">
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['time_in']);?></div>        </td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" align="center"><div style="font-size:11px;">
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['time_out']);?></div>        </td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>"  align="center"><div style="font-size:11px;"><?php echo $this->map['items']['current']['room_num'];?><?php $total_night += $this->map['items']['current']['room_num'];?></div></td>
        <?php
            }
        ?>
        <td class="report_table_column"><div style="font-size:11px;"><?php echo $this->map['items']['current']['flight_code'];?></div></td>
        <td class="report_table_column"  align="center"><div style="font-size:11px;">
            <?php echo $this->map['items']['current']['flight_arrival_time']?date('d/m/Y H:i',$this->map['items']['current']['flight_arrival_time']):'';?></div>        </td>
        <td class="report_table_column"><div style="font-size:11px;"><?php echo $this->map['items']['current']['car_note_arrival'];?></div></td>
        <?php 
            if($is_rowspan == false)
            {
        ?>

        <?php
            $is_rowspan = true;
            } 
        ?>
        <?php
            if($k ==0 || $k ==1 || $i>$k)
            {
                $i = 1;
                $is_rowspan = false;
            } 
        ?>
    </tr>
	<?php }}unset($this->map['items']['current']);} ?>
    <!-- Manh them truong hop phan trang co tong trang truoc sau. -->
    <tr>
        <th><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
        <th><?php echo System::display_number($this->map['total_sammary']['total_room']); ?></th>
        <th></th>
        <th><?php echo System::display_number($this->map['total_sammary']['total_price']); ?></th>
        <th colspan="3"></th>
        <th><?php echo System::display_number($this->map['total_sammary']['total_adult'])."/".System::display_number($this->map['total_sammary']['total_child']); ?></th>
        <th colspan="2"></th>
        <th><?php echo System::display_number($this->map['total_sammary']['total_night']); ?></th>
        <th colspan="3"></th>
    </tr>
    
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <?php 
				if((($this->map['room_count']!=$this->map['real_room_count']) && (($this->map['real_page_no']==$this->map['real_total_page']))))
				{?>
	<!--<tr bgcolor="white">
		<td colspan="1" class="report_table_column"><strong><div style="font-size:10px;"><?php echo Portal::language('total');?></div></strong></td>
		<td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room);?></div></strong></td>
        <td class="report_table_column">&nbsp;</td>
        <td class="report_table_column"  align="right"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_money) ?></div></strong></td>
		<td colspan="3" class="report_table_column">&nbsp;</td>
        <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_adult);?>/<?php //echo System::display_number($total_child);?></div></strong></td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room_4);?></div></strong></td>
        <td colspan="10" class="report_table_column">&nbsp;</td>
	</tr>-->
     <?php }else{ ?>
    	<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
    	<!--<tr bgcolor="white">
    		<td colspan="1" class="report_table_column"><strong><div style="font-size:10px;"><?php echo Portal::language('total');?></div></strong></td>
    		<td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room);?></div></strong></td>
            <td class="report_table_column">&nbsp;</td>
            <td class="report_table_column"  align="right"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_money) ?></div></strong></td>
   		  <td colspan="3" class="report_table_column">&nbsp;</td>
            <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_adult);?>/<?php //echo System::display_number($total_child);?></div></strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td class="report_table_column" align="center"><strong><div style="font-size:10px;"><?php //echo System::display_number($total_room_4);?></div></strong></td>
            <td colspan="10" class="report_table_column">&nbsp;</td>
    	</tr>-->
    	
				<?php
				}
				?>
    
				<?php
				}
				?>
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">
  <?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></div>
</center>
<br/>

<?php 
				if((($this->map['page_no']==$this->map['total_page']) || ($this->map['real_page_no']==$this->map['real_total_page'])))
				{?>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
        <td></td>
	<td align="center" > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>

				<?php
				}
				?>
<div style="page-break-before:always;page-break-after:always;"></div>

 <?php }else{ ?>
<strong><?php echo Portal::language('no_data');?></strong>

				<?php
				}
				?>