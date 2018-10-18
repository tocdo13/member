<style>
    .simple-layout-bound,.simple-layout-middle,.simple-layout-content{
        width:100%;
    }
    @media print{
        .print_after{
            display: block; 
        }
        .panel-heading{
            text-align: center;
        }
        #TicketInvoiceRevenueReportForm{
            display: none;
        }
        .panel-title{
            font-size: 40px;
            line-height: 40px;
        }
        .panel-heading
        {
            line-height: 40px;
        }
    }
    .content_final{
        display: inline-block;
    }
    .nav1{
        position: relative;
        left: 800px;
        top: 0px;
    }
    .print_after{
            display: none; 
        }
</style>
<div class="row">
    <div class="container-fluid">
        <div class="col-md-12 content_final">

            <div class="col-md-12">
                <form name="TicketInvoiceRevenueReportForm" id="TicketInvoiceRevenueReportForm" method="POST"  onsubmit="return check();">
                    <div class="form-group col-md-1" style="width: 110px;">
                        <label class="form-label">[[.From_invoice.]]</label>
                        <input name="from_invoice" type="number" id="from_invoice" class="form-control" />
                    </div>
                    <div class="form-group col-md-1" style="width: 110px;">
                        <label class="form-label">[[.To_invoice.]]</label>
                        <input name="to_invoice" type="number" id="to_invoice" class="form-control" />
                    </div>
                    <div class="form-group col-md-2">
                        <label class="form-label">[[.User_name.]]</label>
                        <select name="user_name" id="user_name" class="form-control">
                          <option value="all" <?php if(isset($_REQUEST['user_name']) && 'all'==$_REQUEST['user_name']) echo "selected=''"; ?>>[[.All.]]</option>
                          <?php
                            foreach([[=user_name_arr=]] as $key=>$value)
                            {
                          ?>
                              <option <?php if(isset($_REQUEST['user_name']) && $value['user_id']==$_REQUEST['user_name']) echo "selected=''"; ?> value="<?php echo $value['user_id']; ?>"><?php echo $value['user_id']; ?></option>
                          <?php      
                            }
                          ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="form-label">[[.Card_sale.]]</label>
                        <select name="ticket_card_sales" id="ticket_card_sales" class="form-control">
                          <option value="all" <?php if(isset($_REQUEST['ticket_card_sales']) && 'all'==$_REQUEST['ticket_card_sales']) echo "selected=''"; ?>>[[.All.]]</option>
                          <?php
                            foreach([[=ticket_card_sales=]] as $key=>$value)
                            {
                          ?>
                              <option <?php if(isset($_REQUEST['ticket_card_sales']) && $key==$_REQUEST['ticket_card_sales']) echo "selected=''"; ?> value="<?php echo $key ?>"><?php echo $value['name']; ?></option>
                          <?php      
                            }
                          ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2" style="width: 210px;">
                        <label class="form-label" style="width: 100%;">[[.From_date.]]</label>
                        <input name="from_hour" type="text" id="from_hour" class="form-control" style="width: 40%; float: left; margin-right: 3%;" />
                        <input name="start_date" type="text" id="start_date" class="form-control" style="width: 55%;" />
                    </div>
                    <div class="form-group col-md-2" style="width: 210px;">
                        <label class="form-label" style="width: 100%;">[[.To_date.]]</label>
                        <input name="to_hour" type="text" id="to_hour" class="form-control" style="width: 40%; float: left; margin-right: 3%;" />
                        <input name="end_date" type="text" id="end_date" class="form-control" style="width: 55%;" />
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary" style="margin-top: 20px;" type="submit" name="search" value="1">[[.View.]]</button>
                    </div>
                </form>
                
            </div>
            <div class="col-md-2 nav1 print_after">
                    <p>
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>  
               </p>
            </div>
            <div class="panel panel-primary" style="width: 100%; float:left;">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.ticket_revenue_report.]]</h4>
                </div>
                <div class="panel-body">
                    <p style="text-align: center; display: block;">
                       <span class="date_info" style="display: block;">[[.From.]] : <?php echo $_REQUEST['from_hour']." ".$_REQUEST['start_date']; ?>  -  [[.To.]] : <?php echo $_REQUEST['to_hour']." ".$_REQUEST['end_date']; ?></span>
                    </p>
                    <table border="1" cellspacing="0" class="table table-bordered table_content" style="margin: 0 auto; width:100%;">
                        <thead>
                            <tr>
                                <th rowspan="2">[[.Stt.]]</th>
                                <th rowspan="2">[[.Invoice_id.]]</th>
                                <th rowspan="2">[[.MICE_ID.]]</th>
                                <th rowspan="2">[[.Sale_name.]]</th>
                                <th rowspan="2">[[.Ticket_type.]]</th>
                                <th rowspan="2">[[.Quantity.]]</th>
                                <th rowspan="2">[[.Total_after_tax.]]</th>
                                <th rowspan="2">[[.Tax.]]</th>
                                <th rowspan="2">[[.Total_before_tax.]]</th>
                                <th rowspan="2">[[.Total.]]</th>
                                <th colspan="3">[[.Payment.]]</th>
                                <th rowspan="2">[[.MICE.]]</th>
                                <th rowspan="2">[[.Seller.]]</th>
                            </tr>
                            <tr>
                                <th>[[.CASH.]]</th>
                                <th>[[.CREDIT_CARD.]]</th>
                                <th>[[.PREPAID_CARD.]]</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php
                               $i=0;
                               foreach($this->map['items'] as $key=>$value)
                               {
                                 if(isset($value['count']))
                                 {
                                    $i++; 
                                    ?>
                                      <tr>
                                         <td rowspan="<?php echo $value['count']; ?>"><?php echo $i; ?></td>
                                         <td rowspan="<?php echo $value['count']; ?>"><?php echo $value['ticket_card_wicket_id']; ?></td>
                                         <td rowspan="<?php echo $value['count']; ?>"></td>                                         
                                         <td><?php echo $value['ticket_card_sales_name']; ?></td>
                                         <td><?php echo $value['ticket_card_types_name']; ?></td>                                         
                                         <td align="right" style="text-align: right;"><?php echo System::display_number($value['quantity']); ?></td>
                                         <td align="right" style="text-align: right;"><?php echo System::display_number($value['total']/1.1); ?></td>
                                         <td align="right" style="text-align: right;"><?php echo System::display_number($value['total']-$value['total']/1.1); ?></td>
                                         <td align="right" style="text-align: right;"><?php echo System::display_number($value['total']); ?></td>
                                         <td align="right" style="text-align: right;" rowspan="<?php echo $value['count']; ?>"><?php echo System::display_number($value['total_final']); ?></td>
                                         <td align="right" style="text-align: right;" rowspan="<?php echo $value['count']; ?>"><?php echo System::display_number($value['payment_info']['CASH']); ?></td>
                                         <td align="right" style="text-align: right;" rowspan="<?php echo $value['count']; ?>"><?php echo System::display_number($value['payment_info']['CREDIT_CARD']); ?></td>
                                         <td align="right" style="text-align: right;" rowspan="<?php echo $value['count']; ?>"><?php echo System::display_number($value['payment_info']['PREPAID_CARD']); ?></td>
                                         <td align="right" style="text-align: right;" rowspan="<?php echo $value['count']; ?>"></td>
                                         <td rowspan="<?php echo $value['count']; ?>"><?php echo $value['seller']; ?></td>
                                      </tr>
                                    <?php
                                 }
                                 else{
                                    ?>
                                 <tr>                                                                            
                                     <td><?php echo $value['ticket_card_sales_name']; ?></td>
                                     <td><?php echo $value['ticket_card_types_name']; ?></td>                                     
                                     <td align="right" style="text-align: right;"><?php echo System::display_number($value['quantity']); ?></td>
                                     <td align="right" style="text-align: right;"><?php echo System::display_number($value['total']/1.1); ?></td>
                                     <td align="right" style="text-align: right;"><?php echo System::display_number($value['total']-$value['total']/1.1); ?></td>
                                     <td align="right" style="text-align: right;"><?php echo System::display_number($value['total']); ?></td>
                                 </tr>   
                                    <?php
                                 }
                               }
                             ?> 
                             <tr>
                                <th align="center" style="text-align: center;" colspan="5">[[.Total.]]</th>
                                <td align="right" style="text-align: right;">[[|total_quantity|]]</td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_invoice=]]/1.1); ?></td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_invoice=]]-([[=total_invoice=]]/1.1)); ?></td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_invoice=]]); ?></td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_invoice=]]); ?></td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_cash=]]); ?></td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_credit_card=]]); ?></td>
                                <td align="right" style="text-align: right;"><?php echo System::display_number([[=total_prepaid_card=]]); ?></td>
                                <td></td>
                                <td></td>
                             </tr>                            
                        </tbody>
                    </table>
                    <div class="col-md-12" style="width: 100%; float: left;">
                        <div class="pull-right" style="margin: 20px; float: right;">
                            <span>[[.Date.]] <?php echo Date("d"); ?> [[.Month.]] <?php echo date("m"); ?> [[.Year.]] <?php echo date("Y"); ?></span>
                        </div>
                    </div>
                    <div class="col-md-12"  style="width: 100%; float: left;">
                        <div style="width: 49%; float: left; text-align: center;">
                           [[.Founder.]]
                        </div>
                        <div style="width: 50%; float: left; text-align: center;">
                           [[.Head_of_department.]] 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    jQuery("#from_hour").mask("99:99");
    jQuery("#to_hour").mask("99:99");
    jQuery(document).ready(function(){
        
        var table_width = jQuery(".table_content").width();
        jQuery(".content_final").width(table_width+100);
        jQuery("a[title=In]").removeAttr("onclick");
        jQuery("a[title=In]").click(function(){
            jQuery(".print_after").each(function(){
                jQuery(this).css("display","block");
            });
            var user = "<?php echo User::id(); ?>";
            printWebPart('printer',user);
            window.location.reload();
        });
    });
    function check()
    {
        var start_date=jQuery("#start_date").val();
        start_date=start_date.split("/");
        var new_start_date=start_date[1]+"/"+start_date[0]+"/"+start_date[2];
        new_start_date = new Date(new_start_date).getTime()/1000;
        var from_hour = jQuery("#from_hour").val();
        from_hour_temp = from_hour.split(":");
        
        new_start_date += from_hour_temp[0]*3600+from_hour_temp[1]*60;
        var end_date=jQuery("#end_date").val();
        end_date=end_date.split("/");
        var new_end_date=end_date[1]+"/"+end_date[0]+"/"+end_date[2];
        new_end_date = new Date(new_end_date).getTime()/1000;
        var to_hour = jQuery("#to_hour").val();
        to_hour_temp = to_hour.split(":");
        new_end_date += to_hour_temp[0]*3600+to_hour_temp[1]*60;
        
        if(new_end_date<=new_start_date)
        {
            if(!confirm("Thời gian bắt đầu lớn hơn thời gian kết thúc. Bạn có muốn tiếp tục không?"))
            {
                return false;
            }
        }
        
        return true;
    }
</script>