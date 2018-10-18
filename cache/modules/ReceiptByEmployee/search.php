<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css"/>
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title"><?php echo Portal::language('receipt_by_employee_report');?></font>
		<br>
		<form name="SearchForm" id="SearchForm" method="post">
   		<?php if(User::can_admin(false,ANY_CATEGORY)){?>
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
        <table style="margin: 0 auto;">
            <tr>
             		<td align="right"> <input  name="checked_all" id="checked_all" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('checked_all'));?>"></td> 
                    <td align="left"><b><label for="checked_all"><?php echo Portal::language('select_all_bar');?></label></b></td>
             </tr>
             <?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key1=>&$item1){if($key1!='current'){$this->map['bars']['current'] = &$item1;?>
             <tr >
             	<?php 
				if((Session::is_set('bar_id') and Session::get('bar_id')==$this->map['bars']['current']['id']))
				{?>
                	<td align="right"> <input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>" value="<?php echo String::html_normalize(URL::get('bar_id_'.$this->map['bars']['current']['id'],$this->map['bars']['current']['id']));?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" class="check_box" checked="checked"  / type ="checkbox"></td>
                 <?php }else{ ?>
                  <td align="right"><input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>"  type="checkbox" value="<?php echo $this->map['bars']['current']['id'];?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" <?php if(isset($_REQUEST['bar_id_'.$this->map['bars']['current']['id']])) echo 'checked="checked"' ?> class="check_box"  /></td>
                
				<?php
				}
				?>
                  <td><label for="bar_id_<?php echo $this->map['bars']['current']['id'];?>"><?php echo $this->map['bars']['current']['name'];?></label></td>
    		 </tr>
             <?php }}unset($this->map['bars']['current']);} ?>
		  </table>
        <?php }?>
		<table><tr><td>
		<fieldset><legend><?php echo Portal::language('time_select');?></legend>
		<table border="0">
			<tr>
                <td align="right"><?php echo Portal::language('from_day');?>:</td>
                <td><input  name="date_from" id="date_from" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
            </tr>
            <tr>
                <td align="right"><?php echo Portal::language('to_day');?>:</td>
                <td><input  name="date_to" id="date_to" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
            
	    
	    
	    
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
			  <td>
                <input  name="start_time" id="start_time" style="width:40px;" onblur="validate_time(this,this.value);" / type ="text" value="<?php echo String::html_normalize(URL::get('start_time'));?>">
                <?php echo Portal::language('to');?>
                <input  name="end_time" id="end_time" style="width:40px;" onblur="validate_time(this,this.value);" / type ="text" value="<?php echo String::html_normalize(URL::get('end_time'));?>">
              </td>
			 </tr>
			<tr>
            <tr>
			  <td align="left"><?php echo Portal::language('user');?></td>
			  <td><select  name="user_id" id="user_id" style="width:180px;"><?php
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
			<tr>
			  <td colspan="2" nowrap="nowrap">&nbsp;</td>
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
    jQuery("#checked_all").click(function ()
    {
        var check  = this.checked;
        jQuery(".check_box").each(function()
        {
            this.checked = check;
        });
    });
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
	//$('hotel_id').value = '<?php echo PORTAL_ID;?>';
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
    
    function validate_time(obj,value)
    {
        if(value != "__:__")
        {
            var arr = value.split(":")
            var h = arr[0];
            var m = arr[1];
            if(is_numeric(h.toString()))
            {
                if(h>23)
                {
                    alert('<?php echo Portal::language('invalid_time');?>');
                    jQuery(obj).val('');
                    return false;    
                }
            }
            if(is_numeric(m.toString()))
            {
                if(m>59)
                {
                    alert('<?php echo Portal::language('invalid_time');?>');
                    jQuery(obj).val('');
                    return false;    
                }
            }  
        }
    }
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
    //luu nguyen giap add search bars with portal_id
    function get_bar(object)
    {
        document.getElementById('SearchForm').submit();
    }
    //end giap
</script>