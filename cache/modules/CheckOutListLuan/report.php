<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<?php 
				if(($this->map['real_page_no']==1))
				{?>
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <link rel="stylesheet" href="packages/core/skins/default/css/jquery/datepicker.css" />
    <script type="text/javascript" src="packages/core/includes/js/jquery/datepicker.js"></script>
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
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('checkout_room_list');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                <?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['date'];?>
                            </span> 
                        </div>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
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
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td><?php echo Portal::language('line_per_page');?></td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('from_page');?></td>
                                	<td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>|</td>
                                    <td><?php echo Portal::language('date');?></td>
                                    <td><input  name="date" id="date" / type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
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
		<th class="report_table_header" width="30px"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" width="30px"><?php echo Portal::language('reservation_room_code');?></th>
        <th class="report_table_header" width="40px"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="70px"><?php echo Portal::language('room_level');?></th>
        <th class="report_table_header" width="70px"><?php echo Portal::language('price');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('tour');?>,<?php echo Portal::language('company');?></th>                        
		<th class="report_table_header" width="150px"><?php echo Portal::language('customer_name');?></th>
		<th class="report_table_header" width="70px"><?php echo Portal::language('nationality');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('pax(a/c)');?></th>
		<th class="report_table_header" width="70px"><?php echo Portal::language('time_in');?></th>
		<th class="report_table_header" width="70px"><?php echo Portal::language('time_out');?></th>
        <th class="report_table_header" width="40px"><?php echo Portal::language('night');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('note');?></th>        
        <th class="report_table_header" width="50px"><?php echo Portal::language('flight_code');?></th>
        <th class="report_table_header" width="80px"><?php echo Portal::language('flight_departure_time');?></th>
	</tr>
    
    <?php 
        $i=1;
        $is_rowspan = false; 
        $is_rowspan_1 = false;
        //System::debug($this->map['count_traveller'][$this->map['items']['current']['reservation_room_code']]['num']);
    ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr bgcolor="white">
		
        <?php 
            $k = $this->map['count_traveller'][$this->map['items']['current']['reservation_room_code']]['num'];
            if($is_rowspan == false)
            {
        ?>
        <td class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['stt'];?></td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>">
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['reservation_id']));?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a>
        </td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['room_name'];?></td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['room_level'];?></td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
        <td class="report_table_column" style="white-space: normal; text-align: left;" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['note'];?></td> 
        <?php 
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>                           
		<td class="report_table_column" style="white-space: normal; text-align: left;">
        <a href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['traveller_id']));?>" target="_blank"><?php echo $this->map['items']['current']['fullname'];?></a>
        </td>
		<td class="report_table_column" style="white-space: normal;"><?php echo $this->map['items']['current']['nationality'];?></td>
        <?php
            }
        ?>
        <?php
            if($is_rowspan == false)
            {
        ?>
        <td class="report_table_column" rowspan="<?php echo $k; ?>" >
            <?php echo ($this->map['items']['current']['adult']?$this->map['items']['current']['adult']:0) ?>/<?php echo ($this->map['items']['current']['child']?$this->map['items']['current']['child']:0) ?>
        </td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" >
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['time_in']);?>
        </td>
		<td class="report_table_column" rowspan="<?php echo $k; ?>" >
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['time_out']);?>
        </td>
        <td class="report_table_column" rowspan="<?php echo $k; ?>" ><?php echo $this->map['items']['current']['night'];?></td>
        <td align="left" class="note" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['reservation_note'];?><br /><?php echo $this->map['items']['current']['note_room'];?></td>
        <?php
            }
        ?>
        <td class="report_table_column"><?php echo $this->map['items']['current']['flight_code'];?></td>
        <td class="report_table_column">
            <?php echo $this->map['items']['current']['flight_departure_time']?date('d/m/Y H:i',$this->map['items']['current']['flight_departure_time']):'';?>
        </td>
        <?php
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
        <?php
            $i++;
            }
        ?>
        
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
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <?php 
				if((($this->map['room_count']!=$this->map['real_room_count']) && (($this->map['real_page_no']==$this->map['real_total_page']))))
				{?>
	<tr bgcolor="white">
		<td colspan="2" class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
		<td class="report_table_column"><strong><?php echo $this->map['real_room_count'];?></strong></td>
        <td class="report_table_column">&nbsp;</td>
        <td class="report_table_column"><strong><?php echo System::display_number($this->map['real_total_money']) ?></strong></td>
		<td colspan="3" class="report_table_column">&nbsp;</td>
        <td class="report_table_column"><strong><?php echo $this->map['real_num_people'];?>/<?php echo $this->map['real_num_child'];?></strong></td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        <td class="report_table_column"><strong><?php echo $this->map['real_night'];?></strong></td>
        <td colspan="10" class="report_table_column">&nbsp;</td>
	</tr>
     <?php }else{ ?>
    	<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
    	<tr bgcolor="white">
    		<td colspan="2" class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
    		<td class="report_table_column"><strong><?php echo $this->map['real_room_count'];?></strong></td>
            <td class="report_table_column">&nbsp;</td>
            <td class="report_table_column"><strong><?php echo System::display_number($this->map['real_total_money']) ?></strong></td>
    		<td colspan="3" class="report_table_column">&nbsp;</td>
            <td class="report_table_column"><strong><?php echo $this->map['real_num_people'];?>/<?php echo $this->map['real_num_child'];?></strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td class="report_table_column"><strong><?php echo $this->map['real_night'];?></strong></td>
            <td colspan="10" class="report_table_column">&nbsp;</td>
    	</tr>
    	
				<?php
				}
				?>
    
				<?php
				}
				?>
</table>


<!---------FOOTER----------->
<center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
<br/>

<?php 
				if((($this->map['page_no']==$this->map['total_page']) || ($this->map['real_page_no']==$this->map['real_total_page'])))
				{?>
<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td ><?php echo Portal::language('quantity_of_guest_has_breakfast');?> ........... </td>
	<td > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" ><?php echo Portal::language('general_accountant');?></td>
	<td width="33%" ><?php echo Portal::language('director');?></td>
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
