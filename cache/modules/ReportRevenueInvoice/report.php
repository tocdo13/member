<style>
    .simple-layout-middle{
		width:100%;	
	}
    .simple-layout-center{
        overflow-x: auto;
    }
    #search table tr td{
        text-align: center;
    }
    #search table tr td:last-child{
        border-right: none;
    }
    #container table tr th{
        height: 30px; text-align: center;
    }
    #container table tr td{
        text-align: center;
    }
    @media print{
	    #search{
	       display: none;
	    }
        a{
            text-decoration: none;
            color: #000000;
        }
    }
    .invoce_1 {
display: inline-block;
width: 75px;
margin-left: 6px;
}
.invoce_2 {
display: inline-block;
width: 61px;
margin-left: 6px;
}
.invoce_div{
        margin-bottom: 12px;
    }
    .do_search{
        padding: 6px 8px 5px 8px;
      
        border-radius: 5px;
        border: none;
        margin-left: 10px;
    }
    .wrapper-invoice{
        margin-top: 16px;
        margin-bottom: 12px;
        text-align: left;
    }
    .invoce_3{
        display:inline-block;
        margin-right: 6px;
     
    }  
    .b_blur{background: #cdcdcd;}
	.clear{clear:both;}
	.k_2{text-align:left;float:left;margin-top: 6px;border: 1px solid #FF7F50;border-radius: 10px;}
	.k_2 label{font-weight:100}
   .hidden_col{white-space: nowrap;}
   .bg_header{background:#dddddd;}
</style>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table  id="tblExport"  width="100%">
<tr id="header">
<td>
    <table style="border: none; width: 100%;">
        <tr valign="top" stype="font-size:11px;">
			<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			<td align="right" nowrap width="35%">
			<strong><?php echo Portal::language('template_code');?></strong>
            <br />
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>
		</tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <h2 class="report-title-new"><?php echo Portal::language('room_order_revenue_report');?></h2>
             <?php if(isset($this->map['search_invoice']) AND $this->map['search_invoice']!=''){ ?>
             <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                    <?php if($this->map['from_code']!=''){
                        ?>
                     <?php echo Portal::language('from_code');?>
                     <?php echo $this->map['from_code']; } ?>
                        <?php if($this->map['to_code']!=''){ ?> 
                        <?php echo Portal::language('to_code');?>
                    <?php echo $this->map['to_code'];
                     } 
                     }else{
                        ?>
                        <?php echo Portal::language('from');?>: <?php echo $this->map['from_time'];?> <?php echo $this->map['from_date'];?> - <?php echo Portal::language('to');?>: <?php echo $this->map['to_time'];?> <?php echo $this->map['to_date'];?>
                        <?php
                     }
                     ?> 
              
              </div>
                
            </td>
        </tr>
    </table>
</td>
</tr> 
<tr id="search" style="width: 99%; margin: 0px auto;">
<td>
    <form name="SearchFrom" method="post">
        <fieldset style="margin: 0 10%;text-align: center;margin-bottom: 15px;border: 1px solid #008B8B;">
            <legend style="margin-left: 3%;"><?php echo Portal::language('search');?></legend>
            <div style="text-align: center;display:inline-block;">
            <table style="float:left">
                <tr>
                <td>
                 <fieldset style="width:177px;margin-right: 20px;">
                    <legend>
                    <input name="search_invoice" type="checkbox" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !='')echo 'checked="checked"';} ?>><label><?php echo Portal::language('search_by_bill');?></label></legend>
                    <div class="invoce_div">
                        <label class="invoce_1"><?php echo Portal::language('bill_id');?></label> 
                        <input  name="from_code" id="from_code" style="width: 70px;" class="b_blur input_number" readonly="read" / type ="text" value="<?php echo String::html_normalize(URL::get('from_code'));?>"> 
                    </div>
                    <div class="invoce_div">
                        <label class="invoce_1"><?php echo Portal::language('to');?>:</label>
                         <input  name="to_code" id="to_code" style="width: 70px;" class="b_blur input_number" readonly="read" / type ="text" value="<?php echo String::html_normalize(URL::get('to_code'));?>">
                    </div>   
                    <div>
                        <label class="invoce_1"><?php echo Portal::language('recode');?>:</label>
                         <input  name="recode" id="recode" style="width: 70px;" class="b_blur input_number" readonly="read" / type ="text" value="<?php echo String::html_normalize(URL::get('recode'));?>">
                    </div> 
                  </fieldset>
                </td>
				  </tr>
                
            </table>
                <div class="k_2">
					<div style="">
					<fieldset style="border:none;display: inline-block;float: left;margin-right: 10px;">
                    <legend>
                    <input name="search_time" type="checkbox" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_time']) && $_REQUEST['search_time'] !='')echo 'checked="checked"'; } ?>><label><?php echo Portal::language('search_by_time');?></label></legend>
                    <div class="invoce_div">
                        <table>
                            <tr>
                                <td><label class="invoce_2"><?php echo Portal::language('from_date');?>:</label></td>
                                <td><input  name="from_date" id="from_date" style="width: 80px;" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                <td><label class="invoce_2"><?php echo Portal::language('from_time');?>:</label></td>
                                <td><input  name="from_time" id="from_time" class="input-short-text" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>"></td>
                                <td nowrap><label class="invoce_3"><?php echo Portal::language('hotel');?>:</label></td>
                                <td colspan="3"><select  name="portal_id" id="portal_id" style="width: 100%;"><?php
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
                            </tr>
                            <tr>
                                <td><label class="invoce_2"><?php echo Portal::language('to_date');?>:</label></td>
                                <td><input  name="to_date" id="to_date" style="width: 80px;" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"> </td>
                                <td><label class="invoce_2"><?php echo Portal::language('to_time');?>:</label></td>
                                <td><input  name="to_time" id="to_time" class="input-short-text" / type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>"></td>
                                <td  align="left" nowrap="nowrap"><?php echo Portal::language('user_status');?></td>
                			    <td>
                                  <!-- 7211 -->  
                                  <select  style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                                    <option value="1">Active</option>
                                    <option value="0">All</option>
                                  </select>
                                  <!-- 7211 end--> 
                                </td>
                                <td><label class="invoce_3"><?php echo Portal::language('user');?>:</label></td>
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
                            </tr>
                        </table>  
                    </div>
                  </fieldset>
                  <!--
				  <div style="padding-top:24px;">
                    <div class="invoce_div" style="margin-bottom: 15px;float:left">
                     
                     
                    </div>
                    
                    <div style="float:left">
                        <label class="invoce_3"><?php echo Portal::language('line_per_page');?>:</label> <input  name="line_per_page" id="line_per_page" style="width: 35px;text-align:center" / type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>">
                         <label class="invoce_3"><?php echo Portal::language('no_of_page');?>:</label> <input  name="no_of_page" id="no_of_page" style="width: 35px;text-align:center" / type ="text" value="<?php echo String::html_normalize(URL::get('no_of_page'));?>">
                         <label class="invoce_3"><?php echo Portal::language('from_page');?>:</label> <input  name="start_page" id="start_page" style="width: 35px;text-align:center"  / type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>">
                    </div>
                   </div> --> 
                    </div>
                </div>
				<div class="clear"></div>
                <div>
                    <!-- Oanh add -->
                    <button id="export"><?php echo Portal::language('export_file_excel');?></button>
                    <!-- Edn oanh -->
                    <input name="do_search" type="submit" value="<?php echo Portal::language('search');?>" class="do_search" onclick="return check_search();"/>
                </div>
              
            </div>
            
        </fieldset>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</td>
</tr>
<div style="text-align:left;margin-bottom: 15px;margin-left: 10%;">
                    <input type="checkbox" name="check_hidden_col" id="check_hidden_col"/><?php echo Portal::language('hidden_col');?>
</div>
<tr id="container">
<td>
    <table cellpadding="2" cellspacing="0" border="1" bordercolor="#cccccc" id="container_table">
        <tr class="bg_header">
            <th rowspan="3" style="white-space: nowrap;"><?php echo Portal::language('stt');?></th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;"><?php echo Portal::language('order_id');?></th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;"><?php echo Portal::language('recode');?></th>
            <th rowspan="3" style="width: 200px;"><?php echo Portal::language('guest_name');?></th>
            <!--<th rowspan="3" style="width: 50px;white-space: nowrap;"><?php echo Portal::language('room');?></th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;"><?php echo Portal::language('arrival_date');?><br /><?php echo Portal::language('departure_date');?></th>
            <th rowspan="3" style="width: 30px;white-space: nowrap;"><?php echo Portal::language('room_night');?></th>
            <th rowspan="3" style="width: 50px;white-space: nowrap;"><?php echo Portal::language('room_rate');?></th>-->
            <th rowspan="3" style="width: 50px;" class="hidden_col"><?php echo Portal::language('room_price_total');?></th>
            <th rowspan="3" style="width: 50px;" class="hidden_col"><?php echo Portal::language('extra_bed_1');?></th>
            <th rowspan="3" style="width: 50px;" class="hidden_col"><?php echo Portal::language('baby_cot');?></th>
			<th rowspan="3" class="hidden_col"><?php echo Portal::language('tour1');?></th>
			<th rowspan="3" class="hidden_col"><?php echo Portal::language('extra_service');?></th>
			<th rowspan="3" class="hidden_col"><?php echo Portal::language('telephone');?></th>
			
            <th rowspan="3" class="hidden_col"><?php echo Portal::language('minibar');?></th>
            <th rowspan="3" class="hidden_col"><?php echo Portal::language('laundry');?></th>
			<th rowspan="3" class="hidden_col"><?php echo Portal::language('equipment');?></th>
			
			<th rowspan="3" class="hidden_col"><?php echo Portal::language('restaurant');?></th>
			<!---<th rowspan="3" class="hidden_col"><?php echo Portal::language('banquet');?></th>
            <th rowspan="3" class="hidden_col"><?php echo Portal::language('karaoke');?></th>--->
			
			<th rowspan="3" class="hidden_col"><?php echo Portal::language('spa');?></th>
            <!---<th rowspan="3" class="hidden_col"><?php echo Portal::language('vending');?></th>
            <th rowspan="3" class="hidden_col"><?php echo Portal::language('ticket');?></th>--->
			
            <th rowspan="3" class="hidden_col"><?php echo Portal::language('deposit');?></th>
            <th rowspan="3" style="width: 100px;"><?php echo Portal::language('total');?></th>
			
            <th colspan="<?php echo ((sizeof($this->map['payment_type'])-2)*sizeof($this->map['currency']))+2; ?>"><?php echo Portal::language('payment');?></th>
           
            <th rowspan="3" style="white-space: nowrap;"><?php echo Portal::language('user_id');?></th>
        </tr>
        <tr class="bg_header">
            <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key1=>&$item1){if($key1!='current'){$this->map['payment_type']['current'] = &$item1;?>
            <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
            <th style="white-space: nowrap;"><?php echo $this->map['payment_type']['current']['name'];?></th>
            <?php }else{ ?>
            <th colspan="<?php echo sizeof($this->map['currency']); ?>" style="white-space: nowrap;"><?php echo $this->map['payment_type']['current']['name'];?></th>
            <?php } ?>
            <?php }}unset($this->map['payment_type']['current']);} ?>
        </tr>
        <tr class="bg_header">
            <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key2=>&$item2){if($key2!='current'){$this->map['payment_type']['current'] = &$item2;?>
                <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
                    <th>VND</th>
                <?php }else{ ?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key3=>&$item3){if($key3!='current'){$this->map['currency']['current'] = &$item3;?>
                    <th><?php echo $this->map['currency']['current']['id'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
                <?php } ?>
            <?php }}unset($this->map['payment_type']['current']);} ?>
           
        </tr>
        <?php 
        $total_money_folio=0; 
        $total_money_room=0;
        $total_money_extra_bed=0;
        $total_money_baby_cot=0;
        $total_money_tour=0;
        $total_money_extra_service=0;
        $total_money_telephone=0;
        $total_money_minibar=0;
        $total_money_laundry=0;
        $total_money_equip=0;
        $total_money_restaurant=0;
        $total_money_spa=0;
        $total_money_banquet=0;
        $total_money_karaoke=0;
        $total_money_vending=0;
        $total_money_ticket=0;
        $total_money_deposit = 0;
        ?>
        <?php if(isset($this->map['invoice']) and is_array($this->map['invoice'])){ foreach($this->map['invoice'] as $key4=>&$item4){if($key4!='current'){$this->map['invoice']['current'] = &$item4;?>
            <?php $count=0; ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current'] = &$item5;?>
                <?php if($this->map['invoice']['current']['folio_id']==$this->map['items']['current']['folio_id'])
                        {
                            
                            $count++; 
                            if($count==1){       
                ?>
                <tr>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>"><?php echo $this->map['invoice']['current']['id'];?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>"><a target="_blank" href="<?php echo $this->map['invoice']['current']['link'];?>"><!--<?php echo $this->map['invoice']['current']['folio_id'];?>-->
                    <!-- oanh add -->
                        <?php if(isset($this->map['invoice']['current']['folio_code']) and ($this->map['invoice']['current']['folio_code'])){?>
                            <?php echo 'No.F'.str_pad($this->map['invoice']['current']['folio_code'],6,"0",STR_PAD_LEFT);?>
                        <?php } else {?>
                            <?php echo 'Ref'.str_pad($this->map['invoice']['current']['folio_id'],6,"0",STR_PAD_LEFT);?>
                        <?php }?>   
                    <!-- oanh add --> 
                    </a></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>"><a target="_blank" href="?page=reservation&layout=list&cmd=edit&id=<?php echo $this->map['invoice']['current']['reservation_id']?>"><?php echo $this->map['invoice']['current']['reservation_id'];?></a></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" style="text-align: left; padding-left: 5px; width: 200px;">
                        <?php echo Portal::language('customer_name');?>: <?php echo $this->map['invoice']['current']['customer_name'];?> 
                        <?php echo Portal::language('traveller_name');?>: <?php echo isset($this->map['invoice']['current']['traveller_name']) && trim($this->map['invoice']['current']['traveller_name'])!=""? '<b>'.$this->map['invoice']['current']['traveller_name'].'</b>':''?>
                    </td>
                    <!--<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>"><?php echo $this->map['invoice']['current']['room_name'];?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>"><?php //echo ($this->map['items']['current']['time_in']==0?'':date('d/m/Y',$this->map['items']['current']['time_in']));?><br/><?php echo ($this->map['items']['current']['time_out']==0?'':date('d/m/Y',$this->map['items']['current']['time_out']));?></td>
                    <td><?php //echo System::Display_number(round(($this->map['items']['current']['time_out']-$this->map['items']['current']['time_in'])/(24*6400)));?></td>
                    <td style="text-align: right;"><?php //echo System::Display_number(round(($this->map['items']['current']['total_room'])/($this->map['items']['current']['departure_time']-$this->map['items']['current']['arrival_time'])));?></td>-->
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_room +=$this->map['items']['current']['total_room']; echo System::display_number(round($this->map['items']['current']['total_room'])); ?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_extra_bed+=$this->map['items']['current']['extra_bed']; echo System::display_number(round($this->map['items']['current']['extra_bed'])); ?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_baby_cot +=$this->map['items']['current']['baby_cot']; echo System::display_number(round($this->map['items']['current']['baby_cot'])); ?></td>
            
					<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_tour +=$this->map['items']['current']['tour']; echo System::display_number(round($this->map['items']['current']['tour'])); ?></td>
					<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_extra_service +=$this->map['items']['current']['extra_service']; echo System::display_number(round($this->map['items']['current']['extra_service'])); ?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_telephone +=$this->map['items']['current']['telephone']; echo System::display_number(round($this->map['items']['current']['telephone'])); ?></td>
					
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_minibar +=$this->map['items']['current']['minibar']; echo System::display_number(round($this->map['items']['current']['minibar'])); ?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_laundry +=$this->map['items']['current']['laundry']; echo System::display_number(round($this->map['items']['current']['laundry'])); ?></td>
					<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_equip +=$this->map['items']['current']['equip']; echo System::display_number(round($this->map['items']['current']['equip'])); ?></td>
					
					<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_restaurant +=$this->map['items']['current']['restaurant']; echo System::display_number(round($this->map['items']['current']['restaurant'])); ?></td>
                    <!---<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_banquet +=$this->map['items']['current']['banquet']; echo System::display_number(round($this->map['items']['current']['banquet'])); ?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_karaoke +=$this->map['items']['current']['karaoke']; echo System::display_number(round($this->map['items']['current']['karaoke'])); ?></td>--->
                    
					<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_spa +=$this->map['items']['current']['spa']; echo System::display_number(round($this->map['items']['current']['spa'])); ?></td>
                    <!---<td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_vending +=$this->map['items']['current']['vending']; echo System::display_number(round($this->map['items']['current']['vending'])); ?></td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php $total_money_ticket +=$this->map['items']['current']['ticket']; echo System::display_number(round($this->map['items']['current']['ticket'])); ?></td>--->
					
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" class="hidden_col" style="text-align: right;"><?php 
                    if(isset($this->map['items']['current']['deposit']))
                    {
                        $total_money_deposit +=$this->map['items']['current']['deposit'];
                        echo System::display_number($this->map['items']['current']['deposit']);
                    }
                    else 
                    echo '';
                     ?>
                     </td>
                    <td rowspan="<?php echo $this->map['invoice']['current']['num'];?>" style="text-align: right;">
                    <?php
                    $total_money_folio +=$this->map['items']['current']['total'];
                    echo System::display_number($this->map['items']['current']['total']);
                    ?>
                    </td>
                    <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key6=>&$item6){if($key6!='current'){$this->map['payment_type']['current'] = &$item6;?>
                        <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
                        <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key7=>&$item7){if($key7!='current'){$this->map['currency']['current'] = &$item7;?>
                        <?php if(($this->map['currency']['current']['id']=='VND')){ ?>
                        <th style="text-align: right;">
                            <?php
                                if(($this->map['currency']['current']['id']==$this->map['items']['current']['currency_id']) AND ($this->map['payment_type']['current']['id']==$this->map['items']['current']['payment_type_id']))
                                    echo System::display_number($this->map['items']['current']['amount']); 
                            ?>
                        </th>
                        <?php } ?>
                        <?php }}unset($this->map['currency']['current']);} ?>
                        <?php }else{ ?>
                        <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key8=>&$item8){if($key8!='current'){$this->map['currency']['current'] = &$item8;?>
                        <th style="text-align: right;">
                            <?php
                                if(($this->map['currency']['current']['id']==$this->map['items']['current']['currency_id']) AND ($this->map['payment_type']['current']['id']==$this->map['items']['current']['payment_type_id']))
                                    echo System::display_number($this->map['items']['current']['amount']); 
                            ?>
                        </th>
                        <?php }}unset($this->map['currency']['current']);} ?>
                        <?php } ?>
                    <?php }}unset($this->map['payment_type']['current']);} ?>
                  
                    <td><?php echo $this->map['items']['current']['user_id'];?></td>
                </tr>
                <?php
                                        }
                                        else
                                        {
                ?> 
                <tr>
                    <!--<td><?php echo $this->map['items']['current']['time'];?></td>-->
                    <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key9=>&$item9){if($key9!='current'){$this->map['payment_type']['current'] = &$item9;?>
                        <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
                        <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key10=>&$item10){if($key10!='current'){$this->map['currency']['current'] = &$item10;?>
                        <?php if(($this->map['currency']['current']['id']=='VND')){ ?>
                        <th style="text-align: right;"><?php
                            if(($this->map['currency']['current']['id']==$this->map['items']['current']['currency_id']) AND ($this->map['payment_type']['current']['id']==$this->map['items']['current']['payment_type_id']))
                                echo System::display_number($this->map['items']['current']['amount']); 
                            ?>
                        </th>
                        <?php } ?>
                        <?php }}unset($this->map['currency']['current']);} ?>
                        <?php }else{ ?>
                        <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key11=>&$item11){if($key11!='current'){$this->map['currency']['current'] = &$item11;?>
                        <th style="text-align: right;"><?php
                            if(($this->map['currency']['current']['id']==$this->map['items']['current']['currency_id']) AND ($this->map['payment_type']['current']['id']==$this->map['items']['current']['payment_type_id']))
                                echo System::display_number($this->map['items']['current']['amount']); 
                            ?>
                        </th>
                        <?php }}unset($this->map['currency']['current']);} ?>
                        <?php } ?>
                    <?php }}unset($this->map['payment_type']['current']);} ?>
                    <td><?php echo $this->map['items']['current']['user_id'];?></td>
                </tr>    
                <?php   
                                        }
                        } 
                ?>
            <?php }}unset($this->map['items']['current']);} ?>
        <?php }}unset($this->map['invoice']['current']);} ?>
        <tr style="background: #dddddd;">
             <th colspan="4" rowspan="3" id="col_total" style="text-align: right; text-transform: uppercase;"><?php echo Portal::language('total');?>: </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_room));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_extra_bed));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_baby_cot));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_tour));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_extra_service));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_telephone));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_minibar));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_laundry));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_equip));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_restaurant));?> </th>
             <!---<th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_banquet));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_karaoke));?> </th>--->
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_spa));?> </th>
             <!---<th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_vending));?> </th>
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_ticket));?> </th>--->
             <th rowspan="3" class="hidden_col" style="text-align: right;"><?php echo System::display_number(round($total_money_deposit));?> </th>
             <th rowspan="3"  style="text-align: right;"><?php echo System::display_number(round($total_money_folio));?> </th>
             
            <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key12=>&$item12){if($key12!='current'){$this->map['payment_type']['current'] = &$item12;?>
            <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
            <th><?php echo $this->map['payment_type']['current']['name'];?></th>
            <?php }else{ ?>
            <th colspan="<?php echo sizeof($this->map['currency']); ?>"><?php echo $this->map['payment_type']['current']['name'];?></th>
            <?php } ?>
            <?php }}unset($this->map['payment_type']['current']);} ?>
            
            <th rowspan="3"></th>
        </tr>
        <tr style="background: #dddddd;">
            <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key13=>&$item13){if($key13!='current'){$this->map['payment_type']['current'] = &$item13;?>
                <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
                    <th>VND</th>
                <?php }else{ ?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key14=>&$item14){if($key14!='current'){$this->map['currency']['current'] = &$item14;?>
                    <th><?php echo $this->map['currency']['current']['id'];?></th>
                <?php }}unset($this->map['currency']['current']);} ?>
                <?php } ?>
            <?php }}unset($this->map['payment_type']['current']);} ?>
         
        </tr>
        <tr style="background: #dddddd;">
            <?php if(isset($this->map['payment_type']) and is_array($this->map['payment_type'])){ foreach($this->map['payment_type'] as $key15=>&$item15){if($key15!='current'){$this->map['payment_type']['current'] = &$item15;?>
                <?php if($this->map['payment_type']['current']['id']=='FOC' OR $this->map['payment_type']['current']['id']=='DEBIT'){ ?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key16=>&$item16){if($key16!='current'){$this->map['currency']['current'] = &$item16;?>
                <?php if($this->map['currency']['current']['id']=='VND'){ ?>
                <th style="text-align: right;">
                    <?php
                        if($this->map[$this->map['payment_type']['current']['id']."_".$this->map['currency']['current']['id']]>0)
                        echo System::display_number($this->map[$this->map['payment_type']['current']['id']."_".$this->map['currency']['current']['id']]); 
                    ?>
                </th>
                <?php } ?>
                <?php }}unset($this->map['currency']['current']);} ?>
                <?php }else{ ?>
                <?php if(isset($this->map['currency']) and is_array($this->map['currency'])){ foreach($this->map['currency'] as $key17=>&$item17){if($key17!='current'){$this->map['currency']['current'] = &$item17;?>
                <th style="text-align: right;">
                    <?php
                        if($this->map[$this->map['payment_type']['current']['id']."_".$this->map['currency']['current']['id']]>0)
                        echo System::display_number($this->map[$this->map['payment_type']['current']['id']."_".$this->map['currency']['current']['id']]); 
                    ?>
                </th>
                <?php }}unset($this->map['currency']['current']);} ?>
                <?php } ?>
            <?php }}unset($this->map['payment_type']['current']);} ?>
            
        </tr>
    </table>
</td>
</tr>
<tr id="footer">
<td>
<!-- Oanhbtk add -->
    <table style="width:100%;height:120px;margin-top:15px;">
        <tr valign="top">
    		<td width="33%" align="center"><b><?php echo Portal::language('creator');?></b></td>
    		<td width="33%" align="center"><b><?php echo Portal::language('cm_truongbophan');?></b></td>
    		<td width="33%" align="center"><b><?php echo Portal::language('cm_acountting');?></b></td>
    	</tr>
	</table>
<!-- End Oanhbtk -->
</td>
</tr>
</table>
<script type="text/javascript">
jQuery(document).ready(function(){
    <?php
    if(isset($_REQUEST)){
        if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !=''){
            ?>
            document.getElementById("search_time").checked = false;
            jQuery("#from_date").addClass('b_blur').attr('value','');
                jQuery("#to_date").addClass('b_blur').attr('value','');
                 jQuery("#from_time").addClass('b_blur').attr('value','');
                jQuery("#to_time").addClass('b_blur').attr('value','');
            <?php
        }
    
    }
    ?>
        if(jQuery("#search_invoice").is(":checked")){
                  jQuery("#from_code").removeClass();
                  jQuery("#from_code").attr('readonly',false);
                  jQuery("#to_code").removeClass();
                  jQuery("#to_code").attr('readonly',false);
                  jQuery("#recode").removeClass();
                  jQuery("#recode").attr('readonly',false);
    };
    
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
		jQuery("#from_date").addClass('b_blur').attr('value','');
		jQuery("#to_date").addClass('b_blur').attr('value','');
		 jQuery("#from_time").addClass('b_blur').attr('value','');
		jQuery("#to_time").addClass('b_blur').attr('value','');
		  jQuery("#from_code").removeClass();
		  jQuery("#from_code").attr('readonly',false);
          jQuery("#recode").removeClass();
		  jQuery("#recode").attr('readonly',false);
		  jQuery("#to_code").removeClass();
		  jQuery("#to_code").attr('readonly',false);
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#to_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#from_code").addClass('b_blur').attr('readonly',true).attr('value','');
            jQuery("#recode").addClass('b_blur').attr('readonly',true).attr('value','');
             jQuery("#to_code").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#recode").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#recode").attr('readonly',true);
            jQuery("#to_code").attr('readonly',true);
            jQuery("#from_date").removeClass('b_blur');
            jQuery("#to_date").removeClass('b_blur');
            jQuery("#from_time").removeClass('b_blur');
            jQuery("#to_time").removeClass('b_blur');
        }
    });
    
        jQuery("#search_time").click(function(){
			jQuery("#from_date").removeClass('b_blur');
            jQuery("#to_date").removeClass('b_blur');
            jQuery("#from_time").removeClass('b_blur');
            jQuery("#to_time").removeClass('b_blur');
        if(jQuery("#search_invoice").is(":checked")){
            jQuery("#from_code").removeClass();
            jQuery("#to_code").removeClass();
            jQuery("#recode").removeClass();
             jQuery("#from_code").attr('readonly',false);
            jQuery("#to_code").attr('readonly',false);
            jQuery("#recode").attr('readonly',false);
	   jQuery("#from_date").addClass('b_blur').attr('value','');
            jQuery("#to_date").addClass('b_blur').attr('value','');
            jQuery("#from_time").addClass('b_blur').attr('value','');
            jQuery("#to_time").addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").attr('value','00:00');
			jQuery("#to_time").attr('value','<?php echo date('H:i');?>');
            jQuery("#from_code").addClass('b_blur').attr('readonly',true).attr('value','');
            jQuery("#recode").addClass('b_blur').attr('readonly',true).attr('value','');
             jQuery("#to_code").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#recode").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#recode").attr('readonly',true);
            jQuery("#to_code").attr('readonly',true);
            jQuery("#from_code").attr('value','');
            jQuery("#recode").attr('value','');
            jQuery("#to_code").attr('value','');
	
        }
            
    });
    

});
</script>

<script>
    
    function check_bar()
    {    var from_code = jQuery("#from_code").val();
        var to_code = jQuery("#to_code").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code==''){
                   
                    alert('<?php echo Portal::language('empty_report');?>');
                    return false;
                }
                
            }
    }   
    
    function fun_check_option(id)
    {
        if(id==1)
        {
            if(document.getElementById("search_time").checked==true)
            {
                document.getElementById("search_invoice").checked=false;
            }
            else
            {
                document.getElementById("search_invoice").checked=true;
            }
        }
        else
        {
            if(document.getElementById("search_invoice").checked==true)
            {
                document.getElementById("search_time").checked=false;
            }
            else
            {
                document.getElementById("search_time").checked=true;
            }
        }
    }

    $('portal_id').value = '<?php echo PORTAL_ID;?>';
    jQuery('#from_time').mask("99:99");
    jQuery('#to_time').mask("99:99");
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1]-1,mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1]-1,mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
	    function check_search()
    {
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
		var from_code = jQuery("#from_code").val();
        var to_code = jQuery("#to_code").val();
        var recode = jQuery("#recode").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code=='' && recode==''){
                   
                    alert('<?php echo Portal::language('empty_report');?>');
                    return false;
                }
                
            }
		
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
                if(jQuery("#from_code").val()!='' && jQuery("#to_code").val()!='')
                {
                    if( to_numeric(jQuery("#from_code").val())<=to_numeric(jQuery("#to_code").val()) )
                    {
                        return true;
                    }
                    else
                    {
                        
                        alert('<?php echo Portal::language('the_bill_number_is_not_valid_try_again');?>');
                        return false;
                    }
                }
                else
                {  
                    return true; 
                }
            }
        }
    }
    
 //binh add chuc nang an cot chi tiet
 jQuery('#check_hidden_col').click(function(){
    if (jQuery(this).is(":checked")){
        
        jQuery('.hidden_col').css('display','none');
        jQuery('#container_table').css({'width':'99%','margin':'10px auto'});
        ///jQuery('#col_total').attr('colspan','4');
    }else{
        jQuery('.hidden_col').css('display','');
         jQuery('#container_table').css({'width':'','margin':'0'});
         //jQuery('#col_total').attr('colspan','20');
    }
 }); 
 //giap.ln add truong hop in full ca trang  
 if(jQuery('#check_hidden_col').is(":checked")==false)
 {
    document.getElementById('container_table').style.fontSize='10px';
 }
 //end giap.ln
 
 
</script>

<script type="text/javascript"> 
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#export").remove();
            jQuery("#search").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
             location.reload();
        });
    });
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
</script>
