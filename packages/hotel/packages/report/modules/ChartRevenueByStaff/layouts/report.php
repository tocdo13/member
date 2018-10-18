<style type="text/css">
@media print {
    .description
    {
        display: none;
    }
    .chart_days
    {
        border:0px !important;
    }
    .chart_months
    {
        border:none !important;
    }
    .chart_summary
    {
       margin-top: -260px !important;
    }
}
</style>

<!---------SEARCH----------->
<div width="100%" style="">
    <div width="100%" style=" border: 0px solid green ; height:auto;">
        <table width="100%">
            <tr><td align="center" >
                <form name="SearchForm" method="post">
                    <table style="margin: 0 auto;" width="100%">
                        <tr>
                            <!--Start Luu Nguyen Giap add portal -->
                            
                            <td align="center">[[.hotel.]]
                            <select name="portal_id" id="portal_id"></select>
                            
                            <!--End Luu Nguyen Giap add portal -->
                            [[.date.]]
                            
                                <input name="date" type="text" id="date" style="width:100px;" class="by-year"/>
                            
                            <input type="submit" name="do_search" value="[[.report.]]"/></td>
                            <td align="right" width="170px;">
                            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                            <br />
                            [[.user_print.]]:<?php echo ' '.User::id();?>
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
            </tr>
        </table>
    </div>
</div>


<table width="100%" >
    <tr>
        <td  style=" padding: 5px; " align ="center" >
            <div style=" border: 1px solid green ;height: auto; " class="chart_days">
                <div style=" height: 300px; " id="revention_indate" ></div>
                <?php if(HOTEL_CURRENCY=="VND"){ ?>
                <strong>Unit 1.000.000 VND</strong>
                <?php }else{?>
                <strong>Unit USD</strong>
                <?php }?>
                <br />&nbsp
            </div>
        </td>
    </tr>
    <tr>
        <td  style=" padding: 5px; " align ="center" >
            <div style=" border: 1px solid green ; height: auto; " class="chart_months">
                <div style="height: 300px; " id="revention_dates_in_month" ></div>
                <?php if(HOTEL_CURRENCY=="VND"){; ?>
                <strong>Unit 1.000.000 VND</strong>
                <?php }else{?>
                <strong>Unit USD</strong>
                <?php }?>
                <br />&nbsp
            </div>
        </td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding ="0" class="chart_summary">
    <tr>
        <td width="38%" style="padding: 5px; " >
            <div style=" border: 1px solid green ; height: auto; ">
                <table  style=" width: 100%; margin: 0px; ">
                    <tr height="30">
                        <td colspan="4" align="center" ><strong>[[.real_revenue_today.]]</strong></td>
                    </tr>
                    <tr height="25">
                        <th width="30%" align="left" style=" padding-left: 30px; "  >[[.quota.]]</th>
                        <th width="20%" align="center" >VND</th>
                        <th width="10%" align="center" >USD</th>
                        <th width="20%" align="center" >[[.total.]]</th>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.cash.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['CASH']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo $this->map['real_revention_indate']['CASH']['TOTAL_USD']; ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['CASH']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.credit_card.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['CREDIT_CARD']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['real_revention_indate']['CREDIT_CARD']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['CREDIT_CARD']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.debit.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['DEBIT']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['real_revention_indate']['DEBIT']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['DEBIT']['TOTAL'],0); ?></td>
                    </tr>
                   <!-- <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.free.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php //echo number_format($this->map['real_revention_indate']['FOC']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php //echo number_format($this->map['real_revention_indate']['FOC']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php //echo number_format($this->map['real_revention_indate']['FOC']['TOTAL'],0); ?></td>
                    </tr>-->
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.bank_tranfer.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['BANK']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['real_revention_indate']['BANK']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['BANK']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.refund.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['REFUND']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['real_revention_indate']['REFUND']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['real_revention_indate']['REFUND']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  ><strong>[[.total.]]</strong></td>
                        <td align="right" style=" padding-right: 30px; "  >
                            <strong>
                                <?php echo number_format(($this->map['real_revention_indate']['CASH']['TOTAL_VND']
                                        +$this->map['real_revention_indate']['CREDIT_CARD']['TOTAL_VND']
                                        +$this->map['real_revention_indate']['DEBIT']['TOTAL_VND']
                                        +$this->map['real_revention_indate']['BANK']['TOTAL_VND']
                                        -$this->map['real_revention_indate']['REFUND']['TOTAL_VND']),0); 
                                        
                                ?>
                            </strong>
                        </td>
                        <td align="right" style=" padding-right: 10px; "  >
                            <strong>
                                <?php echo number_format(($this->map['real_revention_indate']['CASH']['TOTAL_USD']
                                        +$this->map['real_revention_indate']['CREDIT_CARD']['TOTAL_USD']
                                        +$this->map['real_revention_indate']['DEBIT']['TOTAL_USD']
                                        +$this->map['real_revention_indate']['BANK']['TOTAL_USD']
                                        -$this->map['real_revention_indate']['REFUND']['TOTAL_USD']),0); 
                                        
                                ?>
                            </strong>
                        </td>
                        <td align="right" style=" padding-right: 30px; "  >
                            <strong>
                                <?php echo number_format(($this->map['real_revention_indate']['CASH']['TOTAL']
                                        +$this->map['real_revention_indate']['CREDIT_CARD']['TOTAL']
                                        +$this->map['real_revention_indate']['DEBIT']['TOTAL']
                                        +$this->map['real_revention_indate']['BANK']['TOTAL']
                                        -$this->map['real_revention_indate']['REFUND']['TOTAL']),0); 
                                        
                                ?>
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td width="28%" height="100%" rowspan="2" style=" padding: 5px; " >
            <div style=" border: 1px solid green ; height: 420px; ">
                <table  style=" width: 100%; margin: 0px;">
                    <tr height="30">
                        <td colspan="2" align="center" ><strong>[[.expect_revenue_today.]]</strong></td>
                    </tr>
                    <tr height="25" >
                        <th width="45%" align="left"  style=" padding-left: 30px; "  >[[.quota.]]</th>
                        <th width="45%" align="center" >[[.amount.]]</th>
                    </tr>
                    <?php if($this->map['list_part_php']['ROOM']){ ?>
                    <tr height="25" >
                        <td align="left" style=" padding-left: 30px; "  >[[.room_revenue.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_ROOM_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(BREAKFAST_SPLIT_PRICE){ ?>
                    <tr height="25" >
                        <td align="left" style=" padding-left: 30px; "  ><i>[[.breakfast_revenue.]]</i></td>
                        <td align="right" style=" padding-right: 30px; "  ><i><?php echo number_format($this->map['revention_indate_php']['TOTAL_BREAKFAST_IN_DATE_REVENUE'],0); ?></i></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['HOUSEKEEPING']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.housekeeping.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_HOUSEKEEPING_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['EXTRASERVICE']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.extra_sevice.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_EXTRASERVICE_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['SPA']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.spa.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_SPA_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['SALE']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.shop.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_SALE_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['TICKET']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.ticket.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_TICKET_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['BAR']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.bar.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_indate_php']['TOTAL_BAR_IN_DATE_REVENUE'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  ><strong>[[.total.]]</strong></td>
                        <td align="right" style=" padding-right: 30px; "  >
                            <strong>
                                <?php echo number_format(($this->map['revention_indate_php']['TOTAL_ROOM_IN_DATE_REVENUE']
                                        +$this->map['revention_indate_php']['TOTAL_HOUSEKEEPING_IN_DATE_REVENUE']
                                        +$this->map['revention_indate_php']['TOTAL_EXTRASERVICE_IN_DATE_REVENUE']
                                        +$this->map['revention_indate_php']['TOTAL_SPA_IN_DATE_REVENUE']
                                        +$this->map['revention_indate_php']['TOTAL_SALE_IN_DATE_REVENUE']
                                        +$this->map['revention_indate_php']['TOTAL_TICKET_IN_DATE_REVENUE']
                                        +$this->map['revention_indate_php']['TOTAL_BAR_IN_DATE_REVENUE']),0); 
                                ?>
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td width="28%" height="100%" rowspan="2" style=" padding: 5px; " >
            <div style=" border: 1px solid green ; height: 420px; ">
                <table  style=" width: 100%; margin: 0px; ">
                    <tr height="30" >
                        <td colspan="2" align="center" ><strong>[[.expect_revenue_inmonth.]]</strong></td>
                    </tr>
                    <tr height="25" >
                        <th width="45%" align="left"  style=" padding-left: 30px; "  >[[.quota.]]</th>
                        <th width="45%" align="center" >[[.amount.]]</th>
                    </tr>
                    <?php if($this->map['list_part_php']['ROOM']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.room_revenue.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php  echo number_format($this->map['revention_inmonth']['TOTAL_ROOM_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(BREAKFAST_SPLIT_PRICE){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  ><i>[[.breakfast_revenue.]]</i></td>
                        <td align="right" style=" padding-right: 30px; "  ><i><?php  echo number_format($this->map['revention_inmonth']['TOTAL_BREAKFAST_IN_MONTH'],0); ?></i></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['HOUSEKEEPING']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.housekeeping.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_inmonth']['TOTAL_HOUSEKEEPING_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['EXTRASERVICE']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.extra_sevice.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_inmonth']['TOTAL_EXTRASERVICE_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['SPA']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.spa.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_inmonth']['TOTAL_SPA_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['SALE']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.shop.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_inmonth']['TOTAL_SALE_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['TICKET']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.ticket.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_inmonth']['TOTAL_TICKET_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($this->map['list_part_php']['BAR']){ ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.bar.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['revention_inmonth']['TOTAL_BAR_IN_MONTH'],0); ?></td>
                    </tr>
                    <?php } ?>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  ><strong>[[.total.]]</strong></td>
                        <td align="right" style=" padding-right: 30px; "  >
                            <strong>
                                <?php echo number_format(($this->map['revention_inmonth']['TOTAL_ROOM_IN_MONTH']
                                        +$this->map['revention_inmonth']['TOTAL_HOUSEKEEPING_IN_MONTH']
                                        +$this->map['revention_inmonth']['TOTAL_EXTRASERVICE_IN_MONTH']
                                        +$this->map['revention_inmonth']['TOTAL_SPA_IN_MONTH']
                                        +$this->map['revention_inmonth']['TOTAL_SALE_IN_MONTH']
                                        +$this->map['revention_inmonth']['TOTAL_TICKET_IN_MONTH']
                                        +$this->map['revention_inmonth']['TOTAL_BAR_IN_MONTH']),0); 
                                ?>
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td width="38%" style=" padding: 5px; " >
            <div style=" border: 1px solid green ; height: auto; ">
                <table  style="width: 100%; margin: 0px; ">
                    <tr height="30" >
                        <td colspan="4" align="center" ><strong>[[.deposit_amount_today.]]</strong></td>
                    </tr>
                    <tr height="25" >
                        <th width="30%" align="left" style=" padding-left: 30px; "  >[[.quota.]]</th>
                        <th width="20%" align="center" >VND</th>
                        <th width="10%" align="center" >USD</th>
                        <th width="20%" align="center" >[[.total.]]</th>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.cash.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['deposit_indate']['CASH']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['deposit_indate']['CASH']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['deposit_indate']['CASH']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.credit_card.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['deposit_indate']['CREDIT_CARD']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['deposit_indate']['CREDIT_CARD']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['deposit_indate']['CREDIT_CARD']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  >[[.bank_tranfer.]]</td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['deposit_indate']['BANK']['TOTAL_VND'],0); ?></td>
                        <td align="right" style=" padding-right: 10px; "  ><?php echo number_format($this->map['deposit_indate']['BANK']['TOTAL_USD'],0); ?></td>
                        <td align="right" style=" padding-right: 30px; "  ><?php echo number_format($this->map['deposit_indate']['BANK']['TOTAL'],0); ?></td>
                    </tr>
                    <tr height="25">
                        <td align="left" style=" padding-left: 30px; "  ><strong>[[.total.]]</strong></td>
                        <td align="right" style=" padding-right: 30px; "  >
                            <strong>
                                <?php echo number_format(($this->map['deposit_indate']['CASH']['TOTAL_VND']
                                        +$this->map['deposit_indate']['CREDIT_CARD']['TOTAL_VND']
                                        +$this->map['deposit_indate']['BANK']['TOTAL_VND']),0); 
                                ?>
                            </strong>
                        </td>
                        <td align="right" style=" padding-right: 10px; "  >
                            <strong>
                                <?php echo number_format(($this->map['deposit_indate']['CASH']['TOTAL_USD']
                                        +$this->map['deposit_indate']['CREDIT_CARD']['TOTAL_USD']
                                        +$this->map['deposit_indate']['BANK']['TOTAL_USD']),0); 
                                ?>
                            </strong>
                        </td>
                        <td align="right" style=" padding-right: 30px; "  >
                            <strong>
                                <?php echo number_format(($this->map['deposit_indate']['CASH']['TOTAL']
                                        +$this->map['deposit_indate']['CREDIT_CARD']['TOTAL']
                                        +$this->map['deposit_indate']['BANK']['TOTAL']),0); 
                                ?>
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<table width="100%" >
    <tr>
        <td width="100%" style=" padding: 5px; " align ="center" >
            <div style=" border: 1px solid green ; ">
                <div style="height: 150px; " id="occupied_room_days_in_month" ></div>
            </div>
        </td>
    </tr>
</table>

<div width="100%" style=" padding: 5px; ">
    <div width="100%" style=" border: 1px solid green ; height: auto; padding: 5px; ">
        <table  style="width: 100%; margin: 0px; ">
            <tr width="30%" height="16" >
                <td align="left" width="20%" >[[.booked_room.]]</td>
                <td align="left" width="10%"><?php echo $this->map['static_room']['ROOM_BOOKED']; ?></td>
                <td align="left" width="20%" >[[.occupied_rooms.]]:  </td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_OCCUTIE']; ?> </td>
                <td align="left" width="20%" >[[.room_dayuse.]]:</td>
                <td align="left" width="10%" ><?php echo $this->map['static_room']['ROOM_DAYUSE']; ?></td>
            </tr>
            <tr width="40%" height="16">
                <td align="left" width="20%" >EI LI LO: </td>
                <td align="left" width="10%"  > <?php echo $this->map['static_room']['ROOM_EI_LO'];?></td>
                <td align="left" width="30%" >[[.cancel_rooms_today.]]: </td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_CANCEL']; ?></td>
                <td align="left" width="30%" >[[.Guest_in_house.]](a/c):</td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_GUEST']; ?>/<?php echo $this->map['static_room']['ROOM_GUEST_CHILD']; ?></td>
            </tr>
            <tr width="30%" height="16">
                <td align="left" width="20%" >[[.arrival_room_today.]]: </td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_ARRIVE']; ?></td>
                <td align="left" width="30%" >[[.repairing_rooms.]]: </td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_REPAIR']; ?></td>
                <td align="left" width="20%" >[[.extra_bed.]]: </td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_EXTRA_BED']; ?></td>
                
            </tr>
            <tr width="30%" height="16">
                <td align="left" width="20%" >[[.departure_room_today.]]: </td>
                <td align="left" width="10%"  ><?php echo $this->map['static_room']['ROOM_DEPART']; ?></td>
                <td align="left" width="20%" >[[.expect_room_capacity.]]:</td>
                <td align="left" width="10%"  ><?php echo number_format($this->map['static_room']['ROOM_CAPACITY'],2); ?>%</td>
                <td align="left" width="20%" >[[.average_price.]]: </td>
                <td align="left" width="10%"  ><?php echo number_format($this->map['static_room']['ROOM_PRICE'],0); ?></td>
            </tr>
        </table>
    </div>
</div>

<div width="100%" style=" padding: 5px; " class="description">
    <div width="100%" style=" border: 1px solid green ; height: auto; padding: 10px; ">
        <table  style="width: 100%; margin: 0px; ">
            <tr>
                <td align="center"><strong>MÔ TẢ CHI TIẾT</strong></td>
            </tr>
            <tr>
                <td ailgb="left">
                    <b>*Doanh thu thực trong ngày:</b><br />
                    -Tiền mặt: Tổng các khoản đã được thanh toán và có loại thanh toán là tiền mặt  trong phần thanh toán(VND hay USD phụ thuộc vào lựa chọn của người thanh toán)<br />
                    -Thẻ: Tổng các khoản đã được thanh toán và có loại thanh toán là thẻ trong phần thanh toán(VND hay USD phụ thuộc vào lựa chọn của người thanh toán)<br />
                    -Nợ: Tổng các khoản đã được thanh toán và có loại thanh toán là nợ trong phần thanh toán)<br />
                    -Chuyển khoản: Tổng các khoản đã được thanh toán và có loại thanh toán là chuyển khoản trong phần thanh toán(VND hay USD phụ thuộc vào lựa chọn của người thanh toán)<br />
                    -Refund: Tổng các khoản đã được trả lại trong phần trả lại (VND hay USD phụ thuộc vào lựa chọn của người trả lại)
                    <br />
                    <b>*Tiền đặt cọc trong ngày:</b><br />
                    -Tiền mặt: Tổng các loại đặt cọc có type = tiền mặt và có ngày đặt cọc bằng ngày xem báo cáo<br />
                    -Thẻ: Tổng các loại đặt cọc có type = thẻ và có ngày đặt cọc bằng ngày xem báo cáo<br />
                    -Chuyển khoản: Tổng các loại đặt cọc có type = chuyển khoản và có ngày đặt cọc bằng ngày xem báo cáo<br /><br />
                    <b>*Dự kiến doanh thu trong ngày:</b><br />
                    -Doanh thu phòng: Tổng doanh thu phòng của những phòng khách ở + phòng dayuse + phòng book(đã gán phòng và chưa gán phòng) + tổng doanh thu LI LO EI + tổng doanh thu của những dịch vụ có type thanh toán về phòng trong ngày xem báo cáo + tiền ăn sáng<br />
                    -Bộ phận buồng: Tổng doanh thu của bộ phận buồng trong ngày bao gồm minibar, giặt là và đền bù và có ngày tạo hóa đơn bằng ngày xem báo cáo<br />
                    -Dịch vụ khác: Tổng doanh thu của các dịch vụ có type khác room trong ngày và có ngày tạo hóa đơn bằng ngày xem báo cáo<br />
                    -Spa: Tổng doanh thu của Spa trong ngày(bao gồm cả trạng thái booked, checkin, checkout) và có ngày tạo bằng ngày xem báo cáo<br />
                    -Nhà hàng: Tổng doanh thu của nhà hàng trong ngày (bao gồm cả trạng thái booked, checkin, checkout) và có ngày tạo bằng ngày xem báo cáo(không có tiền ăn sáng)<br /><br />
                    <b>*Dự kiến doanh thu trong tháng:</b><br />
                    -Doanh thu phòng: Tổng doanh thu phòng của những phòng khách ở + phòng dayuse + phòng book(đã gán phòng và chưa gán phòng) + tổng doanh thu LI LO EI + tổng doanh thu của những dịch vụ có type thanh toán về phòng + tiền ăn sáng trong tháng xem báo cáo<br />
                    -Bộ phận buồng: Tổng doanh thu của bộ phận buồng trong tháng<br />
                    -Dịch vụ khác: Tổng doanh thu của các dịch vụ có type khác room trong tháng<br />
                    -Spa: Tổng doanh thu của Spa trong tháng<br />
                    -Nhà hàng: Tổng doanh thu của nhà hàng trong tháng (không có tiền ăn sáng)<br /><br />
                    <b>*Phần tổng hợp cuối báo cáo:</b><br />
                    -Phòng booked: Đếm số phòng booked đã gán phòng và chưa gán phòng có ngày đi khác ngày xem báo cáo<br />
                    -Phòng khách ở: Đếm những phòng đang chekin có ngày đi lớn hơn ngày hôm nay<br />
                    -Phòng dayuse: Đếm những phòng có ngày đến bằng ngày đi và bằng hôm nay có trạng trạng thái checkin hoặc đã checkout<br />
                    -LI LO EI: Đếm những khoản Li Lo Ei có ngày sử dụng bằng ngày hôm nay<br />
                    -Phòng hủy trong ngày: Đếm những phòng hủy có ngày hủy bằng ngày hôm nay<br />
                    -Khách ở phòng(a/c): Đếm số khách người lớn và trẻ em của các phòng khách ở trên phần đặt phòng<br />
                    -Phòng đến trong ngày: Đếm những phòng có ngày đến bằng ngày hôm nay bao gồm cả checkin,dayuse và booked(đã gán và chưa gán phòng)<br />
                    -Phòng sửa chữa:Đếm những phòng trên sơ đồ phòng đang thể hiện trạng thái phòng hỏng tại thời điểm xem báo cáo<br />
                    -Extra bed:Đếm số giường phụ của những phòng khách ở<br />
                    -Phòng đi trong ngày: Đếm những phòng có ngày đi bằng ngày hôm nay và đang có trạng thái in hoặc out<br />
                    -Công suất phòng:((số phòng khách ở+số phòng dayuse+số phòng booked+số phòng li lo ei)*100)/(tổng số phòng - phòng hỏng - phòng ảo)<br />
                    -Giá trung bình: Doanh thu của(phòng khách ở + phòng dayuse+ phòng booked + li lo ei +dịch vụ về phòng)/(số phòng khách ở+số phòng dayuse+số phòng booked+số phòng li lo ei)<br />
                    <br />
                    <b>*Biểu đồ Doanh thu trong ngày:</b><br />
                    - Là biểu đồ tròn thể hiện các số liệu của phần <b>Dự kiến doanh thu trong ngày</b>.<br />
                    - Mỗi màu sẽ tương ứng với 1 chỉ tiêu trên biểu đồ<br /><br />
                    <b>*Biểu đồ Doanh thu trong tháng:</b><br />
                    -Là biểu đồ cột trong đó mỗi cột sẽ lấy số liệu của tổng doanh thu dự kiến trong ngày tương ứng với từng ngày trong tháng<br /><br />
                    <b>*Biểu đồ dự kiến bán phòng:</b><br />
                    -Là biểu đồ cột trong đó mỗi cột là tổng số phòng khách ở và phòng booked qua ngày (không tính dayused)  tương ứng với từng ngày trong 1 tháng<br />
                </td>
            </tr>
        </table>                               
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date').datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
});
</script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        var in_date_check=jQuery("#date").val().split("/");
        var in_date_view = new Date(in_date_check[2],in_date_check[1],in_date_check[0]);
        var date_view = in_date_view.getMonth();
        var in_date_to_day = new Date();
        var date_now = in_date_to_day.getMonth()+1;
        var check_month = false;
        if(date_now==date_view)
        {
            check_month = true;
        }
        console.log(date_now);
        console.log(date_view);
        //revention in date
        var chart_indate;
        var data_date = [];
        var array_names = ['[[.room_revenue.]]'
                        ,'[[.housekeeping.]]'
                        ,'[[.extra_sevice.]]'
                        ,'[[.spa.]]'
                        ,'[[.shop.]]'
                        ,'[[.ticket.]]'
                        ,'[[.bar.]]'];
        var items = [[|revention_indate_js|]];
        var list_depart = [[|list_part_js|]];
        j = 0;
        k = 0;
        var tong = 0;
        
        var curency ='<?php echo(HOTEL_CURRENCY?HOTEL_CURRENCY:''); ?>';
        for(i in items)
        {
            if(list_depart[k+1]=='1')
            {
                data_date[j] = [];
                data_date[j][0] = array_names[k];
                if(curency == 'VND')
                {
                    data_date[j][1] = to_numeric(items[i]/1000000);
                }
                else
                {
                    data_date[j][1] = to_numeric(items[i]);
                }
                j++;
            }
            k++;
        }
        
        
        chart_indate = new Highcharts.Chart(
        {
            chart:{
                renderTo:'revention_indate',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                    text: "[[.revenue_in_date.]]"
            },
            colors: [
               '#890d43', 
               '#ff0000', 
               '#0099ff', 
               '#99ff00', 
               '#003322', 
               '#ffff72',
               '#ff9999'
            ],
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                            {
                                return this.point.name + '(' + roundNumber(this.percentage,1) + ' %) '+ number_format(this.y);
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '9px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_room_revenue.]]',
				data:data_date
			}]
        });
        
        
        //revention dates in month
        items = [[|revention_dates_in_month_js|]];
        data_date = [];
        var xAsis = [];
        j=0;
        for(i in items)
        {
            if(curency == 'VND')
            {
                data_date[j] = to_numeric(items[i]/1000000);
            }
            else
            {
                data_date[j] = to_numeric(items[i]);
            }
            xAsis[j]=j+1;
            j++;
        }
        
        var d = new Date();
        var to_day = d.getDate();
        var chart_dates = new Highcharts.Chart({
                chart: {
                    renderTo:'revention_dates_in_month',
                    type: 'column'
                },
                title: {
                    text: "[[.revenue_in_month.]]"
                },
                xAxis: {
                    categories: xAsis
                },
                yAxis: {
                    title: {
                        text: "Revenue"
                    }
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    },
                    column: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                //console.log(this.x);
                                if(this.x == to_day && check_month == true)
                                {
                                    return '<p style="color: red;font-weight: bold;" >'+number_format(this.y)+'</p>';
                                }
                                else
                                    return number_format(this.y);
            				}
                        }
                    }
                },
                tooltip:{
                    formatter: function() {
                        if(this.y > 0)
                            return number_format(this.y);
                        else
                            enable: false
    				}
                },
                legend:{
                    enabled:false
                },
                series: [{
                    data: data_date        
                }]
            });
            
        //room occupied dates in month
        items = [[|occupied_room_dates_in_month_js|]];
        data_occ = [];
        var xAsis = [];
        j=0;
        for(i in items)
        {
            data_occ[j] = to_numeric(items[i]);
            xAsis[j]=j+1;
            j++;
        }
        
        var chart_dates = new Highcharts.Chart({
                chart: {
                    renderTo:'occupied_room_days_in_month',
                    type: 'column'
                },
                title: {
                    text: "[[.expected_room_soid.]]"
                },
                xAxis: {
                    categories: xAsis
                },
                yAxis: {
                    title: {
                        text: "Room"
                    }
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    },
                    column: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                if(this.x == to_day && check_month == true)
                                    return '<p style="color: red;font-weight: bold;" >'+this.y+'</p>';
                                else
                                    return this.y;
            				}
                        }
                    }
                },
                tooltip:{
                    formatter: function() {
                        if(this.y > 0)
                            return this.y;
                        else
                            enable: false
    				}
                },
                legend:{
                    enabled:false
                },
                series: [{
                    data: data_occ        
                }]
            });
        
    });
    
    
</script>
