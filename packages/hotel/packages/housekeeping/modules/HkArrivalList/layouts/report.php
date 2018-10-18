<style>
    
</style>
<table style="width: 99%; margin: 5px auto;">
    <tr>
        <td>
            <b><?php echo HOTEL_NAME; ?></b><br />
            <b><?php echo HOTEL_ADDRESS; ?></b>
        </td>
        <td style="text-align: right;">
            <b>[[.template_code.]]</b><br />
            <b>Date: <?php echo date('d/m/Y H:i');?></b><br />
            <b>Printer: <?php $user_data = Session::get('user_data'); echo $user_data['full_name']; ?></b>
        </td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: center;">
            <p><h1 style="text-transform: uppercase;">[[.arrival_customer_list.]]</h1></p>
            <span>[[.day.]] [[|date|]]</span>
        </th>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            <form name="ReportArrivalList" method="POST">
                <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                    [[.hotel.]]: <select name="portal_id" id="portal_id"></select>
                <?php }?>
                [[.date.]]: <input name="date" type="text" id="date"/>
            	[[.status.]]: <select name="status" id="status"></select>
            	<input type="submit" name="do_search" value="[[.view_report.]]"/>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table style="width: 100%;" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
                <tr style="text-align: center;">
                    <th>[[.stt.]]</th>
                    <th>[[.reservation_room_code.]]</th>
                    <th>[[.tour.]], [[.company.]]</th>
                    <th>[[.room.]]</th>
                    <th>[[.room_level.]]</th>
                    <th>[[.house_status.]]</th>
                    <th>[[.note.]]</th>
                    <th>[[.extra_bed.]]</th>
                    <th>[[.baby_cot.]]</th>
                    <th>[[.guest_name.]]</th>
                    <th>[[.countries.]]</th>
                    <th>[[.A/c.]]</th>
                    <th>[[.arrival_date.]]</th>
                    <th>[[.departure_date.]]</th>
                    <th>[[.night.]]</th>
                </tr>
                <!--LIST:items-->
                    <tr>
                        <td rowspan="[[|items.count_child|]]">[[|items.stt|]]</td>
                        <td rowspan="[[|items.count_child|]]"><a href="?page=reservation&cmd=edit&id=[[|items.recode|]]">[[|items.recode|]]</a></td>
                        <td rowspan="[[|items.count_child|]]">[[|items.customer_name|]]</td>
                        <?php $items_child = ''; ?>
                        <!--LIST:items.child-->
                            <?php $items_child = [[=items.child.id=]]; ?>
                            <?php if([[=items.child.count_child=]]==0){ [[=items.child.count_child=]]=1; } ?>
                            <td rowspan="[[|items.child.count_child|]]">[[|items.child.room_name|]]</td>
                            <td rowspan="[[|items.child.count_child|]]">[[|items.child.room_level_name|]]</td>
                            <td rowspan="[[|items.child.count_child|]]">[[|items.child.house_status|]]</td>
                            <td rowspan="[[|items.child.count_child|]]">[[|items.child.note|]]</td>
                            <td rowspan="[[|items.child.count_child|]]"><?php if([[=items.child.extrabed=]]!=0){echo 'yes';} ?></td>
                            <td rowspan="[[|items.child.count_child|]]"><?php if([[=items.child.baby_cot=]]!=0){echo 'yes';} ?></td>
                            <?php if(sizeof([[=items.child.child_child=]])==0){ ?>
                                <td></td>
                                <td></td>
                                <td><?php //echo ([[=items.child.count_traveler=]]>0)?[[=items.child.count_traveler=]]:[[=items.child.adult=]];?>[[|items.child.adult|]]/[[|items.child.child|]]</td>
                                <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                                <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                                <td>[[|items.child.night|]]</td>
                                </tr>
                            <?php }else{ ?>
                                <?php $items_child_childchild = '';  ?>
                                <!--LIST:items.child.child_child-->
                                    <?php $items_child_childchild = [[=items.child.child_child.id=]]; ?>
                                    <td>[[|items.child.child_child.traveller_name|]]</td>
                                    <td>[[|items.child.child_child.country_name|]]</td>
                                    <td rowspan="[[|items.child.count_child|]]"><?php //echo ([[=items.child.count_traveler=]]>0)?[[=items.child.count_traveler=]]:[[=items.child.adult=]];?>[[|items.child.adult|]]/[[|items.child.child|]]</td>
                                    <td rowspan="[[|items.child.count_child|]]"><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                                    <td rowspan="[[|items.child.count_child|]]"><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                                    <td rowspan="[[|items.child.count_child|]]">[[|items.child.night|]]</td>
                                    </tr>
                                    <?php break; ?>
                                <!--/LIST:items.child.child_child-->
                                <!--LIST:items.child.child_child-->
                                    <?php if($items_child_childchild != [[=items.child.child_child.id=]]){ ?>
                                    <tr>
                                    <td>[[|items.child.child_child.traveller_name|]]</td>
                                    <td>[[|items.child.child_child.country_name|]]</td>
                                    </tr>
                                    <?php } ?>
                                <!--/LIST:items.child.child_child-->
                            <?php } ?>
                            
                            <?php break; ?>
                        <!--/LIST:items.child-->
                        <!--LIST:items.child-->
                            <?php if($items_child != [[=items.child.id=]]){ ?>
                            <tr>
                            <?php if([[=items.child.count_child=]]==0){ [[=items.child.count_child=]]=1; } ?>
                                <td rowspan="[[|items.child.count_child|]]">[[|items.child.room_name|]]</td>
                                <td rowspan="[[|items.child.count_child|]]">[[|items.child.room_level_name|]]</td>
                                <td rowspan="[[|items.child.count_child|]]">[[|items.child.house_status|]]</td>
                                <td rowspan="[[|items.child.count_child|]]">[[|items.child.note|]]</td>
                                <td rowspan="[[|items.child.count_child|]]"><?php if([[=items.child.extrabed=]]!=0){echo 'yes';} ?></td>
                                <td rowspan="[[|items.child.count_child|]]"><?php if([[=items.child.baby_cot=]]!=0){echo 'yes';} ?></td>
                            <?php if(sizeof([[=items.child.child_child=]])==0){ ?>
                                    <td></td>
                                    <td></td>
                                    <td>[[|items.child.adult|]]/[[|items.child.child|]]</td>
                                    <td><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                                    <td><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                                    <td>[[|items.child.night|]]</td>
                                </tr>
                            <?php }else{ ?>
                                <?php $items_child_childchild = '';  ?>
                                <!--LIST:items.child.child_child-->
                                    <?php $items_child_childchild = [[=items.child.child_child.id=]]; ?>
                                        <td>[[|items.child.child_child.traveller_name|]]</td>
                                        <td>[[|items.child.child_child.country_name|]]</td>
                                        <td rowspan="[[|items.child.count_child|]]">[[|items.child.adult|]]/[[|items.child.child|]]</td>
                                        <td rowspan="[[|items.child.count_child|]]"><?php echo date('H:i',[[=items.child.time_in=]]);  ?> &nbsp;&nbsp;[[|items.child.arrival_time|]]</td>
                                        <td rowspan="[[|items.child.count_child|]]"><?php echo date('H:i',[[=items.child.time_out=]]);  ?> &nbsp;&nbsp;[[|items.child.departure_time|]]</td>
                                        <td rowspan="[[|items.child.count_child|]]">[[|items.child.night|]]</td>
                                    </tr>
                                    <?php break; ?>
                                <!--/LIST:items.child.child_child-->
                                
                                <!--LIST:items.child.child_child-->
                                    <?php if($items_child_childchild != [[=items.child.child_child.id=]]){ ?>
                                    <tr>
                                        <td>[[|items.child.child_child.traveller_name|]]</td>
                                        <td>[[|items.child.child_child.country_name|]]</td>
                                    </tr>
                                    <?php } ?>
                                <!--/LIST:items.child.child_child-->
                            <?php } ?>
                            <?php } ?>
                        <!--/LIST:items.child-->
                <!--/LIST:items-->
            <tr>
                <td style="text-align: right;" colspan="3"><strong>[[.total.]]: </strong></td>
                <td style="text-align: center;"><strong>[[|total_room|]]</strong></td>
                <td colspan="7"></td>
                <td style="text-align: center;"><strong>[[|total_adult|]]/[[|total_child|]]</strong></td>
                <td colspan="2"></td>
                <td style="text-align: center;"><strong>[[|total_night|]]</strong></td>
            </tr>
            </table>
        </td>
    </tr>
</table>
<script>
    jQuery("#date").datepicker();
    full_screen();
</script>