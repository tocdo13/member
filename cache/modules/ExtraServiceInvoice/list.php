<div class="room-type-supplier-bound">
<form name="ListExtraServiceInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" style="text-transform: uppercase; font-size: 20px;"><?php echo $this->map['title'];?></td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="25%" style="text-align: right; padding-right: 50px; "><a href="<?php echo URL::build_current(array('cmd'=>'add','type'=>Url::get('type')));?>"  class="w3-btn w3-cyan w3-text-white" style="margin-right: 10px; text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Add');?></a><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="submit" class="w3-btn w3-red" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListExtraServiceInvoiceForm.cmd.value='delete';ListExtraServiceInvoiceForm.type.value='<?php echo Url::get('type');?>';" value="<?php echo Portal::language('Delete');?>" style="text-transform: uppercase;"  /></td><?php }?>
        </tr>
    </table>        
	<div class="content"><br />
    	<fieldset>
        <legend style="text-transform: uppercase;"><?php echo Portal::language('search');?></legend>
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td ><?php echo Portal::language('hotel');?></td>
            <td style="margin: 0;"><select  name="portal_id" id="portal_id" style="height: 24px; margin-right: 20px;"><?php
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
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            
            <td><?php echo Portal::language('bill_number');?> <input  name="bill_number" id="bill_number" size="8"style="height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('bill_number'));?>"></td>
            <td><?php echo Portal::language('room');?> <input  name="room_name" id="room_name" size="8" style="height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('room_name'));?>"></td>
             <td><?php echo Portal::language('create_time');?> <input  name="time" id="time" size="8" style="height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('time'));?>"></td>
             <td><?php echo Portal::language('from_date');?> 
             <input  name="from_date" id="from_date" onchange="changevalue();" size="8" style="height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
             <td><?php echo Portal::language('to_date');?>
             <input  name="to_date" id="to_date" size="8" onchange="changefromday();" style="height: 24px; margin-right: 20px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
             <td><input name="check_type" type="hidden" id="check_type" value="<?php echo $this->map['type'];?>" /><input class="w3-btn w3-gray" name="submit" type="submit" value="<?php echo Portal::language('OK');?>" style="height: 24px; padding-top: 5px;"/></td>
          </tr>
        </table>
        </fieldset><br />
        <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase; font-size: 11px;">
			  <th width="20px" align="center"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30px" align="center"><?php echo Portal::language('order_number');?></th>
              <th width="60px" align="center"><?php echo Portal::language('code');?></th>
              <th width="100px" align="center"><?php echo Portal::language('bill_number');?></th>
			  <th width="80px" align="center"><?php echo Portal::language('total');?></th>
			  <th width="130px" align="center"><?php echo Portal::language('payment');?> <?php echo Portal::language('with');?> </th>
			  <th width="170px" align="center"><?php echo Portal::language('room');?></th>
			  <th width="200px" align="center"><?php echo Portal::language('note');?></th>
			  <th width="100px" align="center"><?php echo Portal::language('status');?></th>
			  <th width="80px" align="center"><?php echo Portal::language('create_time');?></th>
			  <th width="100px" align="center"><?php echo Portal::language('create_user');?></th>
			  <th width="80px" align="center"><?php echo Portal::language('lastest_edited_time');?></th>
			  <th width="100px" align="center"><?php echo Portal::language('lastest_edited_user');?></th>
			  <th width="50px"><?php echo Portal::language('view');?></th>
              <th width="50px"><?php echo Portal::language('edit');?></th>
		      <th width="50px"><?php echo Portal::language('delete');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr>
			  <td><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
			  <td style="text-align: center;"><?php echo $this->map['items']['current']['i'];?></td>
				<td><?php echo $this->map['items']['current']['bill_number'];?></td>
                <td><?php echo $this->map['items']['current']['code'];?></td>
				<td align="right"><?php echo $this->map['items']['current']['total'];?></td>
				<td style="text-align: center;"><?php echo $this->map['items']['current']['payment_type'];?></td>
				<td><strong><?php echo $this->map['items']['current']['room_name'];?></strong><br />
		      <?php echo Portal::language('arrival');?>: <?php echo $this->map['items']['current']['arrival_date'];?>-<?php echo $this->map['items']['current']['departure_date'];?> </td>
		        <td><?php echo $this->map['items']['current']['note'];?></td>
		        <td style="text-align: center;"><?php echo $this->map['items']['current']['status'];?></td>
		        <td style="text-align: center;"><?php echo $this->map['items']['current']['time'];?></td>
               <td style="text-align: center;"><?php echo $this->map['items']['current']['user_id'];?></td>
			   <td style="text-align: center;"><?php echo $this->map['items']['current']['lastest_edited_time'];?></td>
			   <td style="text-align: center;"><?php echo $this->map['items']['current']['lastest_edited_user_id'];?></td>
               <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'view_receipt','id'=>$this->map['items']['current']['id']));?>" target="_blank" title="<?php echo Portal::language('view_receipt');?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></a></td>
              <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'edit','type'=>$this->map['items']['current']['type'],'id'=>$this->map['items']['current']['id']));?>" target="_blank" title="<?php echo Portal::language('edit');?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></a></td>
			    <?php 
				if(($this->map['items']['current']['mice_invoice']))
				{?><!--<td> <a class="delete-one-item" href="<?php //echo Url::build_current(array('cmd'=>'delete','type'=>$this->map['items']['current']['type'],'id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>-->
				<?php
				}
				?>
                <td style="text-align: center;"> <a href="<?php echo Url::build_current(array('cmd'=>'delete','type'=>$this->map['items']['current']['type'],'id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a></td>
            </tr>
		  <?php }}unset($this->map['items']['current']);} ?>			
		</table>
<br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
    <input  name="type" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('type'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
    jQuery(".paging a").each(function(){
        var url='&type=<?php echo Url::get('type'); ?>';
        jQuery(this).attr('href',jQuery(this).attr('href')+url);
    });
	jQuery("#time").datepicker();
	jQuery("#from_date").datepicker();
	jQuery("#to_date").datepicker();
	jQuery("#delete_button").click(function (){
		ListExtraServiceInvoiceForm.cmd.value = 'delete';
        ListExtraServiceInvoiceForm.type.value = <?php echo '\''.Url::get('type').'\''; ?>;
		ListExtraServiceInvoiceForm.submit();

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