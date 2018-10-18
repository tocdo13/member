<style>
    .table td {
       text-align: center;   
    }
    .table td:first-child {
       text-align: left;   
    }
    table td, table th{
        font-size:15px;
    }
</style>
<script src='http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>/socket.io/socket.io.js'></script> 
<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                   <h5>Danh sách đặt món</h5>
                </div>
                <div class="panel-body">
                    <div id="select_type" style="margin-left: 30px;">
                        <div class="checkbox" style="float: left;">
                            <label><input type="checkbox" class="checkbox" checked="" onclick="selectType(this);" value="cooking" style="display: block; float: left; margin-top: 12px;" /><h4>Bếp </h4></label>
                        </div>
                        <div class="checkbox" style="float: left; margin-top: 10px; margin-left: 20px;">
                            <label><input type="checkbox" class="checkbox" checked="" onclick="selectType(this);" value="bar" style="display: block; float: left; margin-top: 12px;" /><h4>Bar </h4></label>
                        </div>
                    </div>
                   <!-- <div style="margin-top: 30px; margin-bottom: 30px;">
                        <div class="pull-right"><button type="button" class="btn btn-sm btn-info" onclick="showChooseTab(this);"><span class="glyphicon glyphicon-chevron-down"></span></button></div>
                        <div class="col-md-12" style="display: none;" id="chooseTab">
                              // Nav tabs 
                              <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
                                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
                                <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
                              </ul>
                            
                              // Tab panes 
                              <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">...</div>
                                <div role="tabpanel" class="tab-pane" id="profile">...</div>
                                <div role="tabpanel" class="tab-pane" id="messages">...</div>
                                <div role="tabpanel" class="tab-pane" id="settings">...</div>
                              </div>
                        </div>
                    </div> -->
                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="row-order col-md-6">                           
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h6>Chưa chế biến</h6>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12" style="min-height: 1000px; overflow: auto;">
                                        <table class="table table-hover">
                                            <thead>
                                                <th class="col-md-8">Tên món</th>
                                                <th class="col-md-1">Bàn</th>
                                                <th class="col-md-1">SL</th>
                                                <th class="col-md-1">Xóa</th>
                                                <th class="col-md-1">Chuyển</th>
                                            </thead>
                                            <tbody  id="up_eating">
                                                <!--LIST:items-->
                                                        <tr id="A[[|items.id|]]" type="[[|items.type|]]" style="<?php if([[=items.complete=]]==[[=items.quantity=]]){ echo "display:none"; } ?>" special="<?php if([[=items.complete=]]==[[=items.quantity=]]){ echo "hidden"; } else echo "show"; ?>">
                                                            <td>[[|items.name|]]</td>
                                                            <td>[[|items.table_name|]]</td>
                                                            <td><?php echo [[=items.quantity=]]-[[=items.complete=]]; ?></td>
                                                            <td><button type='button' onclick="if(confirm('[[.Are_you_sure.]] ?')) removeEating(this);" remove=''><span class='glyphicon glyphicon-remove' style="color:red;"></span></button></td>
                                                            <td><button type='button' onclick="inputQuantity(this,'right');" data-toggle="modal" data-target="#myModal"><span class='glyphicon glyphicon-arrow-right' style="color:blue;"></span></button></td>
                                                            <td style="display: none;">[[|items.id|]]</td>
                                                        </tr>
                                                <!--/LIST:items-->
                                            </tbody>
                                        </table>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="row-order col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h6>Đã hoàn thành</h6>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12" style="min-height: 1000px; overflow: auto;">
                                        <table class="table table-hover">
                                            <thead>
                                                <th class="col-md-8">Tên món</th>
                                                <th class="col-md-1">Bàn</th>
                                                <th class="col-md-1">SL</th>
                                                <th class="col-md-1">Chuyển</th>
                                            </thead>
                                            <tbody id="move_eating">
                                                <!--LIST:items-->
                                                        <tr id="A[[|items.id|]]_div" type='[[|items.type|]]' style="<?php if([[=items.complete=]]==0){ echo "display:none;"; } ?>" special="<?php if([[=items.complete=]]==0){ echo "hidden"; } else echo "show"; ?>">
                                                            <td>[[|items.name|]]</td>
                                                            <td>[[|items.table_name|]]</td>
                                                            <td>[[|items.complete|]]</td>
                                                            <td><button type='button' onclick="inputQuantity(this,'left');" data-toggle="modal" data-target="#myModal"><span class='glyphicon glyphicon-arrow-left' style="color:blue;"></span></button></td>
                                                            <td style="display: none;">[[|items.id|]]</td>
                                                        </tr> 
                                                <!--/LIST:items-->
                                            </tbody>
                                        </table>
                                    </div>    
                                </div>
                            </div>
                        </div>  
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content"> 
                                <div class="modal-header"> 
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="font-size: 17px; color: red;">×</span></button> 
                                    <h4 class="modal-title" id="mySmallModalLabel">Số lượng hoàn thành</h4> 
                                </div> 
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="quantity">Mời nhập số lượng</label>
                                        <input class="form-control" name="quantity" id="quantity" type="number" oninput="checkValue(this,this.value);" min="1" />
                                    </div>
                                </div> 
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="sendValue();">Ok</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">[[.close.]]</button>
                                </div>
                            </div>
                          </div>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
    var socket = io.connect('http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>'); // Thanh add -- Kết nối server Websocket
    
    jQuery(document).ready(function(){
        socket.on('recei_eating',function(data){
            var content = "";
            var content2 = "";
            var checkSplit ="";
            for(var key in data ){
                if(key!='table_move_order'){
                if(!data[key]['target']){ // Phan nay danh cho ghep ban, doi ban, hien mon khi checkin
                   if(!jQuery("tr#A"+data[key]['id']).length){
                    if(data[key]['complete']==0){
                        var quantity = data[key]['quantity'];
                    }
                    else{
                        var quantity = data[key]['quantity']-data[key]['complete'];
                    }    
                                  
                    content +="<tr id='A"+data[key]['id']+"' style=\"display:";
                    if(quantity!=0 && quantity!=data[key]['complete']){
                      content+="'';\" special='show'";  
                    }
                    else{
                       content+="none;\" special='hidden'"; 
                    }
                    content+=" type='"+data[key]['type']+"'>"
                                +"<td>"+data[key]['name']+"</td>"
                                +"<td>"+data[key]['table_name']+"</td>"
                                +"<td>"+quantity+"</td>" 
                                +"<td><button type='button' onclick='removeEating(this);' remove=''><span class='glyphicon glyphicon-remove' style=\"color:red;\"></span></button></td>"
                                +"<td><button type='button' data-toggle=\"modal\" data-target=\"#myModal\" onclick=\"inputQuantity(this,'right');\"><span class='glyphicon glyphicon-arrow-right' style=\"color:blue;\"></span></button></td>" 
                                +"<td style='display:none;'>"+data[key]['id']+"</td>"
                             +"</tr>";          
                   } 
                   else{ 
                      jQuery("tr#A"+data[key]['id']+" td:nth-child(2)").html(data[key]['table_name']); 
                      if(data[key]['complete']==0){
                        jQuery("tr#A"+data[key]['id']+" td:nth-child(3)").html(data[key]['quantity']); 
                      }  
                      else{
                        jQuery("tr#A"+data[key]['id']+" td:nth-child(3)").html(data[key]['quantity']-data[key]['complete']); 
                      } 
                      if(data[key]['complete']==data[key]['quantity']){
                        jQuery("tr#A"+data[key]['id']).fadeOut("3000ms");
                        jQuery("tr#A"+data[key]['id']).attr('special','hidden');
                      }
                      else{
                        jQuery("tr#A"+data[key]['id']).fadeIn("3000ms");
                        jQuery("tr#A"+data[key]['id']).attr('special','show');
                      }
                      
                   }
                   
                   if(!jQuery("tr#A"+data[key]['id']+"_div").length){
                     var complete = data[key]['complete'];
                    content2 +="<tr id='A"+data[key]['id']+"_div' style=\"display:";
                    if(complete!=0){
                      content2+="'';\" special='show'"; 
                    }
                    else{
                       content2+="none;\" special='hidden'";
                    }
                    content2+=" type='"+data[key]['type']+"'>"
                            +"<td>"+data[key]['name']+"</td>"
                            +"<td>"+data[key]['table_name']+"</td>"
                            +"<td>"+complete+"</td>" 
                            +"<td><button type='button' data-toggle=\"modal\" data-target=\"#myModal\" onclick=\"inputQuantity(this,'left');\"><span class='glyphicon glyphicon-arrow-left' style=\"color:blue;\"></span></button></td>" 
                            +"<td style='display:none;'>"+data[key]['id']+"</td>"
                         +"</tr>";          
                   } 
                   else{
                      jQuery("tr#A"+data[key]['id']+"_div td:nth-child(2)").html(data[key]['table_name']);  
                      if(data[key]['complete']!=0){
                        jQuery("tr#A"+data[key]['id']+"_div td:nth-child(3)").html(data[key]['complete']); 
                      } 
                   }
                   
                   
                   
                   if(checkSplit==""){
                        var arr_temp = data[key]['table_name'].split("+");
                        if(arr_temp.length>=2){
                            checkSplit="ok";
                        }
                   }
               }
               else{ // Phan nay danh cho tach ban
                    if(!jQuery("tr#A"+data[key]['id']).length){
                            if(data[key]['complete']==0){
                               var quantity = data[key]['quantity']; 
                            }
                            else{
                                var quantity = data[key]['quantity'] - data[key]['complete'];
                            }
                        content +="<tr id='A"+data[key]['id']+"' style=\"display:";
                        if(data[key]['quantity']!=0 && data[key]['quantity']!=data[key]['complete']){
                          content+="'';\" special='show'";  
                        }
                        else{
                           content+="none;\" special='hidden'"; 
                        }
                            
                            content+=" type='"+data[key]['type']+"'>"
                                    +"<td>"+data[key]['name']+"</td>"
                                    +"<td>"+data[key]['table_name']+"</td>"
                                    +"<td>"+quantity+"</td>" 
                                    +"<td><button type='button' onclick='removeEating(this);' remove=''><span class='glyphicon glyphicon-remove' style=\"color:red;\"></span></button></td>"
                                    +"<td><button type='button' data-toggle=\"modal\" data-target=\"#myModal\" onclick=\"inputQuantity(this,'right');\"><span class='glyphicon glyphicon-arrow-right' style=\"color:blue;\"></span></button></td>" 
                                    +"<td style='display:none;'>"+data[key]['id']+"</td>"
                                 +"</tr>"; 
                   } 
                   else{
                        jQuery("tr#A"+data[key]['id']+" td:nth-child(2)").html(data[key]['table_name']); 
                        jQuery("tr#A"+data[key]['id']+" td:nth-child(3)").html(data[key]['quantity']-data[key]['complete']); 
                        if(data[key]['complete'] == data[key]['quantity']){
                            jQuery("tr#A"+data[key]['id']).fadeOut("3000ms");
                            jQuery("tr#A"+data[key]['id']).attr('special','hidden');
                        }
                        else{
                            jQuery("tr#A"+data[key]['id']).fadeIn("3000ms");
                            jQuery("tr#A"+data[key]['id']).attr('special','show');
                        }
                   }
                   if(!jQuery("tr#A"+data[key]['id']+"_div").length){
                            var complete = data[key]['complete'];
                            content2 +="<tr id='A"+data[key]['id']+"_div' style=\"display:";
                            if(complete!=0){
                              content2+="'';\" special='show'"; 
                            }
                            else{
                               content2+="none;\" special='hidden'";
                            }
                            content2+=" type='"+data[key]['type']+"'>"
                                    +"<td>"+data[key]['name']+"</td>"
                                    +"<td>"+data[key]['table_name']+"</td>"
                                    +"<td>"+complete+"</td>" 
                                    +"<td><button type='button' data-toggle=\"modal\" data-target=\"#myModal\" onclick=\"inputQuantity(this,'left');\"><span class='glyphicon glyphicon-arrow-left' style=\"color:blue;\"></span></button></td>" 
                                    +"<td style='display:none;'>"+data[key]['id']+"</td>"
                                 +"</tr>"; 
                   }
                   else{
                        jQuery("tr#A"+data[key]['id']+"_div td:nth-child(2)").html(data[key]['table_name']);  
                       if(data[key]['complete']==0){
                           jQuery("tr#A"+data[key]['id']+"_div").fadeOut("3000ms");
                           jQuery("tr#A"+data[key]['id']+"_div").attr('special','hidden');
                       }
                       else{
                            jQuery("tr#A"+data[key]['id']+"_div td:nth-child(3)").html(data[key]['complete']); 
                            jQuery("tr#A"+data[key]['id']+"_div").fadeIn("3000ms");
                            jQuery("tr#A"+data[key]['id']+"_div").attr('special','show');
                       } 
                   }
               }
            }
            }
            if(data['table_move_order']){
                            // Phan nay su dung de xoa nhung mon da chuyen het qua ban khac
                var table = data['table_move_order'].split("+");
                for( var key in table){
                    jQuery("tbody#up_eating tr td:nth-child(2)").each(function(){
                        var current_table = jQuery(this).html();
                        if(current_table == table[key]){
                            var current_id = jQuery(this).parent().find("td:last-child").html();
                            var not_in = true;
                            for(var k in data){
                                if(k!='table_move_order'){
                                    if(current_id==k){
                                        not_in = false;
                                        break;
                                    }
                                }
                            }
                            if(not_in){
                                jQuery(this).parent().remove();
                            }
                        }
                    });
                    
                    jQuery("tbody#move_eating tr td:nth-child(2)").each(function(){
                        var current_table = jQuery(this).html();
                        if(current_table == table[key]){
                            var current_id = jQuery(this).parent().find("td:last-child").html();
                            var not_in = true;
                            for(var k in data){
                                if(k!='table_move_order'){
                                    if(current_id==k){
                                        not_in = false;
                                        break;
                                    }
                                }
                            }
                            if(not_in){
                                jQuery(this).parent().remove();
                            }
                        }
                    });
                }
            }
            
            if(checkSplit=="ok"){ // phan nay su dung de xoa nhung mon khi ghep ban 
                for(var i=0; i < arr_temp.length; i++){
                    jQuery("tbody#up_eating tr").each(function(){
                        var table_current = jQuery(this).find("td:nth-child(2)").html();
                        if(table_current==arr_temp[i]){
                            jQuery(this).remove();
                        }
                    });
                    jQuery("tbody#move_eating tr").each(function(){
                        var table_current = jQuery(this).find("td:nth-child(2)").html();
                        if(table_current==arr_temp[i]){
                            jQuery(this).remove();
                        }
                    });
                }
            }
            jQuery("tbody#up_eating").append(content);
            jQuery("tbody#move_eating").append(content2);
            var status = [];
            var i=0;
            jQuery("div#select_type input[type=checkbox]").each(function(){
                if(jQuery(this).is(":checked")){
                    status[i] = jQuery(this).val();
                    i++;
                }
            });
            jQuery("tbody#up_eating tr:hidden").each(function(){
                var this_status = jQuery(this).attr('type');
                for(var i=0; i< status.length; i++){
                    if(this_status==status[i] && jQuery(this).attr('special')=='show'){
                        jQuery(this).fadeIn("3000ms");
                        jQuery(this).attr('special','show');
                        break;
                    }
                }
            });
        });
               
        
        
        socket.on('recei_move_eating',function(data){
            var content = "";
            if(data[0]['type']=='total'){
                        jQuery("tr#A"+data[0]['id']).fadeOut('2000ms',function(){
                            var current_quantity = jQuery("tr#A"+data[0]['id']+"_div td:nth-child(3)").html();
                            value = to_numeric(current_quantity) + to_numeric(data[0]['quantity']);
                            jQuery("tr#A"+data[0]['id']+"_div td:nth-child(3)").html(value); 
                            jQuery(this).attr('special','hidden');
                            jQuery(this).find("td:nth-child(3)").html('0');
                            jQuery("tr#A"+data[0]['id']+"_div").attr('special','show');
                            jQuery("tr#A"+data[0]['id']+"_div").fadeIn('3000ms');
                        });
            }
            else{
                    var current_quantity_left = jQuery("tr#A"+data[0]['id']+" td:nth-child(3)").html();
                    value_left = to_numeric(current_quantity_left) - to_numeric(data[0]['quantity']);
                    jQuery("tr#A"+data[0]['id']+" td:nth-child(3)").html(value_left);  
                    var current_quantity_right = jQuery("tr#A"+data[0]['id']+"_div td:nth-child(3)").html();
                    value_right = to_numeric(current_quantity_right) + to_numeric(data[0]['quantity']);
                    jQuery("tr#A"+data[0]['id']+"_div td:nth-child(3)").html(value_right); 
                    jQuery("tr#A"+data[0]['id']+"_div").attr('special','show');
                    jQuery("tr#A"+data[0]['id']+"_div").fadeIn('3000ms');
            }                          
        });
        socket.on('recei_back_eating',function(data){
            var content = "";
            if(data[0]['type']=='total'){
                        jQuery("tr#A"+data[0]['id']+"_div").fadeOut('2000ms',function(){
                            var current_quantity = jQuery("tr#A"+data[0]['id']+" td:nth-child(3)").html();
                            value = to_numeric(current_quantity) + to_numeric(data[0]['quantity']);
                            jQuery("tr#A"+data[0]['id']+" td:nth-child(3)").html(value);
                            jQuery(this).attr('special','hidden');
                            jQuery(this).find("td:nth-child(3)").html('0');
                            jQuery("tr#A"+data[0]['id']).attr('special','show');
                            jQuery("tr#A"+data[0]['id']).fadeIn("3000ms");
                        });
            }
            else{
                    var current_quantity_right = jQuery("tr#A"+data[0]['id']+"_div td:nth-child(3)").html();
                        value_right = to_numeric(current_quantity_right) - to_numeric(data[0]['quantity']);
                        jQuery("tr#A"+data[0]['id']+"_div td:nth-child(3)").html(value_right);           
                        var current_quantity_left = jQuery("tr#A"+data[0]['id']+" td:nth-child(3)").html();
                        value_left = to_numeric(current_quantity_left) + to_numeric(data[0]['quantity']);
                        jQuery("tr#A"+data[0]['id']+" td:nth-child(3)").html(value_left); 
                        jQuery("tr#A"+data[0]['id']).attr('special','show');
                        jQuery("tr#A"+data[0]['id']).fadeIn('3000ms');
                    }       
        });
        socket.on('recei_remove_eating',function(data){
            var id = data[0]['id'];
            jQuery("tr#A"+id).remove();
            jQuery("tr#A"+id+"_div").remove();
        });
        socket.on('recei_checkout',function(data){
            for(var key in data){
                var id = data[key]['id'];
                jQuery('tr#A'+id).remove();
                jQuery("tr#A"+id+"_div").remove();
            }
        });
    });
    
    function removeEating(obj){
        jQuery(obj).parent().parent().fadeOut('2000ms',function(){
            var quantity = jQuery(obj).parent().prev().html();
            var id = jQuery(obj).parent().parent().attr('id');
            arr_eating = [];
            arr_eating[0] = {};
            arr_eating[0]['id'] = id;
            socket.emit('remove_eating',arr_eating);
            var url = "set_status_order.php";
            jQuery.ajax({
                   url : url,
                   data : {'quantity':quantity,'id':id},
                   type : 'POST',
                   dataType : 'JSON'
            });
            jQuery(this).remove();
        });
    }

    
    function inputQuantity(obj,target){
        var maxValue = jQuery(obj).parent().parent().find("td:nth-child(3)").html();
        var id = jQuery(obj).parent().parent().attr('id');
        jQuery("input#quantity").val(maxValue);
        jQuery("input#quantity").attr('status',target);
        jQuery("input#quantity").attr('max',maxValue);
        jQuery("input#quantity").attr('target',id);
    }
    
    function checkValue(obj,value){
        value = eval(value);
         var max = jQuery(obj).attr('max');
         if(value>max){
            jQuery(obj).val(max);
         }
         else if(value<1){
            jQuery(obj).val(1);
         } 
    }
    
    function sendValue(){
        
        var valueSend = jQuery("input#quantity").val();
        var target = jQuery("input#quantity").attr("target");
        var valueCurrent = jQuery("tr#"+target+" td:nth-child(3)").html();
        var status = jQuery("input#quantity").attr("status");
        var node = jQuery("tr#"+target+" td:last-child").prev().find('button');
        if(valueCurrent == valueSend){
            moveEating(node,status,'total',valueSend);
        }
        else{
           jQuery("tr#"+target+" td:nth-child(3)").html(valueCurrent-valueSend); 
           moveEating(node,status,'div',valueSend); 
        }
        jQuery("#myModal").modal('hide');
    }    
    
    function moveEating(obj,target,type,quantity_complete){
        var content="";
        var arr_eating = [];
        var orderName = jQuery(obj).parent().parent().find('td:first-child').html(); 
        var tableName = jQuery(obj).parent().parent().find('td:nth-child(2)').html(); 
        var quantity = jQuery(obj).parent().parent().find('td:nth-child(3)').html(); 
        var id = jQuery(obj).parent().parent().find("td:hidden").html();
        var category = jQuery(obj).parent().parent().attr('type');
        var code = jQuery(obj).parent().parent().attr("type");
        if(type=='div'){
            quantity = quantity_complete;
        }
        var url = "set_status_order.php";
            arr_eating[0] = {};
            arr_eating[0]['name'] = orderName;
            arr_eating[0]['quantity'] = quantity;
            arr_eating[0]['table_name'] = tableName;   
            arr_eating[0]['index'] = jQuery(obj).parent().parent().index();
            arr_eating[0]['id'] = id;
            arr_eating[0]['type'] ='';
            arr_eating[0]['target'] = target; 
            arr_eating[0]['code'] = code;
            arr_eating[0]['category'] = category;
        if(type=='total'){    
            jQuery(obj).parent().parent().fadeOut("1000ms",function(){
                jQuery(this).attr('special','hidden');
                if(target=='right'){
                arr_eating[0]['type'] = 'total';    
                socket.emit('move_eating',arr_eating);
                        var current_quantity = jQuery("tr#A"+id+"_div td:nth-child(3)").html();
                        value = to_numeric(current_quantity) + to_numeric(quantity);
                        jQuery("tr#A"+id+"_div td:nth-child(3)").html(value);
                        jQuery("tr#A"+id).attr('special','hidden');
                        jQuery("tr#A"+id+"_div").attr('special','show');
                        jQuery("tr#A"+id+"_div").show("3000ms");
                        jQuery("tr#A"+id+" td:nth-child(3)").html("0");
                    jQuery.ajax({
                            url : url,
                            data : {'id':id,'status':'1','type':'total','quantity':quantity},
                            dataType : 'TEXT',
                            type : 'POST'
                        });
                }
                else{
                    arr_eating[0]['type'] = 'total';     
                    socket.emit('back_eating',arr_eating);
                        var current_quantity = jQuery("tr#A"+id+" td:nth-child(3)").html();
                        value = to_numeric(current_quantity) + to_numeric(quantity);
                        jQuery("tr#A"+id+" td:nth-child(3)").html(value);
                        jQuery("tr#A"+id).show("3000ms");
                        jQuery("tr#A"+id).attr('special','show');
                        jQuery("tr#A"+id+"_div td:nth-child(3)").html("0");
                    jQuery.ajax({
                        url : url,
                        data : {'id':id,'status':'0','type':'total','quantity':quantity},
                        dataType : 'TEXT',
                        type : 'POST'
                    });
                }
                
                jQuery(this).hide("3000ms");
            });
        }
        else{
            if(target=='left'){
                arr_eating[0]['type'] = 'div'; 
                socket.emit('back_eating',arr_eating);
                    var current_quantity = jQuery("tr#A"+id+" td:nth-child(3)").html();
                        value = to_numeric(current_quantity) + to_numeric(quantity);
                        jQuery("tr#A"+id+" td:nth-child(3)").html(value);
                        
                        jQuery("tr#A"+id).attr('special','show');
                        jQuery("tr#A"+id).fadeIn("3000ms");
                 jQuery.ajax({
                    url : url,
                    data : {'id':id,'status':'0','type':'div','quantity':quantity},
                    dataType : 'TEXT',
                    type : 'POST'
                });   
            }
            else{
                arr_eating[0]['type'] = 'div'; 
                socket.emit('move_eating',arr_eating);
                    var current_quantity = jQuery("tr#A"+id+"_div td:nth-child(3)").html();
                        value = to_numeric(current_quantity) + to_numeric(quantity);
                        jQuery("tr#A"+id+"_div td:nth-child(3)").html(value);
                        jQuery("tr#A"+id+"_div").attr('special','show');
                        jQuery("tr#A"+id+"_div").show("3000ms");
                jQuery.ajax({
                    url : url,
                    data : {'id':id,'status':'1','type':'div','quantity':quantity},
                    dataType : 'TEXT',
                    type : 'POST'
                });
            }
        }    
    }
    function selectType(obj){
        var value = jQuery(obj).val();
            if(jQuery(obj).is(":checked")){
                jQuery("tr[type="+value+"][special=show]").show("3000ms");
            }
            else{
                jQuery("tr[type="+value+"][special=show]").hide("3000ms");
            }
    }
    
   /* function showChooseTab(obj){
        var current_status = jQuery(obj).find("span.glyphicon-chevron-down");
        if(current_status.length){
            current_status.removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
           jQuery("div#chooseTab").stop(true,true).slideDown("slow"); 
        }
        else{
           jQuery(obj).find("span.glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down"); 
           jQuery("div#chooseTab").stop(true,true).slideUp("slow");
        }
        
    }*/
    
</script>