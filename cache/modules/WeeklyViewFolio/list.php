<form name="ListFolio" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="" width="50%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('list_folio');?></td>
            <td width="50%" nowrap="nowrap" align="right" style="padding-right: 30px;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input class="w3-btn w3-pink" type="button" onclick="print_vat();" value="  <?php echo Portal::language('print_group_vat');?>  "/><?php }?>             
            </td>
        </tr>
    </table>
	<fieldset>
    	<legend class="title"><?php echo Portal::language('search');?></legend>
        
        <table border="0" cellpadding="3" cellspacing="0">
				<tr style="font-weight: normal !important;">
                    
                    <!-- trung add search customer -->
                    <td align="right" nowrap ><?php echo Portal::language('customer_name_search');?></td>
					<td>:</td>
                    <td nowrap>
                    <input  name="customer_name" id="customer_name" onfocus="Autocomplete();" autocomplete="off" style="width:150px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                    <input  name="customer_id" id="customer_id" class="hidden" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
					</td>
                    <!--end trung add search customer -->
                    <td align="right" nowrap ><?php echo Portal::language('recode_id');?></td>
					<td>:</td>
                    <td nowrap>
						<input  name="recode_id" id="recode_id" style="width:70px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('recode_id'));?>">
					</td>
					<td align="right" nowrap ><?php echo Portal::language('folio_id');?></td>
					<td>:</td>
					<td nowrap>
						<input  name="folio_id" id="folio_id" style="width:70px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('folio_id'));?>">
					</td>
					<td align="right" nowrap ><?php echo Portal::language('from_date');?></td>
					<td>:</td>
					<td nowrap>
						<input  name="from_date" id="from_date" onchange="ValidateTime();" readonly="" style="width:70px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
					</td>
					<td align="right" nowrap ><?php echo Portal::language('to_date');?></td>
					<td>:</td>
					<td nowrap>
						<input  name="to_date" id="to_date" onchange="ValidateTime();" readonly="" style="width:70px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
					</td>
                    <td>
                        <?php echo Portal::language('status');?>:<select  name="status" id="status" style=" height: 24px;"><?php
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
	</select>
                    </td>
                    <td>
                        <?php echo Portal::language('rank');?>:<select  name="order_by" id="order_by" onchange="ListFolio.submit();" style=" height: 24px;"><?php
					if(isset($this->map['order_by_list']))
					{
						foreach($this->map['order_by_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('order_by',isset($this->map['order_by'])?$this->map['order_by']:''))
                    echo "<script>$('order_by').value = \"".addslashes(URL::get('order_by',isset($this->map['order_by'])?$this->map['order_by']:''))."\";</script>";
                    ?>
	</select>
                    </td>
					<td><input name="search" type="button" value="  <?php echo Portal::language('search');?>  " onclick="fun_check();" style=" height: 24px;"/></td>
				</tr>
		</table>
    </fieldset>
</div>

<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
    <tr style="background: #EEEEEE; height: 25px; text-align: center; text-transform: uppercase;;">
        <th><input type="checkbox" id="checkboxall"/></th>
        <th><?php echo Portal::language('stt');?></th>
        <th><?php echo Portal::language('recode');?></th>
        <th><?php echo Portal::language('info');?></th>
        <th><?php echo Portal::language('folio_id');?></th>
        <th><?php echo Portal::language('room');?></th>
        <th><?php echo Portal::language('date');?></th>
        <th style="text-align: right;" ><?php echo Portal::language('total');?>: <?php echo $this->map['total'];?></th>
        <th style="text-align: right;"><?php echo Portal::language('total_remain_vat');?>: <?php echo $this->map['total_remain'];?></th>
        <th><?php echo Portal::language('VAT');?></th>
    </tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr style="text-align: center; font-weight: normal !important;">
        <th style="width: 30px;font-weight: normal !important;"><input id="item_<?php echo $this->map['items']['current']['id'];?>" type="checkbox" class="checkitem" value="<?php echo $this->map['items']['current']['id'];?>" /></th>
        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['stt'];?></th>
        <th style="font-weight: normal !important;"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['recode'];?>" id="<?php echo $this->map['items']['current']['recode'];?>" onclick="thenao(this)" ><?php echo $this->map['items']['current']['recode'];?>  </a></th>
        <th style="text-align: left;font-weight: normal !important;"><?php echo Portal::language('customer_name');?>: <?php echo $this->map['items']['current']['customer_name'];?> <br /> <?php echo Portal::language('guest_name');?> <?php echo $this->map['items']['current']['traveller_name'];?></th>
        <th style="font-weight: normal !important;"><a target="_blank" href="<?php echo $this->map['items']['current']['href'];?>"><?php echo $this->map['items']['current']['code'];?></a></th>
        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['room_name'];?></th>
        <th style="font-weight: normal !important;"><?php echo $this->map['items']['current']['time'];?></th>
        <th style="text-align: right;font-weight: normal !important;"><?php echo $this->map['items']['current']['total'];?></th>
        <th style="text-align: right;font-weight: normal !important;"><?php echo $this->map['items']['current']['total_remain'];?></th>
        <th><input class="w3-btn w3-pink" type="button" onclick="print_vat('<?php echo $this->map['items']['current']['id'];?>');" value="<?php echo Portal::language('print_vat');?>"/></th>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>			
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
<script type="text/javascript">
var array_order = [];



//jQuery('#order_by').on('change', function() {
//  var value = jQuery(this).val();
//  console.log(value);
//   
//});

    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
                        
            }
        }) ;
    }

    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    jQuery("#checkboxall").click(function(){
        if(document.getElementById('checkboxall').checked==true) {
            jQuery(".checkitem").attr('checked',true);
        } else {
            jQuery(".checkitem").attr('checked',false);
        }
    });
    function print_vat($id=false){
        if(!$id) {
            $list_id = '';
            jQuery(".checkitem").each(function(){
                if(document.getElementById(this.id).checked==true) {
                    if($list_id=='')
                        $list_id = this.value;
                    else
                        $list_id += ','+this.value;
                }
            });
            if($list_id=='')
                alert('<?php echo Portal::language('are_you_select_folio_code');?>');
            else
                window.location.href='?page=vat_bill&cmd=add&type=FOLIO&invoice_id='+$list_id+'';
        }
        else {
            window.location.href='?page=vat_bill&cmd=add&type=FOLIO&invoice_id='+$id+'';
        }
        return false;
    }
    
    function fun_check(){
        ListFolio.submit();
    }
    
    function ValidateTime() {
        
    }
    
</script>
