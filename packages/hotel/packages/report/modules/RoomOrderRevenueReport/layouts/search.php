<style>
.k_1{margin: 0 auto;text-align: center;
display: inline-block;}
.left{float:left}
.clear{clear:both;}
.center{text-align:center;}
.inline{display:inline-block;}
.k10{padding:0 20px;}
.k_13{margin: 10px 0;}
.k_13 label{min-width: 100px; display:inline-block;}
 .k_1 div{text-align:left !important}
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
.k_11{border: 1px solid #cdcdcd;
padding: 15px 15px 0;
margin-bottom: 10px;}
.highlight tr td {padding: 5px 0;}

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
			<strong>[[.template_code.]]</strong></td>
		</tr>	
	</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.room_order_revenue_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset style="border: none;"><legend></legend>
        <div style="border: 1px solid #cdcdcd;padding:10px;">
		  <table border="0" class="highlight">
            <tr>
                <fieldset style="margin: 5px 0px 10px;border: 1px solid #AEA8A8;padding:10px" class="inline">
                    <legend>
                    <input name="search_time" type="checkbox" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_time']) && $_REQUEST['search_time'] !='')echo 'checked="checked"'; } ?>>
                    <label>[[.search_for_time.]]</label>
                    </legend>
                    <div class="invoce_div inline">
                        <label class="invoce_2 ">[[.from_date.]]:</label>
                         <input name="date_from" type="text" id="date_from" style="width: 80px;" onchange="changevalue();" />
                          <label class="invoce_2" style="width: 40px;">[[.from_time.]]:</label>
                          <input name="start_time" type="text" id="start_time" style="width: 40px;"/>
                    </div>
					<div class="clear"></div>
                    <div class="inline">
                        <label class="invoce_2">[[.to_date.]]:</label>
                        <input name="date_to" type="text" id="date_to" style="width: 80px;" onchange="changefromday();" /> 
                         <label class="invoce_2" style="width: 40px;">[[.to_time.]]:</label>
                        <input name="end_time" type="text" id="end_time"  style="width: 40px;" />
                    </div>  
					</fieldset>
            </tr>
			     <tr>
			  <td  nowrap="nowrap">[[.hotel.]]</td>
			  <td align="left"><select name="portal_id" id="portal_id" style="width:116px;"></select>
              </td>
			  </tr>
             
              <tr>
                  <td >[[.vat_id.]]: </td>
                  <td align="left"><input name="from_code" type="text" id="from_code" class="input_number" style="width:43px;"/> [[.to.]]: <input name="to_code" type="text" id="to_code" class="input_number" style="width:43px;"></td>
              </tr>
              <tr>
			  <td nowrap="nowrap">[[.by_user_checkout.]]</td>
			  <td><select name="user_id" id="user_id" style="width:120px;"></select></td>
            </tr>
            <tr>
			  <td nowrap="nowrap">[[.user_create_folio.]]</td>
			  <td><select name="user_create_folio" id="user_create_folio" style="width:120px;"></select></td>
            </tr>
            <tr>
                <td>[[.payment_type.]]</td>
                <td><select name="payment_type" id="payment_type" style="width:120px;"></select></td>
            </tr> 
            <tr>
            <td>[[.customer.]]</td>
            <td><input name="customer" type="text" id="customer" style="width:116px;"  onkeypress="Autocomplete();"  autocomplete="off"/></td>
            </tr> 
            <tr>
                <td>[[.room_name.]]</td>
                <td><input name="room_name" type="text" id="room_name" style="width:116px;"/></td>
            </tr>
            <!--
            <tr>
			  <td nowrap="nowrap">[[.by_user.]]</td>
			  <td><select name="user_id" id="user_id" style="width:116px;"></select></td>
			  </tr>
              -->
              </table>
		</div>
             <fieldset style="margin: 5px 0px 15px;border: 1px solid #AEA8A8;padding:10px" >
						<legend>
						<input name="search_invoice" type="checkbox" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" <?php if(isset($_REQUEST)){if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !='')echo 'checked="checked"';} ?>>
						<label>[[.search_for_invoice.]]</label></legend>
						<div class="invoce_div">
							<label class="invoce_1 ">[[.folio_id.]]:</label> 
							<input name="from_bill" type="text" id="from_bill" class="input_number b_blur" style="width:45px;" readonly/>
							<label class=" ">[[.to.]]:</label>
							<input name="to_bill" type="text" id="to_bill" class="input_number b_blur" style="width:45px;" readonly/>
						</div>
				</fieldset>
			</form>     
		</td></tr></table>
        <table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="999" size="4" maxlength="3" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="400" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			</table>
         <p>
				<input type="submit" name="do_search" value="  [[.report.]]  " onclick="return check_search();">
				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
		</p>
	</div>
	</div>
</td>
</tr></table>
<script type="text/javascript">
jQuery(document).ready(function(){
    <?php
    if(isset($_REQUEST)){
        if(isset($_REQUEST['search_invoice']) && $_REQUEST['search_invoice'] !=''){
            ?>
            document.getElementById("search_time").checked = false;
            jQuery("#date_from").addClass('b_blur').attr('value','');
                jQuery("#date_to").addClass('b_blur').attr('value','');
                 jQuery("#start_time").addClass('b_blur').attr('value','');
                jQuery("#end_time").addClass('b_blur').attr('value','');
            <?php
        }
    
    }
    ?>
        if(jQuery("#search_invoice").is(":checked")){
                  jQuery("#from_bill").attr('readonly',false).removeClass('b_blur');
                  jQuery("#to_bill").attr('readonly',false).removeClass('b_blur');
    };
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
            
                jQuery("#date_from").addClass('b_blur').attr('value','');
                jQuery("#date_to").addClass('b_blur').attr('value','');
                 jQuery("#start_time").addClass('b_blur').attr('value','');
                jQuery("#end_time").addClass('b_blur').attr('value','');
                jQuery("#from_bill").attr('readonly',false).removeClass('b_blur');;
                jQuery("#to_bill").attr('readonly',false).removeClass('b_blur');;
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#date_from").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#date_to").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#start_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#end_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#from_bill").attr('readonly',false).addClass('b_blur').attr('value','');
            jQuery("#to_bill").attr('readonly',false).addClass('b_blur').attr('value',''); 
      
		}else{
            jQuery("#from_bill").attr('readonly',true).addClass('b_blur');
            jQuery("#to_bill").attr('readonly',true).addClass('b_blur');
        }
    });
      jQuery("#search_time").click(function(){
                 jQuery("#date_from").removeClass('b_blur');
                jQuery("#date_to").removeClass('b_blur');
                 jQuery("#start_time").removeClass('b_blur');
                jQuery("#end_time").removeClass('b_blur');
        
        if(jQuery("#search_invoice").is(":checked")){
             jQuery("#from_bill").attr('readonly',false).removeClass('b_blur');
            jQuery("#to_bill").attr('readonly',false).removeClass('b_blur');
			   jQuery("#date_from").attr('readonly',true).addClass('b_blur').attr('value','');
            jQuery("#date_to").attr('readonly',true).addClass('b_blur').attr('value','');
			 jQuery("#start_time").attr('readonly',true).addClass('b_blur').attr('value','');
			  jQuery("#end_time").attr('readonly',true).addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#date_from").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#date_to").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#start_time").attr('value','00:00');
			jQuery("#end_time").attr('value','<?php echo date('H:i');?>'); 
             jQuery("#from_bill").attr('readonly',false).addClass('b_blur').attr('value','');
            jQuery("#to_bill").attr('readonly',false).addClass('b_blur').attr('value','');         
		}else{
             jQuery("#from_bill").attr('value','').addClass('b_blur').attr('readonly',true);
            jQuery("#to_bill").attr('value','').addClass('b_blur').attr('readonly',true);
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
<script>
    $('portal_id').value = '<?php echo PORTAL_ID;?>';
    jQuery('#start_time').mask("99:99");
    jQuery('#end_time').mask("99:99");
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1]-1,mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
        /*if(myfromdate[2] > mytodate[2])
            $('date_to').value =$('date_from').value;
        else{
            if(myfromdate[1] > mytodate[1])
                $('date_to').value =$('date_from').value;
            else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1]))
                    $('date_to').value =$('date_from').value;
            }    
        }*/   
    }
    
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1]-1,myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1]-1,mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
        
        /*if(myfromdate[2] > mytodate[2])
            $('date_from').value= $('date_to').value;
        else
        {
            if(myfromdate[1] > mytodate[1])
                $('date_from').value = $('date_to').value;
            else
            {
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1]))
                    $('date_from').value =$('date_to').value;
            }
       } */
    }
    function check_code()
    {
        if(jQuery("#from_bill").val()!='' && jQuery("#to_bill").val()!='')
        {
            if( to_numeric(jQuery("#from_bill").val())<=to_numeric(jQuery("#to_bill").val()) )
            {
                if(jQuery("#from_code").val()!='' && jQuery("#to_code").val()!='')
                {
                    if( to_numeric(jQuery("#from_code").val())<=to_numeric(jQuery("#to_code").val()) )
                    {
                        return true;
                    }
                    else
                    {
                        
                        alert('so luong bill khong kha dung!');
                        return false;
                    }
                }
                else
                {
                    return true;
                }
            }
            else
            {
                
                alert('so luong bill khong kha dung!');
                return false;
            }
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
                    
                    alert('so luong bill khong kha dung!');
                    return false;
                }
            }
            else
            {
                return true;
            }
        }
    }
    jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
	 $('start_time').value='<?php if(Url::get('start_time')){echo Url::get('start_time');}else{ echo ('00:00');}?>';
    $('end_time').value='<?php if(Url::get('end_time')){echo Url::get('end_time');}else{ echo (date('H').':'.date('i'));}?>';
    function Autocomplete()
    {
        jQuery("#customer").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item) {
                 //console.log(item);
                document.getElementById('customer_id').value = item.data[0];
               // console.log(document.getElementById('customer_id').value);
            }
        }) ;
    }
    //start:KID them ham check dieu kien search
    function check_search()
    {
        var hour_from = (jQuery("#start_time").val().split(':'));
        var hour_to = (jQuery("#end_time").val().split(':'));
        var date_from_arr = jQuery("#date_from").val();
        var date_to_arr = jQuery("#date_to").val();
         var from_code = jQuery("#from_bill").val();
        var to_code = jQuery("#to_bill").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code==''){
                   
                    alert('[[.empty_folio_one.]]');
                    return false;
                }
                
            }
        if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
        {
            alert('[[.start_time_longer_than_end_time_try_again.]]');
            return false;
        }
        else
        {
            if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
            {
                alert('[[.the_max_time_is_2359_try_again.]]');
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
                        
                        alert('[[.the_bill_number_is_not_valid_try_again.]]');
                        return false;
                    }
                }
                else
                {
                    if( to_numeric(jQuery("#from_code").val())<=to_numeric(jQuery("#to_code").val()) )
                    {
                        return true;
                    }
                    else
                    {
                        
                        alert('[[.the_vat_number_is_not_valid_try_again.]]');
                        return false;
                    }
                }
            }
        }   
    }
    //end:KID them ham check dieu kien search
</script>
