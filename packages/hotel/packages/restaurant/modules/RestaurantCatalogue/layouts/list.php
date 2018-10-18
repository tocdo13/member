<style type="text/css" media="print">	
	#search_bound{
		display:none;
	}
</style>
<?php 
$title = Portal::language('product_list');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_list'));
?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left">
			<table cellSpacing=0 width="100%" style="font-size:11px;">
			<tr valign="top">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
			</td>
			</tr>	
		</table>
            <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC">
                <tr>
                    <td width="100%" align="center">
						<h2>[[.list_of_goods.]]</h2>
						<div style="padding-bottom:5px;text-decoration:underline;">[[.date.]]: [[|from_arrival_time|]] - [[|to_arrival_time|]]</div>
                        <div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">[[.time.]] : 
                        <?php if( isset( [[=start_shift_time=]] ) ) echo [[=start_shift_time=]]; ?> - <?php if( isset( [[=end_shift_time=]] ) ) echo [[=end_shift_time=]]; ?>
                        </div>
                        [[.total.]]: [[|total|]] [[.goods1.]]
					</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
        <form method="post" name="SearchRestaurantCatalogueForm">
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
				<div id="search_bound">
				
					<input type="hidden" name="page" value="<?php echo URL::get('page');?>" />
                    <table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px; font-size:11px; border-collapse:collapse;" id="search">
                        <tr><td>
                        	<div style="width:100%;height:100%;background-color:white;">
                            	<table width="100%">
                                    <tr><td >
                                            <table style="margin: 0 auto;">
                                                <tr>
                                                    <!--Start Luu Nguyen Giap add portal -->
                                                    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                                                    <td  >[[.hotel.]]</td>
                                                    <td style="margin: 0;"><select name="portal_id" id="portal_id" onchange="get_bar();"></select></td>
                                                    <?php //}?>
                                                    <!--End Luu Nguyen Giap add portal -->
                                                    
                            						<td >
                                                        [[.date_from.]]:  </td>
                                                    <td style="margin: 0;"> <input name="from_arrival_time" type="text" id="from_arrival_time" size="8" onchange="changevalue();"/>
                                                    </td>
                                                    <td> 
                                                        [[.date_to.]]:</td>
                                                    <td style="margin: 0;"> <input name="to_arrival_time" type="text" id="to_arrival_time" size="8" onchange="changefromday();"/>
                                                    </td>
                                                    
                                                    <td>
                                                        [[.by_time.]]</td>
                                                    <td colspan="2">
                                                        <input name="start_time" type="text" id="start_time" style="width:30px;" onblur="validate_time(this,this.value);" />
                                                    
                                                        [[.to.]]
                                                        <input name="end_time" type="text" id="end_time" style="width:30px;" onblur="validate_time(this,this.value);" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    
                            						<td>[[.code.]]</td>
                            						<td><input name="code" type="text" id="code" style="width:84px;"/></td>
                                                    
                                                    <td>[[.name.]]</td>
                            						<td><input name="name" type="text" id="name" style="width:100px;"/></td>
                                                    
                            						<td>[[.category_id.]]</td>
                            						
                            							<td><select name="category_id" id="category_id" style="width:150px;"></select>
                            							<input name="action" type="hidden" id="action"/>
                                                    </td>
                            						<td>[[.Sort.]]</td>
                                                    <td style="margin: 0;padding:0"><select name="order_by" id="order_by" style="width:90px;"></select></td>
                            						<td><input type="submit" value="[[.search.]]" name="on_search" onclick=" return check_bar();"/></td>
                                                </tr>
                                                <!--Luu Nguyen Giap add search for restaurant-->
                                                <tr>
                                                    <td colspan="9" align="center">
                                                    <input name="checked_all" type="checkbox" id="checked_all" >
                                                    <b><label for="checked_all">[[.select_all_bar.]]</label></b>
                                                    
                                                    <!--LIST:bars-->
                                                        <input name="bars[]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]"  class="check_box" <?php if(!isset($_REQUEST['on_search'])) { echo 'checked="true"';} ?>/>
                                                        <label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label>
                                                    <!--/LIST:bars-->
                                                    <input name="barids" type="hidden" id="barids" >
                                                    </td>
                                                </tr>
                                                <!--End Luu Nguyen Giap-->
                                            </table>
                                    </td></tr>
                                </table>
                        	</div>
                        </td></tr>
                    </table>
				
				</div>
                    <div style="border:2px solid #FFFFFF;">
					<table width="95%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CECFCE" style="font-size:11px; border-collapse:collapse;">
						<tr valign="middle" style="text-transform:uppercase;" bgcolor="#EFEFEF">
							<th nowrap align="center" width="10%">[[.code.]]</th>
							<th nowrap align="center" width="40%">[[.name.]]</th>
							    <th width="10%" align="center" nowrap="nowrap">[[.unit_id.]]</th>
							    <th width="10%" align="center" nowrap="nowrap">[[.quantity.]] </th>
                                <th width="10%" align="center" nowrap="nowrap">[[.res_quantity_discount.]]</th>
					    </tr>
						<!--LIST:items-->
						<tr>
							<td align="left" nowrap id="id_[[|items.id|]]">
									[[|items.id_product|]]								</td>
							<td align="left" nowrap id="name_[[|items.id|]]">
									[[|items.name|]]
								</td>
				            <td align="center" nowrap="nowrap" id="unit_[[|items.id|]]"> [[|items.unit_name|]] </td>
				            <td align="right" nowrap="nowrap" id="price_[[|items.id|]]"> [[|items.quantity|]] </td>
                            <td align="right" nowrap="nowrap" id="price_[[|items.id|]]">[[|items.quantity_discount|]]</td>
					    </tr>
						<!--/LIST:items-->
					</table>
                    </div>
            <input name="cmd" type="hidden" value="">
			           
		</td>
		</tr>
	</table>
    </form> 
    <br />
	<table width="100%" style="font-family:'Times New Roman', Times, serif; font-size:11px;">
		<tr>
			<td></td>
			<td></td>
			<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center"><strong>&nbsp;</strong></td>
			<td width="33%" align="center"><strong>&nbsp;</strong></td>
			<td width="33%" align="center"><strong>[[.creator.]]</strong></td>
		</tr>
		</table>
		<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
 </td>
</tr>
</table>                                                 
<div style="page-break-before:always;page-break-after:always;"></div>
<script type="text/javascript">
    var barids = document.getElementById('barids');
    jQuery(".check_box").each(function(){
            if(barids.value!='')
            {
                var arr_barid = barids.value.split(",");
                for(var i=0;i<arr_barid.length;i++)
                {
                    if(this.value==arr_barid[i])
                    {
                         this.checked = true;
                         break;
                    }
                }
                
            }
            
        });
    
    jQuery('#from_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    jQuery('#to_arrival_time').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    function changevalue()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_arrival_time").val(jQuery("#from_arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_arrival_time").val(jQuery("#to_arrival_time").val());
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
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }
            if(is_numeric(m.toString()))
            {
                if(m>59)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }  
        }
    }
    
//Luu nguyen giap add function check all for check box

function get_bar()
{
    SearchRestaurantCatalogueForm.submit();
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
                    if(ChekItemInArray(document.getElementById('barids').value,this.value)==false)
                    {
                       document.getElementById('barids').value +=',';
                       document.getElementById('barids').value +=this.value; 
                    }
                       
                }
        });      
        }
        else
        {
            document.getElementById('barids').value ='';   
        }
    });
    
    function ChekItemInArray(arr,item)
    {
        if(arr.indexOf(",")!=-1)
        {
            var str_arr = arr.split(",");
            
            
            for(var i=0;i<str_arr.length;i++)
            {
                if(item==str_arr[i])
                {
                    return true;
                }
            }
            return false;
        }
        else
        {
            if(item==arr)
                return true;
            else
                return false;
        }
        
    }
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
        var validate = test_checked();
        if( validate)
        {
            return true;
        }
        else
        {
            alert('[[.you_must_choose_bar.]]');
            return false;
        }
    }   
//End luu nguyen giap

</script>