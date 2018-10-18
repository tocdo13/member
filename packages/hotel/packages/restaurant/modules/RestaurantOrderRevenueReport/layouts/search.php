<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<style>
    #main_div{
        margin: 0px auto;
        width: 600px;
        height: 500px;
        position: relative;
    }
    .td-left{
        padding-left: 20px;
        background: url('packages/hotel/packages/report/includes/image/border_bg.png') no-repeat 3px;
    }
    .td-right{
        background-image: url('packages/hotel/packages/report/includes/image/border_bg.png');
        background-repeat: no-repeat;
        background-position: calc(100% - 3px) center;
    }
    .simple-layout-center{
        height: 800px;
    }
    #table_cmd{
        margin: 0px auto;
        position: absolute;
        bottom: -120px;
        left: 180px;
    }
</style>
<div id="main_div">
    <form name="SearchForm" id="SearchForm" method="post">
      <table>
        <tr>
            <td><h3 class="report-title-new">[[.restaurant_order_revenue_report.]]</h3></td>
        </tr>
        <tr>
            <td align="center">
                <table id="table_main">
                    <tr>
                        <td colspan="3" style="border: none;">
                            <fieldset>
                                <legend>
                                    <input name="search_time" id="search_time" checked="checked" value="search_time" onclick="fun_check_option(1);" type="checkbox"/>
                                    <label>[[.search_for_time.]]</label>
                                </legend>
                                <table>
                                    <tr>
                                        <td>[[.from_date.]]</td>
                                        <td><input name="from_date" type="text" id="from_date" onchange="changevalue()" class="style_date" class="input-long-text" /></td>
                                        <td>[[.by_time.]]</td>
                                        <td><input name="start_time" type="text" id="start_time" onblur="validate_time(this,this.value);" class="input-short-text"/></td>
                                    </tr>
                                    <tr>
                                        <td>[[.to_date.]]</td>
                                        <td><input name="to_date" type="text" id="to_date" onchange="changefromday()" class="style_date" class="input-long-text"/></td>
                                        <td>[[.by_time.]]</td>
                                        <td><input name="end_time" type="text" id="end_time" onblur="validate_time(this,this.value);" class="input-short-text" /></td>
                                    </tr>
                                </table>   
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left"><label for="hotel_id">[[.Hotel.]]</label></td>
                        <td colspan="2" class="td-right"><select name="hotel_id" id="hotel_id" class="input-long-text" onchange="get_bar(this);"></select></td>
                    </tr>
                    <tr>
                        <td class="td-left"><label> [[.revenue.]]</label></td>
                        <td colspan="2" class="td-right">
                            <select  name="revenue" id="revenue" class="input-long-text" onchange="if(this.value=='min'){jQuery('.check_box').attr('checked',true);jQuery('#checked_all').attr('checked',true);}" >
                                <option value="all">All</option>
                                <option value="min">-Dưới 200,000-</option>
                                <option value="max">-Từ 200,000-</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left"><label>[[.user_status.]]</label></td>
                        <td colspan="2" class="td-right">
                            <!-- 7211 -->  
                            <select style="" name="user_status" id="user_status" class="input-long-text">
                                <option value="1">Active</option>
                                <option value="0">All</option>
                            </select>
                            <!-- 7211 end-->
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left"><label>[[.user.]]</label></td>
                        <td colspan="2" class="td-right"><select name="user_id" id="user_id" class="input-long-text"></select></td>
                    </tr>
                    <tr>
                        <td class="td-left"><label>[[.customer_code.]]</label></td>
                        <td colspan="2" class="td-right"><input name="code" type="text" id="code" onfocus="customerAutocomplete();" autocomplete="off" class="input-long-text"/></td>
                    </tr>
                    <tr>
                        <td class="td-left"><label>[[.vat_code.]]</label></td>
                        <td colspan="2" class="td-right"><input name="from_code" type="text" id="from_code" class="input_number input-short-text"/><label>[[.to.]]:</label><input name="to_code" type="text" id="to_code" class="input_number input-short-text"/></td>
                    </tr>
                    <tr>
                        <td class="td-left"></td>
                        <td style="width: 20px;"><input name="checked_all" type="checkbox" id="checked_all" /></td>
                        <td class="td-right"><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
                    </tr>
                    <!--LIST:bars-->
                         <tr>
                            <td class="td-left"></td>
                            <!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                                <td align="right"> <input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked"  /></td>
                            <!--ELSE-->
                              <td align="right"><input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" /></td>
                            <!--/IF:cond-->
                              <td class="td-right"><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
                         </tr>
                     <!--/LIST:bars-->
                     <tr>
                        <td colspan="3" style="position: relative;">
                            <fieldset style="position: absolute;top: -8px;width: 99%;">
                                <legend>
                                    <input name="search_invoice" id="search_invoice" value="search_invoice" onclick="fun_check_option(2);" type="checkbox"/>
                                    <label>[[.search_for_invoice.]]</label>
                                </legend>
                                    <label style="margin-bottom: 10px;display:inline-block;">[[.bill_id.]]:</label>
                                    <input name="from_bill" type="text" id="from_bill" class="input_number b_blur" style="width:75px;" onchange="fun_check_bill();"  readonly/>
                                    <label>[[.to.]]:</label> 
                                    <input name="to_bill" type="text" id="to_bill" class="input_number b_blur" style="width:75px;" onchange="fun_check_bill();"   readonly/>
                            </fieldset>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>
      </table>
      <table id="table_cmd">
        <tr>
            <td>
                <input type="submit" name="do_search" value="  [[.report.]]  " id="do_search" onclick=" return check_bar();"/>
                <input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
            </td>
        </tr>
      </table>
      <input type="hidden" name="line_per_page" value="30" />
      <input type="hidden" name="no_of_page"  value="400"/>
      <input type="hidden" name="start_page" value="1"/>
  </form>
</div>
<script>
jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
    function get_bar(object)
    {
        document.getElementById('SearchForm').submit();
    }
	var bars_list = [[|bar_lists|]];
	getBarShift([[|bar_id|]]);
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#to_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
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
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
    	});
        return check;
    }
   /* 
    function check_bar()
    {
        var validate = test_checked();
        if( validate)
        {
            if(to_numeric(jQuery("#from_bill").val())>to_numeric(jQuery("#to_bill").val()))
            {
                alert("nhap so bill khong dung, nhap lai!");
                return false;
            }
            else
            {
                return true
            }

        }
        else
        {
            alert('[[.you_must_choose_bar.]]');
            return false;

        }
    } 
    */
	   function check_bar()
    {
        //start:KID them doan nay vao check valid time
        var hour_from = (jQuery("#start_time").val().split(':'));
        var hour_to = (jQuery("#end_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
        //end:KID them doan nay vao check valid time
        var validate = test_checked();
        
        
        var from_code = jQuery("#from_bill").val();
        var to_code = jQuery("#to_bill").val();
        var search_invoice =jQuery("#search_invoice").is(":checked");
            if(search_invoice){
                if(from_code=='' && to_code==''){
                   
                    alert('[[.empty_report.]]');
                    return false;
                }
                
            }
        if( validate)
        {
            //start:KID them doan nay vao check valid time
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
            //end:KID them doan nay vao check valid time
        }
        else
        {
            alert('[[.you_must_choose_bar.]]');
            return false;

        }
    } 
    
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
   
    //customerAutocomplete
    jQuery(".simple-layout-middle").click(function (){
       jQuery(".acResults").css('display','none');
    });
    function customerAutocomplete()
    {
    	jQuery("#code").autocomplete({
             url: 'get_customer_search.php?customer=1',
        onItemSelect: function(item){
    		
            }
        }) 
    }
 //end customerAutocomplete   
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
  
    jQuery("#search_invoice").click(function(){
        if(jQuery("#search_invoice").is(":checked")){
		jQuery("#from_date").addClass('b_blur').attr('value','');
		jQuery("#to_date").addClass('b_blur').attr('value','');
		 jQuery("#start_time").addClass('b_blur').attr('value','');
		jQuery("#end_time").addClass('b_blur').attr('value','');
		  jQuery("#from_bill").attr('readonly',false).removeClass();
		  jQuery("#to_bill").attr('readonly',false).removeClass();
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").removeClass('b_blur').attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").removeClass('b_blur').attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#start_time").removeClass('b_blur').attr('value','00:00');
			jQuery("#end_time").removeClass('b_blur').attr('value','<?php echo date('H:i');?>');
            jQuery("#from_bill").addClass('b_blur').attr('readonly',true).attr('value','');
             jQuery("#to_bill").addClass('b_blur').attr('readonly',true).attr('value','');
		}else{
            jQuery("#from_bill").attr('readonly',true).addClass('b_blur');
            jQuery("#to_bill").attr('readonly',true).addClass('b_blur');
            jQuery("#from_date").removeClass('b_blur');
            jQuery("#to_date").removeClass('b_blur');
            jQuery("#start_time").removeClass('b_blur');
            jQuery("#end_time").removeClass('b_blur');
        }
    });
    
        jQuery("#search_time").click(function(){
			jQuery("#from_date").removeClass('b_blur');
            jQuery("#to_date").removeClass('b_blur');
            jQuery("#start_time").removeClass('b_blur');
            jQuery("#end_time").removeClass('b_blur');
        if(jQuery("#search_invoice").is(":checked")){
             jQuery("#from_bill").attr('readonly',false).removeClass();
            jQuery("#to_bill").attr('readonly',false).removeClass();
			jQuery("#from_date").addClass('b_blur').attr('value','');
            jQuery("#to_date").addClass('b_blur').attr('value','');
            jQuery("#start_time").addClass('b_blur').attr('value','');
            jQuery("#end_time").addClass('b_blur').attr('value','');
        }else if(jQuery("#search_time").is(":checked")){
			jQuery("#from_date").attr('value','<?php echo date('01/m/Y'); ?>');
			jQuery("#to_date").attr('value','<?php echo date('d/m/Y'); ?>');
			jQuery("#start_time").attr('value','00:00');
			jQuery("#end_time").attr('value','<?php echo date('H:i');?>');
            jQuery("#from_bill").addClass('b_blur').attr('readonly',true).attr('value','');
             jQuery("#to_bill").addClass('b_blur').attr('readonly',true).attr('value','');
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
    // 7211
    var users = <?php echo String::array2js([[=users=]]);?>;
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