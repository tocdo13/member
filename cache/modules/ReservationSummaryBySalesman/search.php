<?php
$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
$this->link_js('packages/core/includes/js/jquery/datepicker.js');
?>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table width="100%" height="100%" bgcolor="#B5AEB5" cellspacing="0" cellpadding="5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellspacing="0" cellpadding="5" width="100%" style="font-family:'Times New Roman', Times, serif">
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
        <h2 class="report-title-new"><?php echo Portal::language('reservation_summary_by_seller');?></h2>
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
		
		
        
        <br />
			<table width="100%">
			<tr>
			  <td align="center" nowrap="nowrap">
				<table border="0" cellspacing="0" cellpadding="5">
                <tr style="display: none;">
                    <td><?php echo Portal::language('view_for_create_date');?>:<input  name="view_create_date" id="view_create_date" checked="checked" onclick="fun_view_date(1);" value="view_create_date" style="display: none;" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('view_create_date'));?>"></td>                    
                    <td><?php echo Portal::language('view_for_arrival_date');?>:<input  name="view_arrival_date" id="view_arrival_date" onclick="fun_view_date(2);" value="view_arrival_date" style="display: none;" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('view_arrival_date'));?>"></td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('from_date');?>:</td>                    
                    <td><input  name="from_date" id="from_date" onchange="changevalue();"class="input-long-text" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                </tr>
                <tr>                   
                    <td><?php echo Portal::language('to_date');?>:</td>
                    <td><input  name="to_date" id="to_date" onchange="changefromday();"class="input-long-text" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                </tr>
                <tr>
					<td align="right"><?php echo Portal::language('customer_group_1');?>:&nbsp;</td>
					<td><select  name="customer_group" id="customer_group" class="input-long-text"><?php
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
					<input  name="customer_id"  id="customer_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                    <td><input  name="customer_name" id="customer_name" class="input-long-text" autocomplete="off" onfocus="customerAutocomplete();"/ type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"></td>
				<tr>
                  <td align="right"><?php echo Portal::language('booking_code');?>:&nbsp;</td>
				  <td><input  name="booking_code" id="booking_code" class="input-long-text" / type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
				  </tr>
				<tr>
					<td align="right"><?php echo Portal::language('status');?>:&nbsp;</td>
					<td><select   name="status" id="status" class="input-long-text">
					<option value="0" selected="selected">All</option>
                    <option value="ALL_CANCEL">ALL(-CANCEL)</option>
			     	<option value="BOOKED">BOOKED</option>
					<option value="CANCEL">CANCEL</option>
			      	<option value="CHECKIN">CHECKIN</option>
			      	<option value="CHECKOUT">CHECKOUT</option>
                    
			     </select></td>
				</tr>
                <tr>
                    <td><label><?php echo Portal::language('user_status');?></label></td>
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
					<td align="right"><?php echo Portal::language('salesman');?>:&nbsp;</td>
					<td> <select  name="user_id" id="user_id" class="input-long-text"><?php
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
</tr>
</table>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
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
    function fun_view_date(id)
    {
        if(id==1)
        {
            if(document.getElementById("view_create_date").checked==true)
                document.getElementById("view_arrival_date").checked=false;
            else
                document.getElementById("view_arrival_date").checked=true;
        }
        else
        {
            if(document.getElementById("view_arrival_date").checked==true)
                document.getElementById("view_create_date").checked=false;
            else
                document.getElementById("view_create_date").checked=true;
        }
    }
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
    function changefromday(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    

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
        onItemSelect: function(item) {
            jQuery("#customer_id").val(item['data'][0]);
        }
        })
    }
</script>