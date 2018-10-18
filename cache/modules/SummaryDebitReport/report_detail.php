<form name="SummaryDebitReportForm" method="POST">
    <input type="hidden" name="act" id="act" />
    <table cellpadding="5" style="width: 100%;">
        <tr>
            <td style="width: 100px;">
                <img src="<?php echo HOTEL_LOGO;?>" style="width: 100px;"/>
            </td>
            <td>
                <b><?php echo HOTEL_NAME;?></b><br />
				ADD: <?php echo HOTEL_ADDRESS;?><br />
				Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
				Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
            </td>
            <td style="text-align: right;">
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?><br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;"><h1 style="text-transform: uppercase;"><?php echo Portal::language('debit_detail_report');?></h1></th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;"><?php echo Portal::language('customer');?>: <?php echo $this->map['customer_name'];?></th>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <!--<label><?php echo Portal::language('customer');?></label>
                <select  name="customer_id" id="customer_id" style="padding: 5px; max-width: 350px;"><?php
					if(isset($this->map['customer_id_list']))
					{
						foreach($this->map['customer_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))
                    echo "<script>$('customer_id').value = \"".addslashes(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))."\";</script>";
                    ?>
	</select>-->
                <label><?php echo Portal::language('from_date');?></label>
                <input  name="from_date" id="from_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                <label><?php echo Portal::language('to_date');?></label>
                <input  name="to_date" id="to_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">
                <label><?php echo Portal::language('filter');?></label>
                <select  name="find" id="find" style="padding: 5px;" onchange="find_status();"><?php
					if(isset($this->map['find_list']))
					{
						foreach($this->map['find_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('find',isset($this->map['find'])?$this->map['find']:''))
                    echo "<script>$('find').value = \"".addslashes(URL::get('find',isset($this->map['find'])?$this->map['find']:''))."\";</script>";
                    ?>
	</select>
                <input value="<?php echo Portal::language('search');?>" type="submit" onclick="return CheckSubmit('SEARCH');" style="padding: 5px;" />
            </td>
        </tr>
        <tr>
            <th colspan="3" style="text-align: right; text-transform: uppercase;"><?php echo Portal::language('beginning_outstanding_debt');?>: <?php echo System::display_number($this->map['debit_last_period_before']); ?></th>
        </tr>
        <tr>
            <th colspan="2">
                <div style="width: 20px; height: 20px; background: #ffa2a2;float: left; margin: 0px; padding: 0px; margin-right: 3px;"></div><p style="float: left; line-height: 20px; margin: 0px; padding: 0px;"> Khoản phát sinh tăng đã được giảm trừ hết</p>
                <div style="width: 20px; height: 20px; background: #99e6ff;float: left; margin: 0px; padding: 0px; margin-right: 3px; margin-left: 20px;"></div><p style="float: left; line-height: 20px; margin: 0px; padding: 0px;"> Khoản phát sinh giảm</p>
            </th>
            <th style="text-align: right; text-transform: uppercase;"><input id="submit" value="<?php echo Portal::language('save');?>" type="submit" onclick="return CheckSubmit('DEDUCTIBLE');" style="padding: 10px;" /></th>
        </tr>
    </table>
    <table cellpadding="5" border="1" bordercolor="#CCCCCC" style="width: 100%; margin: 10px auto;">
        <tr style="text-align: center; background: #EEEEEE;">
            <th><input type="checkbox" id="check_all" /></th>
            <th><?php echo Portal::language('date');?></th>
            <th><?php echo Portal::language('description');?></th>
            <th><?php echo Portal::language('recode');?></th>
            <th><?php echo Portal::language('folio');?></th>
            <th><?php echo Portal::language('bar_reservation_code');?></th>
            <th><?php echo Portal::language('ve_reservation_code');?></th>
            <th><?php echo Portal::language('mice');?></th>
            <th><?php echo Portal::language('mice_invoice_code');?></th>
            <th><?php echo Portal::language('ps_up');?></th>
            <th><?php echo Portal::language('ps_down');?></th>
            <th style="background: #fafbcb;"><?php echo Portal::language('ps_up_balance');?></th>
            <th><?php echo Portal::language('user_deductible');?></th>
            <!--<th><?php echo Portal::language('outstanding_debt');?></th>-->
            <th><?php echo Portal::language('deductible');?></th>
            <th><?php echo Portal::language('delete');?></th>
        </tr>
        <?php $stt=1; $ps_up=0; $ps_down=0; $up_balance = 0; $outstanding_debt_last = $this->map['debit_last_period_before']; ?>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr id="tr_<?php echo $this->map['items']['current']['id'];?>" style="text-align: center; <?php 
				if(($this->map['items']['current']['edit']==0 && $this->map['items']['current']['up']>0 && $this->map['items']['current']['down']==0))
				{?>  background: #ffa2a2;  <?php }else{ ?> <?php 
				if(($this->map['items']['current']['down']>0 && $this->map['items']['current']['up']==0))
				{?> background: #99e6ff; 
				<?php
				}
				?> 
				<?php
				}
				?>" class="<?php 
				if(($this->map['items']['current']['down']>0 && $this->map['items']['current']['up']==0))
				{?> PSGitems  <?php }else{ ?> PSTitems 
				<?php
				}
				?>">
            <td>
                <?php 
				if(($this->map['items']['current']['edit']==1 && $this->map['items']['current']['down']==0 && $this->map['items']['current']['up']>0))
				{?>
                    <input type="checkbox" class="check_items" name="up[<?php echo $this->map['items']['current']['id'];?>][id]" id="id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['id'];?>" lang="<?php echo $this->map['items']['current']['id'];?>" onclick="CheckDeductible(this);" /> 
                 <?php }else{ ?>
                    <?php 
				if(($this->map['items']['current']['edit']==1 && $this->map['items']['current']['down']>0 && $this->map['items']['current']['up']==0))
				{?>
                    <input type="checkbox" class="check_itemsdown" name="down[<?php echo $this->map['items']['current']['id'];?>][id]" id="downid_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['id'];?>" lang="<?php echo $this->map['items']['current']['id'];?>" onclick="CheckDownDeductible(this);" />
                    
				<?php
				}
				?>
                
				<?php
				}
				?>
            </td>
            <td><?php echo $this->map['items']['current']['in_date'];?></td>
            <td><?php echo $this->map['items']['current']['description'];?></td>
            <td><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['reservation_id'];?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
            <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_folio'];?>"><?php echo $this->map['items']['current']['folio_number'];?></a></td>
            <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_bar'];?>"><?php echo $this->map['items']['current']['bar_reservation_code'];?></a></td>
            <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_vend'];?>"><?php echo $this->map['items']['current']['ve_reservation_code'];?></a></td>
            <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['items']['current']['mice_reservation_id'];?>"><?php echo $this->map['items']['current']['mice_reservation_id'];?></a></td>
            <td><a target="_blank" href="<?php echo $this->map['items']['current']['link_mice'];?>"><?php echo $this->map['items']['current']['mice_invoice_code'];?></a></td>
            <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['up']); $ps_up+=$this->map['items']['current']['up']; $outstanding_debt_last+=$this->map['items']['current']['up']; ?></td>
            <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['down']); $ps_down+=$this->map['items']['current']['down']; $outstanding_debt_last-=$this->map['items']['current']['down']; ?></td>
            <td style="text-align: right; background: #fafbcb;"><?php echo System::display_number($this->map['items']['current']['up_balance']); $up_balance+=$this->map['items']['current']['up_balance'];  ?></td>
            <td><?php echo $this->map['items']['current']['user_id'];?></td>
            <!--<td style="text-align: right;"><?php echo System::display_number($outstanding_debt_last); ?></td>-->
            <td>
                <?php 
				if(($this->map['items']['current']['edit']==1 && $this->map['items']['current']['down']==0 && $this->map['items']['current']['up']>0))
				{?> 
                    <input type="hidden" id="up_balance_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo System::display_number($this->map['items']['current']['up_balance']); ?>" />
                    <input type="hidden" name="up[<?php echo $this->map['items']['current']['id'];?>][invoice_date]" id="invoice_date_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['in_date'];?>" />
                    <input type="hidden" name="up[<?php echo $this->map['items']['current']['id'];?>][folio_id]" id="folio_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['folio_id'];?>" />
                    <input type="hidden" name="up[<?php echo $this->map['items']['current']['id'];?>][bar_reservation_id]" id="bar_reservation_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['bar_reservation_id'];?>" />
                    <input type="hidden" name="up[<?php echo $this->map['items']['current']['id'];?>][ve_reservation_id]" id="ve_reservation_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['ve_reservation_id'];?>" />
                    <input type="hidden" name="up[<?php echo $this->map['items']['current']['id'];?>][mice_invoice_id]" id="mice_invoice_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['mice_invoice_id'];?>" />  
                    
                    <input type="text" name="up[<?php echo $this->map['items']['current']['id'];?>][description]" id="description_<?php echo $this->map['items']['current']['id'];?>" value="" style="display: none; width: 100%; padding: 3px;" placeholder="<?php echo Portal::language('description');?>" />
                    <select id="payment_type_id_<?php echo $this->map['items']['current']['id'];?>" name="up[<?php echo $this->map['items']['current']['id'];?>][payment_type_id]" style="display: none; padding: 3px;"><?php echo $this->map['payment_type_option'];?></select>
                    <input type="text" name="up[<?php echo $this->map['items']['current']['id'];?>][price]" id="price_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo System::display_number($this->map['items']['current']['up_balance']); ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));if(to_numeric(jQuery(this).val())><?php echo $this->map['items']['current']['up_balance'];?>){alert('Không được nhập số tiền lớn hơn số tiền PST chưa giảm trừ!');jQuery(this).val(number_format(<?php echo $this->map['items']['current']['up_balance'];?>));}if(to_numeric(jQuery(this).val())<=0){alert('Không được nhập số tiền nhỏ hơn hoặc bằng 0!');jQuery(this).val(number_format(<?php echo $this->map['items']['current']['up_balance'];?>));}" style="display: none; width: 100px; padding: 3px; text-align: right;" />
                 <?php }else{ ?>
                    <?php 
				if(($this->map['items']['current']['edit']==1 && $this->map['items']['current']['down']>0 && $this->map['items']['current']['up']==0))
				{?>
                        <input type="hidden" id="down_balance_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo System::display_number($this->map['items']['current']['down']); ?>" />
                        <input type="hidden" name="down[<?php echo $this->map['items']['current']['id'];?>][invoice_date]" id="downinvoice_date_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['in_date'];?>" />
                        <input type="hidden" name="down[<?php echo $this->map['items']['current']['id'];?>][folio_id]" id="downfolio_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['folio_id'];?>" />
                        <input type="hidden" name="down[<?php echo $this->map['items']['current']['id'];?>][bar_reservation_id]" id="downbar_reservation_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['bar_reservation_id'];?>" />
                        <input type="hidden" name="down[<?php echo $this->map['items']['current']['id'];?>][ve_reservation_id]" id="downve_reservation_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['ve_reservation_id'];?>" />
                        <input type="hidden" name="down[<?php echo $this->map['items']['current']['id'];?>][mice_invoice_id]" id="downmice_invoice_id_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['mice_invoice_id'];?>" />  
                        
                        <input type="text" name="down[<?php echo $this->map['items']['current']['id'];?>][description]" id="downdescription_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['description'];?>" style="display: none; width: 100%; padding: 3px;" placeholder="<?php echo Portal::language('description');?>" />
                        <select id="downpayment_type_id_<?php echo $this->map['items']['current']['id'];?>" name="down[<?php echo $this->map['items']['current']['id'];?>][payment_type_id]" style="display: none; padding: 3px;"><?php echo $this->map['payment_type_option'];?></select>
                        <script>
                            jQuery("#downpayment_type_id_<?php echo $this->map['items']['current']['id'];?>").val('<?php echo $this->map['items']['current']['payment_type_id'];?>');
                        </script>
                        <input type="text" name="down[<?php echo $this->map['items']['current']['id'];?>][price]" id="downprice_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo System::display_number($this->map['items']['current']['down']); ?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));if(to_numeric(jQuery(this).val())<=0){alert('Không được nhập số tiền nhỏ hơn hoặc bằng 0!');jQuery(this).val(number_format(<?php echo $this->map['items']['current']['down'];?>));}" style="display: none; width: 100px; padding: 3px; text-align: right;" />
                    
				<?php
				}
				?>
                
				<?php
				}
				?>
            </td>
            <td>
                <?php 
				if(($this->map['items']['current']['edit']==1 && $this->map['items']['current']['down']>0 && $this->map['items']['current']['up']==0))
				{?>
                    <input type="checkbox" name="down[<?php echo $this->map['items']['current']['id'];?>][delete]" id="downdelete_<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['id'];?>" style="display: none;"/>
                    <input type="button" onclick="if(confirm('bạn chắc chắn muốn xóa khoản giảm trừ?')){DeleteDownDeductible('<?php echo $this->map['items']['current']['id'];?>');}else{return false;}" value="<?php echo Portal::language('delete');?>" style="padding: 10px;" />
                
				<?php
				}
				?>
            </td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
        <tr style="text-align: right; background: #EEEEEE;">
            <th colspan="9"><?php echo Portal::language('total');?></th>
            <th><?php echo System::display_number($ps_up); ?></th>
            <th><?php echo System::display_number($ps_down); ?></th>
            <th style="background: #fafbcb;"><?php echo System::display_number($up_balance); ?></th>
            <th></th>
            <!--<th><?php echo System::display_number($outstanding_debt_last); ?></th>-->
            <td colspan="2"></td>
        </tr>
        <table cellpadding="5" style="width: 100%;">
            <tr style="text-align: right;">
                <th style="text-align: right; text-transform: uppercase;"><?php echo Portal::language('outstanding_debt_last');?>: <?php echo System::display_number($outstanding_debt_last); ?></th>
            </tr>
        </table>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    find_status();
    jQuery("#check_all").click(function() {
        jQuery(".check_items").attr('checked',false);
        jQuery(".check_itemsdown").attr('checked',false);
        if(document.getElementById("check_all").checked==true){
            jQuery(".check_items").attr('checked',true);
            jQuery(".check_itemsdown").attr('checked',true);
        }
        jQuery(".check_items").each(function(){
            CheckDeductible(this);
        });
        jQuery(".check_itemsdown").each(function(){
            CheckDownDeductible(this);
        });
    });
    function find_status() {
        if(jQuery("#find").val()!='') {
            if(jQuery("#find").val()=='PST') {
                jQuery(".PSGitems").css('display','none');
                jQuery(".PSTitems").css('display','');
                jQuery("#check_all").css('display','');
            }else {
                jQuery(".PSTitems").css('display','none');
                jQuery("#check_all").css('display','none');
                jQuery(".PSGitems").css('display','');
            }
        }else {
            jQuery(".PSGitems").css('display','');
            jQuery(".PSTitems").css('display','');
            jQuery("#check_all").css('display','');
        }
    }
    function CheckDeductible(obj) {
        var id = obj.id;
        var key = obj.value;
        if(document.getElementById(id).checked==true){
            jQuery("#payment_type_id_"+key).css('display','');
            jQuery("#price_"+key).css('display','');
            jQuery("#description_"+key).css('display','');
        }else {
            jQuery("#payment_type_id_"+key).css('display','none');
            jQuery("#price_"+key).css('display','none');
            jQuery("#description_"+key).css('display','none');
        }
    }
    function CheckDownDeductible(obj) {
        var id = obj.id;
        var key = obj.value;
        if(document.getElementById(id).checked==true){
            jQuery("#downpayment_type_id_"+key).css('display','');
            jQuery("#downprice_"+key).css('display','');
            jQuery("#downdescription_"+key).css('display','');
        }else {
            jQuery("#downpayment_type_id_"+key).css('display','none');
            jQuery("#downprice_"+key).css('display','none');
            jQuery("#downdescription_"+key).css('display','none');
        }
    }
    function DeleteDownDeductible($id) {
        jQuery("#downdelete_"+$id).attr('checked',true);
        jQuery("#downid_"+$id).attr('checked',true);
        jQuery("#tr_"+$id).css('display','none');
        
    }
    function CheckSubmit(act) {
        jQuery("#act").val(act);
        jQuery('#submit').hide();
        SummaryDebitReportForm.submit();
    }
</script>