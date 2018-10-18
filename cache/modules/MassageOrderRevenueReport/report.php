<style>
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<?php 
				if(($this->map['page_no'] <= 1))
				{?>
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="60%">
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
                            <font class="report_title specific" ><?php echo Portal::language('bao_cao_thu_tien_theo_hoa_don');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>
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
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td><?php echo Portal::language('line_per_page');?></td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <!--
                                    <td><?php echo Portal::language('from_page');?></td>
                                	<td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    -->
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
                                    <td><?php echo Portal::language('from');?></td>
                                	<td><input  name="from_date" id="from_date" style="width: 80px;" onchange="changevalue();"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><?php echo Portal::language('hour_from');?></td>
                                    <td><input  name="hour_from" id="hour_from" style="width: 40px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('hour_from'));?>"></td>
                                    <td><?php echo Portal::language('to');?></td>
                                	<td><input  name="to_date" id="to_date" style="width: 80px;" onchange="changefromday();"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><?php echo Portal::language('hour_to');?></td>
                                    <td><input  name="hour_to" id="hour_to" style="width: 40px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('hour_to'));?>"></td>
                                    <td><?php echo Portal::language('code');?></td>
                                	<td><input  name="from_code" id="from_code" style="width: 40px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_code'));?>"></td>
                                    <td><?php echo Portal::language('to');?></td>
                                    <td><input  name="to_code" id="to_code" style="width: 40px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_code'));?>"></td>
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
        jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>
 <?php }else{ ?>
<br />
<br />
<br />

				<?php
				}
				?>



<!---------REPORT----------->	
<?php 
				if((!isset($this->map['has_no_data'])))
				{?>
<?php $i=0;$j=0;$k=0;?>
<?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key1=>&$item1){if($key1!='current'){$this->map['payment_types']['current'] = &$item1;?><?php $i++;?><?php }}unset($this->map['payment_types']['current']);} ?>
<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key2=>&$item2){if($key2!='current'){$this->map['currencies']['current'] = &$item2;?><?php $j++;?><?php }}unset($this->map['currencies']['current']);} ?>
<?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key3=>&$item3){if($key3!='current'){$this->map['credit_card']['current'] = &$item3;?><?php $k++;?><?php }}unset($this->map['credit_card']['current']);} ?>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="10px" rowspan="3" class="report-table-header"><?php echo Portal::language('stt');?></th>
	  <th width="10px" rowspan="3" class="report-table-header"><?php echo Portal::language('bill_number');?></th>
      <th width="10px" rowspan="3" class="report-table-header"><?php echo Portal::language('recode');?></th>
	  <th width="20px" rowspan="3" class="report-table-header"><?php echo Portal::language('room_name');?></th>
      <th width="100px" rowspan="3" class="report-table-header"><?php echo Portal::language('guest_name');?></th>
	  <th width="140px" rowspan="3" class="report-table-header" ><?php echo Portal::language('service_name');?></th>  
	  <th width="10px" rowspan="3" class="report-table-header"><?php echo Portal::language('quantity');?></th>
      <th width="40px" rowspan="3" class="report-table-header"><?php echo Portal::language('price');?></th>
      <th width="40px" rowspan="3" class="report-table-header"><?php echo Portal::language('amount');?></th>
      <th width="40px" rowspan="3" class="report-table-header"><?php echo Portal::language('discount_amount');?></th>
      <th width="40px" rowspan="3" class="report-table-header"><?php echo Portal::language('discount_persent');?></th>
	  <th colspan="<?php echo ($i+$j-1);?>" class="report-table-header"><?php echo Portal::language('payment');?></th>
	  <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('total');?></th>     
	  <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('user');?></th>
      <th width="120px" rowspan="3" class="report-table-header"><?php echo Portal::language('note');?></th>
	</tr>
    <tr valign="middle" bgcolor="#EFEFEF">
    <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key4=>&$item4){if($key4!='current'){$this->map['payment_types']['current'] = &$item4;?>
        <?php if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD'){?>
        	<th class="report-table-header"><?php echo $this->map['payment_types']['current']['name'];?></th>
        <?php }elseif($this->map['payment_types']['current']['def_code']=='CASH'){?>
        	<th class="report-table-header" colspan="<?php echo $j;?>"><?php echo $this->map['payment_types']['current']['name'];?></th>
         <?php }else{?>
         	<th class="report-table-header"><?php echo $this->map['payment_types']['current']['name'];?></th>
         <?php }?>
    <?php }}unset($this->map['payment_types']['current']);} ?>
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
     <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key5=>&$item5){if($key5!='current'){$this->map['payment_types']['current'] = &$item5;?>
      	<?php if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD'){?>
                <th class="report-table-header" >&nbsp;</th>
        <?php }else if($this->map['payment_types']['current']['def_code']=='CASH'){?>
        	 <?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key6=>&$item6){if($key6!='current'){$this->map['currencies']['current'] = &$item6;?>
				<th class="report-table-header"><?php echo $this->map['currencies']['current']['id'];?></th>
   			 <?php }}unset($this->map['currencies']['current']);} ?>
         <?php }else{?>
         		<th class="report-table-header"></th>
         <?php }?>
      <?php }}unset($this->map['payment_types']['current']);} ?>    
     </tr>
	<?php 
				if(($this->map['page_no']!=1))
				{?>
  <?php $card = 0; ?>
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="6" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['quantity']);?></strong></td>
        <td>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['discount_amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['last_group_function_params']['total_discount_amount_persent']));?></strong></td>
     <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key7=>&$item7){if($key7!='current'){$this->map['payment_types']['current'] = &$item7;?>
        	 <?php if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD'){?>
        	 <?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key8=>&$item8){if($key8!='current'){$this->map['credit_card']['current'] = &$item8;?>
                <?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key9=>&$item9){if($key9!='current'){$this->map['currencies']['current'] = &$item9;?>
                	 <?php $cr = $this->map['payment_types']['current']['def_code'].'_'.$this->map['credit_card']['current']['id'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['last_group_function_params'][$cr])){
						$card += (($this->map['last_group_function_params'][$cr]==0)?0:($this->map['last_group_function_params'][$cr]));	
					}?>	
   				<?php }}unset($this->map['currencies']['current']);} ?>
             <?php }}unset($this->map['credit_card']['current']);} ?>
             
        <?php echo '<td align="right"  class="report_table_column" ><strong>'.System::Display_number($card).'</strong></td>';	
		}else if($this->map['payment_types']['current']['def_code']=='CASH'){?>
        		<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key10=>&$item10){if($key10!='current'){$this->map['currencies']['current'] = &$item10;?>
                <?php $tt = $this->map['payment_types']['current']['def_code'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['group_function_params'][$tt])){
					echo '<td align="right"  class="report_table_column" ><strong>'.(($this->map['last_group_function_params'][$tt]==0)?'':System::Display_number($this->map['last_group_function_params'][$tt])).'</strong></td>';	
				}else{
					echo '<td align="right"  class="report_table_column" >&nbsp;</td>';
				}?>
   				<?php }}unset($this->map['currencies']['current']);} ?>
         <?php }else if($this->map['payment_types']['current']['def_code']=='DEBIT'){?>
					<td align="right"  class="report_table_column" ><strong><?php echo (($this->map['last_group_function_params']['debit']<=0)?'':System::Display_number($this->map['last_group_function_params']['debit']));?></strong></td>	
         <?php }else if($this->map['payment_types']['current']['def_code']=='FOC'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (($this->map['last_group_function_params']['foc']==0)?'':System::Display_number($this->map['last_group_function_params']['foc']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='ROOM CHARGE'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (($this->map['last_group_function_params']['room']==0)?'':System::Display_number($this->map['last_group_function_params']['room']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='BANK'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (($this->map['last_group_function_params']['bank']==0)?'':System::Display_number($this->map['last_group_function_params']['bank']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='REFUND'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (($this->map['last_group_function_params']['refund']==0)?'':System::Display_number($this->map['last_group_function_params']['refund']));?></strong></td>
         <?php }?>
        <?php }}unset($this->map['payment_types']['current']);} ?>
        	<td align="right"  class="report_table_column" ><strong><?php echo System::Display_number($this->map['last_group_function_params']['total']);?></strong></td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	
				<?php
				}
				?>
    <?php
        $i=1;
        $is_rowspan = false; 
    ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key11=>&$item11){if($key11!='current'){$this->map['items']['current'] = &$item11;?>
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
    <?php 
        $k = $this->map['count_items'][$this->map['items']['current']['reservation_room_id']]['num'];
        //echo $k.'-'.$this->map['items']['current']['reservation_room_code'].'-'.$this->map['items']['current']['reservation_id'];
        if($is_rowspan == false)
        {
    ?>
		<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['items']['current']['reservation_room_id']));?>" target="_blank"><?php echo $this->map['items']['current']['reservation_room_id'];?></a></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['mrr_id'];?>"><?php echo $this->map['items']['current']['mrr_id'];?></a></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['hotel_room_name'];?></td>
        <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['traveller_name'];?></td>
    <?php
        } 
    ?>
    
    <?php 
        if($k ==0 || $k ==1 || $i<=$k)
        {
    ?>   
        <td align="left" class="report_table_column" ><?php echo $this->map['items']['current']['product_name'];?></td>
        <td align="center" class="report_table_column" ><?php echo System::display_number($this->map['items']['current']['quantity']); ?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number($this->map['items']['current']['amount']); ?></td>
    <?php
        $i++;
        }
    ?>
    
    <?php 
        if($is_rowspan == false)
        {
    ?>
       <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number($this->map['items']['current']['discount_amount']);?></td>
       <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number(round($this->map['items']['current']['total_discount_amount_persent']));?></td>
       <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key12=>&$item12){if($key12!='current'){$this->map['payment_types']['current'] = &$item12;?>
       
        	 <?php 
             if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD')
             { 
                $card_item = 0; 
                $count = 0;
             ?>
        	 <?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key13=>&$item13){if($key13!='current'){$this->map['credit_card']['current'] = &$item13;?>
             	<?php $count ++;?>
                <?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key14=>&$item14){if($key14!='current'){$this->map['currencies']['current'] = &$item14;?>
                	 <?php $cr = $this->map['payment_types']['current']['def_code'].'_'.$this->map['credit_card']['current']['id'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['items']['current'][$cr]))
                     {	
						$card_item += $this->map['items']['current'][$cr];
				     }
                     
                     ?>	
   				<?php }}unset($this->map['currencies']['current']);} ?>
             <?php }}unset($this->map['credit_card']['current']);} ?>
            <?php echo '<td align="right" class="report_table_column" >'.System::Display_number($card_item).'</td>';	
    		}
            else if($this->map['payment_types']['current']['def_code']=='CASH')
            {
            ?>
        		<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key15=>&$item15){if($key15!='current'){$this->map['currencies']['current'] = &$item15;?>
                <?php 
                $tt = $this->map['payment_types']['current']['def_code'].'_'.$this->map['currencies']['current']['id']; 
                if(isset($this->map['items']['current'][$tt]))
                {
					echo '<td align="right" class="report_table_column" rowspan="'.$k.'">'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}
                else
                {
					echo '<td align="right" class="report_table_column" rowspan="'.$k.'">&nbsp;</td>';
				}?>
   				<?php }}unset($this->map['currencies']['current']);} ?>
         <?php }else if($this->map['payment_types']['current']['def_code']=='DEBIT'){?>
					<td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['debit']<=0?'':System::Display_number($this->map['items']['current']['debit']));?></td>	
         <?php }else if($this->map['payment_types']['current']['def_code']=='FOC'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['foc']==0?'':System::Display_number($this->map['items']['current']['foc']));?></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['room']==0?'':System::Display_number($this->map['items']['current']['room']));?></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='BANK'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['bank']==0?'':System::Display_number($this->map['items']['current']['bank']));?></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='REFUND'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['refund']==0?'':System::Display_number($this->map['items']['current']['refund']));?></td>
         <?php }?>
        
        <?php }}unset($this->map['payment_types']['current']);} ?>
        <td align="right" class="report_table_column" rowspan="<?php echo $k;?>"><?php echo $this->map['items']['current']['total']?System::display_number($this->map['items']['current']['total']):'';?></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['user_id'];?></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo $this->map['items']['current']['note'];?></td>
    <?php 
        }
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
    <?php $total_card = 0;?>
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="6" class="report_sub_title" align="right"><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['quantity']);?></strong></td>
        <td></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['discount_amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_discount_amount_persent']));?></strong></td>
     <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key16=>&$item16){if($key16!='current'){$this->map['payment_types']['current'] = &$item16;?>
        	 <?php if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD'){?>
        	 <?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key17=>&$item17){if($key17!='current'){$this->map['credit_card']['current'] = &$item17;?>
                <?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key18=>&$item18){if($key18!='current'){$this->map['currencies']['current'] = &$item18;?>
                	 <?php $cr = $this->map['payment_types']['current']['def_code'].'_'.$this->map['credit_card']['current']['id'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['group_function_params'][$cr])){
						//echo '<td align="right" class="report_table_column" ><strong>'.(($this->map['group_function_params'][$cr]==0)?'':System::Display_number($this->map['group_function_params'][$cr])).'</strong></td>';	
						$total_card += (($this->map['group_function_params'][$cr]==0)?0:($this->map['group_function_params'][$cr]));
					}else{
						//echo '<td align="right" class="report_table_column" >&nbsp;</td>';
					}?>	
   				<?php }}unset($this->map['currencies']['current']);} ?>
             <?php }}unset($this->map['credit_card']['current']);} ?>
        <?php echo '<td align="right" class="report_table_column" ><strong>'.System::Display_number($total_card).'</strong></td>';	
		}else if($this->map['payment_types']['current']['def_code']=='CASH'){?>
        		<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key19=>&$item19){if($key19!='current'){$this->map['currencies']['current'] = &$item19;?>
                <?php $tt = $this->map['payment_types']['current']['def_code'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['group_function_params'][$tt])){
					echo '<td align="right" class="report_table_column" ><strong>'.(($this->map['group_function_params'][$tt]==0)?'':System::Display_number($this->map['group_function_params'][$tt])).'</strong></td>';	
				}else{
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<?php }}unset($this->map['currencies']['current']);} ?>
         <?php }else if($this->map['payment_types']['current']['def_code']=='DEBIT'){?>
					<td align="right" class="report_table_column" ><strong><?php echo (($this->map['group_function_params']['debit']<=0)?'':System::Display_number($this->map['group_function_params']['debit']));?></strong></td>	
         <?php }else if($this->map['payment_types']['current']['def_code']=='FOC'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (($this->map['group_function_params']['foc']==0)?'':System::Display_number($this->map['group_function_params']['foc']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (($this->map['group_function_params']['room']==0)?'':System::Display_number($this->map['group_function_params']['room']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='BANK'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (($this->map['group_function_params']['bank']==0)?'':System::Display_number($this->map['group_function_params']['bank']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='REFUND'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (($this->map['group_function_params']['refund']==0)?'':System::Display_number($this->map['group_function_params']['refund']));?></strong></td>
         <?php }?> 
        <?php }}unset($this->map['payment_types']['current']);} ?>
        
			 <td align="right" class="report_table_column" ><strong><?php echo System::Display_number($this->map['group_function_params']['total']);?></strong></td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>
<br />
<br />
<!---<div style="page-break-before:always;page-break-after:always;"></div>--->
 <?php }else{ ?>
<strong><?php echo Portal::language('no_data');?></strong>

				<?php
				}
				?>
<script>
    jQuery('#hour_from').mask("99:99");
    jQuery('#hour_to').mask("99:99");
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