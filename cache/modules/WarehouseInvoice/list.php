<div class="warehouse-bound">
<form name="ListWarehouseInvoiceForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type','choose_warehouse'=>1));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('delete');?>" id="delete_button" class="w3-btn w3-red" style="text-transform: uppercase;"/><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
    	<div class="search-box">
        	<fieldset style="font-weight: normal !important;">
            	<legend><?php echo Portal::language('search');?></legend>
        		<span><?php echo Portal::language('bill_number');?>:</span> 
        		<input  name="bill_number" id="bill_number" size="4" style="height: 24px; width: 100px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('bill_number'));?>">
        		<span><?php echo Portal::language('description');?>:</span> 
        		<input  name="note" id="note" size="10" style="height: 24px; width: 150px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('note'));?>">
				<span><?php echo Portal::language('date_from');?>:</span> 
				<input  name="create_date_from" id="create_date_from" onchange="changevalue();" size="6" style="height: 24px; width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('create_date_from'));?>">
				<span><?php echo Portal::language('date_to');?>:</span> 
				<input  name="create_date_to" id="create_date_to" size="6" onchange="changefromday();" style="height: 24px; width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('create_date_to'));?>">
				<span><?php echo Portal::language('supplier');?>:</span> <select  name="supplier_id" id="supplier_id" style="width:100px; height: 24px;"><?php
					if(isset($this->map['supplier_id_list']))
					{
						foreach($this->map['supplier_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))
                    echo "<script>$('supplier_id').value = \"".addslashes(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))."\";</script>";
                    ?>
	</select>
        		<span><?php echo Portal::language('warehouse');?>:</span> <select  name="warehouse_id" id="warehouse_id" style="width:150px; height: 24px;"><?php
					if(isset($this->map['warehouse_id_list']))
					{
						foreach($this->map['warehouse_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))
                    echo "<script>$('warehouse_id').value = \"".addslashes(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))."\";</script>";
                    ?>
	</select>
                <?php 
				if((Url::get('type')=='IMPORT'))
				{?>
                <span><?php echo Portal::language('invoice_number');?>:</span> <input  name="invoice_number" id="invoice_number" style="width:80px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('invoice_number'));?>"></select>
                
				<?php
				}
				?>
                <input style="height: 24px; padding-top: 3px; margin-left: 10px;" type="submit" name="search" value="<?php echo Portal::language('search');?>"/>
                 
            </fieldset>
            
        </div><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="text-transform: uppercase; height: 24px; text-align: center;">
			  <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30"><?php echo Portal::language('order_number');?></th>
			  <th width="70" align="center"><?php echo Portal::language('create_date');?></th>
			  <th width="110" align="center"><?php echo Portal::language('bill_number');?></th>
              <th width="150" align="center"><?php echo Portal::language('warehouse');?></th>
              <?php 
				if((Url::get('type')=='EXPORT'))
				{?><th width="10%" align="center"><?php echo Portal::language('to_warehouse');?></th>
				<?php
				}
				?>
			  <th width="150" align="center"><?php echo Portal::language('deliver');?></th>
			  <th width="150" align="center"><?php echo Portal::language('receiver');?></th>
			  <th width="250" align="center"><?php echo Portal::language('description');?></th>
			  <?php 
				if((Url::get('type')=='IMPORT'))
				{?>
              <th width="250" align="center"><?php echo Portal::language('supplier');?></th>
              <th width="80" align="center"><?php echo Portal::language('invoice_number');?></th>
              
				<?php
				}
				?>
			  <th width="40" align="center"><?php echo Portal::language('view');?></th>
			  <th width="40" align="center"><?php echo Portal::language('edit');?></th>
		      <th width="40" align="center"><?php echo Portal::language('delete');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr <?php echo ($this->map['items']['current']['id']==Url::iget('just_edited_id'))?' bgcolor="#FFFF99"':'';?>>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
			  <td style="cursor:pointer; text-align: center;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['i'];?></td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['create_date'];?></td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['bill_number'];?></td>
                <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['warehouse_name'];?></td>
                <?php 
				if((Url::get('type')=='EXPORT'))
				{?><td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['to_warehouse_name'];?></td>
				<?php
				}
				?>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['deliver_name'];?></td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['receiver_name'];?></td>
				<td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['note'];?></td>
				<?php 
				if((Url::get('type')=='IMPORT'))
				{?>
                <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['supplier_name'];?></td>
                <td style="cursor:pointer;" onclick="window.location='<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>'"><?php echo $this->map['items']['current']['invoice_number'];?></td>
                
				<?php
				}
				?>
				<td style="height: 24px;text-align: center;"><a target="_blank" href="<?php echo Url::build('warehouse_invoice',array('cmd'=>'view','id'=>$this->map['items']['current']['id'],'type'));?>" title="<?php echo Portal::language('view_bill');?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 20px; padidng-top: 2px;"></i></a></td>
				<td style="height: 24px;text-align: center;">
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('cmd'=>'edit','type','id'=>$this->map['items']['current']['id'],'warehouse_id'=>$this->map['items']['current']['warehouse_id'],'edit_average_price'=>$this->map['items']['current']['for_edit_average_price']));?>"><i class="fa fa-fw fa-edit" style="color: green;"></i></a>
                    <?php }?>
                </td>
			    <td style="height: 24px;text-align: center;">
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top 2px;"></i></a>
                    <?php }?>
                </td>
			</tr>
		  <?php }}unset($this->map['items']['current']);} ?>			
		</table>
  <br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#create_date_from").mask("99/99/9999");
	jQuery("#create_date_to").mask("99/99/9999");
	jQuery("#create_date_from").datepicker();
	jQuery("#create_date_to").datepicker();
    function changevalue()
    {
        var myfromdate = $('create_date_from').value.split("/");
        var mytodate = $('create_date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#create_date_to").val(jQuery("#create_date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('create_date_from').value.split("/");
        var mytodate = $('create_date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#create_date_from").val(jQuery("#create_date_to").val());
        }
    }
	jQuery("#delete_button").click(function (){
        if(confirm('<?php echo Portal::language('are_you_sure');?>')){
    		ListWarehouseInvoiceForm.cmd.value = 'delete';
    		ListWarehouseInvoiceForm.submit();
        };

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
</script>