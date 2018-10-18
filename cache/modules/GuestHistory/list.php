<script>
	traveller_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	}
</script>
<style>
	.link_url{
		color:#03F;	
	}
    #hover:hover{
        background: #cccccc;
    }
</style>
<table cellpadding="15" cellspacing="0" width="100%" border="0" class="table-bound">
		<tr>
        	<td width="90%" class="form-title"><?php echo Portal::language('guest_history');?></td>
        </tr>
</table> 
<table bgcolor="#FFFFFF" cellspacing="0" width="100%">
<tr>
	<td width="100%" align="center" > 
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<form name="SearchGuestHistoryForm" method="post">
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
                    <td>
                        <?php echo Portal::language('full_name');?>:<input  name="traveller_name" id="traveller_name" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('traveller_name'));?>">
                        <?php echo Portal::language('birthday');?>:<input  name="birthday" id="birthday" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('birthday'));?>">
                        <?php echo Portal::language('gender');?>:<select  name="gender" id="gender" style="width:80px"><?php
					if(isset($this->map['gender_list']))
					{
						foreach($this->map['gender_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))
                    echo "<script>$('gender').value = \"".addslashes(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))."\";</script>";
                    ?>
	</select>
                        <?php echo Portal::language('nationality');?>:<select  name="nationality_id" id="nationality_id" style="width:100px"><?php
					if(isset($this->map['nationality_id_list']))
					{
						foreach($this->map['nationality_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))
                    echo "<script>$('nationality_id').value = \"".addslashes(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))."\";</script>";
                    ?>
	</select>
                        <?php echo Portal::language('company_name');?>:<input  name="customer_name" id="customer_name" onfocus="customerAutocomplete();" style="width:205px" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>">
                        <?php echo Portal::language('booking_code');?>:<input  name="booking_code" id="booking_code" style="width:60px" / type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>">
                    </td> 
                    
				</tr>
                <tr>
                    <td >
                        <!--Start Luu Nguyen Giap add portal -->
                        <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                        <?php echo Portal::language('hotel');?>
                        <select  name="portal_id" id="portal_id" onchange="SearchGuestHistoryForm.submit()"><?php
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
                        <?php //}?>
                        <!--End Luu Nguyen Giap add portal -->
                        <?php echo Portal::language('from_day');?>:<input  name="arrival_date" id="arrival_date" onchange="changevalue();" style="width:70px" / type ="text" value="<?php echo String::html_normalize(URL::get('arrival_date'));?>">
                        <?php echo Portal::language('to_day');?>:<input  name="departure_date" id="departure_date" onchange="changefromday();" style="width:70px" onchange="check_validate_time();" / type ="text" value="<?php echo String::html_normalize(URL::get('departure_date'));?>">
                        <?php echo Portal::language('passport');?>:<input  name="passport" id="passport" style="width:100px" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>">
                        <?php echo Portal::language('room');?>:<select  name="room_id" id="room_id" style="width:100px"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select>
                        <?php echo Portal::language('status');?>:<select  name="status" id="status" style="width:100px;"><?php
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
                        <?php echo Portal::language('RE_code');?>:<input  name="code" id="code" style="width:30px" / type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                        <label><?php echo Portal::language('number_of_guests_at_the_hotel');?> </label><input  name="count_traveller" style="width:40px;text-align:center;"/ type ="text" value="<?php echo String::html_normalize(URL::get('count_traveller'));?>">
                        <input type="submit" value="<?php echo Portal::language('search');?>" class="button-medium search" style="float:right;" />                                                                        
                    </td>                                    
				</tr>                        
			</table>            
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        <input name="export_excel" id="export_excel" type="button" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 100px; height: 23px;"/>
		</fieldset><br />        
		<div class="content">        
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
		<tr class="table-header">
         <th width="1%" ><?php echo Portal::language('re_code');?></th>
				<th align="left"  align="right" style="width: 230px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.birth_date' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.birth_date'));?>" title="<?php echo Portal::language('sort');?>">
                  <?php if(URL::get('order_by')=='traveller.birth_date') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  <?php echo Portal::language('TA_company');?></a></th>
                <th align="left" style="width: 200px; text-align:center"><?php echo Portal::language('email');?> - <?php echo Portal::language('phone');?> - <?php echo Portal::language('address');?></th>
				<th align="left" style="width: 120px; text-align:center"><?php echo Portal::language('nationality');?></th>
				<th align="left" style="width: 90px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.passport' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.passport'));?>" title="<?php echo Portal::language('sort');?>">
                  <?php if(URL::get('order_by')=='traveller.passport') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  <?php echo Portal::language('passport');?></a></th>
				<th align="left" style="width: 75px; text-align:center"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='time_in' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_in'));?>" title="<?php echo Portal::language('sort');?>">
                  <?php if(URL::get('order_by')=='time_in') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  <?php echo Portal::language('date_in');?></a></th>
				<th align="left" style="width: 75px; text-align:center">
					<a href="<?php echo URL::build_current(((URL::get('order_by')=='time_out' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_out'));?>" title="<?php echo Portal::language('sort');?>">
					<?php if(URL::get('order_by')=='time_out') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('date_out');?></a></th>
				<th align="left" style="width: 150px; text-align:center"><?php echo Portal::language('room');?></th>
                <th align="left" style="width: 50px; text-align:center"><?php echo Portal::language('created_person');?></th>
                <th align="left" style="width: 70px; text-align:center"><?php echo Portal::language('to_judge');?></th>
				<th align="left" style="width: 150px; text-align:center"><?php echo Portal::language('note');?></th>
			</tr>
			<?php $temp = '';$i=1;?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
            <?php if($temp!=$this->map['items']['current']['full_name']){$temp == $this->map['items']['current']['full_name'];?>
            <?php }?>
			<tr style="background-color: whitesmoke;">
             <th width="1%" ></th>
				<td><a href="#" class="link_url" onClick="window.open('?page=traveller&id=<?php echo $this->map['items']['current']['id'];?>');">
				<strong><?php echo $i;$i++;?>. </strong><?php echo $this->map['items']['current']['gender'];?>. <strong><?php echo $this->map['items']['current']['full_name'];?> (<?php echo count($this->map['items']['current']['vn_time']); ?>)</strong>
                <?php if($this->map['items']['current']['birth_date']!=null)echo '<br/>'.$this->map['items']['current']['birth_date'];?></a>
                </td>
                <td align="left" style="text-align:left">
                    <?php 
				if(($this->map['items']['current']['traveller_email'] != ''))
				{?><?php echo $this->map['items']['current']['traveller_email'];?><br />
				<?php
				}
				?>
                    <?php 
				if(($this->map['items']['current']['traveller_phone'] != ''))
				{?><em>phone: </em><?php echo $this->map['items']['current']['traveller_phone'];?><br />
				<?php
				}
				?>
                    <?php echo $this->map['items']['current']['address'];?>
                </td>
				<td align="left" style="text-align:center"><?php echo $this->map['items']['current']['nationality'];?></td>
				<td align="left" style="text-align:center"> <?php echo $this->map['items']['current']['passport'];?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php if(isset($this->map['items']['current']['vn_time']) and is_array($this->map['items']['current']['vn_time'])){ foreach($this->map['items']['current']['vn_time'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['vn_time']['current'] = &$item3;?>
                <tr id="hover">
                    <td width="1%" align="center" nowrap="nowrap"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['vn_time']['current']['reservation_id']));?>"><?php echo $this->map['items']['current']['vn_time']['current']['reservation_id'];?></a></td>
                    <td>
                        <!--<i><?php echo Portal::language('stay_time');?>:</i><?php echo $this->map['items']['current']['vn_time']['current']['arrival_time'];?> - <?php echo $this->map['items']['current']['vn_time']['current']['departure_time'];?>-->
                        <?php if($this->map['items']['current']['vn_time']['current']['customer_name']!=null) echo '<strong>'.$this->map['items']['current']['vn_time']['current']['customer_name'].'</strong>';?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $this->map['items']['current']['vn_time']['current']['arrival_time'];?></td>
                    <td><?php echo $this->map['items']['current']['vn_time']['current']['departure_time'];?></td>
                    <td>
                        <?php echo '<strong> '.$this->map['items']['current']['vn_time']['current']['room_name'].'</strong>('.$this->map['items']['current']['vn_time']['current']['status'].') - '.System::display_number($this->map['items']['current']['vn_time']['current']['price']);?>
                    </td>
                    <td><?php echo $this->map['items']['current']['vn_time']['current']['user_id'];?></td>
                    <td>
                        <?php if($this->map['items']['current']['vn_time']['current']['to_judge']>0)
                            {
                                for($i=1;$i<=$this->map['items']['current']['vn_time']['current']['to_judge'];$i++)
                                {    
                        ?>
                                <img style="height: 13px; width: auto; float: left;" src="resources\default\sao.png" />
                        <?php }}?>
                        <!--<?php echo $this->map['items']['current']['vn_time']['current']['to_judge'];?><img style="height: 13px; width: auto; float: left;" src="resources\default\sao.png" />-->
                    </td>
                    <td><?php echo $this->map['items']['current']['vn_time']['current']['note'];?></td>
                </tr>
            <?php }}unset($this->map['items']['current']['vn_time']['current']);} ?>
				
			<?php }}unset($this->map['items']['current']);} ?>
	  </table>
      
      <!--Export excel -->
        <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC" id="Export" style="display: none;">
    		<tr class="table-header">
             <th width="1%" ><?php echo Portal::language('re_code');?></th>
    				<th align="left"  align="right" style="width: 230px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.birth_date' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.birth_date'));?>" title="<?php echo Portal::language('sort');?>">
                      <?php if(URL::get('order_by')=='traveller.birth_date') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				  <?php echo Portal::language('TA_company');?></a></th>
                    <th align="left" style="width: 200px; text-align:center"><?php echo Portal::language('email');?> - <?php echo Portal::language('phone');?> - <?php echo Portal::language('address');?></th>
    				<th align="left" style="width: 120px; text-align:center"><?php echo Portal::language('nationality');?></th>
    				<th align="left" style="width: 90px; text-align:center"><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.passport' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.passport'));?>" title="<?php echo Portal::language('sort');?>">
                      <?php if(URL::get('order_by')=='traveller.passport') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				  <?php echo Portal::language('passport');?></a></th>
    				<th align="left" style="width: 75px; text-align:center"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='time_in' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_in'));?>" title="<?php echo Portal::language('sort');?>">
                      <?php if(URL::get('order_by')=='time_in') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				  <?php echo Portal::language('date_in');?></a></th>
    				<th align="left" style="width: 75px; text-align:center">
    					<a href="<?php echo URL::build_current(((URL::get('order_by')=='time_out' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_out'));?>" title="<?php echo Portal::language('sort');?>">
    					<?php if(URL::get('order_by')=='time_out') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('date_out');?></a></th>
    				<th align="left" style="width: 150px; text-align:center"><?php echo Portal::language('room');?></th>
                    <th align="left" style="width: 50px; text-align:center"><?php echo Portal::language('created_person');?></th>                    
    				<th align="left" style="width: 150px; text-align:center"><?php echo Portal::language('note');?></th>
    			</tr>
    			<?php $temp = '';$i=1;?>
                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current'] = &$item4;?>
                <?php if($temp!=$this->map['items']['current']['full_name']){$temp == $this->map['items']['current']['full_name'];?>
                <?php }?>
    			<tr style="background-color: whitesmoke;">
                 <th width="1%" ></th>
    				<td><a href="#" class="link_url" onClick="window.open('?page=traveller&id=<?php echo $this->map['items']['current']['id'];?>');">
    				<strong><?php echo $i;$i++;?>. </strong><?php echo $this->map['items']['current']['gender'];?>. <strong><?php echo $this->map['items']['current']['full_name'];?> (<?php echo count($this->map['items']['current']['vn_time']); ?>)</strong>
                    <?php if($this->map['items']['current']['birth_date']!=null)echo '<br/>'.$this->map['items']['current']['birth_date'];?></a>
                    </td>
                    <td align="left" style="text-align:left">
                        <?php 
				if(($this->map['items']['current']['traveller_email'] != ''))
				{?><?php echo $this->map['items']['current']['traveller_email'];?><br />
				<?php
				}
				?>
                        <?php 
				if(($this->map['items']['current']['traveller_phone'] != ''))
				{?><em>phone: </em><?php echo $this->map['items']['current']['traveller_phone'];?><br />
				<?php
				}
				?>
                        <?php echo $this->map['items']['current']['address'];?>
                    </td>
    				<td align="left" style="text-align:center"><?php echo $this->map['items']['current']['nationality'];?></td>
    				<td align="left" style="text-align:center"> <?php echo $this->map['items']['current']['passport'];?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php if(isset($this->map['items']['current']['vn_time']) and is_array($this->map['items']['current']['vn_time'])){ foreach($this->map['items']['current']['vn_time'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current']['vn_time']['current'] = &$item5;?>
                    <tr id="hover">
                        <td width="1%" align="center" nowrap="nowrap"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['vn_time']['current']['reservation_id']));?>"><?php echo $this->map['items']['current']['vn_time']['current']['reservation_id'];?></a></td>
                        <td>
                            <!--<i><?php echo Portal::language('stay_time');?>:</i><?php echo $this->map['items']['current']['vn_time']['current']['arrival_time'];?> - <?php echo $this->map['items']['current']['vn_time']['current']['departure_time'];?>-->
                            <?php if($this->map['items']['current']['vn_time']['current']['customer_name']!=null) echo '<strong>'.$this->map['items']['current']['vn_time']['current']['customer_name'].'</strong>';?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $this->map['items']['current']['vn_time']['current']['arrival_time'];?></td>
                        <td><?php echo $this->map['items']['current']['vn_time']['current']['departure_time'];?></td>
                        <td>
                            <?php echo '<strong> '.$this->map['items']['current']['vn_time']['current']['room_name'].'</strong>('.$this->map['items']['current']['vn_time']['current']['status'].') - '.System::display_number($this->map['items']['current']['vn_time']['current']['price']);?>
                        </td>
                        <td><?php echo $this->map['items']['current']['vn_time']['current']['user_id'];?></td>                                                
                        <td><?php echo $this->map['items']['current']['vn_time']['current']['note'];?></td>
                    </tr>
                <?php }}unset($this->map['items']['current']['vn_time']['current']);} ?>    				
    			<?php }}unset($this->map['items']['current']);} ?>
    	  </table>
      <!--Export excel -->
	  <input  name="cmd" type ="hidden" id="d" value="<?php echo String::html_normalize(URL::get('cmd',''));?>">
	  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	  </div>
	</td>
</tr>
</table>
<div class="paging"><?php echo $this->map['paging'];?></div>
<script>
        function customerAutocomplete()
    {
    	jQuery("#customer_name").autocomplete({
             url: 'get_customer_search.php?customer=1',
        onItemSelect: function(item) {
    			//getCustomerFromCode(jQuery("#customer_id").val());
                console.log('test');
                //document.waitingbooklist.submit();
    		}
        }) 
    }
	jQuery("#arrival_date").datepicker();
	jQuery("#departure_date").datepicker();
    jQuery("#birthday").datepicker();
	jQuery('#company_name').autocomplete({
	url:'r_get_customer.php?name=1',
			onItemSelect: function(item) {
		},
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}	
});
    
    function changevalue()
    {
        var myfromdate = $('arrival_date').value.split("/");
        var mytodate = $('departure_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#departure_date").val(jQuery("#arrival_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('arrival_date').value.split("/");
        var mytodate = $('departure_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#arrival_date").val(jQuery("#departure_date").val());
        }
    }
    jQuery('#export_excel').click(function(){
        jQuery("#Export").battatech_excelexport({
            containerid: "Export"
           , datatype: 'table'
           , fileName: '<?php echo Portal::language('guest_history');?>'
        });
    });    
</script>
