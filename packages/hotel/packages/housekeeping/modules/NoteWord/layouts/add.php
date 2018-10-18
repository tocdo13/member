<style>
.clear{clear:both;}
.gio{width: 100px;}
.n2{margin-top: 20px;}
.autoUpdate{width: 400px;height: 200px;background: #cdcdcd;}
.warper-note{padding-left: 25px;
background: #fff;
float: left;
width: 78%;
margin: 0 11%;
border: 2px solid #cdcdcd;
font-weight:500 !important;
}

.navbar h3 {
      color: #f5f5f5;
      margin-top: 14px;
}
.hljs-pre {
      background: #f8f8f8;
      padding: 3px;
}
.footer {
      border-top: 1px solid #eee;
      margin-top: 40px;
      padding: 40px 0;
}
.input-group {
      width: 65px;
      margin-bottom: 10px;
}
.pull-center {
      margin-left: auto;
      margin-right: auto;
}
@media (min-width: 768px) {
  .container {
    max-width: 730px;
  }
}
@media (max-width: 767px) {
  .pull-center {
    float: right;
  }
}
label{white-space: nowrap;}
.to-date{padding:0 9px;padding-top:3px;}
.form-control{height:23px !important;}
.popover:first-child{left:225px !important ;}
.content_repeat{display:inline;}
.edit_con{color:red !important;}
.mo{background:#cdcdcd;}
.autoUpdate{margin-top:20px;display:none;border: 1px solid #cdcdcd;
background: #DDDCDC;width:400px}
.them{display: block;}
.showweek{display:none;}
.hide_date{display:none;}
.hide_date1{display:none;}
.radio_month{display:none;}
.checkbox2{margin-left: 10px !important;}
.widthinput{width:144px;}
</style>

<link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/github.min.css"/>


<div class="warper-note" id="warper-note">
<form name="EquipmentForm" id="EquipmentForm" method="post" onsubmit="return validateForm();" >
    <div>
<div style="float: right;">
	<a style="margin-right: 5px; text-transform: uppercase; text-decoration: none;" href="<?php echo Url::build_current();?>" class="w3-btn w3-green">[[.back.]]</a>
<input name="save" type="submit" id="save" class="w3-btn w3-orange w3-text-white" value="[[.Save.]]" style="margin-right: 5px; text-transform: uppercase; text-decoration: none;"/>
<input type="reset"  class="w3-btn w3-lime" value="[[.cancel.]]" style="text-transform: uppercase; text-decoration: none;"/>
</div>
</div>
<div class="clear"></div>

<div class="note_content">
      <div> 
      <label>[[.title.]]</label>
     <input name="title" type="text" id="title" size="30"/>
     <label>Đã thực hiện</label>
     <input name="status" type="checkbox" id="status" <?php if(isset([[=doned=]]) && [[=doned=]] !='') echo 'checked="checked"'; ?> value="1" />  
      </div>
</div>
<div class="n2">
<table>
<tr>
        <td><label class="to-date" style="padding-left: 0px;">Từ</label>
        <input  name="from_date" type="text" id="from_date" value="<?php if(isset($_REQUEST['from_date'])){echo $_REQUEST['from_date'];}else{ echo date("d/m/Y");} ?>" class="widthinput"/>
        </td>
        <td class="hour"> 
            <input name="hour_from" type="text" id="hour_from" onkeyup="check_time();" style="width: 40px;"/>
        </td>
        <td> <label class="to-date">Đến</label></td>
        <td><input  name="to_date" type="text" id="to_date" value="<?php if(isset($_REQUEST['to_date'])){echo $_REQUEST['to_date'];}else{ echo date("d/m/Y");} ?>" class="widthinput"/></td>
        <td class="hour">
                <input name="hour_to" type="text" id="hour_to" style="width: 40px;"/>
        </td>
       
       
</tr>
</table>
 

</div>
<div class="n2">
<input name="repeat_full" type="checkbox" id="full_time" <?php if(isset([[=full_day=]]) && [[=full_day=]]==2) echo 'checked="checked"'; ?> value="2"  />
 <p class="content_repeat" id="content_repeat">Cả ngày</p>
 
<input name="repeat" type="checkbox" id="checkbox1" class="checkbox2" <?php if(isset([[=repeat=]]) && [[=repeat=]]==1) echo 'checked="checked"'; ?> value="1"  />
 <p class="content_repeat" id="content_repeat">Lặp lại :</p>
     <p class="content_repeat" id="change_summer"> </p>
 <?php if(isset([[=repeat=]]) && [[=repeat=]]){?>
   
 
    <a class="edit_con" href="#" id="edit_content">Chỉnh sửa</a>
 <?php } ?>




<div id="autoUpdate" class="autoUpdate" >
   <table width="500px" style="margin-left: 15px;">
   
   
        <tr style="height:30px;">
        <td style="width:75px;">Lần lặp lại:</td>
         <td><select name="repeat_type" id="repeat_type">
         </select></td>
        </tr>
        
        
        <tr style="height: 30px;">
        <td>Lặp lại mỗi: </td>
        <td><select name="repeat_time" id="repeat_time"></select><span id="for_id">
        <?php
        if(isset($_REQUEST)){
            if(isset($_REQUEST['REPEAT_TYPE'])){
                if($_REQUEST['REPEAT_TYPE']==1){
                    echo 'Ngày';
                }else if($_REQUEST['REPEAT_TYPE']==2){
                    echo 'Tuần';
                }else if($_REQUEST['REPEAT_TYPE']==3){
                    echo 'Tháng';
                }else{
                    echo 'Năm';
                }
            }else{
                echo 'Ngày';
            }
        }
        ?>
       
        
        </span></td></tr>
          
          <tr id="radio_month" class="radio_month">
            <td>Lặp lại theo:</td>
            <td>
            <input type="radio" name="repeat_month" <?php if(isset([[=repeat_month=]])&& [[=repeat_month=]]==1) echo 'checked="checked"'; ?> value="1" />
          <span>ngày trong tháng</span>
          <input type="radio" name="repeat_month" <?php if(isset([[=repeat_month=]])&& [[=repeat_month=]]==2) echo 'checked="checked"'; ?>  value="2" />
          <span>ngày trong tuần</span>
            </td>
          </tr>
            
          <tr id="tr_check" class="showweek" style="height: 30px;"><td>Lặp lại vào:</td>
          <td>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/1/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="1" checked="checked"/><span>cn</span>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/2/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="2"/><span>t2</span>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/3/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="3"/><span>t3</span>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/4/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="4"/><span>t4</span>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/5/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="5"/><span>t5</span>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/6/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="6"/><span>t6</span>
          <input name="check_list[]" type="checkbox" <?php if(isset([[=repeat_on=]])){if (preg_match("/7/", [[=repeat_on=]])) { echo "checked";} else {echo "";}} ?> value="7"/><span>t7</span>
          </td>
          </tr>
          
           <tr id="hide_date1" style="height: 30px;"><td>Bắt đầu vào:</td>
           <td>
            <input  name="from_date1" type="text" id="edit_date" class="edit_day" value="<?php if(isset($_REQUEST['from_date'])){echo $_REQUEST['from_date'];}else{ echo date("d/m/Y");} ?>" style="width: 100px;" readonly="read"/>
           </td>
          </tr>
          
          <tr class="hide_date" id="hide_date" style="height: 30px;"><td>Bắt đầu vào:</td>
           <td>
            <input name="from_dateh" type="text" id="from_dateh" style="width: 100px;" />
           </td>
          </tr>
          
          <tr style="height: 80px;">
          <td>Kết thúc:
          </td>
          <td>
          <div>
          <input type="radio" name="ends" id="ra1" <?php if(isset([[=ends=]])&& [[=ends=]]==1) echo 'checked="checked"'; ?> checked="checked" value="1"/>
          <span>Chưa bao giờ</span>
          </div>
          <div>
          <input type="radio" name="ends" id="ra2" <?php if(isset([[=ends=]])&& [[=ends=]]==2) echo 'checked="checked"'; ?> value="2" />
          <span>sau</span>
          <input name="end_time[1]" type="text" id="end_time_1" <?php if(isset([[=ends=]])&& [[=ends=]]==2) {echo "value = '".[[=end_time=]]."'";}?>  /><span>lần</span>
          </div>
          <div>
          <input type="radio" name="ends" id="ra3" <?php if(isset([[=ends=]])&& [[=ends=]]==3) echo 'checked="checked"'; ?> value="3" />
          <span>vào</span>
          <input name="end_time[2]" type="text" id="end_time_2" <?php if(isset([[=ends=]])&& [[=ends=]]==3) {echo "value = '".[[=end_time=]]."'";}?>  />
          </div>
          </td>
          </tr>
            
   </table>
</div>

</div>
<div class="n2">
 Mô tả
</div>
<div class="n2">
 <textarea name="description" id="description" rows="4" cols="50" style="width: 400px;">
</textarea>
</div>
<div class="n2" style="margin-bottom: 20px;">
 <label style="font-weight: normal;">Thời gian nhắc trước:</label>
 <input name="time_remin" type="text" id="time_remin" />
     <select name="type_time_remin" id="type_time_remin">
          
     </select>
</div>

</form>
</div>
        <?php 
        $repeats=0;
        if(isset($_REQUEST['REPEAT_TYPE'])){
            if($_REQUEST['REPEAT_TYPE']){
                $repeats=$_REQUEST['REPEAT_TYPE'];
            }
            
        }
         ?>
<script type="text/javascript"> 

    jQuery(document).ready(function(){
        if(jQuery('#full_time').is(":checked")){
            jQuery('.hour').css('display','none');
        }
        jQuery('#ra1').click(function(){
           document.getElementById("end_time_1").value='';
            document.getElementById("end_time_1").disabled=true;
            document.getElementById("end_time_2").value='';
            document.getElementById("end_time_2").disabled=true;
        });
        jQuery('#ra2').click(function(){
           document.getElementById("end_time_2").value='';
            document.getElementById("end_time_2").disabled=true;
            jQuery(this).parents('td').find('#end_time_1').attr('disabled',false);
        });
        jQuery('#ra3').click(function(){
           document.getElementById("end_time_1").value='';
            document.getElementById("end_time_1").disabled=true;
            jQuery(this).parents('td').find('#end_time_2').attr('disabled',false);
        });
        
        jQuery('#full_time').click(function(){
      
        if(jQuery(this).is(":checked"))
        {
         jQuery(this).parents('#warper-note').find('.hour').css('display','none');
      //  jQuery(this).parents('.n2').find('#checkbox1').removeAttr('checked');
        }
        else{
          jQuery(this).parents('.warper-note').find('.hour').css('display','block');
        }
    });
        
        jQuery('#from_date').change(function(){
            document.getElementById("edit_date").value=jQuery(this).val();
        });
        jQuery('#checkbox1').click(function(){
      
        if(jQuery(this).is(":checked"))
        {
         jQuery(this).parents('.n2').find('#autoUpdate').css('display','block');
        }
        else{
          jQuery(this).parents('.n2').find('#autoUpdate').css('display','none');
        }
    });
    

     jQuery('#edit_content').click(function(){
              var repeats=0;
         repeats= <?php echo $repeats; ?>;
        if(repeats==2){
            jQuery(this).parents('.n2').find('#tr_check').removeClass('showweek');     
        }
        if(repeats==3){
            jQuery(this).parents('.n2').find('#radio_month').removeClass('radio_month');     
        }
               
        if(jQuery(this).hasClass('edit_con')){
          jQuery(this).parent().find('#autoUpdate').css('display','block');
            jQuery(this).removeClass('edit_con');
        
        }
       else{
             jQuery(this).parent().find('#autoUpdate').css('display','none');
            jQuery(this).addClass('edit_con');
             
        }
        
        
     });
     
    
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        
        jQuery('#end_time_2').datepicker();
        jQuery('#to_day').datepicker();
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
        
        jQuery('#hour_from').mask("99:99");
        jQuery('#hour_to').mask("99:99");
       // jQuery('#tr_check').css('display','none');
       
      jQuery('#repeat_type').change(function(){
      //   jQuery(this).parents('table').find('#repeat_time').val('1');
//         jQuery(this).parents('table').find('#ra1').checked();
            // var e = document.getElementById("repeat_event");
//                var re_very = e.options[e.selectedIndex].value;
                    
             var e = document.getElementById("repeat_type");
            var strUser = e.options[e.selectedIndex].value;
            
           // alert(strUser);
            if(strUser==1){
                 jQuery(this).parents('table').find('#hide_date').addClass('hide_date');
                jQuery(this).parents('table').find('#radio_month').addClass('radio_month');
                jQuery(this).parents('table').find('#tr_check').addClass('showweek');
               document.getElementById("for_id").innerHTML=" Ngày";
               document.getElementById("change_summer").innerHTML = "Hàng Ngày";
              
            }else if(strUser==2){
                 jQuery(this).parents('table').find('#hide_date').addClass('hide_date');
                jQuery(this).parents('table').find('#radio_month').addClass('radio_month');
                jQuery(this).parents('table').find('#tr_check').removeClass('showweek');
                 document.getElementById("for_id").innerHTML="Tuần";
                
                if(re_very==1){
                document.getElementById("change_summer").innerHTML = "Hàng Tuần vào thứ 3";
                }
                 //document.getElementById("change_summer").innerHTML = re_very+"ngày 1 lần";
              //   document.getElementById("demo").innerHTML
            }
            else if(strUser==3){
                jQuery(this).parents('table').find('#radio_month').removeClass('radio_month');
                jQuery(this).parents('table').find('#tr_check').addClass('showweek');
                document.getElementById("for_id").innerHTML="Tháng";
                if(re_very==1){
                document.getElementById("change_summer").innerHTML = "Hàng Tháng vào ngày 13";
                 jQuery(this).parents('table').find('#hide_date1').addClass('hide_date');
                 jQuery(this).parents('table').find('#hide_date').addClass('hide_date');
                }
            }
            else{
                jQuery(this).parents('table').find('#hide_date1').addClass('hide_date1');
                jQuery(this).parents('table').find('#hide_date').removeClass('hide_date');
                jQuery(this).parents('table').find('#radio_month').addClass('radio_month');
                jQuery(this).parents('table').find('#tr_check').addClass('showweek');
                document.getElementById("for_id").innerHTML="Năm";
            }
           
         });
         
         
         jQuery('#repeat_type').on(click(),function(){
            
             var e = document.getElementById("repeat_event");
            var re_very = e.options[e.selectedIndex].value;
            var e = document.getElementById("repeat_type");
            var re = e.options[e.selectedIndex].value;
            if(re==1){
                jQuery(this).parents('.n2').find('#change_summer').innerHTML="Nam";
                document.getElementById("change_summer").innerHTML = re_very+"ngày 1 l?n";
            }else if(re==2){
                document.getElementById("change_summer").innerHTML = re_very+"tu?n 1 l?n";
            }else if(re==3){
                document.getElementById("change_summer").innerHTML = re_very+"tháng 1 l?n";
            }
            else{
                document.getElementById("change_summer").innerHTML = re_very+"nam 1 l?n";
            }
            
            alert(strUser);
         });

    });
   
</script>



<script type="text/javascript">
function validateForm(){
    
     var title = document.forms["EquipmentForm"]["title"].value;
     var hour_from = document.forms["EquipmentForm"]["hour_from"].value;
     var hour_to = document.forms["EquipmentForm"]["hour_to"].value;
     if(hour_from.length && hour_from.length !='' && hour_from.length<4){
        alert('Bạn phải nhập giờ theo định dạng 00:00')
     }else{
         var hour_from =  hour_from.split(':');
         if(hour_from[0]>23){
            alert('Hour không được lớn hơn 23'); return false;
         }
         if(hour_from[1]>59){
            alert('Số phút không được lớn hơn 59'); return false;
         }
     }
      if(hour_to.length && hour_to.length !='' && hour_to.length<4){
        alert('Bạn phải nhập giờ theo định dạng 00:00'); return false;
     }else{
         var hour_to =  hour_to.split(':');
         if(hour_to[0]>23){
            alert('Hour không được lớn hơn 23'); return false;
         }
         if(hour_to[1]>59){
            alert('Số phút không được lớn hơn 59'); return false;
         }
     }
     
     if(hour_to[0]<hour_from[0]){
        alert('Thời gian kết thúc không được nhỏ hơn thời gian bắt đầu');
        return false;
     }
    if (title==null || title=="") {
        alert("Title không được để trống !");
        document.forms["EquipmentForm"]["title"].focus();
        return false;
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
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
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



</script>

<script src="packages/core/includes/js/jquery/datepicker.js">
</script>



