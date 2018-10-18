<style>
.both{clear:both;}
.warrap_2{width:33%;border: 1px solid #cdcdcd; float:left; margin-bottom: 10px;}
.left{float:left;}

.h_v1{width:30%;}
.h_v2{width:70%;  margin: 13px 0;}
.h_v21{ font: bold 25px Arial;text-transform: uppercase;color: #4F4CB6;}
.h_v22{font: bold 22px Arial;text-transform: uppercase;color: black;}
.img_bf{width: 100%;margin:0 auto;display:block}
.center{text-align:left;margin-left:20px;font: normal 13px Arial;}
.c2{margin:5px 20px;}
.foo{margin-bottom:5px;}
.none{display:none;}
.footer_bf_1{
    font-size:13px;
    font-family: Arial;
}
#ui-datepicker-div{
    z-index:999999;
}
.no-border td{
    border:none;
}
</style>
<script src="packages/core/includes/js/JsBarcode.all.min.js"></script>
<div>
<?php
    if(!empty($this->map['list_customer'])){
        $i=1;
        
?>  
<div id="button_print" style="float: right; margin-bottom:20px; margin-right:20px">
    <label for="time_group">Nhập giờ cho cả đoàn : </label><input name="time_group" id="time_group" readonly=""  />
    <button type="button" onclick="print_group();" style="margin-left: 10px;"><?php echo Portal::language('print');?></button>
</div>
<div style="clear: both;"></div>
<div id="print_group_div">
    
      <?php if(isset($this->map['list_customer']) and is_array($this->map['list_customer'])){ foreach($this->map['list_customer'] as $key1=>&$item1){if($key1!='current'){$this->map['list_customer']['current'] = &$item1;?> 
      <?php
            if((date("H")*3600+date("i")*60+date("s"))>($this->map['end_time_breakfast'])) //|| (date("H",$this->map['list_customer']['current']['time_in'])*3600+date("i",$this->map['list_customer']['current']['time_in'])*60+date("s",$this->map['list_customer']['current']['time_in']))>$this->map['end_time_breakfast'])
            {
                $this->map['list_customer']['current']['time_in'] = Date_Time::to_time(date("d/m/Y",time()+86400));
            }          
            // Truong hop ngay in toi thieu la ngay hien tai
      
                                    
        ?>
      <div style="page-break-after: always;"> 
       <table class="print_table" width="25%" align="left" class="warrap_2" >
       <tbody> 
        <tr class="no-border">
            <td width="100%">
                  <div style="font-size: 13px; padding:10px;">
                    <div>
                        <div style="width:100%"  align="center" ><img style="margin-top:10px; width:25%;" src="<?php echo HOTEL_LOGO;?>"/></div>                    
                        <div style="text-align: right; width:100%" class="no_seri">No.#<span class="no_<?php echo $this->map['list_customer']['current']['reservation_room_id'];?>"></span></div>
                        <div class="h_v">
                            <div> 
                                <div class="h_v21" style="text-align: center;  font-weight: bold; z-index: 999;"><?php echo HOTEL_NAME; ?> Restaurant</div>
                                <div class="h_v22" style="text-align: center; z-index: 999;font-weight: bold;">★ ★ ★</div>
                                <div class="h_v22" style="text-align: center; z-index: 999;font-weight: bold;">PHIẾU ĂN SÁNG</div>
                                <div class="h_v22" style="text-align: center; z-index: 999;font-weight: bold;">Breakfast Coupon</div>
                            </div>
                        </div>
                        <div class="both"></div>
                        <div>
                            <!--<div class="center" style="margin-top: 16px;">
                                <label>Nguồn khách/ Agent: </label><span style="font-weight: bold;"><?php echo $this->map['customer_name'];?></span>
                            </div>-->
                            <div style="margin-top:5px;" class="center">
                                <label>Tên khách/ GuestName : </label><span class="guest_name" style="word-break: break-all; font-weight: bold; width:100%;"><?php echo $this->map['list_customer']['current']['last_name'];?></span>
                            </div>
                            <div style="margin-top:5px; width:100%;" class="center">
                                <?php
                                    if(isset($this->map['list_customer']['current']['is_child']) && $this->map['list_customer']['current']['is_child']!=1)
                                    {
                                ?>
                                <div style="width: 100%; float:left;">
                                    <label>Vé người lớn (Adult ticket)</label>
                                </div>
                                <?php
                                    }
                                ?>
                                <?php
                                    if(isset($this->map['list_customer']['current']['is_child']) && $this->map['list_customer']['current']['is_child']==1)
                                    {
                                ?>
                                <div style="width: 100%; float:left;">
                                    <label>Vé trẻ em (Child ticket)</label>
                                </div> 
                                <?php
                                    }
                                ?>                                                               
                            </div>                            
                            <div  style="margin-top:5px;" class="center">
                                <div>Số phòng/ Room no.#
                                <?php if(!isset($this->map['is_group'])){
                                 ?>
                                 <span style="font-weight: bold; font-size:18px;"><?php echo $this->map['room_name'];?></span>
                                 <?php   
                                } 
                                else{
                                ?>
                                  <span style="font-weight: bold; font-size:18px;"><?php echo $this->map['list_customer']['current']['room_name'];?></span>  
                                <?php    
                                }
                                ?>                               
                                </div>
                            </div>
                            <div style="margin-top: 5px;" class="center"> Thời gian/Time : <span style="font-weight: bold;"><?php echo BREAKFAST_FROM_TIME." - ".BREAKFAST_TO_TIME; ?></span>
                            </div>
                        </div>
                        <div class="center c2" style="margin-top: 5px;">
                            <div>
                                <span style="float: left;">Ngày dùng/ Valid date : </span>
                                <input id="get_time_print_<?php echo $i; ?>" name="get_time_print_<?php echo $i; ?>" type="text"  class="date-select none1" readonly="" style="width: 120px; float:left;"/>
                                <p class="input_time_<?php echo $i; ?> none" style="font-weight: bold;"></p>
                                <p style="display: none;" class="time_in"><?php echo date('d/m/Y',$this->map['list_customer']['current']['time_in']); ?></p>
                                <p style="display: none;" class="time_in_timestamp"><?php echo $this->map['list_customer']['current']['time_in'];?></p>
                                <p style="display: none;" class="time_out"><?php echo date('d/m/Y',$this->map['list_customer']['current']['time_out']); ?></p>
                                <p style="display: none;" class="time_out_timestamp"><?php echo $this->map['list_customer']['current']['time_out'];?></p>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                        <div style="text-align:  center; width:100%;">
                            <div class="footer_bf_1" style="font-size: 13px;">Vui lòng gửi Phiếu ăn sáng cho nhân viên Nhà hàng</div>
                            <div class="footer_bf_1" style="font-size: 13px;">Pls present this Coupon to your server.</div>
                            <div class="footer_bf_1" style="font-size: 13px;">Chúc quý khách ngon miệng !</div>
                            <div class="footer_bf_1" style="font-size: 13px;">Enjoy your meal</div>
                        </div>
                        <div class="reservation_room_<?php echo $this->map['list_customer']['current']['reservation_room_id'];?>" stt="<?php echo $i; ?>" guest_name="<?php echo $this->map['list_customer']['current']['last_name'];?>" is_child="<?php echo $this->map['list_customer']['current']['is_child'];?>" reservation_room_id="<?php echo $this->map['list_customer']['current']['reservation_room_id'];?>" style="width: 100%; text-align:center;">
                            <svg id="barcode_<?php echo $i; ?>"></svg>
                        </div>
                        <div id="reprint_<?php echo $i; ?>" style="text-align: center; width: 50%; float:right; border: black thin solid; display:none; margin-top: 10px;">
                            In lại lần thứ <span id="reprint_nth_<?php echo $i; ?>">1</span>
                        </div>
                    </div>
                </div> 
                </td> 
            </tr> 
            </tbody>
        </table> 
       </div>
            <?php
            $i++;        
         ?>
        <?php }}unset($this->map['list_customer']['current']);} ?>          
 </div>     
<?php   
    }   
    else{
?>
    <p style="color: red; font-weight: bold;">Phòng chưa có khách hoặc chưa nhập số lượng người lớn, trẻ em hoặc không có xác nhận ăn sáng. Xin vui lòng kiểm tra lại !</p>
    <script>
        jQuery("#button_print").remove();
    </script>
<?php
    }
?>
</div>
<script>
<?php
    // Truong hop ngay in toi thieu la ngay hien tai
    if((date("H")*3600+date("i")*60+date("s"))>($this->map['end_time_breakfast']))
    {
        $this->map['time_in'] = Date_Time::to_time(date("d/m/Y",time()+86400));
    }
    
?>
var miny= <?php echo date('Y',$this->map['time_in']);?>;
var minm= <?php echo intval(date('m',$this->map['time_in']))-1;?>;
var mind = <?php echo date('d',$this->map['time_in']);?>;
var maxy = <?php echo date('Y',$this->map['time_out']);?>;
var maxm= <?php echo intval(date('m',$this->map['time_out']))-1;?>;
var maxd = <?php echo date('d',$this->map['time_out']);?>;
var count = <?php if(isset($i)) echo $i; else echo "0"; ?>;
   if(count!=0){ 
    for(var i=1; i<count; i++){
        var time_in = jQuery("#get_time_print_"+i).parent().find(".time_in").html();
        var time_in_arr = time_in.split('/');
        var time_out = jQuery("#get_time_print_"+i).parent().find(".time_out").html();
        var time_out_arr = time_out.split('/');
       jQuery("#get_time_print_"+i).datepicker({minDate: new Date(time_in_arr[2],time_in_arr[1]-1,time_in_arr[0]),maxDate: new Date(time_out_arr[2],time_out_arr[1]-1,time_out_arr[0])}); 
       jQuery('#get_time_print_'+i).change(function(){
        jQuery(this).next().html(jQuery(this).val());
        }); 
    }
     jQuery("#time_group").datepicker({minDate: new Date(miny,minm,mind),maxDate: new Date(maxy,maxm,maxd)});
     jQuery('#time_group').change(function(){
        var time_print = jQuery(this).val();
        var time_print_arr = time_print.split('/');
        var date = new Date(time_print_arr[2], parseInt(time_print_arr[1], 10) - 1, time_print_arr[0]);
        var date_timestamp = date.getTime()/1000;
        if(date.getDate()==7){ // Ngay chu nhat thoi gian an sang tu 6h30 - 09h30;
            start_time_breakfast = date_timestamp+<?php echo $this->map['start_time_breakfast'];?>;
            end_time_breakfast = date_timestamp+<?php echo $this->map['end_time_breakfast'];?>;
        }
        else{ // Ngay binh thuong thoi gian an sang tu 6h30 - 9h30;
            start_time_breakfast = date_timestamp+<?php echo $this->map['start_time_breakfast'];?>;
            end_time_breakfast = date_timestamp+<?php echo $this->map['end_time_breakfast'];?>;
        }
        for(var i=1; i<count; i++){
            var time_in_timestamp = jQuery("#get_time_print_"+i).parent().find(".time_in_timestamp").html();
            var date_time_in = new Date(time_in_timestamp*1000);
            
            var time_out_timestamp = jQuery("#get_time_print_"+i).parent().find(".time_out_timestamp").html();
            var date_time_out = new Date(time_out_timestamp*1000);
            
            if(!((start_time_breakfast<=time_in_timestamp && time_in_timestamp<=end_time_breakfast) || (start_time_breakfast<=time_out_timestamp && time_out_timestamp<=end_time_breakfast) || (time_in_timestamp<=start_time_breakfast && time_out_timestamp>=end_time_breakfast))){
                jQuery("#get_time_print_"+i).parent().parent().parent().parent().parent().parent().parent().parent().css('display','none');     
            }          
            else{
                jQuery("#get_time_print_"+i).parent().parent().parent().parent().parent().parent().parent().parent().css('display','block');     
            }
            jQuery("#get_time_print_"+i).val(time_print);
            jQuery("p.input_time_"+i).html(time_print);
        }
      });
  }
  else{  
    count_2 = <?php if(isset($j)) echo $j; else echo "0"; ?>;
    for(var i=1; i<count_2; i++){
       var time_in = jQuery("#get_time_print_"+i).parent().find(".time_in").html();
        var time_in_arr = time_in.split('/');
        var time_out = jQuery("#get_time_print_"+i).parent().find(".time_out").html();
        var time_out_arr = time_out.split('/');
       jQuery("#get_time_print_"+i).datepicker({minDate: new Date(time_in_arr[2],time_in_arr[1]-1,time_in_arr[0]),maxDate: new Date(time_out_arr[2],time_out_arr[1]-1,time_out_arr[0])});  
       jQuery('#get_time_print_'+i).change(function(){
        jQuery(this).next().html(jQuery(this).val());
        }); 
    }
     jQuery("#time_group").datepicker({minDate: new Date(miny,minm,mind),maxDate: new Date(maxy,maxm,maxd)});
     jQuery('#time_group').change(function(){
        var time_print = jQuery(this).val();
        var time_print = jQuery(this).val();
        var time_print_arr = time_print.split('/');
        var date = new Date(time_print_arr[2], parseInt(time_print_arr[1], 10) - 1, time_print_arr[0]);
        var date_timestamp = date.getTime()/1000;
        if(date.getDate()==7){ // Ngay chu nhat thoi gian an sang tu 6h30 - 9h30;
            start_time_breakfast = date_timestamp+<?php echo $this->map['start_time_breakfast'];?>;
            end_time_breakfast = date_timestamp+<?php echo $this->map['end_time_breakfast'];?>;
        }
        else{ // Ngay binh thuong thoi gian an sang tu 6h30 - 9h30;
            start_time_breakfast = date_timestamp+<?php echo $this->map['start_time_breakfast'];?>;
            end_time_breakfast = date_timestamp+<?php echo $this->map['end_time_breakfast'];?>;
        }
        for(var i=1; i<count_2; i++){
            var time_in_timestamp = jQuery("#get_time_print_"+i).parent().find(".time_in_timestamp").html();
            var date_time_in = new Date(time_in_timestamp*1000);
            
            var time_out_timestamp = jQuery("#get_time_print_"+i).parent().find(".time_out_timestamp").html();
            var date_time_out = new Date(time_out_timestamp*1000);
            
            if(!((start_time_breakfast<=time_in_timestamp && time_in_timestamp<=end_time_breakfast) || (start_time_breakfast<=time_out_timestamp && time_out_timestamp<=end_time_breakfast) || (time_in_timestamp<=start_time_breakfast && time_out_timestamp>=end_time_breakfast))){
                jQuery("#get_time_print_"+i).parent().parent().parent().parent().parent().parent().parent().css('display','none');     
            }          
            else{
                jQuery("#get_time_print_"+i).parent().parent().parent().parent().parent().parent().parent().css('display','block');     
            }
            jQuery("#get_time_print_"+i).val(time_print);
            jQuery("p.input_time_"+i).html(time_print);
        }
      });
    }
function print_group(){
    var check = true;
    jQuery("input[id^=get_time_print_]").each(function(){
        if(jQuery(this).val()=="")
        {
            alert("Bạn phải nhập ngày sử dụng cho vé");
            jQuery(this).focus();
            check = false;
            return false;
        }
    });
    if(!check)
    {
        return false;
    }
    if(confirm('<?php echo Portal::language('Are_you_sure');?>?'))
    {
        var date_use = jQuery("#time_group").val();
        var arr_data = {};
        var str_date = "";
        jQuery(".print_table").each(function(){
            if(jQuery(this).is(":visible"))
            {
                var date = "'"+jQuery(this).find("input[id^=get_time_print_]").val()+"'";
                if(str_date.indexOf(date)==-1)
                {
                    str_date+=date+",";
                }
                
                var obj_temp = {};
                obj_temp["id"] = jQuery(this).find("div[class^=reservation_room_]").attr("stt");
                obj_temp["date"] = date;
                obj_temp["reservation_room_id"] = jQuery(this).find("div[class^=reservation_room_]").attr("reservation_room_id");
                obj_temp["is_child"] = jQuery(this).find("div[class^=reservation_room_]").attr("is_child");
                obj_temp["guest_name"] = jQuery(this).find("div[class^=reservation_room_]").attr("guest_name");
                
                arr_data[obj_temp["id"]] = obj_temp;
            }
        });
        str_date = str_date.substr(0,str_date.length-1);
        //console.log(str_date); return false;
        var url = "get_voucher_breakfast_seri.php";
        jQuery.ajax({
            url : url,
            dataType : "JSON",
            type : "POST",
            data : {"str_date":str_date,"arr_data":arr_data},
            success : function(data){
                for(var i in data)
                {
                    JsBarcode("#barcode_"+i, data[i]['barcode']);
                    jQuery("div[class^=reservation_room_][stt="+i+"]").parent().find("span[class^=no_]").html(data[i]['no']);
                    if(data[i]['reprint'])
                    {
                        jQuery("#reprint_"+i).css("display","block");
                        jQuery("#reprint_nth_"+i).html(data[i]['reprint']);
                    }
                }
                jQuery(".print_table").each(function(){
                    jQuery(this).removeAttr("width");
                    jQuery(this).removeAttr("align");
                });

                var time_group = jQuery("#time_group").val();
                if(count!=0){
                    for(var i=1; i<count; i++){
                        if(time_group!=""){
                           jQuery("#get_time_print_"+i).css('display','none'); 
                        }
                        else{
                            jQuery("#get_time_print_"+i).css('width','150px');
                            jQuery("#get_time_print_"+i).css('visibility','hidden');
                        }
                        jQuery("#input_time_"+i).css('display','');
                    }
                    jQuery("table.warrap_2").css("border",'none');
                    jQuery(".h_v1 img").each(function(){
                        jQuery(this).css({'opacity':'0.1','z-index':"99px"});
                    });
                    jQuery(".guest_name").css('display','block');
                    jQuery(".print_table").each(function(){
                        jQuery(this).css("width","100%");
                    });
                    printWebPart('print_group_div','<?php echo User::id(); ?>');
                }
                else{
                    count_2 = <?php if(isset($j)) echo $j; else echo "0"; ?>;
                    for(var i=1; i<count_2; i++){
                        if(time_group!=""){
                           jQuery("#get_time_print_"+i).css('display','none'); 
                        }
                        else{
                            jQuery("#get_time_print_"+i).css('width','150px');
                            jQuery("#get_time_print_"+i).css('visibility','hidden');
                        }
                        jQuery("#input_time_"+i).css('display','');
                    }
                    jQuery(".h_v1 img").each(function(){
                        jQuery(this).css({'opacity':'0.1','z-index':"99px"});
                    });
                    jQuery("table.warrap_2").css("border",'none');
                     jQuery(".print_table").each(function(){
                        jQuery(this).css("width","100%");
                    });
                     printWebPart('single_print_div','<?php echo User::id(); ?>');
                } 
                window.close();                         
            }
        });       
        
    }
}

</script>