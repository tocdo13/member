<div>
    <table width="98%" style="margin: auto;">
        <tr>
            <td style="font-weight: bold;"><?php echo HOTEL_NAME; ?></td>
            <td width="90px" >[[.print_date.]] : </td>
            <td width="90px" style="text-align: left;"><?php echo date('d/m/Y',time()); ?></td>
        </tr>
        <tr>
            <td><?php echo HOTEL_ADDRESS; ?></td>
            <td>[[.print_time.]] : </td>
            <td style="text-align: left;"><?php echo date('h:m:s A',time()); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="height : 40px;line-height: 40px; text-align: center; font-weight: bold; font-size: 1.5em;">[[.arrival_report.]]</td>
        </tr>
        <tr>
            <td colspan="3">
                <fieldset style="width : 50%; margin : auto;">
                    <legend>[[.select_parameters.]]</legend>
                    <table style="width: 90%; margin: auto;">
                        <tr>
                            <form action="" method="post"> 
                                <td >
                                    [[.arrival_date_from.]] &nbsp;&nbsp;&nbsp; : 
                                </td>
                                <td >
                                    <input type="text" name="from_date" id="from_date" style="width: 70px;" value="<?php echo Url::get('from_date') ?>" /> 
                                </td>
                                <td>     
                                [[.to.]]
                                </td>
                                <td>
                                    <input type="text" name="to_date" id="to_date"  style="width: 70px;" value="<?php echo Url::get('to_date') ?>" /> 
                                </td>
                                <td>
                                    <input type="submit" name='submit' id="button_submit" />
                                </td>
                             </form>
                        </tr>
                    </table>
                </fieldset> 
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 60px; line-height: 60px;">
                <table width="100%" class="list_service">
                    <tr>
                        <td>[[.service.]]</td>
                        <!--LIST:service-->
                            <td>[[|service.name|]]</td>
                        <!--/LIST:service-->
                    </tr>
                    <tr>
                        <td>[[.id.]]</td>
                        <!--LIST:service-->
                            <td>[[|service.id|]]</td>
                        <!--/LIST:service-->
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="arrival_report">
                    <tr>
                        <td style="width: 3%;">[[.room.]]</td>
                        <td style="width : 7%">[[.status.]]</td>
                        <td style="width : 10%">[[.type.]]</td>
                        <td style="width : 15%">[[.guest_name.]]</td>
                        <td style="width : 8% ;">[[.arrival_time.]]</td>
                        <td style="width : 8% ;">[[.departure_time.]]</td>
                        <td style="width: 5%;">[[.VIP.]]</td>
                        <td style="width: 15%;">[[.TA/Company.]]</td>
                        <td style="width: 7%;">[[.rate.]]</td>
                        <td style="width : 6%">[[.return.]]</td>
                        <td style="width : 6%">[[.service.]]</td>
                        <td>[[.remark.]]</td>
                    </tr>
                     <!--LIST:report-->
                        <tr>
                            <td>[[|report.room_id|]]</td>
                            <td>[[|report.status|]]</td>
                            <td>[[|report.room_type|]]</td>
                            <td style="text-align: left; padding-left : 5px;">
                                <!--LIST:report.guest-->
                                    [[|report.guest.name|]]<br />
                                <!--/LIST:report.guest-->
                            </td>
                            <td><?php echo date('d/m/Y',[[=report.time_in=]])."<br/>".date('h:i:s A',[[=report.time_in=]]) ?></td>
                            <td><?php echo date('d/m/Y',[[=report.time_out=]])."<br/>".date('h:i:s A',[[=report.time_out=]]) ?></td>
                            <td>&nbsp;</td>
                            <td style="text-align: left; padding-left : 5px;">[[|report.company_name|]]</td>
                            <td style="text-align: right; padding-right : 2px;"><?php echo number_format([[=report.price=]]); ?></td>
                            <td>
                                <!--LIST:report.guest-->
                                    [[|report.guest.return|]]<br />
                                <!--/LIST:report.guest-->
                            </td>
                            <td>[[|report.service_id|]]</td>
                            <td style="text-align: left; padding-left : 5px;">[[|report.group_note|]]<br />[[|report.room_note|]]</td> 
                        </tr>
                     <!--/LIST:report-->
                </table>
            </td>
        </tr>
    </table>
</div>
<style>
table.arrival_report{
    margin : 20px 0;
    width : 100%;
}
table.list_service tr td{
    text-align: center;
    font-weight: bold;
    border : 1px solid #CCC;
}
table.arrival_report tr{
    border-collapse: collapse;
}
table.arrival_report tr:first-child{
    font-weight: bold;
}
table.arrival_report tr td{
    text-align: center;
    border : 1px solid #CCC;
}
@media print{
    #button_submit{
        display: none;
    }
    legend{
        display: none;
    }
}
</style>
<script>
jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
jQuery(document).ready(function(){
    function checkdate(){
        from_date = jQuery('#from_date').val();
        from_date = from_date.split('/');
        from_date = new Date(from_date[2] , from_date[1] , from_date[0] ).getTime();
        to_date = jQuery('#to_date').val();
        to_date = to_date.split('/');
        to_date = new Date(to_date[2] , to_date[1] , to_date[0] ).getTime();
        if(from_date > to_date){
            return false;
        }else{
            return true;
        }
    }
    function write_date(){
        if(jQuery('#to_date').val() == '' || !checkdate() ){
            from_date = jQuery('#from_date').val();
            from_date = from_date.split('/');
            if(from_date[0] == new Date(from_date[2] , from_date[1] , 0).getDate()){
                from_date[0] = 1;
                if(from_date[1] == 12 ){
                    from_date[1] = '01' ;
                    from_date[2]++;
                }else{
                    from_date[1]++;
                }
            }else{
                from_date[0]++;
            }
            if(from_date[0] < 10){
                from_date[0] = '0' + from_date[0];
            }
            to_date = from_date[0] + '/' + from_date[1] + '/' + from_date[2];
            jQuery('#to_date').val(to_date); 
        }
    }
    jQuery('#from_date').change(function(){
       write_date(); 
    });
    jQuery('#to_date').change(function(){
        if(!checkdate()){
            alert('[[.from_date_not_greater_than_to_date.]]');
            write_date();
        }
    });
    jQuery('#bottom_submit').click(function(){
       if(jQuery('#from_date').val() != '' && jQuery('#to_date').val() != '' && checkdate() ){
            this.form.submit();
                return;
           }else{
                return false;
           }
    });
});
</script>