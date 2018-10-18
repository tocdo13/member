<script type="text/javascript">
	min_year = <?php echo date('Y',$this->map['arrival_time']);?>;   
	min_month = <?php echo date('m',$this->map['arrival_time']);?>;
	min_day = <?php echo date('d',$this->map['arrival_time']);?>;
	max_year = <?php echo date('Y',$this->map['departure_time']);?>;
	max_month = <?php echo date('m',$this->map['departure_time']);?>;
	max_day = <?php echo date('d',$this->map['departure_time']);?>;	
</script>
<style>
div.content fieldset legend.legend-title:hover{
    background: #fff;
}
</style>
<span style="display:none;">
	<span id="mi_product_group_sample">
		<div id="input_group_#xxxx#">
        	<span>
                <input  name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
                <span class="multi-input"><select   name="mi_product_group[#xxxx#][service_id]" style="width:155px;" id="service_id_#xxxx#" onclick="hide_latein('#xxxx#');" onblur="updateServiceInfo(this,'#xxxx#');" onchange="updateServiceInfo(this,'#xxxx#');"><?php echo $this->map['service_options'];?></select><input name="mi_product_group[#xxxx#][service_id1]" style="width:185px;display: none; height: 22px;" id="service_id1_#xxxx#" readonly="readonly" /></span>
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="width:63px;" <?php if(Url::get('cmd')=='edit'){echo "readonly"; }?> onkeyup="updatePaymentPrice('#xxxx#');" autocomplete="off"></span><!--onkeypress="return event.charCode>=48 && event.charCode<=57;"-->                
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][change_quantity]"  type="<?php if(Url::get('cmd')=='edit'){echo 'text';}else { echo 'hidden';}?>" id="change_quantity_#xxxx#" style="width:63px;" onkeypress="return isNumberKey(event)" onkeyup="if(parseInt(jQuery(this).val()) + parseInt(jQuery('#quantity_#xxxx#').val()) <0){alert('tổng số lượng không được nhỏ hơn không');jQuery(this).val(0);} updatePaymentPrice('#xxxx#');" autocomplete="off"></span>                
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][unit]" style="width:64px;" type="text" id="unit_#xxxx#"/></span>
                <span class="multi-input price"><input  name="mi_product_group[#xxxx#][price]" style="width:84px;text-align:right;" type="text" id="price_#xxxx#" onkeypress="return event.charCode>=48 && event.charCode<=57;" onkeyup="jQuery('#usd_price_#xxxx#').val(number_format(to_numeric(jQuery('#price_#xxxx#').val())/to_numeric(jQuery('#exchange_rate').val())));updatePaymentPrice('#xxxx#');"></span>
                <span class="multi-input price"><input  name="mi_product_group[#xxxx#][usd_price]" style="width:84px;text-align:right;" type="text" id="usd_price_#xxxx#" onkeypress="return isNumberKey(event)" onkeyup="jQuery('#price_#xxxx#').val(number_format(to_numeric(jQuery('#usd_price_#xxxx#').val())*to_numeric(jQuery('#exchange_rate').val())));updatePaymentPrice('#xxxx#');"></span>
                <span class="multi-input price"><input  name="mi_product_group[#xxxx#][payment_price]" style="width:104px;text-align:right;" type="text" id="payment_price_#xxxx#" readonly="readonly" class="readonly"  tabindex="-1"></span>
                <span class="multi-input price"><input name="mi_product_group[#xxxx#][from_date]" type="text" style="width:85px;text-align:right;" id="from_date_#xxxx#" onchange="check_date('#xxxx#');updatePaymentPrice('#xxxx#');input_to_date('#xxxx#');"  /></span>
                <span class="multi-input price"><input name="mi_product_group[#xxxx#][to_date]" type="text" style="width:85px;text-align:right;" id="to_date_#xxxx#" onchange="check_date('#xxxx#');updatePaymentPrice('#xxxx#');"  /></span>
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][total_day]" style="width:80px;" type="text" readonly="readonly" id="total_day_#xxxx#" ></span>                
                <span class="multi-input"><select   name="mi_product_group[#xxxx#][package_sale_detail_id]" style="width:135px;" id="package_sale_detail_id_#xxxx#" ><?php echo $this->map['package_service_detail_options'];?></select><input name="mi_product_group[#xxxx#][package_sale_detail_id1]" style="width:185px;display: none; height: 22px;" id="package_sale_detail_id1_#xxxx#" readonly="readonly" /></span>
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][note]" style="width:200px;" type="text" id="note_#xxxx#" onchange="updatePaymentPrice('#xxxx#');"></span>
                
                <span class="multi-input" style="width:60px;text-align:center;display: none;;"><input  name="mi_product_group[#xxxx#][used]" type="checkbox" id="used_#xxxx#" value="1" checked="checked"></span>
               <span class="multi-input" style="width:40px;" id="span_delete_#xxxx#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','');updateAllPaymentPrice();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span><br clear="all" />
		</div>
	</span> 
</span>
<div class="extra_service_invoice-bound">
<form name="EditExtraServiceInvoiceForm" method="post">
<input  name="deleted_ids" type="hidden"  id="deleted_ids" value="<?php echo URL::get('deleted_ids');?>"/>
	<table cellpadding="5" cellspacing="0" width="100%" class="table-bound">
		<tr height="40">
        	<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px; width: 70%"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?>
                <td style="width: 30%; text-align: right; padding-right: 30px;">
                    <?php if($this->map['close_mice']==0){ ?>
                    <input  name="save" type="button" id="save"  onclick=" return check_service_id();" class="w3-btn w3-orange w3-text-white" style="margin-right: 5px; <?php 
				if((Url::get('cmd')=='add' and $this->map['reservation_room_id']==''))
				{?>display: none;
				<?php
				}
				?> text-transform: uppercase;" <?php echo (isset($this->map['status']) and $this->map['status']=='CHECKOUT')?'disabled="disabled" title="'.Portal::language('room_checked_out_can_not_edit').'" style="background:#CCCCCC;color:#FF0000;"  value="'.Portal::language('room_checked_out_can_not_edit').'"':' value="'.Portal::language('Save').'"';?> />
                    <input name="act" id="act" type="hidden" />
                    <?php }?>
                
            <?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY) and !Url::get('fast')){?><a href="?page=extra_service_invoice&type=<?php echo Url::get('type');?>"  style="text-transform: uppercase; text-decoration: none;"  class="w3-btn w3-green"><?php echo Portal::language('back');?></a></td><?php }?>
        </tr>
        <tr>
            <td colspan="3">
                <?php if(Url::get('cmd')=='edit' and (isset($this->map['check_mice']) and $this->map['check_mice'] == 1) ){?>
                    <table style="" cellpadding="5" cellspacing="0">
                        <tr>
                            <?php if(isset($this->map['mice_reservation_id']) AND $this->map['mice_reservation_id']!=0 AND $this->map['mice_reservation_id']!=''){ ?>
                                <td></td>
                                <td style="color: red; font-weight: bold;"><?php echo Portal::language('mice');?></td>
                                <td style="padding-left:10px;">
                                    <a href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['mice_reservation_id'];?>" style="line-height: 22px; font-weight: bold; color: red;"><?php echo $this->map['mice_reservation_id'];?></a>
                                    <input value="<?php echo Portal::language('split');?> MICE" type="button" onclick="FunctionSplitMice('<?php echo $this->map['mice_reservation_id'];?>','EXS','<?php echo Url::get('id'); ?>');" style="font-weight: bold; padding: 3px 10px; margin-left: 20px;" />
                                </td>
                            <?php }else{ ?>
                                <td></td>
                                <td><input value="<?php echo Portal::language('add_mice');?>" type="button" onclick="FunctionAddMice('EXS','<?php echo Url::get('id'); ?>');" style="font-weight: bold; padding: 3px 10px;" /></td>
                                <td style="padding-left:10px;"><input value="<?php echo Portal::language('select');?> MICE" type="button" onclick="FunctionSelectMice('EXS','<?php echo Url::get('id'); ?>');" style="font-weight: bold; padding: 3px 10px;" /></td>
                                <td></td>
                            <?php } ?>
                        </tr>
                    </table>
                <?php } ?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?><br />
		<fieldset style="height: auto;">
        <legend class="" style="text-transform: uppercase; font-size: 14px; font-weight: normal;"><?php echo Portal::language('service_information');?></legend>
			<table border="0" cellspacing="0" cellpadding="2" id="table_info">
                <tr>
                    <td><?php echo Portal::language('room');?>:</td> <td colspan="3"><select  name="reservation_room_id" id="reservation_room_id" onchange="EditExtraServiceInvoiceForm.submit();" style="width:300px;font-size:11px;"<?php if(Url::get('cmd')=='edit'){echo 'disabled';} ?> ><?php
					if(isset($this->map['reservation_room_id_list']))
					{
						foreach($this->map['reservation_room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_room_id',isset($this->map['reservation_room_id'])?$this->map['reservation_room_id']:''))
                    echo "<script>$('reservation_room_id').value = \"".addslashes(URL::get('reservation_room_id',isset($this->map['reservation_room_id'])?$this->map['reservation_room_id']:''))."\";</script>";
                    ?>
	</select></td>				
					<td class="label"><?php echo Portal::language('code');?>:</td>
				    <td ><input  name="bill_number" id="bill_number" readonly="readonly" class="readonly" style="width:100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('bill_number'));?>"></td>
                    <td ><?php echo Portal::language('bill_number');?></td>
                    <td><input  name="code" id="code" style="width: 140px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>"></td>
				</tr>
                <tr>
                  <td ><?php echo Portal::language('service_type');?></td>
                  <td ><select  name="payment_type" id="payment_type" style="width:100px;"><?php
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
	<option <?php echo 'value='.$this->map['payment_type'].'' ?>><?php echo $this->map['payment_type_name'];?></option></select></td>
                  <td><?php echo Portal::language('net_price');?></td>
                  <td><?php 
				if((Url::get('cmd')=='add'))
				{?>
                        <input  name="net_price" value="1" type="checkbox" id="net_price" <?php if(NET_PRICE_SERVICE==1){ echo 'checked';}?> onclick="change_net_price()"/>
                     <?php }else{ ?>
                    <input  name="net_price" value="1" type="checkbox" id="net_price" onclick="change_net_price()"/>
                    
				<?php
				}
				?>
                  </td>
                  <td class="label"><?php echo Portal::language('note');?></td>
                    <td colspan="3"><textarea  name="note" id="note" rows="3" style="width:295px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea></td>         
                </tr>
                <tr style="display:none;">
                  <td class="label"><?php echo Portal::language('payment_method');?></td>
                  <td colspan="3"><select  name="payment_method_id" id="payment_method_id"><?php
					if(isset($this->map['payment_method_id_list']))
					{
						foreach($this->map['payment_method_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_method_id',isset($this->map['payment_method_id'])?$this->map['payment_method_id']:''))
                    echo "<script>$('payment_method_id').value = \"".addslashes(URL::get('payment_method_id',isset($this->map['payment_method_id'])?$this->map['payment_method_id']:''))."\";</script>";
                    ?>
	
                  </select></td>
                </tr>
             <!-- <tr valign="top">
                <td class="label"><?php echo Portal::language('close');?></td>
                <td colspan="3"><input  name="close" id="close" value="1" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('close'));?>"></td>
              </tr> -->
			</table>
	  </fieldset><br />
      
      <fieldset style="<?php 
				if((Url::get('cmd')=='add' and $this->map['reservation_room_id']==''))
				{?>display: none;
				<?php
				}
				?>">
        <legend class="" style="text-transform: uppercase; font-size: 14px; font-weight: normal;"><?php echo Portal::language('detail');?></legend>
            <span id="mi_product_group_all_elems" style="text-align:center; height: 24px;">
                <span>
                    <span class="multi-input-header" style="width:155px;"><?php echo Portal::language('name');?></span>
                    <span class="multi-input-header" style="width:63px;"><?php echo Portal::language('number');?></span>
                    <?php if(Url::get('cmd')=='edit'){?>
                    <span class="multi-input-header" style="width:63px;"><?php echo Portal::language('edit_quantity');?></span>
                    <?php }?>
                    <span class="multi-input-header" style="width:64px;"><?php echo Portal::language('unit');?></span>
                    <span class="multi-input-header" style="width:84px;"><?php echo Portal::language('price');?></span>
                    <span class="multi-input-header" style="width:84px;"><?php echo Portal::language('usd_price');?></span>
                    <span class="multi-input-header" style="width:104px;"><?php echo Portal::language('amount');?></span>
                    <span class="multi-input-header" style="width:85px;"><?php echo Portal::language('from_date');?></span>
                    <span class="multi-input-header" style="width:85px;"><?php echo Portal::language('to_date');?></span>
                    <span class="multi-input-header" style="width:80px;"><?php echo Portal::language('total_day');?></span>
                    <span class="multi-input-header" style="width:135px;"><?php echo Portal::language('package_sale_detail');?></span>
					<span class="multi-input-header" style="width:200px;"><?php echo Portal::language('note');?></span>
                    
                    <!--<span class="multi-input-header" style="width:70px;"><?php echo Portal::language('used');?></span>-->
                </span><br clear="all">
            </span>
            <input class="w3-btn w3-cyan w3-text-white" style="float: left; margin-top: 5px; text-transform: uppercase; font-weight: normal;" type="button" value="<?php echo Portal::language('add_service');?>" id="add_product" onclick="mi_add_new_row('mi_product_group');jQuery('#quantity_'+input_count).removeAttr('readonly');jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#price_'+input_count).ForceNumericOnly();jQuery('#price_'+input_count).FormatNumber();jQuery('#from_date_'+input_count).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});jQuery('#to_date_'+input_count).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])})"/>
            <?php if(Url::get('cmd')=='add'){?>
            <div style="margin-left: 209px;float: left;">
            <?php }?>
            <?php if(Url::get('cmd')=='edit'){?>
            <div style="margin-left: 273px;float: left;">
            <?php }?>
            <div style="text-align: right;"><?php echo Portal::language('total_before_tax');?>: <input  name="total_amount" type="text" id="total_amount" readonly class="readonly" style="width:104px;font-weight:bold;text-align:right;"/></div>
            <div style="text-align: right;"><?php echo Portal::language('service_rate');?>(<input  name="service_rate" type="text" id="service_rate" value="<?php echo $this->map['service_rate'];?>" onkeyup="UpdateTotal();" style="width:20px;font-weight:bold;text-align:right;border:none"/> %): <input  name="service_amount" type="text" id="service_amount" readonly class="readonly" style="width:104px;font-weight:bold;text-align:right;"/></div>
            <div style="text-align: right;"><?php echo Portal::language('tax_rate');?>(<input  name="tax_rate" type="text" id="tax_rate" value="<?php echo $this->map['tax_rate'];?>" onkeyup="UpdateTotal();" style="width:20px;font-weight:bold;text-align:right; border:none"> %): <input  name="tax_amount" type="text" id="tax_amount" readonly class="readonly" style="width:104px;font-weight:bold;text-align:right;"></div>
			<div style="text-align: right;"><?php echo Portal::language('total');?>: <input  name="total" type="text" id="total" readonly class="readonly" style="width:104px;font-weight:bold;text-align:right;"></div>
            </div>
    </fieldset>
    <i style="color: red;"><?php echo Portal::language('note_detail');?></i>	
    </div>
    <input type="hidden" value="<?php echo $this->map['type'];?>" />  
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<div id="mice_loading" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; text-align: center; background: rgba(255,255,255,0.95); display: none;">
    <img src="packages/hotel/packages/mice/skins/img/loading.gif" style="margin: 100px auto; height: 100px; width: auto;" />
</div>
<div id="mice_light_box" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; text-align: center; background: rgba(0,0,0,0.8); display: none;">
    <div style="width: 720px; height: 450px; background: #FFFFFF; padding: 10px; margin: 50px auto; position: relative; box-shadow: 0px 0px 3px #171717;">
        <div onclick="jQuery('#mice_light_box').css('display','none');" style="width: 20px; height: 20px; border: 2px solid #000000; color: #171717; text-transform: uppercase; line-height: 20px; text-align: center; position: absolute; top: 10px; right: 10px; cursor: pointer;">X</div>
        <div style="width: 500px; height: 22px; color: #171717; text-transform: uppercase; line-height: 22px; position: absolute; text-align: left; top: 10px; left: 10px; cursor: pointer;">Light Box MICE</div>
        <div id="mice_light_box_content" style="width: 700px; height: 400px; overflow: auto; margin: 40px auto 0px; border: 1px solid #EEEEEE;">
            
        </div>
    </div>
</div>
<br /><br /><br /><br /><br /><br />
<!--Daund: Thêm để chuyển đổi giá -->
<input type="hidden" id="exchange_rate" value="<?php echo $this->map['exchange_rate'];?>" />
<!--Daund: Thêm để chuyển đổi giá -->
<style>
#table_info input[type=text],textarea{
    height: 25px;
    border: 1px solid #696969;
    border-radius: 3px 3px 3px 3px;
}
#table_info select{
    height: 25px;
    border: 1px solid #696969;
    border-radius: 3px 3px 3px 3px;
}
input[type=checkbox]{
    width: 20px;height: 20px;
}
</style>
<script>
    var close = <?php echo ((Url::get('close')==1)?1:0);?>;
    var net_price = <?php echo ((Url::get('net_price')==1)?1:0);?>;
    var lock = <?php echo ((Url::get('lock')==1)?1:0);?>;
    var count_submit = 0;
    if(close==1){
        jQuery('#close').attr('checked',true);
    }
    if(net_price==1)
    {
        jQuery('#net_price').attr('checked',true);
    }
    if(lock==1){
        jQuery('#add_product').css('display','none');
        jQuery('#span_delete').css('display','none');
    }
	var miProductGroup = <?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>;
	mi_init_rows('mi_product_group',miProductGroup);
    var services = <?php echo $this->map['services'];?>;
	function updateServiceInfo(obj,index){
		value = obj.value;
		if(typeof(services[value])=='undefined'){
			$('price_'+index).value = '0';
            $('usd_price_'+index).value = '0';
			$('unit_'+index).value = '';
			$('quantity_'+index).value = '0';
			$('payment_price_'+index).value = '0';
		}else{
			$('price_'+index).value = services[value]['price'];
			$('unit_'+index).value = services[value]['unit'];
            $('usd_price_'+index).value = number_format(to_numeric(services[value]['price'])/to_numeric(jQuery('#exchange_rate').val()));
            updateAllPaymentPrice();
		}
	}
    function hide_latein(index)
    {
        var li_id = '<?php echo DB::fetch('select id from extra_service where code = \'LATE_CHECKIN\'','id')?>';
        jQuery("#service_id_"+index+" option").each(function(){
            if(this.value==li_id){
                jQuery(this).css('display','none');
            }
        })
    }
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var room_time= <?php echo $this->map['room_time'];?>;
    var min_date=[];max_date=[];
    min_date[0]=CURRENT_DAY;
    min_date[1]=CURRENT_MONTH+1;
    min_date[2]=CURRENT_YEAR;
    max_date[0]=CURRENT_DAY;
    max_date[1]=CURRENT_MONTH+1;
    max_date[2]=CURRENT_YEAR;
    
    jQuery(document).ready(function(){
        for(var i=101;i<=input_count;i++)
        {  
            if(jQuery("#from_date_"+i).val() != undefined)
            {
                jQuery("#from_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
                jQuery("#to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
            }
        } 
    })
    var cmd=<?php echo '\''.Url::get('cmd').'\''; ?>;
    if(cmd=='add'){
        if(jQuery('#reservation_room_id').val()!=''){
            for(i in room_time){
                if(room_time[i]['id']==jQuery('#reservation_room_id').val()){
                     min_date=room_time[i]['arrival_date'].split('/');
                     max_date=room_time[i]['departure_date'].split('/');
                    jQuery("#from_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
                    jQuery("#to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
                }
            }
        }else{
            max_date[0]='30';
            max_date[1]='12';
            max_date[2]='2050';
            for(var i=101;i<=input_count;i++){
                jQuery("#from_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1],max_date[0])});
                jQuery("#to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1],max_date[0])});
            }    
        }
    }else{
      var arrival_date=<?php echo '\''.$this->map['arrival_date'].'\''; ?>;
      var departure_date=<?php echo '\''.$this->map['departure_date'].'\''; ?>;
      min_date=arrival_date.split('/');
      max_date= departure_date.split('/');
      for(var i=101;i<=input_count;i++){      
    /** if(miProductGroup[$('id_'+i).value]['night_audit_date']!='')
        {   
            //min_date=miProductGroup[$('id_'+i).value]['night_audit_date'].split('/');
            jQuery("#service_id_"+i).attr('disabled','disabled');
            //jQuery("#service_id1_"+i).css('display','');
            jQuery("#package_sale_detail_id_"+i).css('display','none');
            jQuery("#package_sale_detail_id1_"+i).css('display','');
            jQuery("#quantity_"+i).attr('readonly', true);
            jQuery("#change_quantity_"+i).attr('readonly', true);
            jQuery("#unit_"+i).attr('readonly', true);
            jQuery("#in_date_"+i).attr('readonly', true);
            jQuery("#price_"+i).attr('readonly', true);
            jQuery("#note_"+i).attr('readonly', true);
            jQuery("#package_sale_detail_id_"+i).attr('readonly', true);
            jQuery("#used_"+i).css('display','none');
            jQuery("#span_delete_"+i).css('display','none');
            jQuery("#from_date_"+i).attr('readonly', true);
        } **/
        jQuery("#from_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
        jQuery("#to_date_"+i).datepicker({minDate: new Date(min_date[2],min_date[1]-1,min_date[0]),maxDate: new Date(max_date[2],max_date[1]-1,max_date[0])});
       } 
    }
	function updatePaymentPrice(index){
		if($('price_'+index)){
			$('payment_price_'+index).value = number_format(to_numeric($('price_'+index).value) * (to_numeric($('quantity_'+index).value) + to_numeric($('change_quantity_'+index).value)));
		}
		updateAllPaymentPrice();
		UpdateTotal();
	}
	function updateAllPaymentPrice(){
	   
		total_amount = 0;
        total = 0;
		for(var i=101;i<=input_count;i++){
			if($("price_"+i)){
			 $('payment_price_'+i).value = to_numeric($('price_'+i).value) * (to_numeric($('quantity_'+i).value) + to_numeric($('change_quantity_'+i).value) );
			 if(jQuery('#net_price').is(':checked'))
             {
                var payment_price=$('payment_price_'+i).value;
                if(jQuery('#from_date_'+i).val()!='' && jQuery('#to_date_'+i).val()){
                 var from_date=jQuery('#from_date_'+i).val();
                 var to_date=jQuery('#to_date_'+i).val();
                 var from_date_time=from_date.split('/');
                 var to_date_time=to_date.split('/');
                 var date1 = new Date(from_date_time[2],from_date_time[1]-1,from_date_time[0]);
                 var date2 = new Date(to_date_time[2],to_date_time[1]-1,to_date_time[0]);
                 var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                 var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                 payment_price*=(diffDays+1);
                 $('total_day_'+i).value=(diffDays+1) ;
                }
                total += to_numeric(payment_price);
             }
             else
             { 	
                var payment_price=$('payment_price_'+i).value;
                if(jQuery('#from_date_'+i).val()!='' && jQuery('#to_date_'+i).val()){
                 var from_date=jQuery('#from_date_'+i).val();
                 var to_date=jQuery('#to_date_'+i).val();
                 var from_date_time=from_date.split('/');
                 var to_date_time=to_date.split('/');
                 var date1 = new Date(from_date_time[2],from_date_time[1]-1,from_date_time[0]);
                 var date2 = new Date(to_date_time[2],to_date_time[1]-1,to_date_time[0]);
                 var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                 var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                 payment_price*=(diffDays+1);
                 $('total_day_'+i).value=(diffDays+1) ;
                }
				total_amount += to_numeric(payment_price);
             }
			 $('payment_price_'+i).value = number_format($('payment_price_'+i).value);
			}
		}
		$('total_amount').value = number_format(total_amount);
        $('total').value = number_format(total);
		UpdateTotal();
	}
	updateAllPaymentPrice();
	UpdateTotal();
	function UpdateTotal()
    {
	
        	
		var service_rate = to_numeric($('service_rate').value);
		var tax_rate = to_numeric($('tax_rate').value)
		if($('service_rate').value=='')
        {
			sevice_rate = 0;	
		}
		if($('tax_rate').value=='')
        {
			tax_rate = 0;	
		}
        
        if(jQuery('#net_price').is(':checked'))
         {
            var total = to_numeric($('total').value);
            var tax_amount = total - ((total *100 )/(100 + tax_rate));
            var service_amount = ((total *100 )/(100 + tax_rate)) - ((total *100 )/(100 + tax_rate))*100/(100+service_rate) ;
            $('tax_amount').value = number_format(roundNumber(tax_amount,2));
    		$('service_amount').value = number_format(roundNumber(service_amount,2));
    		$('total_amount').value = number_format(roundNumber((total - service_amount - tax_amount),2));
         }
         else
         {
            var total_amount = to_numeric($('total_amount').value);
			var service_amount = total_amount * service_rate * 0.01;
            $('service_amount').value = number_format(roundNumber(service_amount,2));
    		$('tax_amount').value = number_format(roundNumber((total_amount + service_amount) * tax_rate * 0.01,0));
    		$('total').value = number_format(roundNumber((total_amount + service_amount) + ((total_amount + service_amount) * tax_rate * 0.01),0));
         }
		
		
	}
    function change_net_price()
    {
        updateAllPaymentPrice();
    	UpdateTotal();
    }
    function fun_count_submit()
    {
        for(var i=101;i<=input_count;i++)
        {
            if(jQuery('#change_quantity_'+i).val()!=''&&jQuery('#note_'+i).val()==''){
               alert('<?php echo Portal::language('chua_nhap_ghi_chu');?>');
                return false;       
            }
            //change_quan += Math.abs(to_numeric(jQuery('#change_quantity_'+i).val()));
        }
        if(count_submit==0)
        {
            count_submit=1;
            jQuery("#act").val('save');
            EditExtraServiceInvoiceForm.submit();
        }
        else
            return false;
        
    }
    var last_time = <?php echo $this->map['last_time'];?>;
    function check_service_id()
    {
        <?php if(Url::get('cmd')=='edit'){ ?>
            <?php echo 'var block_id = '.Module::block_id().';';?>
            <?php echo 'var extra_service_invoice_id = '.Url::get('id').';';?>
            jQuery.ajax({
        				url:"form.php?block_id="+block_id,
        				type:"POST",
                        dataType: "json",
        				data:{check_last_time:1,id:extra_service_invoice_id,last_time:last_time},
        				success:function(html)
                        {
                            if(html['status']=='error')
                            {
                                alert('RealTime:\n Lưu ý, dữ liệu đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                                return false;
                            }
                            else
                            {
                                var check = true;
                                for(var i=101;i<=input_count;i++)
                                {
                                    if(jQuery('#service_id_'+i).val()=='')
                                    {
                                        alert('Chua chon dich vu !');
                                        check = false;
                                    }
                                    else if(jQuery('#in_date_'+i).val()=='')
                                    {
                                        alert('Chua chon ngay !');
                                        check = false;
                                    }
                                }
                                if(check==true)
                                 return fun_count_submit();
                                else
                                return false;
                            }
        				}
        	});
        <?php }else{ ?>
            var check = true;
            for(var i=101;i<=input_count;i++)
            {
                if(jQuery('#service_id_'+i).val()=='')
                {
                    alert('Chua chon dich vu !');
                    check = false;
                }
                else if(jQuery('#in_date_'+i).val()=='')
                {
                    alert('Chua chon ngay !');
                    check = false;
                }
            }
            if(jQuery('#reservation_room_id').val()== null)
            {
                alert('bạn phải nhập tên phòng');
                check = false;
            }
            if(check==true)
             return fun_count_submit();
            else
            return false;
        <?php } ?>
    }
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        console.log(charCode);
        if (charCode > 31 && ((charCode != 45 && charCode != 46 && charCode < 48) || charCode > 57))
            return false;
        return true;
    }
    function check_date(i){
        
     var from_date=jQuery('#from_date_'+i).val();
     var to_date=jQuery('#to_date_'+i).val();
     if(from_date!='' && to_date!=''){
         var from_date_time=from_date.split('/');
         var to_date_time=to_date.split('/');
         var date1 = new Date(from_date_time[2],from_date_time[1]-1,from_date_time[0]);
         var date2 = new Date(to_date_time[2],to_date_time[1]-1,to_date_time[0]);
         if(date2<date1){
            alert('<?php echo Portal::language('from_date_must_small_to_date');?>');
            jQuery('#from_date_'+i).val(to_date);
         }
      }
    }
    function input_to_date(i){  
     if(jQuery('#to_date_'+i).val()==''){
        jQuery('#to_date_'+i).val(jQuery('#from_date_'+i).val());
        updateAllPaymentPrice();
      }
    }
</script>
