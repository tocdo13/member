<link rel="stylesheet" href="skins/default/report.css"/>
<style>
.multiselect_customer {
  width: 120px;
}
.selectBox_customer {
  position: relative;
}

.selectBox_customer select {
  width: 100%;
  font-weight: bold;
}
.overSelect_customer {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}
#checkboxes_customer {
  display: none;
  border: 1px #1e90ff solid;
  overflow: auto;    
  padding: 2px 15px 2px 5px;
  position: absolute;
  background: white;  
}
#checkboxes_customer{
    height: 300px;
}
#checkboxes_customer label {
  display: block;
}
#checkboxes_customer label:hover {
  background-color: #1e90ff;
}
.date_moth{
	display:none;
}
.report{
	border:1px solid #ccc;
	height:27px;
}
@media print{
.date_moth{
	display:block;
	padding-top:10px;
	font-size:14px;
}
.no_print{
	display:none;
}
#tblExport th{
    padding: 0px !important;
}
</style>
<div style="width: 100%;overflow-x: scroll;">
<form name="SearchForm" method="post">
<table style="width:100%;">
<tr valign="top">
<td align="left" width="100%">
	<table border="0" cellSpacing=0 cellpadding="5" width="100%">
			<tr valign="middle">
			  <td align="left">
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?>
			  </td>
			  <td align="right">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
			  </td>
			</tr>	
		</table>
</td>
</tr>
<tr>
    <td style="text-align:center; padding-top:26px;">
    <font class="report_title">[[.general_reservation_report.]]</font><br />
    <label id="date_moth">[[.from_date.]] : [[|from_date|]] - [[.to_date.]] : [[|to_date|]]</label> 
    </td>
</tr>
<tr class="no_print">
  <td colspan="3" style="padding-left:50px; padding-right:50px;">
  
<fieldset>
  <legend>[[.time_select.]]</legend>
    <table style="margin-left:60px;">
    <tr> <td nowrap="nowrap">[[.by_day.]] &nbsp;&nbsp;</td>
    <td><input type="text" name="from_day" id="from_day" class="date-input" onChange="changevalue();"/>
    <script>
			  $('from_day').value='<?php if(Url::get('from_day')){echo Url::get('from_day');}else{ echo date('01/m/Y');}?>';
			  
	</script>
    </td>
    <td> &nbsp;&nbsp;[[.to_day.]] &nbsp;&nbsp;</td><td><input type="text" name="to_day" id="to_day" class="date-input" onChange="changefromday();"/>
    <script>
			  $('to_day').value='<?php if(Url::get('to_day')){echo Url::get('to_day');}else{  echo date('d/m/Y',(Date_Time::to_time(date('t/m/Y',time()))));}?>';
			  
	</script>
    <?php $tong_foc = 0;  ?>
    </td>
    <td>&nbsp;&nbsp;[[.recode.]]&nbsp;&nbsp;<input name="re_code" type="text" id="re_code" style="width: 70px;"  class="input_number"/></td>
    <td>[[.customer_name.]]:</td>
    <td>
        <div class="multiselect_customer">
            <div style="width: 80px;" class="selectBox_customer" onclick="showCheckboxes('customer');">
                  <select style="height: 20px;">
                    <option></option>
                  </select>
                  <div class="overSelect_customer"></div>
            </div> 
            [[|list_customer|]]
            <input name="customer_ids" type="hidden" id="customer_ids" />
            <input name="customer_id_" type="hidden" id="customer_id_" />
        </div>     
    </td>   
    <td>&nbsp;&nbsp;[[.creater.]]&nbsp;&nbsp;<select name="create_user" id="create_user" style="width: 170px;height: 20px;"></select></td>
    <td>&nbsp;&nbsp;[[.cut_of_date.]]&nbsp;&nbsp;<input type="text" name="cut_of_date" id="cut_of_date" class="date-input" style="width: 80px;"/></td>
    <td>&nbsp;&nbsp;[[.hotel.]]&nbsp;&nbsp; <select name="portal_id" id="portal_id" style="height: 20px;"></select></td>
	<td>&nbsp;<input type="submit" name="do_search" value="[[.report.]]" id="btnsubmit"/></td>
    <!--<td><button id="export">[[.export.]]</button></td>-->
     </tr></table>
     </fieldset>
     </td>
    </tr>
     <tr>
        <td style="padding-left:10px;">
            <div style="width:100%; padding-bottom:10px; font-size:11px;">        
                <table border="1px" id="tblExport" style="width:100%; margin-top:10px; font-size:11px;">
                   <?php $rl = 1;?>
                   <!--LIST:room_level-->
                   <?php if($rl==1){ ?>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;">
                        <td colspan="2" rowspan="<?php echo sizeof([[=room_level=]])-1;?>" style="width:50px;text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b>[[.room_level.]]</b></td>
                        <td colspan="2" style="text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b>[[|room_level.name|]]</b></td>
                        <!--LIST:items-->
                            <?php if([[=room_level.id=]]==[[=items.room_level_id=]]){?>
                                <td bgcolor="#F0FFF0" style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sat'){ echo 'color: blue';} ?>">[[|items.room_soild|]]</td>
                            <?php }?>
                        <!--/LIST:items-->
                        <td style="text-align: right;padding-right: 5px;"bgcolor="#F0FFF0"><b>[[|room_level.room_soild|]]</b></td>
                        <td colspan="4" style="padding: 0px;" bgcolor="#EFEFEF" rowspan="<?php echo sizeof([[=room_level=]])-1+3;?>"></td>
                   </tr>
                   <?php $rl++;}else{?>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#F0FFF0">
                        <td colspan="3" style="text-align: left;padding-left: 3px;"><b>[[|room_level.name|]]</b></td>
                        <!--LIST:items-->
                            <?php if([[=room_level.id=]]==[[=items.room_level_id=]]){?>
                                <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sat'){ echo 'color: blue';} ?>">[[|items.room_soild|]]</td>
                            <?php }?>
                        <!--/LIST:items-->
                        <td style="text-align: right;padding-right: 5px;"><b>[[|room_level.room_soild|]]</b></td>
                   </tr>
                   <?php }?>
                   <!--/LIST:room_level-->
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#C6E2FF">
                        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>[[.total.]]</td>
                        <!--LIST:total_day-->
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sat'){ echo 'color: blue';} ?>"><b>[[|total_day.room_soild|]]</b></td>
                        <!--/LIST:total_day-->
                        <td style="text-align: right;padding-right: 5px;"><b>[[|total_room_soild|]]</b></td>
                   </tr>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#FAF0E6">
                        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>%[[.oc.]]</b></td>
                        <!--LIST:total_day-->
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sat'){ echo 'color: blue';} ?>"><b>[[|total_day.oc|]]</b></td>
                        <!--/LIST:total_day-->
                        <td style="text-align: right;padding-right: 5px;border-left: none;"><b>[[|total_oc|]]</b></td>
                   </tr>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#FAF0E6">
                        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>[[.available_room1.]]</b></td>
                        <!--LIST:total_day-->
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo [[=total_day.avail_room=]]-[[=total_day.room_soild=]]-[[=total_day.repair_room=]];?></b></td>
                        <!--/LIST:total_day-->
                        <td style="text-align: right;padding-right: 5px;"><b><?php echo [[=total_avail_room=]]-[[=total_room_soild=]]-[[=total_repair_room=]];?></b></td>
                   </tr>
                   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#CDC8B1">
                        <td style="text-align: center;padding: 0px; width: 50px;"><b>[[.stt.]]</b></td>
                        <td style="text-align: center;padding: 0px; width: 50px;"><b>[[.recode.]]</b></td>
                        <td style="text-align: center;padding: 0px; width: 150px;"><b>[[.customer.]]</b></td>
                        <td style="text-align: center;padding: 0px; width: 150px;"><b>[[.booker.]]</b></td>
                        <?php for($i=[[=time_from_day=]] ; $i<=[[=time_to_day=]]; $i +=24*3600){?>
                        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',$i)=='Sun'){echo 'color: red';}elseif(Date('D',$i)=='Sat'){ echo 'color: blue';} ?>"><b><?php echo date('d',$i);?></b></th>
                        <?php }?>
                        <td style="text-align: right;padding-right: 5px;"><b>[[.total.]]</b></td>
                        <td style="text-align: center;padding: 0px;"><b>[[.number_phone.]]</b></td>
                        <!--<td style="text-align: center;padding: 0px;"><b>[[.email.]]</b></td> -->
                        <td style="text-align: center;padding: 0px;"><b>[[.deposit.]]</b></td>
                        <td style="text-align: center;"><b>[[.cut_of_date.]]</b></td>
                        <td style="text-align: left;"><b>[[.group_note.]]</b></td>
                   </tr>
                   <?php $j=1;?>
                   <!--LIST:recode_info-->
                   <tr style="height:30px; border:1px solid #ccc;">
                        <td style="text-align:center;"><?php echo $j++;?></td>
                        <td style="text-align:center;"><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=recode_info.id=]]));?>" target="_blank">[[|recode_info.id|]]</a></td>
                        <td style="text-align:left;padding-left: 3px;">[[|recode_info.customer_name|]]</td>
                        <td style="text-align:left;padding-left: 3px;">[[|recode_info.booker|]]</td>
                        <?php for($i=[[=time_from_day=]] ; $i<=[[=time_to_day=]]; $i +=24*3600){?>
                                <?php if(isset([[=recode_info.num_room=]][date('d/m/Y',$i)]) and [[=recode_info.num_room=]][date('d/m/Y',$i)]['room_count']!=0){?>
                                    <td style="text-align:right;padding-right: 5px;<?php if(Date('D',$i)=='Sun'){echo 'color: red';}elseif(Date('D',$i)=='Sat'){ echo 'color: blue';} ?>" title="[[|recode_info.room_level_info|]]"><?php echo [[=recode_info.num_room=]][date('d/m/Y',$i)]['room_count'];?></td>
                                <?php }else{?>
                                    <td></td>
                                <?php }?>
                            
                        <?php }?>
                        <td style="text-align:right;padding-right: 5px;"><b><?php echo System::display_number([[=recode_info.revenue=]]);?></b></td>
                        <td style="text-align:center;width: 80px;">[[|recode_info.phone_booker|]]</td>
                        <!--<td style="text-align:left;padding-left: 3px;">[[|recode_info.email_booker|]]</td> -->
                        <td style="text-align:right;padding-right: 5px"><?php echo System::display_number([[=recode_info.deposit=]]);?></td>
                        <td style="text-align:center;width: 70px;">[[|recode_info.cut_of_date|]]</td>
                        <td style="text-align:left;">[[|recode_info.group_note|]]</td>
                   </tr>
                   <!--/LIST:recode_info-->
                </table>
            </div> 		 
        </td>
     </tr> 
</table>
<table border="1px" style="width:100%; font-size:11px;background: white; position: absolute; top:0px; z-index:99999; left:15px;display:none;"  id="table_head">
    <?php $rl2 = 1;?>
   <!--LIST:room_level-->
   <?php if($rl2==1){ ?>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;">
        <td rowspan="<?php echo sizeof([[=room_level=]])-1;?>" style="width:100px;text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b>[[.room_level.]]</b></td>
        <td colspan="3" style="text-align: left;padding-left: 3px;" bgcolor="#F0FFF0"><b>[[|room_level.name|]]</b></td>
        <!--LIST:items-->
            <?php if([[=room_level.id=]]==[[=items.room_level_id=]]){?>
                <td bgcolor="#F0FFF0" style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sat'){ echo 'color: blue';} ?>">[[|items.room_soild|]]</td>
            <?php }?>
        <!--/LIST:items-->
        <td style="text-align: right;padding-right: 5px;"bgcolor="#F0FFF0"><b>[[|room_level.room_soild|]]</b></td>
        <td colspan="4" style="padding: 0px;" bgcolor="#EFEFEF" rowspan="<?php echo sizeof([[=room_level=]])-1+3;?>"></td>
   </tr>
   <?php $rl2++;}else{?>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#F0FFF0">
        <td colspan="3" style="text-align: left;padding-left: 3px;"><b>[[|room_level.name|]]</b></td>
        <!--LIST:items-->
            <?php if([[=room_level.id=]]==[[=items.room_level_id=]]){?>
                <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=items.in_date=]]))=='Sat'){ echo 'color: blue';} ?>">[[|items.room_soild|]]</td>
            <?php }?>
        <!--/LIST:items-->
        <td style="text-align: right;padding-right: 5px;"><b>[[|room_level.room_soild|]]</b></td>
   </tr>
   <?php }?>
   <!--/LIST:room_level-->
   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#C6E2FF">
        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>[[.total.]]</td>
        <!--LIST:total_day-->
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sat'){ echo 'color: blue';} ?>"><b>[[|total_day.room_soild|]]</b></td>
        <!--/LIST:total_day-->
        <td style="text-align: right;padding-right: 5px;"><b>[[|total_room_soild|]]</b></td>
   </tr>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;"bgcolor="#FAF0E6">
        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>%[[.oc.]]</b></td>
        <!--LIST:total_day-->
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sat'){ echo 'color: blue';} ?>"><b>[[|total_day.oc|]]</b></td>
        <!--/LIST:total_day-->
        <td style="text-align: right;padding-right: 5px;border-left: none;"><b>[[|total_oc|]]</b></td>
   </tr>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;"bgcolor="#FAF0E6">
        <td colspan="4" style="text-align: left;padding-left: 3px;"><b>[[.available_room1.]]</b></td>
        <!--LIST:total_day-->
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sun'){echo 'color: red';}elseif(Date('D',Date_time::to_time([[=total_day.in_date=]]))=='Sat'){ echo 'color: blue';} ?>"><b><?php echo [[=total_day.avail_room=]]-[[=total_day.room_soild=]]-[[=total_day.repair_room=]];?></b></td>
        <!--/LIST:total_day-->
        <td style="text-align: right;padding-right: 5px;"><b><?php echo [[=total_avail_room=]]-[[=total_room_soild=]]-[[=total_repair_room=]];?></b></td>
   </tr>
   <tr valign="middle" style="height:30px;border:1px solid #ccc;" bgcolor="#CDC8B1">
        <td style="text-align: center;padding: 0px;"><b>[[.stt.]]</b></td>
        <td style="text-align: center;padding: 0px;"><b>[[.recode.]]</b></td>
        <td style="text-align: center;padding: 0px;"><b>[[.customer.]]</b></td>
        <td style="text-align: center;padding: 0px;"><b>[[.booker.]]</b></td>
        <?php for($i=[[=time_from_day=]] ; $i<=[[=time_to_day=]]; $i +=24*3600){?>
        <td style="text-align: right;padding-right: 5px;<?php if(Date('D',$i)=='Sun'){echo 'color: red';}elseif(Date('D',$i)=='Sat'){ echo 'color: blue';} ?>"><b><?php echo date('d',$i);?></b></th>
        <?php }?>
        <td style="text-align: right;padding-right: 5px;"><b>[[.total.]]</b></td>
        <td style="text-align: center;padding: 0px;"><b>[[.phone.]]</b></td>
        <!-- <td style="text-align: center;padding: 0px;"><b>[[.email.]]</b></td> -->
        <td style="text-align: center;padding: 0px;"><b>[[.deposit.]]</b></td>
        <td style="text-align: center;"><b>[[.cut_of_date.]]</b></td>
        <td style="text-align: left;"><b>[[.group_note.]]</b></td>
   </tr>
</table>
</form>
</div>
<script>
jQuery("#from_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#to_day").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR ?>,1, 1) });
jQuery("#cut_of_date").datepicker();
var customer = [[|customer_js|]]; 
/**
jQuery(document).ready(
    function()
    {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    }
);**/
jQuery(document).ready(
        function()
        {   
            jQuery("#table_head").width(jQuery("#tblExport").width());
            jQuery("table#table_head").width(jQuery("table#tblExport").outerWidth()+"px");
            jQuery("table#table_head tr td").each(function(){
                var index = jQuery("table#table_head tr td").index(jQuery(this));
                var element  = jQuery("table#tblExport tr td").get(index);
                var width = to_numeric(jQuery(element).outerWidth());
                jQuery(this).width(width+"px");
            });
            jQuery(window).scroll(function() {
                var documentScrollTop = jQuery(window).scrollTop();
                if(documentScrollTop>300){
                   jQuery("table#table_head").css({"top":documentScrollTop+"px","display":""}); // Bi thay doi boi "display":"in-line"
                }
                else{
                   jQuery("table#table_head").css("display","none");  
                }
            });
            for(var i in customer){                
                jQuery('.customer').each(function(){                    
                    if(jQuery('#'+this.id).attr('flag') == customer[i])
                    {
                        jQuery('#'+this.id).attr('checked', true);                                                
                    }
                })
            }
        }); 
 
function changevalue()
    {
        var myfromdate = $('from_day').value.split("/");
        var mytodate = $('to_day').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_day").val(jQuery("#from_day").val());
        }
    }
function changefromday()
    {
        var myfromdate = $('from_day').value.split("/");
        var mytodate = $('to_day').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_day").val(jQuery("#to_day").val());
        }
    }
var expanded_customer = false;    
function showCheckboxes(value) {
  if(value =='customer'){
    var checkboxes_customer = document.getElementById("checkboxes_customer");
      if (!expanded_customer) {
        checkboxes_customer.style.display = "block";
        expanded_customer = true;
      } else {
        checkboxes_customer.style.display = "none";        
        expanded_customer = false;
      }
  }            
}
jQuery(document).on('click', function(e) {
      var $clicked = jQuery(e.target);
     if (!$clicked.parents().hasClass("multiselect_customer")) jQuery('#checkboxes_customer').hide();
    });
function get_ids(value)
{           
    var strids = "";
    var str_customer = "";
    var customer_id = "";  
    if(value=='customer'){
        var inputs = jQuery('.customer:checkbox:checked');            
        for (var i=0;i<inputs.length;i++)
        {  
            if(inputs[i].id.indexOf('customer_')==0)
            {
                str_customer +=","+"'"+inputs[i].id.replace("customer_","")+"'";
                customer_id +=","+inputs[i].id.replace("customer_","");                
            }
        }                
        str_customer = str_customer.replace(",","");
        customer_id = customer_id.replace(",","");             
        jQuery('#customer_ids').val(str_customer);
        jQuery('#customer_id_').val(customer_id);             
    }                
}
</script>
