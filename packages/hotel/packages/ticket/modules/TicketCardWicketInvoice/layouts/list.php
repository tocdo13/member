<div class="row">
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.ticket_card_wicket_invoice_list.]]</h4>
                </div>
                <div class="panel-body">
                    <form id="TicketCardWicketInvoiceForm" name="TicketCardWicketInvoiceForm" method="POST">
                        <div class="col-md-12" style="margin-bottom: 20px;">
                           <div class="col-md-2">
                              <label class="form-label">[[.Code.]] : </label>
                              <input name="code" type="text" id="code" class="form-control" autocomplete="off" />
                           </div>
                           <div class="col-md-2">
                              <label class="form-label">[[.booking_code.]] : </label>
                              <input name="booking_code" type="text" id="booking_code"  class="form-control" autocomplete="off" />
                           </div>
                           <div class="col-md-2">
                              <label class="form-label">[[.From_date.]] : </label>
                              <input name="from_date" type="text" id="from_date" class="form-control"  />
                           </div>
                           <div class="col-md-2">
                              <label class="form-label">[[.To_date.]] : </label>
                              <input name="to_date" type="text" id="to_date" class="form-control"  />
                           </div>
                           <div class="col-md-3">
                              <label class="form-label">[[.Sale_name.]] : </label>
                              <select name="sale_name" id="sale_name" class="form-control">
                                        <option value="" hidden="">---Chọn quầy vé---</option> 
                                        <option value="">---[[.All.]]---</option> 
                                 <?php
                                    foreach($this->map['ticket_card_sale'] as $value)
                                    {
                                  ?>
                                     <option <?php if([[=sale_name=]]==$value['name']) echo 'selected=""'; ?> value='<?php echo $value['name']; ?>'><?php echo $value['name']; ?></option>
                                  <?php     
                                    }
                                 ?>
                              </select> 
                           </div>                                                   
                        </div>
                        <div class="col-md-12" style="margin-bottom: 20px;">
                           
                           <div class="col-md-2">
                              <label class="form-label">[[.Customer_Type.]] : </label>
                              <select name="customer_type" id="customer_type" class="form-control" onchange="change_value(this.value,'');">
                                 <option value="all">[[.All.]]</option>
                                 <option value="1">Khách lẻ</option>
                                 <option value="2">Khách đoàn</option>
                              </select>
                           </div>
                            
                           <div class="col-md-3">
                              <label class="form-label">[[.Customer.]] : </label>
                              <select name="customer_id" id="customer_id" class="form-control">
                                        <option value="" hidden="">---Chọn nguồn khách---</option> 
                                        <option value="">---[[.All.]]---</option> 
                                 <?php
                                    foreach($this->map['customer_arr'] as $value)
                                    {
                                  ?>
                                     <option <?php if([[=customer_id=]]==$value['name']) echo 'selected=""'; ?> value='<?php echo $value['name']; ?>'><?php echo $value['name']; ?></option>
                                  <?php     
                                    }
                                 ?>
                              </select> 
                           </div>
                           <div class="col-md-3"> 
                              <label class="form-label">[[.User_name.]] : </label>
                              <select name="user_name" id="user_name" class="form-control">
                                        <option value="" hidden="">---Chọn tài khoản---</option> 
                                        <option value="">---[[.All.]]---</option> 
                                 <?php
                                    foreach($this->map['user_name_arr'] as $value)
                                    {
                                  ?>
                                     <option <?php if([[=user_name=]]==$value['user_id']) echo 'selected=""'; ?> value='<?php echo $value['user_id']; ?>'><?php echo $value['user_id']; ?></option>
                                  <?php     
                                    }
                                 ?>
                              </select>    
                           </div>   
                          <button class="btn btn-primary pull-right" type="submit" name="search" value="1" style="margin-top: 20px;">[[.Search.]]</button>
                        </div> 
                    </form>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>[[.Code.]]</th>
                                <th>[[.Booking_online_code.]]</th>
                                <th>[[.Sale_name.]]</th>
                                <th>[[.Time.]]</th>
                                <th>[[.Customer.]]</th>
                                <th>[[.Total.]]</th>
                                <th>[[.Sales.]]</th>
                                <th>[[.View.]]</th>
                                <th>[[.Edit.]]</th>
                                <th>[[.Delete.]]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; $total=0; ?>
                            <!--LIST:ticket_card_wicket-->
                                <tr title="[[|ticket_card_wicket.note|]]">
                                    <td><?php echo $i; $i++; ?></td>
                                    <td>[[|ticket_card_wicket.code|]]</td>
                                    <td>[[|ticket_card_wicket.booking_online_code|]]</td>
                                    <td>[[|ticket_card_wicket.ticket_card_sales_name|]]</td>
                                    <td><?php echo date("H:i d/m/Y",[[=ticket_card_wicket.time=]]); ?></td>
                                    <td>[[|ticket_card_wicket.customer_name|]]</td>
                                    <td style="text-align: right;"><?php echo System::display_number([[=ticket_card_wicket.total=]]); $total+=[[=ticket_card_wicket.total=]]; ?></td>
                                    <td>[[|ticket_card_wicket.seller|]]</td>
                                    <td align="center"><button class="btn btn-success btn-sm"  onclick="window.open('?page=ticket_card_wicket_invoice&cmd=view&id=[[|ticket_card_wicket.id|]]');"><span class="glyphicon glyphicon-search"></span></button></td>
                                    <td align="center">
                                        <?php
                                            if(User::can_edit(false,ANY_CATEGORY)){
                                         ?>
                                        <button class="btn btn-info btn-sm"  onclick="window.open('?page=ticket_card_wicket&cmd=edit&sales_id=[[|ticket_card_wicket.ticket_card_sales_id|]]&id=[[|ticket_card_wicket.id|]]');"><span class="glyphicon glyphicon-pencil"></span></button>
                                        <?php
                                           }
                                         ?>
                                    </td>
                                    <td align="center">
                                         <?php
                                            if(User::can_delete(false,ANY_CATEGORY)){
                                         ?> 
                                         <button class="btn btn-danger btn-sm" onclick="if(confirm('[[.Do_you_want_delete_this_invoice.]]?')) window.location='?page=ticket_card_wicket_invoice&cmd=delete&id=[[|ticket_card_wicket.id|]]';"><span class="glyphicon glyphicon-remove"></span></button>
                                         <?php
                                           }
                                         ?>
                                    </td>
                                </tr>
                            <!--/LIST:ticket_card_wicket-->
                               <tr>
                                  <td colspan="6" style="text-align: right; font-weight:bold;">[[.Total.]]</td>
                                  <td style="text-align: right;"><?php echo System::display_number($total); ?></td>  
                                  <td colspan="4"></td> 
                               </tr>
                        </tbody>
                    </table>
                    <div class="col-md-6 col-md-offset-6">
                        <nav>
                          <ul class="pagination">
                            [[|paging|]]
                          </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
   jQuery("#from_date").datepicker();
   jQuery("#to_date").datepicker();
   jQuery(document).ready(function(){
        
   <?php
    if(Url::get('customer_type'))
    {
   ?>
        change_value('<?php echo Url::get('customer_type'); ?>','<?php echo Url::get('customer_id'); ?>');
   <?php     
    }
   ?>
    
      var content = "";
      
      jQuery("div.paging-bound").children().each(function(){
        //console.log(jQuery(this).prop('outerHTML'));
        var tagName = jQuery(this).prop("tagName");
        if(tagName=='A'){
            content += '<li class="page-item">';
            content += jQuery(this).prop('outerHTML');
            content += '</li>';
        }
        else if(tagName=='FONT')
        {
            content += '<li class="page-item active">';
            content += '<a style="line-height: inherit;" class="page-link" href="javascript:void(0);">'+jQuery(this).html()+'</a>';
            content += '</li>';
        }
        else if(jQuery(this).attr('class')=='dot')
        {
            content += '<li class="page-item">';
            content += '<a style="line-height: inherit;" class="page-link" href="javascript:void(0);">'+jQuery(this).html()+'</a>';
            content += '</li>';
        }
      });
      
      jQuery("ul.pagination").html(content);
   });
   
   
   function change_value(customer_type,default_value = "")
   {
      var customer_list_js = [[|customer_list_js|]];
      var str = '<option value="" hidden="">---Chọn nguồn khách---</option><option value="">---[[.All.]]---</option>';
      if(customer_type==1)
      {
         for(var k in customer_list_js)
         {
            if(customer_list_js[k]['group_id']=='WALKIN')
            {
                str+= "<option value='"+customer_list_js[k]['name']+"'"+((default_value!='' && default_value==customer_list_js[k]['name'])? 'selected=""':"")+">"+customer_list_js[k]['name']+"</option>";
            }
         }
         
      }
      else if(customer_type==2){
        for( var k in customer_list_js)
         {
            if(customer_list_js[k]['group_id']!='WALKIN')
            {
                str+= "<option value='"+customer_list_js[k]['name']+"'"+((default_value!='' && default_value==customer_list_js[k]['name'])? 'selected=""':"")+">"+customer_list_js[k]['name']+"</option>";
            }
         }          
      }
      else{
        
        for( var k in customer_list_js)
         {
                str+= "<option value='"+customer_list_js[k]['name']+"'"+((default_value!='' && default_value==customer_list_js[k]['name'])? 'selected=""':"")+">"+customer_list_js[k]['name']+"</option>";
         } 
      }
       jQuery("#customer_id").html(str);
   }
   
</script>