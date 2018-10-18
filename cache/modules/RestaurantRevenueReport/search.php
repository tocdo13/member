<style>
.c_from_time{ width:80px;}
.text-center{text-align: center;}
.lab1{text-align: center;width:55px;display:inline-block;}
.ca1{margin-bottom: 10px;}

.ca1 select{width:120px !important;}
.lab1{display: inline-block;width:65px}
.fld_1{padding:10px;width: 270px;margin-bottom: 10px;}
.fld_1 legend{margin-left:-5px;}
   .b_blur{
    background: #DDD7D7;
} 
.clear{clear:both;}
.k_1{float: left;margin-top: 5px;border: 1px solid #cdcdcd;border-radius: 5px;padding:0 10px 10px;}
</style>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
  <link rel="stylesheet" href="skins/default/report.css">
  <div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
  <div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
  <p>&nbsp;</p>
  <table cellSpacing=0 width="100%">
    <tr valign="top">
      <td align="left" width="65%" style="padding-left:80px;">
      <strong><?php echo HOTEL_NAME;?></strong><br />
      <?php echo HOTEL_ADDRESS;?></td>
      <td align="right" nowrap width="35%" style="padding-right:20px;">
      <strong><?php echo Portal::language('template_code');?></strong><br />
      <i><?php echo Portal::language('promulgation');?></i>
      </td>
    </tr> 
  </table>
  <table cellpadding="0" cellspacing="0" align="center" width="100%">
  <tr><td width="80px">&nbsp;</td>
  <td align="center">
    <p>&nbsp;</p>
    <font class="report_title"><?php echo Portal::language('restaurant_revenue_report');?></font>
    <br>
    <form name="SearchForm" id="SearchForm" method="post">
    <table><tr>
        <td>

   <fieldset style=" padding:10px 20px 20px 20px;border: none;">    
            <!--Start Luu Nguyen Giap-->
           <div class="k_1">
		    <fieldset class="fld_1" style="margin-top:10px;">
            <legend><input  name="search_time" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" type ="checkbox" value="<?php echo String::html_normalize(URL::get('search_time'));?>"><label><?php echo Portal::language('search_for_time');?></label></legend>
            <table>
                
            <tr>
                <td><?php echo Portal::language('from_date');?></td>
                <td><input  name="from_date_tan" id="from_date_tan" class="c_from_time text-center"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date_tan'));?>">
                <label style="padding-left: 5px;display:inline-block;width:45px;margin-bottom:10px;"><?php echo Portal::language('from_time');?></label>
                <input  name="from_time" id="from_time" style="width: 50px; text-align:center;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>">
                </td>
            </tr>
            <tr>
                <td><?php echo Portal::language('to_date');?></td>
                <td><input  name="to_date_tan" id="to_date_tan" class="c_from_time text-center"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date_tan'));?>">
                <label style="padding-left: 5px;display:inline-block;width:45px;" ><?php echo Portal::language('to_time');?></label>
                <input  name="to_time" id="to_time" style="width: 50px;text-align:center;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>">
                </td>
            </tr>
            </table>
          </fieldset>
            <div class="ca1">
            <label class="lab1"><?php echo Portal::language('hotel');?>:&nbsp;</label>
            <select  name="portal_id" id="portal_id" onchange="get_bar(this);"><?php
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
            </div>
             <div class="ca1">
            <label class="lab1"><?php echo Portal::language('category');?>:&nbsp;</label>
            <select  name="category_id" id="category_id" style="width:180px;"><?php
					if(isset($this->map['category_id_list']))
					{
						foreach($this->map['category_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))
                    echo "<script>$('category_id').value = \"".addslashes(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))."\";</script>";
                    ?>
	</select>
            </div>
            <!--End Luu Nguyen Giap-->
            <table border="0" style="float: left;">
            <tr>
            <td colspan="2"><input name="barids" id="barids" type="hidden"/>
            </td>
            </tr>
            <!--Luu Nguyen Giap add search theo nha hang start-->
            <tr>
                <td align="right"><input  name="checked_all" id="checked_all"  type ="checkbox" value="<?php echo String::html_normalize(URL::get('checked_all'));?>"></td> 
                <td align="left"><b><label for="checked_all"><?php echo Portal::language('select_all_bar');?></label></b></td>
            </tr>
            
            <?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key1=>&$item1){if($key1!='current'){$this->map['bars']['current'] = &$item1;?>
            <tr>
                <td align="right"><input  name="bar_id_<?php echo $this->map['bars']['current']['id'];?>" value="<?php echo String::html_normalize(URL::get('bar_id_'.$this->map['bars']['current']['id'],$this->map['bars']['current']['id']));?>" id="bar_id_<?php echo $this->map['bars']['current']['id'];?>" class="check_box"/ type ="checkbox"></td>
                <td><label for="bar_id_<?php echo $this->map['bars']['current']['id'];?>"><?php echo $this->map['bars']['current']['name'];?></label></td>
            </tr>
            <?php }}unset($this->map['bars']['current']);} ?>
            <!--Luu Nguyen Giap add search theo nha hang End-->
            </table>
			<div class="clear"></div>
			 <table>
			  <tr>
				<td align="left"><?php echo Portal::language('line_per_page');?></td>
				<td align="left">&nbsp;</td>
				<td align="left"><input  name="line_per_page" id="line_per_page" value="999" size="4" maxlength="3" style="text-align:center"/ type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>"></td>
			  </tr>
			  <tr>
				<td align="left"><?php echo Portal::language('no_of_page');?></td>
				<td align="left">&nbsp;</td>
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:center"/></td>
			  </tr>
			  <tr>
				<td align="left"><?php echo Portal::language('from_page');?></td>
				<td align="left">&nbsp;</td>
				<td align="left"><input  name="start_page" id="start_page" value="1" size="4" maxlength="4" style="text-align:center"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>"></td>
			  </tr>
			  </table>
           </div>
    <div style="padding: 0 10px;">
           <table> 
				<tr>
					<td colspan="2" nowrap="nowrap">
						<table border="0" cellspacing="0" cellpadding="0">
						

						<tr style="display:none;">
						  <td align="right"><?php echo Portal::language('company');?>:&nbsp;</td>
						  <td><input  name="customer_name" id="customer_name" style="width:180px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"></td>
						</tr>
					  </table>
					  
					  </td>
				</tr>
              <tr>
                <td colspan="2">
                 <fieldset style="margin: 5px 0px;" class="fld_1">
                <legend>
                <input  name="search_invoice" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('search_invoice'));?>"><label><?php echo Portal::language('search_for_invoice');?></label></legend>
                </legend>
                <?php echo Portal::language('bill_id');?>: <input  name="from_bill" id="from_bill" style="width: 50px;" class="input_number text-center b_blur" / type ="text" value="<?php echo String::html_normalize(URL::get('from_bill'));?>">
                 <?php echo Portal::language('to');?>: <input  name="to_bill" id="to_bill" style="width: 50px;" class="input_number text-center b_blur" / type ="text" value="<?php echo String::html_normalize(URL::get('to_bill'));?>">
            </fieldset>   
                </td>
              </tr>
      </table>
    </div>
      </fieldset>


      </td>
          </tr>
          </table>
      <p>
        <input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  " onclick=" return check_bar();"/>
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



<script type="text/javascript">
jQuery(document).ready(function(){
        
     if(jQuery("#search_invoice").is(":checked")){
          jQuery("#from_bill").removeClass();
          jQuery("#from_bill").attr('readonly',false);
          jQuery("#to_bill").removeClass();
          jQuery("#to_bill").attr('readonly',false);
    };
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
            
                jQuery("#from_date_tan").addClass('b_blur').attr('value','');
                jQuery("#to_date_tan").addClass('b_blur').attr('value','');
                 jQuery("#from_time").addClass('b_blur').attr('value','');
                jQuery("#to_time").addClass('b_blur').attr('value','');
                  jQuery("#from_bill").removeClass();
                  jQuery("#from_bill").attr('readonly',false);
                  jQuery("#to_bill").removeClass();
                  jQuery("#to_bill").attr('readonly',false);
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date_tan").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date_tan").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#to_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
			jQuery("#from_bill").attr('readonly',true).addClass('b_blur').attr('value','');
			jQuery("#to_bill").attr('readonly',true).addClass('b_blur').attr('value','');
		}else{
            jQuery("#from_bill").addClass('b_blur');
            jQuery("#to_bill").addClass('b_blur');
            jQuery("#from_bill").attr('readonly',true);
            jQuery("#to_bill").attr('readonly',true);
            jQuery("#from_date_tan").removeClass('b_blur');
            jQuery("#to_date_tan").removeClass('b_blur');
            jQuery("#from_time").removeClass('b_blur');
            jQuery("#to_time").removeClass('b_blur');
        }
    });
    jQuery("#search_time").click(function(){
			jQuery("#from_date_tan").removeClass('b_blur');
            jQuery("#to_date_tan").removeClass('b_blur');
            jQuery("#from_time").removeClass('b_blur');
            jQuery("#to_time").removeClass('b_blur');
        if(jQuery("#search_invoice").is(":checked")){
            jQuery("#from_bill").removeClass();
            jQuery("#to_bill").removeClass();
             jQuery("#from_bill").attr('readonly',false);
            jQuery("#to_bill").attr('readonly',false);
			jQuery("#from_date_tan").addClass('b_blur').attr('value','');
            jQuery("#to_date_tan").addClass('b_blur').attr('value','');
            jQuery("#from_time").addClass('b_blur').attr('value','');
            jQuery("#to_time").addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date_tan").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date_tan").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#from_time").attr('value','00:00');
			jQuery("#to_time").attr('value','<?php echo date('H:i');?>');
                        jQuery("#from_bill").attr('readonly',true).addClass('b_blur').attr('value','');
			jQuery("#to_bill").attr('readonly',true).addClass('b_blur').attr('value','');
		}else{
            jQuery("#from_bill").addClass('b_blur');
            jQuery("#to_bill").addClass('b_blur');
            jQuery("#from_bill").attr('readonly',true);
            jQuery("#to_bill").attr('readonly',true);
            jQuery("#from_bill").attr('value','');
            jQuery("#to_bill").attr('value','');
	
        }
            
    });
    
});
</script>


<script type="text/javascript">
jQuery(document).ready(function(){
    
   
   
    
          //  var search_invoice =  jQuery("#search_invoice").is(":checked");
//            if(search_invoice==true){
//                alert('sdfs');
//                //jQuery("#from_bill").removeClass();
////                jQuery("#to_bill").removeClass();
//            }
});
//Luu nguyen giap add function check all for check box

function get_bar(object)
{
    document.getElementById('SearchForm').submit();
}
jQuery("#checked_all").click(function (){
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
        //gan gia tri cho barids
        if(check==true)
        {
               jQuery(".check_box").each(function(){
                if(document.getElementById('barids').value=='')
                     document.getElementById('barids').value +=this.value;
                else
                {
                    document.getElementById('barids').value +=',';
                    document.getElementById('barids').value +=this.value;   
                }
        });      
        }
        else
        {
            document.getElementById('barids').value ='';   
        }
    });
 jQuery(".check_box").click(function (){
    var check = this.checked;
    if(check==true)//them 1 id vao text
    {
         var barids =  document.getElementById('barids').value;
         if(barids=='')
         {
             barids +=this.value;
         }
         else
         {
             barids +=',';
             barids +=this.value;
         }
         document.getElementById('barids').value = barids;
    }
    else//remove 1 id co trong text
    {
         var barids =document.getElementById('barids').value;
         //alert(barids);
         if(barids.indexOf(','+ this.value) >=0)
         {
              barids  = barids.replace(','+this.value,'');
         } 
         if(barids.indexOf(this.value)>=0)
         {
             barids = barids.replace(''+this.value,'');
         }
         document.getElementById('barids').value = barids;
    }
 }); 
 
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
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date_tan").val();
        var date_to_arr = jQuery("#to_date_tan").val();
        var myfromdate = jQuery("#from_date_tan").val().split("/");
        var mytodate = jQuery("#to_date_tan").val().split("/");
        var from_bill = jQuery("#from_bill").val();
        var to_bill = jQuery("#to_bill").val();
        var search_invoice =jQuery("#search_invoice").is(":checked")
        
        //end:KID them doan nay vao check valid time
        var validate = test_checked();
        if( validate)
        {
            if(search_invoice){
                if(from_bill=='' && to_bill==''){
                    alert('<?php echo Portal::language('empty');?>');
                    return false;
                }
                
            }
            //start:KID them doan nay vao check valid time
            if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
            {
                alert('<?php echo Portal::language('start_time_longer_than_end_time_try_again');?>');
                return false;
            }
            if(date_from_arr == date_to_arr)
            {
                if(to_numeric(hour_from[0]) > to_numeric(hour_to[0]))
                {
                    alert('<?php echo Portal::language('start_time_longer_than_end_time_try_again');?>');
                    return false;
                }
                else if(to_numeric(hour_from[0]) == to_numeric(hour_to[0]) && (to_numeric(hour_from[1]) > to_numeric(hour_to[1])))
                {
                    alert('<?php echo Portal::language('start_time_longer_than_end_time_try_again');?>');
                    return false;
                }
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
                    if(jQuery("#from_bill").val()!='' && jQuery("#to_bill").val()!='')
                    {
                        if( to_numeric(jQuery("#from_bill").val())<=to_numeric(jQuery("#to_bill").val()) )
                        {
                            return true;
                        }
                        else
                        {
                            
                            alert('<?php echo Portal::language('the_bill_number_is_not_valid_try_again');?>');
                            return false;
                        }
                    }
                    else
                    {
                        return true;
                    }
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
//End luu nguyen giap

</script>
<script>
    function check_date(){
        var from_year = jQuery('#from_year').val();
        //var to_year = jQuery('#to_year').val();
        //console.log(year);
        var from_month = jQuery('#from_month').val();
        var to_month = jQuery('#to_month').val();
        var from_day = jQuery('#from_day').val();
        var to_day = jQuery('#to_day').val();
        var from_date = new Date(from_year,from_month,from_day);
        var to_date = new Date(from_year,to_month,to_day);
        if(from_date>to_date){
            alert('Ngày bắt đầu lớn hơn ngày kết thúc');
            jQuery('#from_year').css('border','1px solid red');
            jQuery('#from_month').css('border','1px solid red');
            jQuery('#from_day').css('border','1px solid red'); 
        }else{
            jQuery('#from_year').css('border','1px solid #000');
            jQuery('#from_month').css('border','1px solid #000');
            jQuery('#from_day').css('border','1px solid #000');
        }
    }
</script>
<script type="text/javascript">
    jQuery('#from_time').mask("99:99");
    jQuery('#to_time').mask("99:99");
    jQuery("#from_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
	jQuery("#to_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    $('from_date_tan').value='<?php if(Url::get('from_date_tan')){echo Url::get('from_date_tan');}else{ echo ('01/'.date('m').'/'.date('Y'));}?>';
    $('to_date_tan').value='<?php if(Url::get('to_date_tan')){echo Url::get('to_date_tan');}else{ echo (date('d/m/Y'));}?>';
    $('from_time').value='<?php if(Url::get('from_time')){echo Url::get('from_time');}else{ echo ('00:00');}?>';
    $('to_time').value='<?php if(Url::get('to_time')){echo Url::get('to_time');}else{ echo (date('H').':'.date('i'));}?>';
    function fun_check_option(id)
    {
        if(id==1)
        {
            if(document.getElementById("search_time").checked==true)
            {
                document.getElementById("search_invoice").checked=false;
            }
            else
            {
                document.getElementById("search_invoice").checked=true;
            }
        }
        else
        {
            if(document.getElementById("search_invoice").checked==true)
            {
                document.getElementById("search_time").checked=false;
            }
            else
            {
                document.getElementById("search_time").checked=true;
            }
        }
    }
</script>