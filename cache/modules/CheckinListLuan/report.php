<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>

<!---------HEADER----------->
<?php 
				if(($this->map['real_page_no']==1))
				{?>
<div class="report-bound"> 
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
                            <font class="report_title specific" ><?php echo Portal::language('checkin_room_list');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                <?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['date'];?>
                            </span> 
                        </div>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
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
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
        jQuery('#date').datepicker();
    
});
</script>

				<?php
				}
				?>



<!---------REPORT----------->	
<?php 
				if(($this->map['real_room_count']))
				{?>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="20px"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" width="30px"><?php echo Portal::language('re_code');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('room_level');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('price');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('guest_name');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('tour');?>,<?php echo Portal::language('company');?></th>
		<th class="report_table_header" width="30px"><?php echo Portal::language('nationality');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('A/c');?></th>
		<th class="report_table_header" width="50px"><?php echo Portal::language('time_in');?></th>
		<th class="report_table_header" width="50px"><?php echo Portal::language('time_out');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('night');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('flight_code');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('flight_arrival_time');?></th>
        </tr>
    
       <?php 
    $stt=0;
    if(isset($this->map['items'])){
        if(count($this->map['items'])>0){
            foreach($this->map['items'] as $k1=>$v1){
        $stt++;$company=1;
        echo '<tr>
        <td class="report_table_column ss" rowspan='.$v1['count'].' align="center">
            <div style="font-size:11px;">'.$stt.'</div></td>';
        echo"
        <td class='report_table_column ss' rowspan=".$v1['count']." align='center'>
            <div style='font-size:11px;'>
            <a href='".Url::build('reservation',array('cmd'=>'edit','id'=>$v1['reservation_id'],'r_r_id'=>$v1['reservation_room_code']))."' target='_blank'>
            ".$v1['reservation_id']."
            </div></td>";
        foreach($v1['child'] as $k2=>$v2){
            $i=1;$j=1;$a =1;$b=1;$q=1; 
            if(count($this->map['items'][$k1]['child'][$k2]['childrend'])>0){
                for($d=1;$d<=$v2['count'];$d++){
                    reset($this->map['items']);
                if($q==1){
                     echo '<td class="report_table_column" rowspan='.$v2['count'].' align="center">
                    <div style="font-size:11px;">'.$v2['room_name'].'</div></td>
                    
                    <td class="report_table_column" rowspan='.$v2['count'].' align="center">
                    <div style="font-size:11px;">'.$v2['brief_name'].'</div></td>
                    
                    <td class="report_table_column" rowspan='.$v2['count'].' align="center">
                    <div style="font-size:11px;">'.System::display_number($v2['price']).'</div></td>
                    
                   
                     '; 
                }
                if($d>1)
                    echo '<tr>';
                foreach($v2['childrend'] as $k3=>$v3){
                    echo '<td class="report_table_column" align="center">
                    <div style="font-size:11px;">
                    <a href="'.Url::build('traveller',array('id'=>$v3['traveller_id'])).'" target="_blank">
                    '.$v3['fullname'].'</a></div></td>';
                    break;
                }
         
         
            if($company==1){
                echo '
                <td class="report_table_column" rowspan='.$v1['count'].' align="center">
                <div style="font-size:11px;">'.$v1['note'].'</div></td>';
            }
             
            foreach($v2['childrend'] as $k3=>$v3){
                echo '<td class="report_table_column" align="center">
                <div style="font-size:11px;">'.$v3['country_code_1'].'</div></td>';
                break;
              }  
            if($q==1){  
                echo '<td class="report_table_column" rowspan='.$v2['count'].' align="center">
                <div style="font-size:11px;">'.$v2['adult'].'/'.$v2['child'].'</div></td>';
            }
                foreach($v2['childrend'] as $k3=>$v3){
                    echo '<td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.date('d/m/Y H:i',$v3['time_in']).'</div></td>
                    <td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.date('d/m/Y H:i',$v3['time_out']).'</div></td>
                    ';
                   break;
                }
            
            if($q==1){  
                echo '<td class="report_table_column" rowspan='.$v2['count'].' align="center">
                <div style="font-size:11px;">'.$v2['night'].'</div></td>';
            }
            $g='';
            /** Minh format time */
                foreach($v2['childrend'] as $k3=>$v3){
                    $v3['flight_arrival_time'] = $v3['flight_arrival_time']!=0?date('H:i',$v3['flight_arrival_time']):'';                    
                    echo '<td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.$v3['flight_code'].'</div></td>
                    <td class="report_table_column" align="center">
                    <div style="font-size:11px;">'.$v3['flight_arrival_time'].'</div></td>
                    
                    </tr>
                    ';
                    $g=$k3;
                unset($v2['childrend'][$k3]); 
                break;
                }
                
            $company++;    
            $q++;
            } 
            }
               
        }   
    }
        }  
    }
  ?>  
    
    <!--Nếu lọc dữ liệu thì room_count != real_room_count-->
    <?php 
				if((($this->map['room_count']!=$this->map['real_room_count']) && (($this->map['real_page_no']==$this->map['real_total_page']))))
				{?>
	<tr bgcolor="white">
		<td align="center" colspan="2" class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
		<td align="" class="report_table_column"><strong><?php echo $this->map['real_room_count'];?></strong></td>
        <td class="report_table_column">&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['real_total_money']) ?></strong></td>
		<td colspan="3" class="report_table_column">&nbsp;</td>
        <td align="center" class="report_table_column"><strong><?php echo $this->map['real_num_people'];?>/<?php echo $this->map['real_num_child'];?></strong></td>
        <td colspan="2" class="report_table_column">&nbsp;</td>
        
        <td align="center" class="report_table_column"><strong><?php echo $this->map['real_night'];?></strong></td>
        <td colspan="10" class="report_table_column">&nbsp;</td>
	</tr>
     <?php }else{ ?>
    	<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
    	<tr bgcolor="white">
    		<td align="center" colspan="2" class="report_table_column"><strong><?php echo Portal::language('total');?></strong></td>
    		<td align="center" class="report_table_column"><strong><?php echo $this->map['real_room_count'];?></strong></td>
            <td class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['real_total_money']) ?></strong></td>
    		<td colspan="3" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong><?php echo $this->map['real_num_people'];?>/<?php echo $this->map['real_num_child'];?></strong></td>
            <td colspan="2" class="report_table_column">&nbsp;</td>
            <td align="center" class="report_table_column"><strong><?php echo $this->map['real_night'];?></strong></td>
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
<center><div style="font-size:11px;"><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></div></center>
<br/>

<?php 
				if((($this->map['page_no']==$this->map['total_page']) || ($this->map['real_page_no']==$this->map['real_total_page'])))
				{?>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ><?php echo Portal::language('quantity_of_guest_has_breakfast');?> ........... </td>
	<td > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" ><?php echo Portal::language('general_accountant');?></td>
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
