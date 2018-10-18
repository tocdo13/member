<style><!---------REPORT----------->
<style>
	.report_table_column{
		width:65px;	
	}
	.simple-layout-middle{
		width:100%;	
	}
</style>
<?php $i=0;$j=0;$k=0;?>
<?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key1=>&$item1){if($key1!='current'){$this->map['payment_types']['current'] = &$item1;?><?php $i++;?><?php }}unset($this->map['payment_types']['current']);} ?>
<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key2=>&$item2){if($key2!='current'){$this->map['currencies']['current'] = &$item2;?><?php $j++;?><?php }}unset($this->map['currencies']['current']);} ?>
<?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key3=>&$item3){if($key3!='current'){$this->map['credit_card']['current'] = &$item3;?><?php $k++;?><?php }}unset($this->map['credit_card']['current']);} ?>
<tr>
<td>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="10px" rowspan="3" class="report-table-header"><?php echo Portal::language('stt');?></th>
	  <th width="100px" rowspan="3" class="report-table-header"><?php echo Portal::language('order_id');?></th>
      <th width="100px" rowspan="3" class="report-table-header"><?php echo Portal::language('recode');?></th>
	  <th width="100px" rowspan="3" class="report-table-header"><?php echo Portal::language('date');?></th>
      <th width="120px" rowspan="3" class="report-table-header"><?php echo Portal::language('customer_name');?></th>
	  <th width="40px" rowspan="3" class="report-table-header" ><?php echo Portal::language('table_name');?></th>
      <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('total_before_discount');?></th>
      <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('discount');?></th>
      <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('service_charge');?></th>
      <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('tax');?></th>
       <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('discount_after_tax');?></th>
      <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('deposit');?></th>
	  <th colspan="<?php echo ($i+$j-1);?>" class="report-table-header"><?php echo Portal::language('payment');?></th>
	  <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('total');?></th>     
	  <th width="60px" rowspan="3" class="report-table-header"><?php echo Portal::language('user');?></th>
      <th width="150px" rowspan="3" class="report-table-header"><?php echo Portal::language('note');?></th>
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
		<tr>
            <td colspan="6" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['last_group_function_params']['before_discount']==0)?'':System::display_number(round($this->map['last_group_function_params']['before_discount'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['last_group_function_params']['total_discount']==0)?'':System::display_number(round($this->map['last_group_function_params']['total_discount'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['last_group_function_params']['fee_rate']==0)?'':System::display_number(round($this->map['last_group_function_params']['fee_rate'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['last_group_function_params']['tax']==0)?'':System::display_number(round($this->map['last_group_function_params']['tax'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['last_group_function_params']['total_discount_after_tax']==0)?'':System::display_number(round($this->map['last_group_function_params']['total_discount_after_tax'])));?></strong></td>                        
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['last_group_function_params']['deposit'])?'':System::display_number(round($this->map['last_group_function_params']['deposit'])));?></strong></td>
            
         <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key7=>&$item7){if($key7!='current'){$this->map['payment_types']['current'] = &$item7;?>
            	 <?php if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD'){?>
            	 <?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key8=>&$item8){if($key8!='current'){$this->map['credit_card']['current'] = &$item8;?>
                    <?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key9=>&$item9){if($key9!='current'){$this->map['currencies']['current'] = &$item9;?>
                    	 <?php $cr = $this->map['payment_types']['current']['def_code'].'_'.$this->map['credit_card']['current']['id'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['last_group_function_params'][$cr])){
    						$card += (($this->map['last_group_function_params'][$cr]==0)?0:($this->map['last_group_function_params'][$cr]));	
    					}?>	
       				<?php }}unset($this->map['currencies']['current']);} ?>
                 <?php }}unset($this->map['credit_card']['current']);} ?>
                 
            <?php echo '<td align="right"  class="report_table_column" ><strong class="change_num">'.System::Display_number($card).'</strong></td>';	
    		}else if($this->map['payment_types']['current']['def_code']=='CASH'){?>
            		<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key10=>&$item10){if($key10!='current'){$this->map['currencies']['current'] = &$item10;?>
                    <?php $tt = $this->map['payment_types']['current']['def_code'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['group_function_params'][$tt])){
    					echo '<td align="right"  class="report_table_column" ><strong class="change_num">'.(($this->map['last_group_function_params'][$tt]==0)?'':System::Display_number($this->map['last_group_function_params'][$tt])).'</strong></td>';	
    				}else{
    					echo '<td align="right"  class="report_table_column" >&nbsp;</td>';
    				}?>
       				<?php }}unset($this->map['currencies']['current']);} ?>
             <?php }else if($this->map['payment_types']['current']['def_code']=='DEBIT'){?>
    					<td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (($this->map['last_group_function_params']['debit']==0)?'':System::Display_number($this->map['last_group_function_params']['debit']));?></strong></td>	
             <?php }else if($this->map['payment_types']['current']['def_code']=='FOC'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (($this->map['last_group_function_params']['foc']==0)?'':System::Display_number($this->map['last_group_function_params']['foc']));?></strong></td>
             <?php }else if($this->map['payment_types']['current']['def_code']=='ROOM CHARGE'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (($this->map['last_group_function_params']['room']==0)?'':System::Display_number($this->map['last_group_function_params']['room']));?></strong></td>
             <?php }else if($this->map['payment_types']['current']['def_code']=='BANK'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (($this->map['last_group_function_params']['bank']==0)?'':System::Display_number($this->map['last_group_function_params']['bank']));?></strong></td>
             <?php }else if($this->map['payment_types']['current']['def_code']=='REFUND'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (($this->map['last_group_function_params']['refund']==0)?'':System::Display_number($this->map['last_group_function_params']['refund']));?></strong></td>
             <?php }?>
            <?php }}unset($this->map['payment_types']['current']);} ?>
            
            	<td align="right"  class="report_table_column" ><strong><?php echo System::Display_number($this->map['last_group_function_params']['total']);?></strong></td>
                <td align="right"  class="report_table_column" >&nbsp;</td>
                <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	
				<?php
				}
				?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key11=>&$item11){if($key11!='current'){$this->map['items']['current'] = &$item11;?>
<!---------GROUP----------->
<!---------CELLS----------->
	<tr <?php echo Draw::hover('#FFFF66');?> bgcolor="white">
		<td align="center" class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="center" class="report_table_column"><a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$this->map['items']['current']['id'],'bar_id')); //echo Url::build('touch_bar_restaurant',array('bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>$this->map['items']['current']['id'],'bar_id'=>$this->map['items']['current']['bar_id'])); ?>"><?php echo $this->map['items']['current']['code'];?></a></td>
        <td align="center" class="report_table_column"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id']; ?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
        <td align="center" class="report_table_column" ><?php echo date('H:i d/m/Y',$this->map['items']['current']['time_out']);?></td>
        
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['customer_name'];?> <br /><?php echo $this->map['items']['current']['traveller_name'];?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['table_name'];?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (($this->map['items']['current']['before_discount']==0)?'0':System::display_number(round($this->map['items']['current']['before_discount'])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo ((($this->map['items']['current']['total_discount']+$this->map['items']['current']['product_discount'])==0)?'':System::display_number(round($this->map['items']['current']['total_discount']+$this->map['items']['current']['product_discount'])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (($this->map['items']['current']['fee_rate']==0)?'':System::display_number(round($this->map['items']['current']['fee_rate'])));?></td>        
        <td align="right" class="report_table_column change_num " ><?php echo (($this->map['items']['current']['tax']==0)?'':System::display_number(round($this->map['items']['current']['tax'])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (($this->map['items']['current']['total_discount_after_tax']==0)?'':System::display_number(round($this->map['items']['current']['total_discount_after_tax'])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (($this->map['items']['current']['deposit']==0)?'':System::display_number($this->map['items']['current']['deposit']));?></td>
       <?php if(isset($this->map['payment_types']) and is_array($this->map['payment_types'])){ foreach($this->map['payment_types'] as $key12=>&$item12){if($key12!='current'){$this->map['payment_types']['current'] = &$item12;?>
       
        	 <?php if($this->map['payment_types']['current']['def_code']=='CREDIT_CARD'){ $card_item = 0; $count = 0;?>
        	 <?php if(isset($this->map['credit_card']) and is_array($this->map['credit_card'])){ foreach($this->map['credit_card'] as $key13=>&$item13){if($key13!='current'){$this->map['credit_card']['current'] = &$item13;?>
             	<?php $count ++;?>
                <?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key14=>&$item14){if($key14!='current'){$this->map['currencies']['current'] = &$item14;?>
                	 <?php $cr = $this->map['payment_types']['current']['def_code'].'_'.$this->map['credit_card']['current']['id'].'_'.$this->map['currencies']['current']['id']; 
                     if(isset($this->map['items']['current'][$cr]))
                     {					
						$card_item += $this->map['items']['current'][$cr];
					 }
                     ?>	
   				<?php }}unset($this->map['currencies']['current']);} ?>
             <?php }}unset($this->map['credit_card']['current']);} ?>
        <?php echo '<td align="right" class="report_table_column change_num" >'.System::Display_number($card_item).'</td>';	
		}else if($this->map['payment_types']['current']['def_code']=='CASH'){?>
        		<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key15=>&$item15){if($key15!='current'){$this->map['currencies']['current'] = &$item15;?>
                <?php $tt = $this->map['payment_types']['current']['def_code'].'_'.$this->map['currencies']['current']['id']; 
                if(isset($this->map['items']['current'][$tt]))
                {
					echo '<td align="right" class="report_table_column change_num" >'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}else{
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<?php }}unset($this->map['currencies']['current']);} ?>
         <?php }else if($this->map['payment_types']['current']['def_code']=='DEBIT'){?>
					<td align="right" class="report_table_column change_num" ><?php echo ($this->map['items']['current']['debit']==0?'':System::Display_number($this->map['items']['current']['debit']));?></td>	
         <?php }else if($this->map['payment_types']['current']['def_code']=='FOC'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ($this->map['items']['current']['foc']==0?'':System::Display_number($this->map['items']['current']['foc']));?></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ($this->map['items']['current']['room']==0?'':System::Display_number($this->map['items']['current']['room']));?></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='BANK'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ($this->map['items']['current']['bank']==0?'':System::Display_number($this->map['items']['current']['bank']));?></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='REFUND'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ($this->map['items']['current']['refund']==0?'':System::Display_number($this->map['items']['current']['refund']));?></td>
         <?php }?>
        <?php }}unset($this->map['payment_types']['current']);} ?>
        
        <td align="right" class="report_table_column change_num" ><?php echo $this->map['items']['current']['total']?System::display_number($this->map['items']['current']['total']):'0';?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['receptionist_id'];?></td>
        <td align="left" class="report_table_column" ><?php echo $this->map['items']['current']['note'];?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
    <?php $total_card = 0;?>
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="6" class="report_sub_title" align="right"><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
    	<td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['group_function_params']['before_discount']==0)?'':System::display_number(round($this->map['group_function_params']['before_discount'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['group_function_params']['total_discount']==0)?'':System::display_number(round($this->map['group_function_params']['total_discount'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['group_function_params']['fee_rate']==0)?'':System::display_number(round($this->map['group_function_params']['fee_rate'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['group_function_params']['tax']==0)?'':System::display_number(round($this->map['group_function_params']['tax'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['group_function_params']['total_discount_after_tax']==0)?'':System::display_number(round($this->map['group_function_params']['total_discount_after_tax'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (($this->map['group_function_params']['deposit']==0)?'':System::display_number(round($this->map['group_function_params']['deposit'])));?></strong></td>
    
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
        <?php echo '<td align="right" class="report_table_column"><strong class="change_num">'.System::Display_number($total_card).'</strong></td>';	
		}else if($this->map['payment_types']['current']['def_code']=='CASH'){?>
        		<?php if(isset($this->map['currencies']) and is_array($this->map['currencies'])){ foreach($this->map['currencies'] as $key19=>&$item19){if($key19!='current'){$this->map['currencies']['current'] = &$item19;?>
                <?php $tt = $this->map['payment_types']['current']['def_code'].'_'.$this->map['currencies']['current']['id']; if(isset($this->map['group_function_params'][$tt])){
					echo '<td align="right" class="report_table_column" ><strong class="change_num">'.(($this->map['group_function_params'][$tt]==0)?'':System::Display_number($this->map['group_function_params'][$tt])).'</strong></td>';	
				}else{
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<?php }}unset($this->map['currencies']['current']);} ?>
         <?php }else if($this->map['payment_types']['current']['def_code']=='DEBIT'){?>
					<td align="right" class="report_table_column" ><strong class="change_num"><?php echo (($this->map['group_function_params']['debit']==0)?'':System::Display_number($this->map['group_function_params']['debit']));?></strong></td>	
         <?php }else if($this->map['payment_types']['current']['def_code']=='FOC'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (($this->map['group_function_params']['foc']==0)?'':System::Display_number($this->map['group_function_params']['foc']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (($this->map['group_function_params']['room']==0)?'':System::Display_number($this->map['group_function_params']['room']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='BANK'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (($this->map['group_function_params']['bank']==0)?'':System::Display_number($this->map['group_function_params']['bank']));?></strong></td>
         <?php }else if($this->map['payment_types']['current']['def_code']=='REFUND'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (($this->map['group_function_params']['refund']==0)?'':System::Display_number($this->map['group_function_params']['refund']));?></strong></td>
         <?php }?> 
        <?php }}unset($this->map['payment_types']['current']);} ?>
        
			 <td align="right" class="report_table_column" ><strong class="change_num"><?php echo System::Display_number($this->map['group_function_params']['total']);?></strong></td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
            
		</tr>
</table>
</td>
</tr>