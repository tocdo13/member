<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
        <h2 class="report-title-new"><?php echo Portal::language('restaurant_order_revenue_less_than_200_report');?></h2>
		<br/>
		<form name="SearchForm" id="SearchForm" method="post">
   		<?php //if(User::can_admin(false,ANY_CATEGORY)){?>
        <div style="margin-top:10px;"><label for="hotel_id"><?php echo Portal::language('Hotel');?>:</label> <select  name="hotel_id" id="hotel_id" onchange="get_bar(this);"><?php
					if(isset($this->map['hotel_id_list']))
					{
						foreach($this->map['hotel_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('hotel_id',isset($this->map['hotel_id'])?$this->map['hotel_id']:''))
                    echo "<script>$('hotel_id').value = \"".addslashes(URL::get('hotel_id',isset($this->map['hotel_id'])?$this->map['hotel_id']:''))."\";</script>";
                    ?>
	</select></div>
        <?php // }?>
		<table><tr><td>
		<fieldset><legend><?php echo Portal::language('time_select');?></legend>
		<table border="0">
			<tr>
                <td ><?php echo Portal::language('from_day');?>:</td>
                <td colspan="2"><input  name="date_from" id="date_from" onchange="changevalue();" class="input-long-text" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
            </tr>
            <tr>
                <td ><?php echo Portal::language('to_day');?>:</td>
                <td colspan="2"><input  name="date_to" id="date_to" onchange="changefromday();" class="input-long-text" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
            </tr>
             <tr>
                    <td></td>
             		<td  style="width: 25px;"> <input  name="checked_all" id="checked_all" onclick="jQuery('.check_box').attr('checked',jQuery(this).attr('checked'));" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('checked_all'));?>"></td> 
                    <td align="left"><b><label for="checked_all"><?php echo Portal::language('select_all_bar');?></label></b></td>
             </tr>
             <?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key1=>&$item1){if($key1!='current'){$this->map['bars']['current'] = &$item1;?>
             <tr>
                <td></td>
             	<?php 
				if((Session::is_set('bar_id') and Session::get('bar_id')==$this->map['bars']['current']['id']))
				{?>
                	<td > <input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>" value="<?php echo String::html_normalize(URL::get('bar_id_'.$this->map['bars']['current']['id'],$this->map['bars']['current']['id']));?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" class="check_box" checked="checked"  / type ="checkbox"></td>
                 <?php }else{ ?>
                  <td ><input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>" value="<?php echo String::html_normalize(URL::get('bar_id_'.$this->map['bars']['current']['id'],$this->map['bars']['current']['id']));?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" class="check_box" / type ="checkbox"></td>
                
				<?php
				}
				?>
                  <td><label for="bar_id_<?php echo $this->map['bars']['current']['id'];?>"><?php echo $this->map['bars']['current']['name'];?></label></td>
			 </tr>
             <?php }}unset($this->map['bars']['current']);} ?>
            <!--
            <tr>
			  <td align="left"><?php echo Portal::language('bar_shift');?></td>
			  <td><select  name="shift_id" id="shift_id" style="width:180px;"><?php
					if(isset($this->map['shift_id_list']))
					{
						foreach($this->map['shift_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('shift_id',isset($this->map['shift_id'])?$this->map['shift_id']:''))
                    echo "<script>$('shift_id').value = \"".addslashes(URL::get('shift_id',isset($this->map['shift_id'])?$this->map['shift_id']:''))."\";</script>";
                    ?>
	<?php echo $this->map['shift_list'];?></select></td>
			  </tr>
              -->
            <tr>
			  <td align="left"><?php echo Portal::language('by_time');?></td>
			  <td colspan="2">
                <input  name="start_time" id="start_time" class="input-short-text" onblur="validate_time(this,this.value);" / type ="text" value="<?php echo String::html_normalize(URL::get('start_time'));?>">
                <?php echo Portal::language('to');?>
                <input  name="end_time" id="end_time" class="input-short-text"  onblur="validate_time(this,this.value);" / type ="text" value="<?php echo String::html_normalize(URL::get('end_time'));?>">
              </td>
			 </tr>
             <tr>
			  <td  align="left" nowrap="nowrap"><?php echo Portal::language('user_status');?></td>
			  <td colspan="2">
                  <!-- 7211 -->  
                  <select  style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                    <option value="1">Active</option>
                    <option value="0">All</option>
                  </select>
                  <!-- 7211 end--> 
              </td>
		    </tr>
            <tr>
			  <td align="left"><?php echo Portal::language('user');?></td>
			  <td colspan="2"><select  name="user_id" id="user_id" class="input-long-text"><?php
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
	</select></td>
			  </tr>
            <!--
            <tr>
                  <td align="left"><?php echo Portal::language('vat_code');?>: </td>
                  <td align="left"><input  name="from_code" id="from_code" class="input_number" style="width:75px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_code'));?>"> <?php echo Portal::language('to');?>: <input  name="to_code" id="to_code" class="input_number" style="width:75px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_code'));?>"></td>
            </tr>
            -->
            			<tr>
			  <td colspan="2" nowrap="nowrap">&nbsp;</td>
			  </tr>
              
		  </table>
		  </fieldset>
		  </td></tr></table>
			<p>
				<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  " id="do_search" onclick=" return check_bar();"/>
				<input type="button" value="  <?php echo Portal::language('cancel');?>  " onclick="location='<?php echo Url::build('home');?>';"/>
			</p>
            <input type="hidden" name="line_per_page" value="30"/>
            <input type="hidden" name="no_of_page" value="300"/>
            <input type="hidden" name="start_page" value="1"/>
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
<script type="text/javascript">
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
	var bars_list = <?php echo $this->map['bar_lists'];?>;
	getBarShift(<?php echo $this->map['bar_id'];?>);
	<?php 
				if(($this->map['view_all']!=1))
				{?>
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#to_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	
				<?php
				}
				?>
	//$('hotel_id').value = '<?php //echo PORTAL_ID;?>';
	function getBarShift(id){
		for(var i in bars_list){
			if(bars_list[i]['id'] == id){
				jQuery('#shift_id').html('<option value="0">-------</option>');
				for(var j in bars_list[i]['shifts']){
					var shifts = bars_list[i]['shifts'][j];
					jQuery('#shift_id').append('<option value="'+shifts['id']+'">'+shifts['name']+': '+shifts['brief_start_time']+'h - '+shifts['brief_end_time']+'h </option>');
				}
			}
		}
	}
    
    jQuery('#start_time').mask("99:99");
    jQuery('#end_time').mask("99:99");
    
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
    	});
        return check;
    }
    
    function check_bar()
    {
        //start:KID them doan nay vao check valid time
        var hour_from = (jQuery("#start_time").val().split(':'));
        var hour_to = (jQuery("#end_time").val().split(':'));
        var date_from_arr = jQuery("#date_from").val();
        var date_to_arr = jQuery("#date_to").val();
        //end:KID them doan nay vao check valid time
        var validate = test_checked();
        if( validate)
        {
            //start:KID them doan nay vao check valid time
            if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
            {
                alert('<?php echo Portal::language('start_time_longer_than_end_time_try_again');?>');
                return false;
            }
            else
            {
                if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
                {
                    alert('<?php echo Portal::language('the_max_time_is_2359_try_again');?>');
                    return false;
                }
                else
                {
                    return true;
                }
            }
            //end:KID them doan nay vao check valid time
        }
        else
        {
            alert('<?php echo Portal::language('you_must_choose_bar');?>');
            return false;

        }
    }
    jQuery("#checked_all").click(function (){
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });   
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
</script>
