<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
#style_list{
    width:95%; margin: 10px auto;
    /*border-radius: 10px;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    -o-border-radius: 10px;*/
    box-shadow: 0px 0px 5px #999;
    -webkit-box-shadow: 0px 0px 5px #999;
    -moz-box-shadow: 0px 0px 5px #999;
    -o-box-shadow: 0px 0px 5px #999;
    padding: 3px;
}
.button_stylea{
    background: #fff;
    color:#171717;
 background-image: none;
}
#msgbox{
    width:100%; height: 30px;
    position: fixed;
    top: 120px;
    left: 0px;
    text-align: center;
}
#msgbox p{
    line-height: 25px;
    padding: 5px;
    background: #fd6fab;
    border:1px solid #720131;
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<?php if(isset($_REQUEST['msg'])){ ?>
<div id="msgbox">
    <p><?php echo $_REQUEST['msg']; ?></p>
</div>
<?php } ?>
<div class="customer-type-supplier-bound" id="style_list" onclick="break_auto_complate();">
<form name="ListCustomerForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr  height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 5px;"><i class="fa fa-book w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="40%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="w3-btn w3-cyan w3-text-white w3-hover-text-white" style="text-decoration: none; text-transform: uppercase; height: 30px; margin-right: 5px;"><i class="fa fa-plus-square" style="font-size: 16px;"></i> <?php echo Portal::language('Add');?></a><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListCustomerForm.cmd.value='delete';ListCustomerForm.submit();"  class="w3-btn w3-red w3-hover-red w3-text-white" style="text-decoration: none; text-transform: uppercase; height: 30px; margin-right: 5px;"><i class="fa fa-remove" style="font-size: 16px;"></i> <?php echo Portal::language('Delete');?></a><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><a href="#" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" class="w3-btn w3-green w3-hover-green w3-text-white" style="text-decoration: none; text-transform: uppercase; height: 30px; margin-right: 20px;"><i class="fa fa-file-excel-o" style="font-size: 16px;"></i> <?php echo Portal::language('import_from_excel');?></a></td><?php }?>
            </td>
        </tr>
    </table>        
	<div class="content">
		<fieldset style="text-transform: uppercase;">
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<span style="font-weight: normal;"><?php echo Portal::language('input_keyword');?></span>
            <input  name="customer_id" id="customer_id" style="width:18%; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>"> 
			<span style="font-weight: normal;"><?php echo Portal::language('customer_group');?></span> <select  name="group_id" id="group_id" style="height: 24px;"><?php
					if(isset($this->map['group_id_list']))
					{
						foreach($this->map['group_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('group_id',isset($this->map['group_id'])?$this->map['group_id']:''))
                    echo "<script>$('group_id').value = \"".addslashes(URL::get('group_id',isset($this->map['group_id'])?$this->map['group_id']:''))."\";</script>";
                    ?>
	</select>
            <input class="w3-btn w3-gray" name="search" type="submit" value="OK" style="height: 24px; padding-top: 4px; margin-bottom: 3px;"/>
            <!--<a class="select-item"  href="<?php //echo Url::build_current(array('action','group_id'=>'KL'));?>"><?php echo Portal::language('show_walk_in');?></a> |
			<a class="select-item" href="<?php //echo Url::build_current(array('group_id'=>'KNH'));?>"><?php echo Portal::language('show_restaurant_customer');?></a>-->
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
			<tr class="w3-light-gray w3-text-blue w3-border" style="text-transform: uppercase; height: 30px;">
			  <th width="1%" style="text-align: center;"><input type="checkbox" id="all_item_check_box"></th>
			  <th width="3%" style="text-align: center;"><?php echo Portal::language('order_number');?></th>
			  <th width="5%" align="center"><?php echo Portal::language('code');?></th>
              <th width="20%" align="center"><?php echo Portal::language('customers_source');?></th>
              <th width="20%" align="center"><?php echo Portal::language('full_name_customer');?></th>
			  <?php 
				if((!Url::get('group_id')))
				{?>
			  <th width="10%" align="center"><?php echo Portal::language('type_source');?></th>
			  
				<?php
				}
				?>
			  <th width="20%" align="center"><?php echo Portal::language('address');?></th>
			  <th width="10%" align="center"><?php echo Portal::language('contact_person_info');?></th>
              <th width="3%" style="text-align: center;"><?php echo Portal::language('commission');?></th>
			  <!---<th width="1%"><?php echo Portal::language('rate');?></th>--->
              <th width="3%" style="text-align: center;"><?php echo Portal::language('Customer_care');?></th>
  			  <th width="1%" style="text-align: center;"><?php echo Portal::language('edit');?></th>
              
		      <th width="1%" style="text-align: center;"><?php echo Portal::language('delete');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr <?php echo $this->map['items']['current']['i']%2==0?'class="row-even"':'class="row-odd"'?>>
			  <td valign="top">
              <!-- Oanh add neu co book thi k cho chon button -->
                <?php if(!DB::exists('select * from reservation where customer_id='.$this->map['items']['current']['id'])){?>
                <input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"/>
                <?php }?>
              <!-- End Oanh -->
             </td>
			  <td valign="top" style="text-align: center;"><?php echo $this->map['items']['current']['rownumber'];?></td>
			  <td valign="top" style="text-align: center;"><span id="code_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['code'];?></span></td>
				<td valign="top">
                    <i class="fa fa-sign-in w3-text-red" style="font-size: 17px;"></i>
                    <?php if(Url::get('action')=='select_customer' or Url::get('action')=='voucher'){?>
                        <a class="select-item" href="#" onclick="pick_value(<?php echo $this->map['items']['current']['id'];?>);get_data_vat(<?php echo $this->map['items']['current']['id'];?>);"><?php echo Portal::language('select');?></a> 
                    <?php }?>
                    <span id="name_<?php echo $this->map['items']['current']['id'];?>">
                        <strong class="w3-hover-text-red" style="text-transform: uppercase; font-weight: normal !important;"><?php echo $this->map['items']['current']['name'];?></strong>
                    </span>
                    
                </td>
                <td valign="top">
                    <i class="fa fa-sign-in w3-text-red" style="font-size: 17px;"></i> 
                    <?php if(Url::get('action')=='select_customer' or Url::get('action')=='voucher'){?>
                        <a class="select-item" href="#" onclick="pick_value(<?php echo $this->map['items']['current']['id'];?>);get_data_vat(<?php echo $this->map['items']['current']['id'];?>);"><?php echo Portal::language('select');?></a> 
                    <?php }?>
                    <span id="name_<?php echo $this->map['items']['current']['id'];?>">
                        <strong class="w3-hover-text-red" style="text-transform: uppercase; font-weight: normal !important;"><?php echo $this->map['items']['current']['def_name'];?></strong>
                    </span>
                </td>
				<?php 
				if((!Url::get('group_id')))
				{?>
				<td valign="top" ><?php echo $this->map['items']['current']['group_name'];?></td>
				
				<?php
				}
				?>
				<td valign="top"><span id="address_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['address'];?></span><span id="tax_code_<?php echo $this->map['items']['current']['id'];?>" style="display: none;"><?php echo $this->map['items']['current']['tax_code'];?></span><span id="bank_code_<?php echo $this->map['items']['current']['id'];?>" style="display: none;"><?php echo $this->map['items']['current']['bank_code'];?></span></td>
		       <td>
               	<?php $stt = 1;?>
               		<?php if(isset($this->map['items']['current']['contacts']) and is_array($this->map['items']['current']['contacts'])){ foreach($this->map['items']['current']['contacts'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['contacts']['current'] = &$item2;?>
                    <?php if($stt>1){?>
                    <hr />
                    <?php }?>
			   		&loz; <span class="note"><?php echo Portal::language('full_name');?>:</span> <?php echo $this->map['items']['current']['contacts']['current']['contact_name'];?><br />
		       		&loz; <span class="note"><?php echo Portal::language('telephone');?>:</span> <?php echo $this->map['items']['current']['contacts']['current']['contact_phone'];?><br />
		       		&loz; <span class="note"><?php echo Portal::language('mobile');?>:</span> <?php echo $this->map['items']['current']['contacts']['current']['contact_mobile'];?><br />
		       		&loz; <span class="note"><?php echo Portal::language('email');?>:</span> <?php echo $this->map['items']['current']['contacts']['current']['contact_email'];?>                    
                    <?php $stt++;?>
               		<?php }}unset($this->map['items']['current']['contacts']['current']);} ?>                    
                    </td>
                <td valign="top"><a href="<?php echo Url::build('customer_rate_commission',array('customer_id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-usd" style="font-size: 20px;"></i> (<?php echo $this->map['items']['current']['rate_policy_commission'];?>)</a></td>
		        <!---<td valign="top"><a href="<?php echo Url::build('customer_rate_policy',array('customer_id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/rate.jpg" width="20" height="19" />(<?php echo $this->map['items']['current']['rate_policy_quantity'];?>)</a></td>--->
                <!--Giap nguyen add 9-5-2014 customercare-->
                <td valign="top" style="width: 100px;"><a href="<?php echo Url::build('customer_care',array('cmd'=>'view','id'=>$this->map['items']['current']['id']));?>" title="<?php echo Portal::language('customer_diary');?>" class="w3-hover-text-red w3-text-indigo" style="text-decoration: none;"><i class="fa fa-fax w3-text-indigo" style="font-size: 20px;"></i> (<?php echo $this->map['items']['current']['cutomer_care_count'];?>)</a></td>
                <!--Giap nguyen end-->
	            <td valign="top" style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id'],'group_id'));?>" title="<?php echo Portal::language('Edit');?>"><i class="fa fa-edit w3-text-green" style="font-size: 20px;"></i></a></td>
			    
                <!-- oanh add neu dang co booking khong cho hien thi nut xoa -->
                <td valign="top" style="text-align: center;">
                        <?php
                         if(!DB::exists('select * from reservation where customer_id='.$this->map['items']['current']['id'])){
                         if(User::can_delete(false,ANY_CATEGORY)){?>
                        <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id'],'group_id'));?>" title="<?php echo Portal::language('delete');?>">
                        <i class="fa fa-times-rectangle w3-text-red" style="font-size: 20px;"></i></a><?php }}?>    
                </td>
                <!-- End Oanh -->
                
			</tr>
		  <?php }}unset($this->map['items']['current']);} ?>			
		</table>
	  <br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListCustomerForm.cmd.value = 'delete';
		ListCustomerForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			window.opener.document.getElementById('customer_name').value=($('name_'+id).innerText!='')?($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			window.opener.document.getElementById('customer_id').value=id;  		
		}
        
	}
    function get_data_vat(id) {
        <?php if(Url::get('to')=='vat'){?>
        if (window.opener && !window.opener.closed)
		{
			window.opener.document.getElementById('customer_address').value=($('address_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			window.opener.document.getElementById('customer_tax_code').value=($('tax_code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
            window.opener.document.getElementById('customer_bank_code').value=($('bank_code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
		}
        <?php } ?>
    }
</script>
<?php if(Url::get('action')=='select_customer' AND !isset($_REQUEST['site'])){?>
<script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('customer_name'))
			{
				window.opener.document.getElementById('customer_name').value=($('name_'+id).innerText!='')?($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('address'))
			{
				window.opener.document.getElementById('address').value=($('address_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('full_name'))
			{
				window.opener.document.getElementById('full_name').value=(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""))?($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.document.getElementById('customer_id'))
			{
				window.opener.document.getElementById('customer_id').value=id;
			}
			if(window.opener.document.getElementById('customer_code'))
			{
				window.opener.document.getElementById('customer_code').value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			if(window.opener.jQuery('#customer_name').val()){
				var inputCount = window.opener.input_count;
                var is_rate_code = window.opener.document.getElementById('is_rate_code');
                if(id!='' && is_rate_code && is_rate_code.checked)
                    get_price_rate_code(id,101);
                else
                    window.close();
                //
				/*for(var i=101;i<=inputCount;i++){
					if(window.opener.jQuery('#price_'+i) && window.opener.jQuery('#old_status_'+i) && window.opener.jQuery('#old_status_'+i).val()==''){
						//window.opener.jQuery('#price_'+i).val(0);
					}
				}*/
                
				//window.opener.jQuery('.price').attr('readonly',true);
				//window.opener.jQuery('.price').addClass('readonly');			
			}
		}
	}
    /**giap.ln lay ra gia theo nguon khach**/
    function get_price_rate_code(customer_id,index)
    {
        var room_level_id = window.opener.document.getElementById('room_level_id_'+ index).value;
        //lay ra arrival_time & departure_time 
        var arrival_time = window.opener.document.getElementById('arrival_time_' + index).value;
        var departure_time = window.opener.document.getElementById('departure_time_' + index).value;
        
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                //console.log(text_reponse);
                var items = JSON.parse(text_reponse);
                var price_common = items['price_common'];
                
                var exchange_rate = window.opener.jQuery('#exchange_rate').val();
                window.opener.document.getElementById('price_' + index).value=number_format(price_common);
                window.opener.document.getElementById('usd_price_' + index).value = number_format(price_common/exchange_rate);
                
                change_price_rate_code(items,index,true);
                var inputCount = window.opener.input_count;
                if(index<inputCount)
                {
                    index++;
                    get_price_rate_code(customer_id,index);
                }
                else
                    window.close();
               
            }
        }
        xmlhttp.open("GET","get_customer_search_fast.php?rate_code=1&room_level_id="+ room_level_id +"&customer_id="+customer_id+ "&arrival_time="+ arrival_time + "&departure_time="+departure_time,true);
        xmlhttp.send();
    }
    function change_price_rate_code(items,index,flag)
    {
        for(var i in items)
        {
            if(i!='price_common')
            {
                if(flag)
                    window.opener.document.getElementById('change_price_'+i+'_'+index).value = number_format(items[i]['price']);
                else
                    document.getElementById('change_price_'+i+'_'+index).value = number_format(items[i]['price']);
            }
        }
    }
    //giap.ln tinh lai gia phong theo ratecode
</script>
<?php }?>

<?php if(Url::get('action')=='select_customer' AND isset($_REQUEST['site'])){?>
<script>
    function pick_value(id)
	{
	   if (window.opener && !window.opener.closed)
		{
			window.opener.document.getElementById('customer_name').value=($('name_'+id).innerText!='')?($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, ""):($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			window.opener.document.getElementById('customer_id').value=id;  		
		}
	   window.close();
    }
</script>
<?php } ?>

<?php if(Url::get('action')=='voucher'){?><script>
	function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('liability_customer_name_'+window.opener.document.current_item))
			{
				if(($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "")=='')
				{
					window.opener.document.getElementById('liability_customer_name_'+window.opener.document.current_item).value=($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				}
				else
				{
					window.opener.document.getElementById('liability_customer_name_'+window.opener.document.current_item).value=($('full_name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");			
				}
			}
			if(window.opener.document.getElementById('liability_customer_id_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_customer_id_'+window.opener.document.current_item).value=id;
			}
			if(window.opener.document.getElementById('liability_customer_code_'+window.opener.document.current_item))
			{
				window.opener.document.getElementById('liability_customer_code_'+window.opener.document.current_item).value=($('code_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>
<?php }?>
<script>
// hàm customerAutocomplete() được gọi khi người dùng onfocus input.
// AUTOSEARCH+AUTOCOMPLATE - KHI LỰA CHỌN INPUT CÓ ID LÀ customer_id - gọi đến file get_customer_search.php để lấy dữ liệu.
// sau khi lấy dữ liệu sự kiện onItemSelect sẽ tự động submit form có tên ListCustomerForm.
function customerAutocomplete()
{
	jQuery("#customer_id").autocomplete({
         url: 'get_customer_search.php?customer=1',
    onItemSelect: function(item) {
			//getCustomerFromCode(jQuery("#customer_id").val());
            console.log('test');
            document.ListCustomerForm.submit();
		}
    }) 
}
function break_auto_complate(){
    jQuery(".acResults").css('display','none');
}
</script>
<script>
jQuery(document).ready(function(){
    jQuery('#msgbox').fadeIn();
   <?php if(isset($_REQUEST['msg'])){ ?> 
    jQuery('#msgbox').fadeIn('300000').fadeOut('300000');
  <?php } ?>
});
</script>
