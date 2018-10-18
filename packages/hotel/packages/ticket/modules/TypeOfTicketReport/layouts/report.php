<style>
    .simple-layout-bound,.simple-layout-middle,.simple-layout-content{
        width:100%;
    }
    @media print{
        .date_info
        {
           display: block; 
        }
        .panel-heading{
            text-align: center;
        }
        #TypeOfTicketReportForm{
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
        .nava{
            display: inline-block;
        }
        
    }
    .nav1{
        position: relative;
        top: -50px;
        left: 900px;
    }
    .print_after{
            display: none; 
        }
</style>
<div class="row">
    <div class="container-fluid">
        <div class="col-md-12 content_final">
           <div class="col-md-12 nav"> 

            <div class="col-md-8">
                <form name="TypeOfTicketReportForm" id="TypeOfTicketReportForm" method="POST" onsubmit="return check();">
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
                    <div class="col-md-2">
                        <button class="btn btn-primary" style="margin-top: 20px;" type="submit" name="search" value="1">[[.View.]]</button>
                    </div>
                </form>
            </div>
            </div>
            <div class="col-md-9 nav1 print_after">
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                 <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
            </div>
            <div class="panel panel-primary" style="width: 100%; float:left;">
                <div class="panel-heading">
                    <h3 class="panel-title" >[[.type_of_ticket_revenue_report.]]</h3>
                </div>
                <div class="panel-body" style="overflow:auto;">
                    <p style="text-align: center; display: block;">
                       <span class="date_info" style="display: block;">[[.From.]] : <?php echo $_REQUEST['from_hour']." ".$_REQUEST['start_date']; ?>  -  [[.To.]] : <?php echo $_REQUEST['to_hour']." ".$_REQUEST['end_date']; ?></span>
                    </p>
                    <table border="1" cellspacing="0" class="table table-bordered table-hover table_content" style="margin: 0 auto; width:100%;">
                        <thead>
                            <tr>
                                <th rowspan="2"></th>
                                <!--LIST:ticket_card_types-->
                                    <th colspan="2" style="text-align: center;">[[|ticket_card_types.name|]]</th>
                                <!--/LIST:ticket_card_types-->
                                <th colspan="2">[[.Total.]]</th>
                            </tr>
                            <tr>
                                <!--LIST:ticket_card_types-->
                                    <th>[[.quantity.]]</th>
                                    <th>[[.total_number.]]</th>
                                <!--/LIST:ticket_card_types-->
                                    <th>[[.quantity.]]</th>
                                    <th>[[.total_number.]]</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                    foreach($this->map['items'] as $key=>$value)
                                    {
                                 ?>
                                    <tr>
                                        <th><?php echo $key; ?></th>
                                        <?php
                                            foreach($value['info'] as $k=>$v)
                                            {
                                        ?>
                                            <td align="right" style="text-align: right;"><?php echo $v['quantity']>0?$v['quantity']:""; ?></td>
                                            <td align="right" style="text-align: right;"><?php echo $v['total']>0?number_format($v['total'],0,'.',','):""; ?></td>
                                        <?php
                                            }
                                        ?>
                                            <td align="right" style="text-align: right;"><?php echo $value['total_quantity']>0?$value['total_quantity']:""; ?></td>
                                            <td align="right" style="text-align: right;"><?php echo $value['grand_total']>0?number_format($value['grand_total'],0,'.',','):""; ?></td>
                                    </tr>
                                 <?php       
                                    }
                                ?>
                                <tr>
                                  <th>[[.Total.]]</th>
                                  <!--LIST:ticket_card_types-->
                                    <td align="right" style="text-align: right;"><?php echo [[=ticket_card_types.total_quantity=]]>0?number_format([[=ticket_card_types.total_quantity=]],0,'.',','):""; ?></th>
                                    <td align="right" style="text-align: right;"><?php echo [[=ticket_card_types.total=]]>0?number_format([[=ticket_card_types.total=]],0,'.',','):""; ?></th>
                                  <!--/LIST:ticket_card_types-->
                                    <td align="right" style="text-align: right;"><?php echo [[=total_quantity_final=]]>0?number_format([[=total_quantity_final=]],0,'.',','):""; ?></th>
                                    <td align="right" style="text-align: right;"><?php echo [[=grand_total_final=]]>0?number_format([[=grand_total_final=]],0,'.',','):""; ?></th>
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
        //jQuery(".content_final").width(table_width+100);
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