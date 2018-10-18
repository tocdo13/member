<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lolkittens" />
    <meta charset="UTF-8"/>
	<title>Untitled 16</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="http://getbootstrap.com/assets/css/docs.min.css" rel="stylesheet">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">   
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="numeral.min.js"></script>
    <style>
        *{
            margin: 0px;
            padding: 0px;
        }
        body{
            overflow-x: hidden;
            
        }
        .required{
            color:red;
        }
    </style>
</head>

<body>
        <div class="wrapper">
            <div class="header">
                <div class="row">
                    <div class="container">
                        <div class="col-md-12" style="background: url('http://tcv.vn/upload/tcv/icon/1331693871_header1_.jpg') no-repeat center; height:100px;">
                        </div>
                    </div>
                </div>
            </div>
            <hr style="width: 50%; margin: 20px auto; height:3px;" />
            <div class="content" style="margin-top: 30px;">
                <div class="row">
                    <div class="container">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5 class="panel-title">Thông tin đặt phòng</h5>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-10 col-md-offset-1" style="height: auto;">
                                    <fieldset>
                                        <legend>Thông tin cá nhân</legend>
                                        <div>
                                            <div class="form-group col-md-4 col-md-offset-1">
                                                <label for="customer_name">Họ và tên <span class="required">(*)</span>: </label>
                                                <input class="form-control" name="customer_name" id="customer_name" type="text" />
                                            </div>
                                            <div class="form-group col-md-4 col-md-offset-1">
                                                <label for="phone_number">Số điện thoại <span class="required">(*)</span>: </label>
                                                <input class="form-control" name="phone_number" id="phone_number" type="text" />
                                            </div>
                                            <div class="form-group col-md-4 col-md-offset-1">
                                                <label for="email">Email : </label>
                                                <input class="form-control" name="email" id="email" type="email" />
                                            </div>
                                            <div class="form-group col-md-4 col-md-offset-1">
                                                <label for="tax_code">Mã số thuế : </label>
                                                <input class="form-control" name="tax_code" id="tax_code" type="text" />
                                            </div>
                                            <div class="form-group col-md-6 col-md-offset-1">
                                                <label for="address">Địa chỉ <span class="required">(*)</span>: </label>
                                                <textarea class="form-control" name="address" id="address" style="resize: none;"></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-11 col-md-offset-1" style="height: auto; margin-top: 20px;">
                                    <div class="form-group col-md-4">
                                        <label>Khoảng giá : </label>
                                        <div>
                                            <input name="from" type="text" id="from" value="" class="form-control" style="width: 45%; float: left;" placeholder="1,000,000" required="" />
                                            <span style="float: left; width: 20px; text-align: center; padding-top: 5px; font-weight: bold;">  -  </span>
                                            <input name="to" type="text" id="to" value="" class="form-control" style="width: 45%; float: left;" placeholder="2,000,000" required="" />
                                        </div>
                                    </div>                                   
                                    <div class="form-group col-md-3">
                                        <label>Check-in : </label>
                                        <input name="check_in" type="date" id="check_in" value="" class="form-control" style="width: 200px;" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Check-out : </label>
                                        <input name="check_out" type="date" id="check_out" value="" class="form-control" style="width: 200px;" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button class="btn btn-sm btn-primary" style="margin-top: 25px; margin-left:10px; width: 110px;" onclick="search();" type="button">Search</button>
                                    </div>
                                </div>
                                <div  id="result" class="col-md-10 col-md-offset-1" style="height: auto; margin-top: 20px; display: none;">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>Hạng phòng</th>
                                            <th style="width:150px;">Giá VNĐ</th>
                                            <th class="col-md-1">Giá USD</th>
                                            <th style="width:100px;">SL trống</th>
                                            <th class="col-md-1">Ng.L</th>
                                            <th class="col-md-1">Tr.E</th>
                                            <th class="col-md-1">SL đặt</th>
                                        </thead>
                                        <tbody id="search_result">
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            <div class="panel-footer">
                                   <div class="col-md-offset-10">
                                        <button type="button" class="btn btn-success" onclick="if(confirm('Bạn có chắc chắn không?')) reservation();">Đặt phòng</button>
                                   </div> 
                            </div>                      
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer"></div>
        </div>
    
    <script>
        
        function search(){
            var date_from = $("#check_in").val();
            var date_to = $("#check_out").val();
            var max_price = $("#to").val();
            var min_price = $("#from").val();
            if(!max_price)
            {
                max_price = 99999999999;
            }
            if(!min_price)
            {
                min_price = 0;
            }
            $.ajax({
              url:"http://newwaypms.ddns.net:8088/version18/webservice/client.php",
              data: {"check_avaiable_room":"","date_from": date_from,"date_to":date_to, 'max_price':max_price,'min_price':min_price}, // tùy chọn
              type : "POST",
              dataType: 'json', 
              success:function(data){
                var content = "";
                    for(var key in data){
                        if(key=='date_from' || key=='date_to'){
                            continue;
                        }
                        var currentcy = data[key]['currencyValue'];
                        var vnd_price =  data[key]['price']; 
                        var usd_price =  vnd_price/ currentcy;                   
                        content+="<tr id='room_level_id_"+data[key]['id']+"'>"
                                    +"<td>"+data[key]['name']+"</td>"
                                    +"<td>"+numeral(vnd_price).format('0,0')+"</td>"
                                    +"<td>"+numeral(usd_price).format('0,0.00')+"</td>"
                                    +"<td>"+data[key]['count_room']+"</td>"
                                    +"<td><input id=\"adult_"+data[key]['id']+"\" type=\"text\" class=\"form-control\" /></td>"
                                    +"<td><input id=\"child_"+data[key]['id']+"\" type=\"text\" class=\"form-control\" /></td>"
                                    +"<td><input id=\"quantity_"+data[key]['id']+"\" type=\"text\" class=\"form-control\" /></td>"
                                +"</tr>";
                    }
                    content+="<tr>"
                                +"<td colspan='7' style='color:red; text-align:right;'>Tỉ giá USD : <span id='exchange_rate'>"+numeral(currentcy).format('0,0')+"</span></td>"
                                +"<td id='date_from' style='display:none;'>"+data['date_from']+"</td>"
                                +"<td id='date_to' style='display:none;'>"+data['date_to']+"</td>"
                            +"</tr>";
                  $("#search_result").html(content);  
              },
              error:function(a, b){
               alert("Error");
             }
            });
            $("#result").css('visibility', 'visible');
            $("#result").show("slow");
        }
            
        function reservation(){
            var customer_name = $("#customer_name").val();
            var phone_number = $("#phone_number").val();
            var email = $("#email").val();
            var tax_code = $("#tax_code").val(); 
            var address = $("#address").val();
            var exchange_rate = $("#exchange_rate").html();
            var date_from = $("#date_from").html();
            var date_to = $("#date_to").html();
            var info = {};
            if(customer_name==''){
                alert("Bạn chưa nhập tên ! Xin vui lòng kiểm tra lại");
                $("#customer_name").focus();
                return false;
            }
            else if(phone_number==''){
                alert("Bạn chưa nhập số điện thoại ! Xin vui lòng kiểm tra lại");
                $("#phone_number").focus();
                return false;
            }
            else if(address==''){
                alert("Bạn chưa nhập địa chỉ ! Xin vui lòng kiểm tra lại");
                $("#address").focus();
                return false;
            }
            var i = 0;
            $("#search_result tr[id^=room_level_id_]").each(function(){
               var quantity =  $(this).find("td:last-child input").val();
               var adult =  $(this).find("td:nth-child(5) input").val();
               var child =  $(this).find("td:nth-child(6) input").val();
               var id = $(this).attr('id');
               var temp_arr_id = id.split("_");
                   id = temp_arr_id[3];
               var price_vnd = $(this).find("td:nth-child(2)").html();
               var price_usd = $(this).find("td:nth-child(3)").html();
               var info_detail={}; 
                if(quantity!='' && quantity>0){
                    info_detail['adult'] = adult;
                    info_detail['child'] = child;
                    info_detail['quantity'] = quantity;
                    info_detail['price']=convertPriceToOginal(price_vnd);
                    info_detail['usd_price']=price_usd;
                    info_detail['net_price']=1;
                    info_detail['exchange_rate']=convertPriceToOginal(exchange_rate);
                    info_detail['note']='';
                    info_detail['room_level_id']=id;
                    info_detail['tax_rate']=0;
                    info_detail['service_rate']=0;
                    info_detail['time_in']=date_from;
                    info_detail['time_out']=date_to;
                    info_detail['id']=i;
                    info[i]=info_detail;
                    i++;
                }
            });  
            
            if(jQuery.isEmptyObject(info)){
                alert("Bạn chưa chọn hạng phòng ! Xin vui lòng kiểm tra lại");
                return false;
            }
            
            $.ajax({
              url:"http://newwaypms.ddns.net:8088/version18/webservice/client.php",
              data: {"customer_name":customer_name,"phone_number": phone_number,"email":email, 'tax_code':tax_code,'address':address,'info':info,'reservation':''}, // tùy chọn
              type : "POST",
              dataType: 'json', 
              success:function(data){
                if(data.length!=0){
                   var error ="Đã xảy ra các lỗi sau : \n";
                    for(var key in data){
                        error+=data[key]['note']+"\n";
                    }
                    alert(error); 
                    return false;
                }
                else{
                    alert("Quý khách đã đặt phòng thành công. Chúc quý khách có một ngày vui vẻ !");
                    location.reload();
                }              
              },
              error: function(xhr, status, error) {
                  alert(xhr.responseText);
                }
            });                       
        }
     
     function convertDate(value){
        var date_arr = value.split("/");
        return date_arr[1]+"/"+date_arr[0]+"/"+date_arr[2];
     }
     function convertPriceToOginal(value){
        return value.replace(/,/g, '');
     }
        
    </script>
</body>
</html>