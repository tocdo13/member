<style>
.k_1{margin: 0 auto;width:50%;background: yellow;}
.left{float:left}
.leftalign{text-align:left;}
.clear{clear:both;}
.center{text-align:center;}
.inline{display:inline-block;}
.k10{padding:20px;border:1px solid #cdcdcd;}
.k_13{margin: 10px 0;}
.k_13 label{min-width: 100px; display:inline-block;}
 .k_1 div{text-align:left !important;}
.k_12{margin-left:20px ;}
.invoce_1{
        display:inline-block;
        width:55px;
        font-weight: normal;
        margin-left: 6px;
    }
    .invoce_2{
        display:inline-block;
        width:61px;
        margin-left: 6px;font-weight: normal;
    }
    .invoce_div{font-weight: normal;
        margin-bottom: 5px;
    }
  .b_blur{
    background: #cdcdcd;
} 
.c10px{margin:5px 0}
.kh{display: inline-block;
padding: 10px 20px 0;
margin-top: 20px;}
.kh div{text-align:left}
.c75px{display:inline-block;width:75px}
</style>
<table  width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align: center;margin: 0 auto;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="text-align: center;margin: 0 auto;font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong><?php echo Portal::language('template_code');?></strong><br />
			<i><?php echo Portal::language('promulgation');?></i>
			</td>
		</tr>	
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" style="text-align: center;margin: 0 auto;">
	<tr>
	<td width="60px">&nbsp;</td>
	<td>
		<p>&nbsp;</p>
		<?php if(Url::get('type')==1){ ?>
        <font class="report_title"><?php echo Portal::language('housekeeping_revenue_report_minibar');?></font>
        <?php }
        else { ?>
        <font class="report_title"><?php echo Portal::language('housekeeping_revenue_report_laundry');?></font>
        <?php } ?>
		<br>
		<form name="SearchForm" method="post">
         <div class="kh">
        <div style="border:1px solid #cdcdcd;display: inline-block;
padding: 13px;">
		   <div class="k_11 left ">
          <fieldset style="margin: 5px 0px;border: none;padding:10px 10px 10px 0" >
                    <legend>
                    <input name="search_time" type="checkbox" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_time']) && $_REQUEST['search_time'] !='')echo 'checked="checked"'; } ?>>
                    <label><?php echo Portal::language('search_for_time');?></label>
                    </legend>
                    <div class="invoce_div">
                        <label class="invoce_2 "><?php echo Portal::language('from_date');?>:</label>
                         <input  name="date_from" id="date_from" style="width: 80px;" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>">
                          <label class="invoce_2" style="width: 40px;"><?php echo Portal::language('from_time');?>:</label>
                          <input  name="hour_from" id="hour_from" style="width: 40px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('hour_from'));?>">
                    </div>
                    <div>
                        <label class="invoce_2"><?php echo Portal::language('to_date');?>:</label>
                        <input  name="date_to" id="date_to" style="width: 80px;" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"> 
                         <label class="invoce_2" style="width: 40px;"><?php echo Portal::language('to_time');?>:</label>
                        <input  name="hour_to" id="hour_to"  style="width: 40px;" / type ="text" value="<?php echo String::html_normalize(URL::get('hour_to'));?>">
                    </div>  

             </fieldset>
			 
           
          </div>
        <?php if(User::can_admin(False, ANY_CATEGORY)){?>
        <div class="c10px"><label for="hotel_id" class="c75px"><?php echo Portal::language('Hotel');?>:</label> 
        <select  name="hotel_id" id="hotel_id" class="leftalign"><?php
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
	</select>
        
        <?php }?>
		<select  name="group" id="group" style="margin:6px 0px;">
        <option value="1"><?php echo Portal::language('by_product');?></option>
        <option value="2" <?php if(Url::get('group')==2) echo 'selected="selected"'; ?> ><?php echo Portal::language('by_invoice');?></option>
        </select>
        </div>
        
       
            <div class="c10px">
                <label class="c75px"><?php echo Portal::language('recode');?></label>
                <input  name="res_id" id="res_id" class="input_number" style="width:85px;" / type ="text" value="<?php echo String::html_normalize(URL::get('res_id'));?>">(*<?php echo Portal::language('only_number');?>)
            </div>
            <div class="c10px">
                <label class="c75px"><?php echo Portal::language('product_id');?></label>
                <input  name="product_id" id="product_id" style="width:85px;" / type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>">
            </div>
     
		  </div>
		   <fieldset style="margin: 5px 0px 15px;border: 1px solid #AEA8A8;padding:10px" >
                    <legend>
                    <input name="search_invoice" type="checkbox" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !='')echo 'checked="checked"';} ?>>
                    <label><?php echo Portal::language('search_for_invoice');?></label></legend>
                    <div class="invoce_div">
                        <label class="invoce_1 "><?php echo Portal::language('invoice_id');?>:</label> 
                        <input  name="from_code" id="from_code" class="input_number b_blur" style="width:45px;" readonly / type ="text" value="<?php echo String::html_normalize(URL::get('from_code'));?>">
                        <label class=" "><?php echo Portal::language('to');?>:</label>
                        <input  name="to_code" id="to_code" class="input_number b_blur" style="width:45px;" readonly / type ="text" value="<?php echo String::html_normalize(URL::get('to_code'));?>">(*<?php echo Portal::language('only_number');?>)
                    </div>
                  
            </fieldset>
          <p>
				<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  " onclick=" return check_search();"/>
				<input type="button" value="  <?php echo Portal::language('cancel');?>  "  onclick="location='<?php echo Url::build('home');?>';"/>
			</p>
         </div>   
          
			
			
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td>
        
        </tr></table>
	</div>
	</div>
</td>
</tr></table>

<script type="text/javascript">
    jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
	jQuery('#hour_from').mask("99:99");
    jQuery('#hour_to').mask("99:99");
    function select_group()
    {
        if(jQuery('#group').val()==2)
        {
            jQuery('.select_gr').css("display","");
        }
        if(jQuery('#group').val()==1)
        {
            jQuery('.select_gr').css("display","none");
        }
    }
    $('date_to').value='<?php if(Url::get('date_to')){echo Url::get('date_to');}else{ echo (date('t').'/'.date('m').'/'.date('Y'));}?>';
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
        $('date_from').value='<?php if(Url::get('date_from')){echo Url::get('date_from');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#hour_from").val().split(':'));
        var hour_to = (jQuery("#hour_to").val().split(':'));
        var date_from_arr = jQuery("#date_from").val();
        var date_to_arr = jQuery("#date_to").val();
        
         var from_code = jQuery("#from_code").val();
        var to_code = jQuery("#to_code").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code==''){
                   
                    alert('<?php echo Portal::language('empty_report');?>');
                    return false;
                }
                
            }
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
                if(jQuery("#from_code").val()!='' && jQuery("#to_code").val()!='')
                {
                    if( to_numeric(jQuery("#from_code").val())<=to_numeric(jQuery("#to_code").val()) )
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
    }
    //end:KID them ham check dieu kien search
</script>      

<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
            
                jQuery("#date_from").addClass('b_blur').attr('value','');
                jQuery("#date_to").addClass('b_blur').attr('value','');
                 jQuery("#hour_from").addClass('b_blur').attr('value','');
                jQuery("#hour_to").addClass('b_blur').attr('value','');
                jQuery("#from_code").attr('readonly',false).removeClass('b_blur');;
                jQuery("#to_code").attr('readonly',false).removeClass('b_blur');;
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#date_from").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#date_to").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#hour_from").removeClass('b_blur').attr('value','00:00');
			jQuery("#hour_to").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#from_code").attr('readonly',false).addClass('b_blur').attr('value','');
            jQuery("#to_code").attr('readonly',false).addClass('b_blur').attr('value',''); 
      
		}else{
            jQuery("#from_code").attr('readonly',true).addClass('b_blur');
            jQuery("#to_code").attr('readonly',true).addClass('b_blur');
        }
    });
      jQuery("#search_time").click(function(){
                 jQuery("#date_from").removeClass('b_blur');
                jQuery("#date_to").removeClass('b_blur');
                 jQuery("#hour_from").removeClass('b_blur');
                jQuery("#hour_to").removeClass('b_blur');
        
        if(jQuery("#search_invoice").is(":checked")){
             jQuery("#from_code").attr('readonly',false).removeClass('b_blur');
            jQuery("#to_code").attr('readonly',false).removeClass('b_blur');
			   jQuery("#date_from").attr('readonly',true).addClass('b_blur').attr('value','');
            jQuery("#date_to").attr('readonly',true).addClass('b_blur').attr('value','');
			 jQuery("#hour_from").attr('readonly',true).addClass('b_blur').attr('value','');
			  jQuery("#hour_to").attr('readonly',true).addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#date_from").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#date_to").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#hour_from").attr('value','00:00');
			jQuery("#hour_to").attr('value','<?php echo date('H:i');?>'); 
             jQuery("#from_code").attr('readonly',false).addClass('b_blur').attr('value','');
            jQuery("#to_code").attr('readonly',false).addClass('b_blur').attr('value','');         
		}else{
             jQuery("#from_code").attr('value','').addClass('b_blur').attr('readonly',true);
            jQuery("#to_code").attr('value','').addClass('b_blur').attr('readonly',true);
        }
            
    });
});

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
          