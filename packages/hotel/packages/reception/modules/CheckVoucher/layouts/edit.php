<style>
    .label_info{
        float: left; 
        display: block; 
        width: 220px;
        padding-top: 10px;
    }
    p{
        display: block; 
        line-height: 45px;
        height: 45px;
        margin: 10px 0px;
        box-sizing: border-box;
        border-bottom: blue thin solid;
    }
</style>
<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.Check_card_using.]]</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>[[.Barcode.]]</th>
                                <th>[[.Room_name.]]</th>
                                <th>[[.Guest_name.]]</th>
                                <th>[[.Ticket_type.]]</th>
                                <th>[[.Date_use.]]</th>
                                <th>[[.Status.]]</th>
                                <th>[[.Delete.]]</th>
                            </tr>
                        </thead>
                        <tbody id="content">
                        </tbody>
                    </table>
                    <div class="col-md-12" id="btn_use" style="display: none;">
                        <div class="col-md-12" style="text-align: center;"><button class="btn btn-danger" type="button" name="active" value="Use" onclick="if(confirm('[[.Do_you_want_use_this_voucher.]]?')) change_action();">[[.Use.]]</button></div>                          
                    </div>
                    <!--<div class="col-md-6 col-md-offset-3">
                        <br />
                        <p> <span class="label_info">[[.Guest_name.]] </span> <span style="padding-top: 8px; display: block;" id="guest_name"></span></p>
                        <p> <span class="label_info">[[.Ticket_type.]] </span> <span style="padding-top: 8px; display: block; color: blue;" id="ticket_type"></span></p>
                        <p> <span class="label_info">[[.Room_name.]] </span> <span style="padding-top: 8px; display: block; text-align: right; font-size: 14px; margin-right: 170px;" id="room_name"></span></p>
                        <p> <span class="label_info">[[.Date_use.]] </span> <span style="padding-top: 8px; display: block; text-align: right;font-size: 14px; margin-right: 170px;" id="date_use"></span></p>                       
                        <p> <span class="label_info">[[.Status.]] </span> <span style="padding-top: 8px; display: block;text-align: right;font-size: 14px; width:100%; text-align:left;" id="status"></span></p>
                        <div class="col-md-12" id="btn_use" style="display: none;">
                            <div class="col-md-12" style="text-align: center;"><button class="btn btn-danger" type="button" name="active" value="Use" onclick="if(confirm('[[.Do_you_want_use_this_voucher.]]?')) change_action();">[[.Use.]]</button></div>                          
                        </div>
                        <input type="hidden" id="barcode" />
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var current_date = '<?php echo date("d/m/Y"); ?>';
    var current_time = <?php echo (date("H")*3600+date("i")*60+date("s")); ?>;
    var start_time_breakfast = calc_time('<?php echo BREAKFAST_FROM_TIME; ?>');
    var end_time_breakfast = calc_time('<?php echo BREAKFAST_TO_TIME; ?>');
    
    jQuery(document).ready(function() {
            var pressed = false; 
            var chars = []; 
            var stt = 0;
            jQuery(window).keypress(function(e) {
                if (e.which >= 48 && e.which <= 57) {
                    chars.push(String.fromCharCode(e.which));
                }
                if (pressed == false) {
                    setTimeout(function(){
                        if (chars.length >= 13) {
                            stt++;
                            var barcode = chars.join("");
                            var strData = "";
                            var checkBarcode = "";
                            var url = "ajax_check_voucher.php";
                            jQuery.ajax({
                                url : url,
                                dataType : "json",
                                type : "POST",
                                data : {"barcode":barcode},
                                success : function(data){
                                    var check = true;
                                    jQuery("#content tr td:last-child").each(function(){
                                        var current_barcode = jQuery(this).html();
                                        if(data["barcode"]==current_barcode)
                                        {
                                            check = false;
                                            return false;
                                        }
                                    });
                                    if(!check)
                                    {
                                        return false;
                                    }
                                    var str = "<tr>";
                                    
                                    if(jQuery.isEmptyObject(data))
                                    {
                                        str+= "<td>"+barcode+"</td>"
                                             +"<td></td>"
                                             +"<td></td>"
                                             +"<td></td>"
                                             +"<td></td>"
                                             +"<td>Mã không tồn tại trong hệ thống!</td>";
                                             +"<td class='check_status' style='display:none;'>1</td>";
                                             +"<td><button onclick=\"delete_voucher(this);\"><span class='glyphicon glyphicon-remove' style='color:red;'></span></button></td>";
                                    }
                                    else
                                    {
                                        str+= "<td>"+barcode+"</td>";
                                        str+= "<td>"+data["room_name"]+"</td>";
                                        str+= "<td>"+(data["guest_name"]!=null ? data["guest_name"]:"")+"</td>";
                                        if(data['is_child']==1)
                                        {
                                            str+= "<td>Vé trẻ em</td>";
                                        }
                                        else
                                        {
                                            str+= "<td>Vé người lớn</td>";
                                        }
                                        
                                        str+= "<td>"+data["date_use"]+"</td>";
                                        if(data['status']=="USED")
                                        {
                                            str+= "<td style='color:maroon; font-weight:bold;'>Đã sử dụng lúc "+data['real_use_date']+"</td>";
                                            str+= "<td class='check_status' style='display:none;'>0</td>";
                                        }
                                        else
                                        {
                                            if(data["date_use"]!=current_date || (data["date_use"]==current_date && current_time>end_time_breakfast))
                                            {
                                                str+= "<td style='color:blue; font-weight:bold;'>Ngày sử dụng không hợp lệ</td>";
                                                str+= "<td class='check_status' style='display:none;'>0</td>";  
                                            }
                                            else
                                            {
                                                str+= "<td style='color:blue; font-weight:bold;'>Chưa sử dụng</td>";
                                                str+= "<td class='check_status' style='display:none;'>1</td>";  
                                            }
                                        }
                                    }
                                    str+="<td><button onclick=\"delete_voucher(this);\"><span class='glyphicon glyphicon-remove' style='color:red;'></span></button></td>";
                                    str+= "<td class='barcode' style='display:none;'>"+data["barcode"]+"</td>";
                                    str+="</tr>";
                                    jQuery("#content").append(str);
                                    
                                    var check_stt = true;
                                    jQuery(".check_status").each(function(){
                                        if(jQuery(this).html()=="0")
                                        {
                                            jQuery("#btn_use").css("display","none");
                                            check_stt = false;
                                        }
                                    });
                                    if(check_stt)
                                    {
                                        jQuery("#btn_use").css("display","block");
                                    }
                                }
                            });
                                              
                        }
                        chars = [];
                        pressed = false;
                    },500);
                }
                pressed = true;
            });
        });
   function change_action()
   {
        var barcode = "";
        
        jQuery("#content tr td:last-child").each(function(){
            var current_barcode = jQuery(this).html();
            barcode += current_barcode+",";
        });
        
        var url = "ajax_check_voucher.php";
        jQuery.ajax({
            url : url,
            dataType : "html",
            type : "POST",
            data : {"barcode":barcode,"type":"use"},
            success : function(data){
                jQuery("#content").html("");
                jQuery("#btn_use").css("display","none");
            }
        }); 
   }
   
   function delete_voucher(obj)
   {
     if(confirm('[[.Are_you_sure.]]?')) 
     {
        jQuery(obj).parent().parent().remove();
        var check_stt = true;
        jQuery(".check_status").each(function(){
            if(jQuery(this).html()==0)
            {
                jQuery("#btn_use").css("display","none");
                check_stt = false;
            }
        });
        if(check_stt)
        {
            jQuery("#btn_use").css("display","block");
        }
     }
   }
   
   function calc_time(inp)
   {
     var temp = inp.split(":");
     return temp[0]*3600+temp[1]*60 + 59;
   }
</script>