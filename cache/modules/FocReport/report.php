<?php //System::debug($this->map['items']); ?>
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
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" ><?php echo Portal::language('foc_report');?><br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php echo $this->map['from_time'];?>&nbsp;<?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['from_date'] ?> - <?php echo $this->map['to_time'];?>&nbsp;<?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['to_date'] ?></span> </div></td>
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
                                    <td><?php echo Portal::language('from_date');?></td>
                                	<td><input  name="from_date" id="from_date" onchange="changevalue()"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    
                                    <td><?php echo Portal::language('to_date');?></td>
                                	<td><input  name="to_date" id="to_date" onchange="changefromday()"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td  align="left" nowrap="nowrap"><?php echo Portal::language('user_status');?></td>
                       			    <td>
                                      <!-- 7211 -->  
                                      <select  style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                                        <option value="1">Active</option>
                                        <option value="0">All</option>
                                      </select>
                                      <!-- 7211 end--> 
                                    </td>
                    			    <td><?php echo Portal::language('by_user');?></td>
                    			    <td><select  name="user_id" id="user_id"><?php
					if(isset($this->map['user_id_list']))
					{
						foreach($this->map['user_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))
                    echo "<script>$('user_id').value = \"".addslashes(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('from_time');?></td>
                                    <td><input  name="from_time" id="from_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>"></td>
                                    <td><?php echo Portal::language('to_time');?></td>
                                    <td><input  name="to_time" id="to_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>"></td>
                                    <td><input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" onclick="return check_search();"/></td>
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
	    <th rowspan="2" class="report-table-header"><?php echo Portal::language('no');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('recode');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('room');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('folio_id');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('guest_name');?></th>
        <th rowspan="2" class="report-table-header" ><?php echo Portal::language('arrival_date');?></th>
        <th rowspan="2" class="report-table-header" ><?php echo Portal::language('departure_date');?></th>
        <th rowspan="2" class="report-table-header" ><?php echo Portal::language('night');?></th>  
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('room_rate');?>(VND)</th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('room_rate');?><br />(USD)</th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('room_price_total');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('extra_bed');?></th>
        <th  class="report-table-header"><?php echo Portal::language('rest');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('minibar');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('laundry');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('telephone');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('compensation');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('spa');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('tour1');?></th>
        <th rowspan="2" class="report-table-header"><?php echo Portal::language('other');?></th>
        <th rowspan="2" class="report-table-header" style="background-color:#FFFF99;"><?php echo Portal::language('total');?></th>
        
           
	</tr>
    <tr>
        <!--<th width="60px" class="report-table-header"><?php echo Portal::language('break_fast');?></th>-->
        <th width="60px" class="report-table-header"><?php echo Portal::language('F&B');?></th>
    </tr>
    <!---<tr>
        <th width="60px" class="report-table-header"><?php echo Portal::language('VND');?></th>
        <th width="60px" class="report-table-header"><?php echo Portal::language('VND');?></th>
        <th width="60px" class="report-table-header"><?php echo Portal::language('VND');?></th>
        <th width="40px"  class="report-table-header"><?php echo Portal::language('USD');?></th>
    </tr>--->
    <?php 
    $i=1;
    $is_rowspan = false;
    ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
	<tr bgcolor="white">
        <?php
            $k = $this->map['count_room'][$this->map['items']['current']['code']]['num'];
            if($is_rowspan == false)
            {
        ?>
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['stt'];?></td>
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <a target="_blank" href="<?php echo "?page=reservation&layout=list&cmd=edit&id=".$this->map['items']['current']['reservation_id']; ?>"><?php echo $this->map['items']['current']['reservation_id'];?></a>
            </td>
        <?php
            } 
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
            <td class="report_table_column" style="text-align: center;"><?php echo $this->map['items']['current']['room_name'];?></td>
        <?php
           }
        ?>
         <?php 
            if($is_rowspan == false)
            {
        ?>
            <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">
            <a target="_blank" href="<?php echo ($this->map['items']['current']['customer_id'] !=''?Url::build('view_traveller_folio',array('folio_id'=>$this->map['items']['current']['code'],'id'=>$this->map['items']['current']['reservation_id'],'cmd'=>'group_invoice','customer_id'=>$this->map['items']['current']['customer_id'])):Url::build('view_traveller_folio',array('traveller_id'=>$this->map['items']['current']['traveller_id'],'folio_id'=>$this->map['items']['current']['code'])));?>">
            <?php if(isset($this->map['items']['current']['folio_code'])){?>
                <?php echo 'No.F'.str_pad($this->map['items']['current']['folio_code'],6,"0",STR_PAD_LEFT) ;?>
            <?php } else {?>
                <?php echo 'Ref'.str_pad($this->map['items']['current']['code'],6,"0",STR_PAD_LEFT) ;?>
            <?php }?>
            </a>
            <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['guest_name'];?></td>
           
        <?php
            }
        ?>
        <?php 
            if($k ==0 || $k ==1 || $i<=$k)
            {
        ?>
             <td align="center" class="report_table_column"><?php echo ($this->map['items']['current']['time_in']==0?'':date('d/m/Y',$this->map['items']['current']['time_in']));?></td>
            <td align="center" class="report_table_column" ><?php echo ($this->map['items']['current']['time_out']==0?'':date('d/m/Y',$this->map['items']['current']['time_out']));?></td>
            <td align="center" class="report_table_column" ><?php echo System::Display_number(round(($this->map['items']['current']['time_out']-$this->map['items']['current']['time_in'])/(24*3600)));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number($this->map['items']['current']['price']);?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number($this->map['items']['current']['price']/RES_EXCHANGE_RATE);?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['room']==0?'':System::Display_number(round($this->map['items']['current']['room']))); ?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['extra_bed']==0?'':System::Display_number(round($this->map['items']['current']['extra_bed'])));?></td>
            <!--<td class="report_table_column" style="text-align: right;"></?php echo ($this->map['items']['current']['break_fast']==0?'':System::Display_number(round($this->map['items']['current']['break_fast'])));?></td>-->
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['restaurant']==0?'':System::Display_number(round($this->map['items']['current']['restaurant'])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['minibar']==0?'':System::Display_number(round($this->map['items']['current']['minibar']))); ?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['laundry']==0?'':System::Display_number(round($this->map['items']['current']['laundry'])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['telephone']==0?'':System::Display_number(round($this->map['items']['current']['telephone'])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['equip']==0?'':System::Display_number(round($this->map['items']['current']['equip'])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['spa']==0?'':System::Display_number(round($this->map['items']['current']['spa'])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['tour']==0?'':System::Display_number(round($this->map['items']['current']['tour'])));?></td>
            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['extra_service']==0?'':System::Display_number(round($this->map['items']['current']['extra_service'])));?></td>
            <td style="background-color:#FFFF99; text-align: right;" class="report_table_column">
            <?php echo System::Display_number(round($this->map['items']['current']['extra_service']
                +$this->map['items']['current']['minibar']
                +$this->map['items']['current']['restaurant']
                +$this->map['items']['current']['laundry']
                +$this->map['items']['current']['telephone']

                +$this->map['items']['current']['equip']
                +$this->map['items']['current']['spa']
                +$this->map['items']['current']['room']
                +$this->map['items']['current']['extra_bed']
				+$this->map['items']['current']['break_fast']
                +$this->map['items']['current']['tour']));?></td>
            
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
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <?php 
				if((($this->map['room_count']!=$this->map['real_room_count']) && (($this->map['real_page_no']==$this->map['real_total_page']))))
				{?>
    <tr bgcolor="white">
		<td class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['real_room_count']); ?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_room_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_extra_bed_total']); ?></strong></td>
        <!--<td align="right" class="report_table_column"><strong></?php echo System::display_number($this->map['total_break_fast_total']); ?></strong></td>-->
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_restaurant_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_minibar_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_laundry_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_telephone_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_equip_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_spa_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_tour_total']); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_extra_service_total']); ?></strong></td>
        <td style="background-color:#FFFF99;" align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['total_room_total']
                                                                                                                                + $this->map['total_extra_bed_total']
                                                                                                                                + $this->map['total_restaurant_total']
                                                                                                                                + $this->map['total_minibar_total']
                                                                                                                                + $this->map['total_laundry_total']
                                                                                                                                + $this->map['total_telephone_total']
                                                                                                                                + $this->map['total_equip_total']
                                                                                                                                + $this->map['total_spa_total']
                                                                                                                                + $this->map['total_tour_total']
																																+ $this->map['total_break_fast_total']
                                                                                                                                + $this->map['total_extra_service_total']); ?></strong></td>
        <td style="background-color:#FFCCFF;"><strong><?php echo System::display_number(round($this->map['total_reduce_amount_total'])); ?></strong></td>
        
        
    </tr>
     <?php }else{ ?>
    	<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
    	<tr bgcolor="white">
		<td class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['real_room_count']); ?></strong></td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_room_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_extra_bed_total'])); ?></strong></td>
        <!--<td align="right" class="report_table_column"><strong></?php echo System::display_number(round($this->map['total_break_fast_total'])); ?></strong></td>-->
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_restaurant_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_minibar_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_laundry_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_telephone_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_equip_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_spa_total'])); ?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_tour_total'])); ?>&nbsp;</strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_extra_service_total'])); ?></strong></td>
        <td style="background-color:#FFFF99;" align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['total_extra_service_total'])
                                                                                                                                        +round($this->map['total_room_total'])
                                                                                                                                        +round($this->map['total_minibar_total'])
                                                                                                                                        +round($this->map['total_restaurant_total'])
                                                                                                                                        +round($this->map['total_laundry_total'])
                                                                                                                                        +round($this->map['total_telephone_total'])
                                                                                                                                        +round($this->map['total_spa_total'])
							 																											+round($this->map['total_break_fast_total'])
                                                                                                                                        +round($this->map['total_equip_total'])
                                                                                                                                        +round($this->map['total_extra_bed_total'])
                                                                                                                                        +round($this->map['total_tour_total'])); ?></strong></td>                                                                                                                               
        
        
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

	<td align="left" colspan="2"></td>
	<td  align="center"> <?php //echo date('H\h : i\p',time());?> <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
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
input[id="from_date"]{width:70px;}
input[id="start_page"]{width:30px;}
input[id="no_of_page"]{width:30px;}
input[id="line_per_page"]{width:30px;}
input[id="to_date"]{width:70px;}
input[type="submit"]{width:100px;}
input[id="from_time"]{width:40px;}
input[id="to_time"]{width:40px;}
selcet[id="user_id"]{width:70px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>

<script type="text/javascript">
// 7211
    var users = <?php echo String::array2js($this->map['users']);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#user_id').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        jQuery('#to_day').datepicker();
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
    }
);
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
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
        
        if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
        {
            alert('<?php echo Portal::language('start_time_longer_than_end_time_try_again');?>');
            return false;
        }
        else
        {
            if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
            {
                alert('<?php echo Portal::language('the_max_time_is_2359_try_again');?>');
                return false;
            }
            else
            {                
                return true;             
            }
        }   
    }
    //end:KID them ham check dieu kien search
</script>
