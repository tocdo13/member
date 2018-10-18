<?php //System::debug($this->map['start_page'].'---'.$this->map['page_no']); ?>
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<!---------HEADER----------->
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
</div>
<table id="tblExport">
<tr id="header_report">
<td>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td >
    <table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong>
               </td>
        <td align="right" style="padding-right:10px;" ><strong><?php echo Portal::language('template_code');?></strong> 
        <br />
        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
        <br />
        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" ><?php echo Portal::language('sale_report');?><br />
        </font> <span style="font-family:'Times New Roman', Times, serif; font-size:12px;"><?php echo $this->map['from_time'];?>&nbsp;<?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['from_date'] ?> - <?php echo $this->map['to_time'];?>&nbsp;<?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['to_date'] ?></span> </div></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</td>
</tr>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
.left{float: left;}
.p1{text-align:left;margin-bottom: 12px;margin-top: 25px;}
.search_sale{padding:0 6%;margin-bottom:20px;}
.p2{
    margin: 0px;
 
}
.fls_1{margin-right:20px;min-height: 70px;}
.fls{margin-bottom:15px ;}
.fr_time{margin-bottom: 12px;}
.center{text-align:center;}
.tr_1{text-align: center;
display: inline-block;}
.k55px{width:55px;display:inline-block}
.k40px{width:40px;display:inline-block}
</style>

<tr id="tr_search">
    <td style="text-align: left;">
<!---------SEARCH----------->
<fieldset id="search_from" style="border:none;">
           
    <table width="100%"  style="margin: 0px  auto 0px;padding:20px 0;text-align: center;" id="search" > 
    <tr class="tr_1">
    <td>
        <link rel="stylesheet" href="skins/default/report.css"/>
        <div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
            <div>
                <table width="100%">
                    <tr><td>
                        <form name="SearchForm" method="post">
                            <table class="table_select left">
                                <tr>   
                                    <td>
                                        <fieldset class="fls_1">
                                            <legend class="fls">
                                              <input  name="search_invoice" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('search_invoice'));?>">
											  <label><?php echo Portal::language('search_for_invoice');?></label>
                                            </legend>
                                            <label><?php echo Portal::language('folio_id');?></label>   
                                            <input  name="folio_number" id="folio_number" class="input_number b_blur" style="width: 40px;" readonly / type ="text" value="<?php echo String::html_normalize(URL::get('folio_number'));?>">
                                        </fieldset>

                                    </td>  
                                    <td style="border: 1px solid #cdcdcd;padding: 5px 10px;">
                                         <fieldset class="fls_1" style="float:left">
                                            <legend>
                                                <input  name="search_time" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" type ="checkbox" value="<?php echo String::html_normalize(URL::get('search_time'));?>">
												<label><?php echo Portal::language('search_for_time');?></label>
                                            </legend>
                                        <div class="fr_time">
                                            <label class="k55px"><?php echo Portal::language('from_date');?></label>
                                            <input  name="from_date" id="from_date" onchange="changevalue()"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                                            
                                            <label class="k40px"><?php echo Portal::language('from_time');?></label>
                                            <input  name="from_time" id="from_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>">
                                        </div>
                                        <div >
                                            <label class="k55px"><?php echo Portal::language('to_date');?></label>
                                            <input  name="to_date" id="to_date" onchange="changefromday()"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                                            <label class="k40px"><?php echo Portal::language('to_time');?></label>
                                            <input  name="to_time" id="to_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>">
                                        </div>
                                    </fieldset>
									
									<div class="public left">

                                    <div class="p1">
                                        <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                        <label><?php echo Portal::language('hotel');?></label>
                                        <select  name="portal_id" id="portal_id" style="margin-left:5px"><?php
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
	</select>
                                        <?php }?>

                                        <label><?php echo Portal::language('by_user');?></label>
                                        <select  name="user_id" id="user_id" style="margin-left:5px"><?php
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
	</select>
                                        <label><?php echo Portal::language('payment_type');?></label>
                                        <select  name="payment_type" id="payment_type" style="width: 106px;margin-left:5px"><?php
					if(isset($this->map['payment_type_list']))
					{
						foreach($this->map['payment_type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_type',isset($this->map['payment_type'])?$this->map['payment_type']:''))
                    echo "<script>$('payment_type').value = \"".addslashes(URL::get('payment_type',isset($this->map['payment_type'])?$this->map['payment_type']:''))."\";</script>";
                    ?>
	</select>
                                    </div>
                                    <div class="p1 p2">
                                         <label><?php echo Portal::language('line_per_page');?></label>
                                        <input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="4" style="text-align:right;margin-left:5px"/>
                                    <label><?php echo Portal::language('no_of_page');?></label>
                                        <input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="4" style="text-align:right;margin-left:5px"/>
                                    <label><?php echo Portal::language('from_page');?></label>
                                    <input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right;margin-left:5px"/>
                                   
                                
                              
                                <label><?php echo Portal::language('customer');?></label>
                                <input  name="customer" id="customer" style="width: 100px;margin-left:5px" onkeypress="Autocomplete();"  autocomplete="off"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer'));?>">
                               <label><?php echo Portal::language('room_name');?></label>
                                <input  name="room_name" id="room_name" style="width: 40px;margin-left:5px" / type ="text" value="<?php echo String::html_normalize(URL::get('room_name'));?>">

                                    </div>

                                </div>
                                    </td>
                                     </tr>
                                </table>

                                
                            
                               
                        
                    </td>
                 
                    </tr>
                   
                </table>
            </div>
        </div>
    </td>
    </tr>
     <tr>
     <td colspan="2">
            <div class="public center" style="margin-top: 5px;">
                     <input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" onclick=" return check_search();"/>
                        <button id="export"><?php echo Portal::language('export');?></button>
                </div>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
     </td>
            
        </tr>
</table>
</fieldset>
</td>
</tr>


				<?php
				}
				?>

                <!---------REPORT----------->	
                <?php 
				if((isset($this->map['items'])))
				{?>
<tr>
<td>
                <table cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
                	<tr bgcolor="#EFEFEF">
                	    <th rowspan="3" class="report-table-header" style="width: 50px !important;"><?php echo Portal::language('no');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('recode');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('room');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('folio_id');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('guest_name');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('arrival_date');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('departure_date');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('night');?></th>  
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('room_rate');?>(VND)</th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('room_rate');?><br />(USD)</th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('room_price_total');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('extra_bed');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('telephone');?></th>
                        <?php if(isset($this->map['portal_department']) and is_array($this->map['portal_department'])){ foreach($this->map['portal_department'] as $key1=>&$item1){if($key1!='current'){$this->map['portal_department']['current'] = &$item1;?>
                         <?php if($this->map['portal_department']['current']['department_code']=='RES'){?>
                	       <th  class="report-table-header"><?php echo Portal::language('rest');?></th>	
                         <?php }else if($this->map['portal_department']['current']['department_code']=='HK'){?>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('minibar');?></th>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('laundry');?></th>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('compensation');?></th>
                         <?php }else if($this->map['portal_department']['current']['department_code']=='SPA'){?>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('spa');?></th>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='KARAOKE'){?>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('karaoke');?></th>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='BANQUET'){?>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('banquet');?></th>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='VENDING'){?>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('vending');?></th>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='TICKET'){?>
                            <th rowspan="3" class="report-table-header"><?php echo Portal::language('ticket');?></th>
                        <?php } ?>       
                        <?php }}unset($this->map['portal_department']['current']);} ?> 
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('tour1');?></th>
                        <th rowspan="3" class="report-table-header"><?php echo Portal::language('other');?></th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFFF99;"><?php echo Portal::language('total');?></th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFCCFF;"><?php echo Portal::language('discount');?></th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFCCFF;"><?php echo Portal::language('deposit_room');?></th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FFCCFF;"><?php echo Portal::language('deposit_group');?></th>
                        <th colspan="6" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('payment');?></th>
                        <th rowspan="3" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('debit');?></th>
                        <th rowspan="1" colspan="2" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('refund');?></th>
                        <th rowspan="3" class="report-table-header" style="background-color:#FF66FF;"><?php echo Portal::language('total_amount');?></th>   
                           
                	</tr>
                    <tr>
                        <?php if(isset($this->map['portal_department']) and is_array($this->map['portal_department'])){ foreach($this->map['portal_department'] as $key2=>&$item2){if($key2!='current'){$this->map['portal_department']['current'] = &$item2;?>
                         <?php if($this->map['portal_department']['current']['department_code']=='RES'){?>
                            <!--<th width="60px" class="report-table-header"><?php echo Portal::language('break_fast');?></th>-->
                            <th width="60px" rowspan="2" class="report-table-header"><?php echo Portal::language('F&B');?></th>	
                        <?php } ?>       
                        <?php }}unset($this->map['portal_department']['current']);} ?> 
                        <th width="60px" colspan="2" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('cash');?></th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('bank_transfer');?></th>
                        <th width="60px" colspan="2" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('credit_card');?></th>
                        <th width="60px" colspan="1" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('foc');?></th>
                        <th width="60px" rowspan="2" colspan="1" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('VND');?></th>
                        <th width="60px" rowspan="2" colspan="1" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('USD');?></th>
                    </tr>
                    <tr>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('VND');?></th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('USD');?></th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"></th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('VND');?></th>
                        <th width="40px"  class="report-table-header" style="background-color:#99CCCC;"><?php echo Portal::language('USD');?></th>
                        <th width="60px" class="report-table-header" style="background-color:#99CCCC;"></th>
                    </tr>
                <!--start: KID  1
                <?php 
				if(($this->map['page_no']!=1))
				{?>
                <!---------LAST GROUP VALUE----------->	        
                    <tr>
                        <td colspan="10" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
                    	<td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_room_total']?System::display_number($this->map['last_group_function_params']['total_room_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_extra_bed_total']?System::display_number($this->map['last_group_function_params']['total_extra_bed_total']):'';?></strong></td>
                        <!--<td align="right" class="report_table_column"><strong></strong></td>-->
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_telephone_total']?System::display_number($this->map['last_group_function_params']['total_telephone_total']):'';?></strong></td>
                        <?php if(isset($this->map['portal_department']) and is_array($this->map['portal_department'])){ foreach($this->map['portal_department'] as $key3=>&$item3){if($key3!='current'){$this->map['portal_department']['current'] = &$item3;?>
                         <?php if($this->map['portal_department']['current']['department_code']=='RES'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_restaurant_total']?System::display_number($this->map['last_group_function_params']['total_restaurant_total']):'';?></strong></td>	
                         <?php }else if($this->map['portal_department']['current']['department_code']=='HK'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_minibar_total']?System::display_number($this->map['last_group_function_params']['total_minibar_total']):'';?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_laundry_total']?System::display_number($this->map['last_group_function_params']['total_laundry_total']):'';?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_equip_total']?System::display_number($this->map['last_group_function_params']['total_equip_total']):'';?></strong></td>
                         <?php }else if($this->map['portal_department']['current']['department_code']=='SPA'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_spa_total']?System::display_number($this->map['last_group_function_params']['total_spa_total']):'';?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='KARAOKE'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_karaoke_total']?System::display_number($this->map['last_group_function_params']['total_karaoke_total']):'';?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='BANQUET'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_banquet_total']?System::display_number($this->map['last_group_function_params']['total_banquet_total']):'';?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='VENDING'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_vending_total']?System::display_number($this->map['last_group_function_params']['total_vending_total']):'';?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='TICKET'){?>
                            <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_ticket_total']?System::display_number($this->map['last_group_function_params']['total_ticket_total']):'';?></strong></td>
                        <?php } ?>       
                        <?php }}unset($this->map['portal_department']['current']);} ?> 
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_tour_total']?System::display_number($this->map['last_group_function_params']['total_tour_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_extra_service_total']?System::display_number($this->map['last_group_function_params']['total_extra_service_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['last_group_function_params']['total_extra_service_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_room_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_minibar_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_restaurant_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_laundry_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_telephone_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_spa_total'])
                							 																											+round($this->map['last_group_function_params']['total_break_fast_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_equip_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_extra_bed_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_tour_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_karaoke_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_banquet_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_vending_total'])
                                                                                                                                                        +round($this->map['last_group_function_params']['total_ticket_total']));?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_reduce_amount_total']?System::display_number($this->map['last_group_function_params']['total_reduce_amount_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_deposit_total']?System::display_number($this->map['last_group_function_params']['total_deposit_total']):'';?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_group_deposit_total']?System::display_number($this->map['last_group_function_params']['total_group_deposit_total']):'';?></strong></td>
                <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_cash_vnd_total']==0?'':System::Display_number(round($this->map['last_group_function_params']['total_cash_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_cash_usd_total']==0?'':System::Display_number(($this->map['last_group_function_params']['total_cash_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_bank_total']==0?'':System::Display_number(round($this->map['last_group_function_params']['total_bank_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_credit_vnd_total']==0?'':System::Display_number(round($this->map['last_group_function_params']['total_credit_vnd_total'])));?></strong></td>
                	    <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_credit_usd_total']==0?'':System::Display_number(($this->map['last_group_function_params']['total_credit_usd_total'])));?></strong></td>  
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_foc_total']==0?'':System::Display_number(round($this->map['last_group_function_params']['total_foc_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_debit_total']==0?'':System::Display_number(round($this->map['last_group_function_params']['total_debit_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_refund_vnd_total']==0?'':System::Display_number(round($this->map['last_group_function_params']['total_refund_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['last_group_function_params']['total_refund_usd_total']==0?'':System::Display_number(($this->map['last_group_function_params']['total_refund_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::Display_number(round($this->map['last_group_function_params']['total_cash_total'])+round($this->map['last_group_function_params']['total_credit_vnd_total'])+round($this->map['last_group_function_params']['total_debit_total'])+round($this->map['last_group_function_params']['total_bank_total'])-round($this->map['last_group_function_params']['total_refund_total']));?></strong></td>
                    </tr>
                
				<?php
				}
				?>
                <!--end:KID-->   
                    <?php 
                    $i=1;
                    $is_rowspan = false;
                    ?>
                    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current'] = &$item4;?>
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
                            <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>">
                            <a target="_blank" href="<?php echo ($this->map['items']['current']['customer_id'] !=''?Url::build('view_traveller_folio',array('folio_id'=>$this->map['items']['current']['code'],'id'=>$this->map['items']['current']['reservation_id'],'cmd'=>'group_invoice','customer_id'=>$this->map['items']['current']['customer_id'])):Url::build('view_traveller_folio',array('traveller_id'=>$this->map['items']['current']['traveller_id'],'folio_id'=>$this->map['items']['current']['code'])));?>">
                            <?php if(isset($this->map['items']['current']['folio_code'])){?>
                                <?php  echo 'No.F'.str_pad($this->map['items']['current']['folio_code'],6,"0",STR_PAD_LEFT); ?>
                            <?php } else {?>
                                 <?php  echo 'Ref'.str_pad($this->map['items']['current']['code'],6,"0",STR_PAD_LEFT); ?>
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
                            <td align="center" class="report_table_column" ><?php echo System::Display_number((Date_Time::to_time(date('d/m/Y',$this->map['items']['current']['time_out']))-Date_Time::to_time(date('d/m/Y',$this->map['items']['current']['time_in'])))/(24*3600));?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number($this->map['items']['current']['price']);?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo System::Display_number($this->map['items']['current']['price']/RES_EXCHANGE_RATE);?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['room']==0?'':System::Display_number(round($this->map['items']['current']['room']))); ?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['extra_bed']==0?'':System::Display_number(round($this->map['items']['current']['extra_bed'])));?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['telephone']==0?'':System::Display_number(round($this->map['items']['current']['telephone'])));?></td>
                            <?php if(isset($this->map['portal_department']) and is_array($this->map['portal_department'])){ foreach($this->map['portal_department'] as $key5=>&$item5){if($key5!='current'){$this->map['portal_department']['current'] = &$item5;?>
                             <?php if($this->map['portal_department']['current']['department_code']=='RES'){?>
                                <!--<td class="report_table_column" style="text-align: right;"></?php echo ($this->map['items']['current']['break_fast']==0?'':System::Display_number(round($this->map['items']['current']['break_fast'])));?></td>-->
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['restaurant']==0?'':System::Display_number(round($this->map['items']['current']['restaurant'])));?></td>	
                             <?php }else if($this->map['portal_department']['current']['department_code']=='HK'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['minibar']==0?'':System::Display_number(round($this->map['items']['current']['minibar']))); ?></td>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['laundry']==0?'':System::Display_number(round($this->map['items']['current']['laundry'])));?></td>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['equip']==0?'':System::Display_number(round($this->map['items']['current']['equip'])));?></td>
                             <?php }else if($this->map['portal_department']['current']['department_code']=='SPA'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['spa']==0?'':System::Display_number(round($this->map['items']['current']['spa'])));?></td>
                            <?php }else if($this->map['portal_department']['current']['department_code']=='KARAOKE'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['karaoke']==0?'':System::Display_number(round($this->map['items']['current']['karaoke'])));?></td></td>
                            <?php }else if($this->map['portal_department']['current']['department_code']=='BANQUET'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['banquet']==0?'':System::Display_number(round($this->map['items']['current']['banquet'])));?></td></td>
                            <?php }else if($this->map['portal_department']['current']['department_code']=='VENDING'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['vending']==0?'':System::Display_number(round($this->map['items']['current']['vending'])));?></td></td>
                            <?php }else if($this->map['portal_department']['current']['department_code']=='TICKET'){?>
                                <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['ticket']==0?'':System::Display_number(round($this->map['items']['current']['ticket'])));?></td></td>
                            <?php } ?>       
                            <?php }}unset($this->map['portal_department']['current']);} ?> 
                            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['tour']==0?'':System::Display_number(round($this->map['items']['current']['tour'])));?></td>
                            <td class="report_table_column" style="text-align: right;"><?php echo ($this->map['items']['current']['extra_service']==0?'':System::Display_number(round($this->map['items']['current']['extra_service'])));?></td>
                            <td style="background-color:#FFFF99; text-align: right;" class="report_table_column"><?php echo System::Display_number(round($this->map['items']['current']['extra_service']
                                                                                                                                                        +$this->map['items']['current']['minibar']
                                                                                                                                                        +$this->map['items']['current']['restaurant']
                                                                                                                                                        +$this->map['items']['current']['laundry']
                                                                                                                                                        +$this->map['items']['current']['telephone']
                
                                                                                                                                                        +$this->map['items']['current']['equip']
                                                                                                                                                        +$this->map['items']['current']['spa']
                                                                                                                                                        +$this->map['items']['current']['room']
                                                                                                                                                        +$this->map['items']['current']['extra_bed']
                																																		+$this->map['items']['current']['break_fast']
                                                                                                                                                        +$this->map['items']['current']['tour']
                                                                                                                                                        +$this->map['items']['current']['karaoke']
                                                                                                                                                        +$this->map['items']['current']['vending']
                                                                                                                                                        +$this->map['items']['current']['banquet']
                                                                                                                                                        +$this->map['items']['current']['ticket']));?></td>
                                 <td style="background-color:#FFCCFF;"><?php echo ($this->map['items']['current']['reduce_amount']==0?'':System::Display_number(round($this->map['items']['current']['reduce_amount'])));?></td>
                            <td class="report_table_column" style="text-align: right; background-color:#FFCCFF;"><?php echo ($this->map['items']['current']['deposit']==0?'':System::Display_number(round($this->map['items']['current']['deposit'])));?></td>
                        <?php
                           $i++ ;}
                        ?>
                        <?php 
                            if($is_rowspan == false)
                            {
                        ?>
                            <td class="report_table_column" style="text-align: right; background-color:#FFCCFF;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['deposit_group']==0?'':System::Display_number(round($this->map['items']['current']['deposit_group'])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['cash_vnd']==0?'':System::Display_number(round($this->map['items']['current']['cash_vnd'])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['cash_usd']==0?'':System::Display_number(($this->map['items']['current']['cash_usd'])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['bank']==0?'':System::Display_number(round($this->map['items']['current']['bank'])));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['credit_card_vnd']==0?'':System::Display_number($this->map['items']['current']['credit_card_vnd']));?></td> 
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['credit_card_usd']==0?'':System::Display_number($this->map['items']['current']['credit_card_usd']));?></td> 
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['foc']==0?'':System::Display_number($this->map['items']['current']['foc']));?></td>  
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['debit']<999?'':System::Display_number($this->map['items']['current']['debit']));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['refund_vnd']<999?'':System::Display_number($this->map['items']['current']['refund_vnd']));?></td>
                            <td align="right" class="report_table_column" style="background-color:#99CCCC;" rowspan="<?php echo $k; ?>"><?php echo ($this->map['items']['current']['refund_usd']<0.0001?'':System::Display_number($this->map['items']['current']['refund_usd']));?></td>
                            <td align="right" class="report_table_column" style="background-color:#FF66FF;" rowspan="<?php echo $k; ?>"><?php echo System::Display_number(round(($this->map['items']['current']['cash'])+($this->map['items']['current']['credit_card'])+($this->map['items']['current']['debit'])+($this->map['items']['current']['bank'])-($this->map['items']['current']['refund'])));?></td>
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
                    </tr>
                	<?php }}unset($this->map['items']['current']);} ?>
                    
                    	<tr bgcolor="white">
                		<td class="report_table_column"><strong><?php if($this->map['real_page_no']==$this->map['real_total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></strong></td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['real_room_count']); ?></strong></td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align="center" class="report_table_column"><strong></strong>&nbsp;</td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_room_total'])); ?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_extra_bed_total'])); ?></strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_telephone_total'])); ?></strong></td>
                        <?php if(isset($this->map['portal_department']) and is_array($this->map['portal_department'])){ foreach($this->map['portal_department'] as $key6=>&$item6){if($key6!='current'){$this->map['portal_department']['current'] = &$item6;?>
                         <?php if($this->map['portal_department']['current']['department_code']=='RES'){?>
                            <!--<td align="right" class="report_table_column"><strong></?php echo System::display_number(round($this->map['group_function_params']['total_break_fast_total'])); ?></strong></td>-->
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_restaurant_total'])); ?></strong></td>	
                         <?php }else if($this->map['portal_department']['current']['department_code']=='HK'){?>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_minibar_total'])); ?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_laundry_total'])); ?></strong></td>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_equip_total'])); ?></strong></td>
                         <?php }else if($this->map['portal_department']['current']['department_code']=='SPA'){?>
                            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_spa_total'])); ?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='KARAOKE'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_karaoke_total'])); ?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='BANQUET'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_banquet_total'])); ?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='VENDING'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_vending_total'])); ?></strong></td>
                        <?php }else if($this->map['portal_department']['current']['department_code']=='TICKET'){?>
                            <td class="report_table_column" style="text-align: right;"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_ticket_total'])); ?></strong></td>
                        <?php } ?>       
                        <?php }}unset($this->map['portal_department']['current']);} ?> 
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_tour_total'])); ?>&nbsp;</strong></td>
                        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_extra_service_total'])); ?></strong></td>
                        <td style="background-color:#FFFF99;" align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_extra_service_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_room_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_minibar_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_restaurant_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_laundry_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_telephone_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_spa_total'])
                							 																											+round($this->map['group_function_params']['total_break_fast_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_equip_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_extra_bed_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_tour_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_karaoke_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_banquet_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_vending_total'])
                                                                                                                                                        +round($this->map['group_function_params']['total_ticket_total'])); ?></strong></td>
                        <td style="background-color:#FFCCFF;"><strong><?php echo System::display_number(round($this->map['group_function_params']['total_reduce_amount_total'])); ?></strong></td>                                                                                                                                
                        <td align="right" class="report_table_column" style="background-color:#FFCCFF;"><?php echo System::display_number(round($this->map['group_function_params']['total_deposit_total'])); ?></td>
                        <td align="right" class="report_table_column" style="background-color:#FFCCFF;"><?php echo System::display_number(round($this->map['group_function_params']['total_group_deposit_total'])); ?></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_cash_vnd_total']==0?'':System::Display_number(round($this->map['group_function_params']['total_cash_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_cash_usd_total']==0?'':System::Display_number(($this->map['group_function_params']['total_cash_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_bank_total']==0?'':System::Display_number(round($this->map['group_function_params']['total_bank_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_credit_vnd_total']==0?'':System::Display_number(round($this->map['group_function_params']['total_credit_vnd_total'])));?></strong></td>
                	    <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_credit_usd_total']==0?'':System::Display_number(($this->map['group_function_params']['total_credit_usd_total'])));?></strong></td>  
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_foc_total']==0?'':System::Display_number(round($this->map['group_function_params']['total_foc_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_debit_total']==0?'':System::Display_number(round($this->map['group_function_params']['total_debit_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_refund_vnd_total']==0?'':System::Display_number(round($this->map['group_function_params']['total_refund_vnd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#99CCCC;"><strong><?php echo ($this->map['group_function_params']['total_refund_usd_total']==0?'':System::Display_number(($this->map['group_function_params']['total_refund_usd_total'])));?></strong></td>
                        <td align="right" class="report_table_column" style="background-color:#FF66FF;"><strong><?php echo System::Display_number(round($this->map['group_function_params']['total_cash_total']+$this->map['group_function_params']['total_credit_total']+$this->map['group_function_params']['total_debit_total']+$this->map['group_function_params']['total_bank_total']-$this->map['group_function_params']['total_refund_total']));?></strong></td>
                    </tr>
                </table>
</td>
</tr>
<tr><td><center><div style="font-size:11px;"><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></div></center></td></tr>
 <?php }else{ ?>
<tr><td><strong><?php echo Portal::language('no_data');?></strong></td></tr>

				<?php
				}
				?>

<!---------FOOTER----------->

<?php 
				if((($this->map['real_page_no']==$this->map['real_total_page'])))
				{?>
<tr><td>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>

	<td colspan="2" align="left"></td>
	<td  align="center"> <?php //echo date('H\h : i\p',time());?> <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td align="center" width="33%" ><?php echo Portal::language('director');?></td>
</tr>
</table>
</table>
</td></tr>
<script>full_screen();</script>

				<?php
				}
				?>
<div style="page-break-before:always;page-break-after:always;"></div>


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
  .b_blur{
    background: #cdcdcd;
    }
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
      <?php
    if(isset($_REQUEST)){
        if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !=''){
            ?>
            document.getElementById("search_time").checked = false;
            document.getElementById("search_invoice").checked = true;
            jQuery("#from_date").addClass('b_blur').attr('value','');
                jQuery("#to_date").addClass('b_blur').attr('value','');
                 jQuery("#from_time").addClass('b_blur').attr('value','');
                jQuery("#to_time").addClass('b_blur').attr('value','');
             jQuery("#folio_number").removeClass('b_blur');
            <?php
        }
    
    }
    ?>
        if(jQuery("#search_invoice").is(":checked")){
                  jQuery("#from_code").removeClass();
                  jQuery("#from_code").attr('readonly',false);
                  jQuery("#to_code").removeClass();
                  jQuery("#to_code").attr('readonly',false);
    };
    
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
		jQuery("#from_date").addClass('b_blur').attr('value','');
		jQuery("#to_date").addClass('b_blur').attr('value','');
		 jQuery("#from_time").addClass('b_blur').attr('value','');
		jQuery("#to_time").addClass('b_blur').attr('value','');
		  jQuery("#from_code").removeClass();
		  jQuery("#from_code").attr('readonly',false);
		  jQuery("#to_code").removeClass();
		  jQuery("#to_code").attr('readonly',false);
                jQuery("#folio_number").removeClass('b_blur').attr('readonly',false);
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#to_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#folio_number").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
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
            jQuery("#folio_number").attr('readonly',false).removeClass();
            
             //jQuery("#from_code").attr('readonly',false);
          
			jQuery("#from_date").addClass('b_blur').attr('value','');
            jQuery("#to_date").addClass('b_blur').attr('value','');
            jQuery("#from_time").addClass('b_blur').attr('value','');
            jQuery("#to_time").addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").attr('value','00:00');
			jQuery("#to_time").attr('value','<?php echo date('H:i');?>');
            jQuery("#folio_number").addClass('b_blur').attr('value','').attr('readonly',true);
            
		}else{
            jQuery("#from_code").addClass('b_blur');
            jQuery("#to_code").addClass('b_blur');
            jQuery("#from_code").attr('readonly',true);
            jQuery("#to_code").attr('readonly',true);
            jQuery("#from_code").attr('value','');
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
    function folio_number()
    { 
	var folio_number = jQuery("#folio_number").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(folio_number==''){
                    alert('<?php echo Portal::language('empty_folio');?>');
                    return false;
                }else{
					return true;
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
</script>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        
        jQuery('#to_day').datepicker();
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
        jQuery("#export").click(function () {
            <?php if($this->map['real_page_no']==$this->map['real_total_page']){ ?>
            jQuery("#tr_search").remove();
            jQuery("#header_report").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
            <?php } ?>
        });
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
function Autocomplete()
{
    jQuery("#customer").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
            document.getElementById('customer_id').value = item.data[0];
           // console.log(document.getElementById('customer_id').value);
        }
    }) ;
}
//start:KID them ham check dieu kien search
function check_search()
{
    var hour_from = (jQuery("#from_time").val().split(':'));
    var hour_to = (jQuery("#to_time").val().split(':'));
    var date_from_arr = jQuery("#from_date").val();
    var date_to_arr = jQuery("#to_date").val();
	var folio_number = jQuery("#folio_number").val();
    var search_invoice =jQuery("#search_invoice").is(":checked");
    if(search_invoice){
                if(folio_number==''){
                    alert('<?php echo Portal::language('empty_folio');?>');
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
                return true; 
        }
    }   
}
    //end:KID them ham check dieu kien search
</script>
