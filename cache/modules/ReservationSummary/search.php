<style></style>
<?php
$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
$this->link_js('packages/core/includes/js/jquery/datepicker.js');
?>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css"/>
	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="100%">
			<strong><?php echo Portal::get_setting('company_name');?></strong><br /><?php echo Portal::get_setting('company_address');?></td>
			<td align="center" nowrap>
			<strong><?php echo Portal::language('template_code');?></strong><br />
			<i><?php echo Portal::language('promulgation');?></i>
			</td>
		</tr>	
	</table>
	<table align="center" width="100%">
	<tr>
		<td align="center" style="border:1px dotted #CCCCCC;">
		<p>&nbsp;</p>
        <h2 class="report-title-new"><?php echo Portal::language('reservation_summary');?></h2>
		<br/>
		<form name="SearchForm" method="post">
		<table>
		  <tr>
		    <td>&nbsp;</td>
		    </tr>
		  <tr>
		    <td align="center"><?php echo Portal::language('hotel');?>: 
		      <select  name="portal_id" id="portal_id"><?php
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
		  <tr><td>
		<fieldset><legend><?php echo Portal::language('time_select');?></legend>
		<table border="0" align="center">
        <tr>
                   <td><?php echo Portal::language('status');?>:</td> 
                    <td><input type="radio" name="status_traveller" value="1"/> CF<input type="radio" name="status_traveller" value="2"/> TE</td>
        </tr>
		<tr>
			  <td nowrap="nowrap"><?php echo Portal::language('today');?></td>
			  <td nowrap="nowrap"><label>
			    <input type="checkbox" name="today" id="today" value="1" onclick="check_fun(this.checked,'today');"/>
			  </label></td>
			  <td nowrap="nowrap">&nbsp;</td>
			  <td nowrap="nowrap"><?php echo Portal::language('view_by_arrival_time');?> 
              <input type="checkbox" name="view_by_arrival_time" id="view_by_arrival_time" value="1" onclick="check_fun(this.checked,'view_by_arrival_time');"/></td>
			  <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
			  <td nowrap="nowrap"><?php echo Portal::language('view_by_occupied_time');?> <input type="checkbox" name="view_by_occupied_time" id="view_by_occupied_time" value="1" onclick="check_fun(this.checked,'view_by_occupied_time');"/></td>
			  <td nowrap="nowrap">&nbsp;</td>
		  </tr>
		 </table>
		 <br>
		<table border="0" align="center" id="select_time">
			<tr>
                <td><?php echo Portal::language('from_date');?>:<input  name="from_date" id="from_date" onchange="fun_check_date(this);" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                <td><?php echo Portal::language('to_date');?>:<input  name="to_date" id="to_date" onchange="fun_check_date(this);" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
            </tr>
		</table>
        <script>
            jQuery("#from_date").datepicker();
            jQuery("#to_date").datepicker();
            function fun_check_date(obj){
                var from_date = jQuery("#from_date").val().split("/");
                var to_date = jQuery("#to_date").val().split("/");
                if(from_date == ''){
                    jQuery("#from_date").focus();
                }
                if(to_date == ''){
                    jQuery("#to_date").focus();
                }
                var arr_from_date = new Date(from_date[2],from_date[1],from_date[0]);
                var arr_to_date = new Date(to_date[2],to_date[1],to_date[0]);
                if(arr_from_date>arr_to_date){
                    jQuery("#from_date").val(obj.value);
                    jQuery("#to_date").val(obj.value);
                }
            }
        </script>
        <br />
			<table width="100%">
			<tr>
			  <td align="center" nowrap="nowrap">
				<table border="0" cellspacing="0" cellpadding="0">
                <tr>
					<td align="right"><?php echo Portal::language('customer_group_1');?>:&nbsp;</td>
					<td><select  name="customer_group" id="customer_group" class="input-long-text""><?php
					if(isset($this->map['customer_group_list']))
					{
						foreach($this->map['customer_group_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_group',isset($this->map['customer_group'])?$this->map['customer_group']:''))
                    echo "<script>$('customer_group').value = \"".addslashes(URL::get('customer_group',isset($this->map['customer_group'])?$this->map['customer_group']:''))."\";</script>";
                    ?>
	</select></td>
				<tr>
				<tr>
					<td align="right"><?php echo Portal::language('company');?>:&nbsp;</td>
					<td><input  name="customer_name" id="customer_name" class="input-long-text" onfocus="customerAutocomplete();"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"></td>
				<tr>
                  <td align="right"><?php echo Portal::language('booking_code');?>:&nbsp;</td>
				  <td><input  name="booking_code" id="booking_code" class="input-long-text" / type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
				  </tr>
				<tr>
					<td align="right"><?php echo Portal::language('status');?>:&nbsp;</td>
					<td><select   name="status" id="status" class="input-long-text">
					<option value="0">All</option>
                    <option value="ALL_CANCEL" selected="selected">ALL(-CANCEL,NOSHOW)</option>
			     	<option value="BOOKED">BOOKED</option>
					<option value="CANCEL">CANCEL</option>
                    <option value="NOSHOW">NOSHOW</option>
			      	<option value="CHECKIN">CHECKIN</option>
			      	<option value="CHECKOUT">CHECKOUT</option>
                    
			     </select></td>
				</tr>
                <tr>
                    <td align="right"><label><?php echo Portal::language('user_status');?></label></td>
                    <td>
                        <!-- 7211 -->  
                        <select  style="" name="user_status" id="user_status" class="input-long-text">
                            <option value="1">Active</option>
                            <option value="0">All</option>
                        </select>
                        <!-- 7211 end-->
                    </td>
                </tr>
				<tr>
					<td align="right"><?php echo Portal::language('user_id');?>:&nbsp;</td>
					<td> <select  name="user_id" id="user_id"class="input-long-text"><?php
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
	</select>	</td>
				</tr>
				</table>
				 </td>
			  </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			
			<p>
				<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/>
				<input type="button" value="  <?php echo Portal::language('cancel');?>  " onclick="location='<?php echo Url::build('home');?>';"/>
			</p>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td></tr></table>
	</div>
	</div>
</td>
</tr></table>
<script>
$('portal_id').value = '<?php echo PORTAL_ID;?>';
<?php 
				if((Url::get('today')))
				{?>
	$('select_time').style.display='none';

				<?php
				}
				?>

    function customerAutocomplete()
    {
        var customer_group=jQuery("#customer_group").val();
    	jQuery("#customer_name").autocomplete({
             url: 'get_customer_search.php?customer=1_'+customer_group,
        onItemSelect: function(item) {}
        }) 
    }
    function check_fun(check,type)
    {
        if(check==true)
        {
            if(type=='view_by_occupied_time')
            {
                $('select_time').style.display='';
                jQuery('#today').attr('checked',false);
                jQuery('#view_by_arrival_time').attr('checked',false);
            }
            else if(type=='view_by_arrival_time')
            {
                $('select_time').style.display='';
                jQuery('#today').attr('checked',false);
                jQuery('#view_by_occupied_time').attr('checked',false);
            }
            else if(type=='today')
            {
                $('select_time').style.display='none';
                jQuery('#view_by_occupied_time').attr('checked',false);
                jQuery('#view_by_arrival_time').attr('checked',false);
            }
        }
        else
        {
            if(type=='today')
            {
                $('select_time').style.display='';
            }
        }
    }
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
