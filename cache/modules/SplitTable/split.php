<fieldset id="toolbar" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="100%" style="#width:99%;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title"><?php echo Portal::language('split_order');?></td>
						</tr>
                         <tr>
                        	<td align="right" style="text-align:right;">
                                <?php echo Portal::language('bar_name');?>: <select  name="bars" id="bars" onchange="updateBar();" style="height:30px;"><?php
					if(isset($this->map['bars_list']))
					{
						foreach($this->map['bars_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))
                    echo "<script>$('bars').value = \"".addslashes(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))."\";</script>";
                    ?>
	</select> 
                                <input  name="acction" value="0" id="acction" / type ="hidden" value="<?php echo String::html_normalize(URL::get('acction'));?>">
                               
                                <script>
                                    var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                                    if(bar_id != ''){
                                    	$('bars').value = bar_id;	
                                    }
                                 </script>
                        	</td>
                        </tr>
					</table>
				</td>	
			</tr>	
			<tr>
				<td width="60%" align="right" valign="top"><?php echo Portal::language('split_from_code');?>
				  <select  name="from_code" class="select-large" id="from_code" onchange="SplitForm.submit();"  style="height:35px;"><?php
					if(isset($this->map['from_code_list']))
					{
						foreach($this->map['from_code_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('from_code',isset($this->map['from_code'])?$this->map['from_code']:''))
                    echo "<script>$('from_code').value = \"".addslashes(URL::get('from_code',isset($this->map['from_code'])?$this->map['from_code']:''))."\";</script>";
                    ?>
	</select>
			    </td>
			</tr>
            <tr>
            	<td align="right">
                	<div style="width:200px;">
                	<fieldset>
                    <legend><?php echo Portal::language('Table');?></legend>
					<?php 
				if((isset($this->map['tables']) and count($this->map['tables'])>0))
				{?>
					<div> <?php $i=1;?>
						<?php if(isset($this->map['tables']) and is_array($this->map['tables'])){ foreach($this->map['tables'] as $key1=>&$item1){if($key1!='current'){$this->map['tables']['current'] = &$item1;?>
							<div align="right" style="margin:3px;">
								&nbsp;<?php echo $this->map['tables']['current']['name'];?> : <select  name="to_table_<?php echo $this->map['tables']['current']['id'];?>" id="to_table" class="to_table" onchange="$('split_table_<?php echo $i;?>').checked=true;update_table_id(this);" style="height:35px;">
                                <?php echo $this->map['tables_option'];?>
                                </select><input name="split_table[<?php echo $this->map['tables']['current']['id'];?>]" type="checkbox" id="split_table_<?php echo $i;?>" value="<?php echo $this->map['tables']['current']['id'];?>" />
							</div>
							<?php $i++;?>
						<?php }}unset($this->map['tables']['current']);} ?>
					</div>
					
				<?php
				}
				?>
                    </fieldset>
                    </div>
                </td>
            </tr>
			<tr>
				<td align="right">
					<?php 
				if((isset($this->map['orders']) and count($this->map['orders'])>0))
				{?>
					<div>
						<?php if(isset($this->map['orders']) and is_array($this->map['orders'])){ foreach($this->map['orders'] as $key2=>&$item2){if($key2!='current'){$this->map['orders']['current'] = &$item2;?>
							<div align="right" style="margin:3px;">
								<label for="order_<?php echo $this->map['orders']['current']['id'];?>"><?php echo $this->map['orders']['current']['name'];?> [<strong><?php echo $this->map['orders']['current']['quantity'];?></strong>]</label> <?php echo Portal::language('conversition');?> 
								<input name="quantity_before[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['quantity'];?>" type="hidden" id="quantity_before_<?php echo $this->map['orders']['current']['id'];?>"/>
								<input name="quantity[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['quantity'];?>" type="text" id="quantity_<?php echo $this->map['orders']['current']['id'];?>" lang="<?php echo $this->map['orders']['current']['id'];?>" style="width:40px;height:30px;font-size:18px;text-align:center"  onclick="select();if($('order_<?php echo $this->map['orders']['current']['id'];?>').checked){$('order_<?php echo $this->map['orders']['current']['id'];?>').checked=false;}else{ if(this.value!=''){$('order_<?php echo $this->map['orders']['current']['id'];?>').checked=true;}}" maxlength="5" class="input_number" onkeyup="if(this.value=='0' || this.value=='' || Number(this.value)>Number(jQuery('#quantity_before_<?php echo $this->map['orders']['current']['id'];?>').val()))
                                                                                                                                                                                                                                                                                                                                                                                                                                                                           {this.value='';$('order_<?php echo $this->map['orders']['current']['id'];?>').checked=false; jQuery('#order_<?php echo $this->map['orders']['current']['id'];?>').css('display','none');} else{jQuery('#order_<?php echo $this->map['orders']['current']['id'];?>').css('display','');}"/>
								<input name="order[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['id'];?>" type="checkbox" id="order_<?php echo $this->map['orders']['current']['id'];?>" class="order" />
                                
                                <?php if($this->map['orders']['current']['quantity_discount']>0){ ?>
                                    <label><?php echo Portal::language('quantity_discount_1');?></label>
                                    <input name="quantity_discount[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['quantity_discount'];?>" type="text" id="quantity_discount_<?php echo $this->map['orders']['current']['id'];?>" onchange="fun_quantity_discount(<?php echo $this->map['orders']['current']['id'];?>,this.value);" style="width: 40px; height: 30px;font-size:18px;text-align:center" />
                                    <input name="quantity_discount_before[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['quantity_discount'];?>" type="text" id="quantity_discount_before_<?php echo $this->map['orders']['current']['id'];?>" style="width: 40px; height: 30px;font-size:18px;text-align:center; display: none;" />
                                <?php }else{ ?>
                                    <label><?php echo Portal::language('quantity_discount_1');?></label>
                                    <input name="quantity_discount[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['quantity_discount'];?>" type="text" id="quantity_discount_<?php echo $this->map['orders']['current']['id'];?>" style="width: 40px; height: 30px;font-size:18px;text-align:center" readonly="" />
                                    <input name="quantity_discount_before[<?php echo $this->map['orders']['current']['id'];?>]" value="<?php echo $this->map['orders']['current']['quantity_discount'];?>" type="text" id="quantity_discount_before_<?php echo $this->map['orders']['current']['id'];?>" style="width: 40px; height: 30px;font-size:18px;text-align:center; display: none;" />
                                <?php } ?>
							</div>
						<?php }}unset($this->map['orders']['current']);} ?>
					</div>
					
				<?php
				}
				?>
				</td>
				<td align="left">&nbsp;</td>
			</tr>				
		</table>
        <div align="center" >
        <input type="button" style="width: 70px; height: 50px;" value="<?php echo Portal::language('split_table_ok');?>" onclick="if(jQuery('#to_table').val() != 0){}else{ alert('Bạn chưa chọn đến bàn!'); return false;} fun_check();"/>
        <input  name="save" style="display: none;" id="save" / type ="text" value="<?php echo String::html_normalize(URL::get('save'));?>">
        <!--<input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.history.back(-1);"/>-->
        <input style="width: 70px; height: 50px;" name="exit" type="reset" value="<?php echo Portal::language('split_table_cancel');?>" id="exit" onclick="window.close()"/>
        </div>
		<input  name="confirm" type ="hidden" id="m" value="<?php echo String::html_normalize(URL::get('confirm','1'));?>">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input  name="action" id="action" type ="hidden" value="<?php echo String::html_normalize(URL::get('action'));?>">
        <input  name="new_table_id" id="new_table_id" type ="hidden" value="<?php echo String::html_normalize(URL::get('new_table_id'));?>">
  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</fieldset>

<script>
    var orders = <?php echo String::array2js($this->map['orders']); ?>;
	jQuery('#from_code').val('<?php if(Url::get('from_code')){echo Url::get('from_code');} else {echo '';}?>');
	function update_table_id(obj){
			jQuery('#new_table_id').val(obj.value);
	}
	function updateBar(){
		jQuery('#acction').val(1);
		SplitForm.submit();
	}
    function fun_check()
    {
        var check=true;
        for(id in orders)
        {
            if(fun_check_quantity_discount(id)==false)
            {
                check=false;
            }
        }
        if(check==true)
        {
            jQuery('#save').val('thuc hien');
            SplitForm.submit();
        }
        else
        {
            alert('số lượng khuyến mãi lớn hơn số lượng món hiện có');
        }
    }
    function fun_check_quantity_discount(id)
    {
        if( (to_numeric(orders[id]['quantity'])!=to_numeric(jQuery("#quantity_"+id).val())) || (to_numeric(orders[id]['quantity_discount'])!=to_numeric(jQuery("#quantity_discount_"+id).val())) )
        {
            var quantity = to_numeric(jQuery("#quantity_"+id).val());
            var quantity_discount = to_numeric(jQuery("#quantity_discount_"+id).val());
            var quantity_old = (to_numeric(orders[id]['quantity'])-to_numeric(jQuery("#quantity_"+id).val()));
            var quantity_discount_old = (to_numeric(orders[id]['quantity_discount'])-to_numeric(jQuery("#quantity_discount_"+id).val()));
            console.log(quantity+"-"+quantity_discount);
            console.log(quantity_old+"-"+quantity_discount_old);
            if(quantity<quantity_discount || quantity_old<quantity_discount_old)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }
    function fun_quantity_discount(id,value)
    {
        if(to_numeric(value)>to_numeric(orders[id]['quantity_discount']))
        {
            alert('số lượng khuyến mãi lớn hơn số lượng km hiện có');
            jQuery("#quantity_discount_"+id).val(to_numeric(orders[id]['quantity_discount']));
        }
    }
</script>
