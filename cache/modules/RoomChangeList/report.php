<link rel="stylesheet" type="text.css" href="packages/core/skins/default/css/jquery/datepicker.css" />
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong><?php echo Portal::language('template_code');?></strong>
        <br />
        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
        <br />
        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
         </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" ><?php echo Portal::language('room_move_list');?><br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"> <?php echo date('H\h : i\p',time());?> <?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['date'];?> </span> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
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
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
</div>
<!---------SEARCH----------->

<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search"> 
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto">
                                <tr>        
                                    <td><?php echo Portal::language('line_per_page');?></td>
                                    <td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('from_page');?></td>
                                    <td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
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
                                    <td><?php echo Portal::language('date');?></td>
                                	<td><input  name="date" id="date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
                                    <td><?php echo Portal::language('time');?></td>
                                    <td><input  name="time" id="time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time'));?>"></td>
                                    <td><input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" /></td>
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



				<?php
				}
				?>
<!---------REPORT----------->	

<?php 
				if(($this->map['real_room_count']))
				{?>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="25px"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" width="35px"><?php echo Portal::language('reservation_room_code');?></th>
        <th class="report_table_header" width="35px"><?php echo Portal::language('booking_code');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('source');?></th>
		<th class="report_table_header" width="150px"><?php echo Portal::language('guest_name');?></th>
        <!---<th class="report_table_header" width="50px"><?php echo Portal::language('date_of_birth');?></th>--->
        <th class="report_table_header" width="30px"><?php echo Portal::language('gender');?></th>
		<th class="report_table_header" width="40px"><?php echo Portal::language('nationality');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('room');?></th>
        <?php if(Url::get('dept') != 'HK') { ?>
        <th class="report_table_header" width="50px"><?php echo Portal::language('price');?></th>
        <?php } ?>
        <th class="report_table_header" width="50px"><?php echo Portal::language('arrival_date');?></th>
		<th class="report_table_header" width="50px"><?php echo Portal::language('transfer_date');?></th>
		<th class="report_table_header" width="50px"><?php echo Portal::language('departure_date');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('night');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('note');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('reason');?></th>
	</tr>
	
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
    
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
	<tr bgcolor="white">
        <?php
            $k = $this->map['count_traveller'][$this->map['items']['current']['reservation_room_code']]['num'];
            
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
        <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['reservation_id']));?>" target="_blank"><?php echo $this->map['items']['current']['reservation_id'];?></a>
        </td>
        <td class="report_table_column" style="white-space: normal; text-align: left;" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['booking_code'];?></td>
        <td class="report_table_column" style="white-space: normal; text-align: left;" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['note'];?></td>
        <?php
            } 
        ?>
        
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?> 
        <td class="report_table_column" style="text-align: left;">
        <a href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['traveller_id']));?>" target="_blank"><?php echo $this->map['items']['current']['fullname'];?></a>
        </td>
        <!---<td align="center" class="report_table_column"><?php echo $this->map['items']['current']['birth_date'];?></td>--->
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['gender'];?></td>
		<td align="center" class="report_table_column"><?php echo $this->map['items']['current']['country_code_1'];?></td>
        <?php
            }
        ?>
        
        <?php 
            if($is_rowspan == false)
            {
        ?>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['room_name'];?></td>
        
        <?php if(Url::get('dept') != 'HK') { ?>
        <td class="report_table_column" rowspan="<?php echo $k; ?>" align="right">
        <div style="font-size:11px;"><?php echo System::display_number($this->map['items']['current']['price']); ?></div>
        </td>
        <?php } ?>
        <?php
            }
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?> 
        <td align="center" class="report_table_column" >
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['arrival_time_before']);?>
        </td>
		<td align="center" class="report_table_column" >
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['time_in']);?>
        </td>
		<td align="center" class="report_table_column" >
			<?php echo date('d/m/Y H:i',$this->map['items']['current']['time_out']);?>
        </td>
        
        <?php   $i++; } ?>
            <!--Luu nguyen giap edit cot dem -->
            <?php
            if(($this->map['items']['current']['row_night']>0))
            { 
                ?>
               <td align="center" class="report_table_column tab_night" rowspan="<?php echo $this->map['items']['current']['row_night']; ?>"> 
               <?php 
               if($this->map['items']['current']['night']==0) 
               {
                   echo 'dayuse';
               }
               else
               {
                   echo   $this->map['items']['current']['night'];
               } 
               ?></td>
               <td><?php echo $this->map['items']['current']['resion_note'];?></td>
               <td><?php echo $this->map['items']['current']['note_room_change'];?></td>
               <?php 
            } 
            
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
        <!--Luu nguyen giap edit cot dem End-->
        
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <?php 
				if((($this->map['room_count']!=$this->map['real_room_count']) && (($this->map['real_page_no']==$this->map['real_total_page']))))
				{?>
	<tr bgcolor="white">
		<td colspan="2" class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
		<td class="report_table_column"><strong></strong></td>
        <td align="center" class="report_table_column"><strong><?php echo $this->map['real_total_guest'];?></strong></td>
        
        <td colspan="2" class="report_table_column"></td>
        <td align="center" class="report_table_column"><?php echo $this->map['real_room_count'];?></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_price']); ?></strong></td>
        <td colspan="1" class="report_table_column">&nbsp;</td>
        <?php if(Url::get('dept') != 'HK') { ?>
        <td>&nbsp;</td>
        <?php } ?>
        <td align="center" class="report_table_column"><strong><?php echo $this->map['real_night'];?></strong></td>
        <td colspan="3">&nbsp;</td>
	</tr>
     <?php }else{ ?>
    	<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
    	<tr bgcolor="white">
    		<td colspan="4" class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
    		<td align="center" class="report_table_column"><strong><?php echo $this->map['real_total_guest'];?></strong></td>
            <td colspan="" class="report_table_column">&nbsp;</td>
            <td colspan="" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong><?php echo $this->map['real_room_count'];?></strong></td>
            <td align="right" class="report_table_column"><strong><!---<?php echo System::display_number($this->map['total_price']); ?>--->&nbsp;</strong></td>
            <td colspan="1" class="report_table_column">&nbsp;</td>
            <td colspan="1" class="report_table_column">&nbsp;</td>
            <?php if(Url::get('dept') != 'HK') { ?>
            <td>&nbsp;</td>
            <?php } ?>
            <td align="center" class="report_table_column"><strong><?php echo $this->map['real_night'];?></strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
    	</tr>
    	
				<?php
				}
				?>
    
				<?php
				}
				?>
    
    

</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;"><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></div></center>
<br/>

<?php 
				if((($this->map['page_no']==$this->map['total_page']) || ($this->map['real_page_no']==$this->map['real_total_page'])))
				{?>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ><?php echo Portal::language('quantity_of_guest_has_breakfast');?> ........... </td>
	<td  align="center"> <?php echo date('H\h : i\p',time());?> <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td align="center" width="33%" ><?php echo Portal::language('director');?></td>
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


<style type="text/css">
th,td{white-space:nowrap;}
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>

<script type="text/javascript">
jQuery(document).ready(function()
    {
        jQuery('#date').datepicker();
        jQuery('#time').mask("99:99");
    }
);
</script>