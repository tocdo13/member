<link rel="stylesheet" href="skins/default/report.css">
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<table cellpadding="5" cellspacing="0" width="100%" style="background-color: #FFFFFF;" class="table-bound">
    <tr>
        <td colspan="4">
<!--HEADER-->
            <table cellspacing="0" width="100%">
                <tr valign="top">
                    <td align="left" width="65%">
            			<strong><?php echo [[=hotel_name=]];?></strong><br />
            			<?php echo [[=hotel_address=]];?>
                    </td>
                    <td align="right" nowrap width="35%">
            			<strong>[[.template_code.]]</strong><br />
            			<i>[[.promulgation.]]</i>
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>	
            </table>
            <table cellpadding="10" cellspacing="0" width="100%">
                <tr>
                	<td align="center">
                		<b>
                            <span style="font-size: 25px;" >[[.sale_synthesis_report.]]</span>
                        </b>
                    </td>
                </tr>
            </table>
<!--/HEADER-->
        </td>
    </tr>

<!--SEARCH-->
    <tr>
        <td align="center" colspan="4">
            <form name="SearchForm" method="post">
                <?php if(User::can_admin(false,ANY_CATEGORY)){?>
                [[.Hotel.]] : <select name="portal_id" id="portal_id" ></select>
                <?php }?>
                [[.date.]] : <input name="date" type="text" id="date" style="width:67px" />
                 <input type="submit" value="[[.xem.]]" />
            </form>
        </td>
    </tr>
<!--/SEARCH-->

<!--REPORT-->
    <tr>
        <td colspan="4">
            <table cellspacing="0" width="100%" border='1'>
                <tr valign="middle" bgcolor="#EFEFEF">
                    <th rowspan="2">Chỉ tiêu doanh thu</th>
                    <th colspan="4">Trong ngày</th>
                    <th colspan="4">Lũy kế tháng</th>
                    <th colspan="4">Lũy kế năm</th>
                </tr>
                <tr valign="middle" bgcolor="#EFEFEF">
                    <th>SUB</th>
                    <th>SRC</th>
                    <th>TAX</th>
                    <th>TOTAL</th>
                    <th>SUB</th>
                    <th>SRC</th>
                    <th>TAX</th>
                    <th>TOTAL</th>
                    <th>SUB</th>
                    <th>SRC</th>
                    <th>TAX</th>
                    <th>TOTAL</th>
                </tr>
                <tr style="background-color: #EFEFEF;">
                    <th align="left">1. LƯU TRÚ</th>
                    <?php foreach($this->map['reven_room_total'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.room_charge.]]</td>
                    <?php foreach($this->map['reven_room_room'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.minibar.]]</td>
                    <?php foreach($this->map['reven_room_minibar'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.laundry.]]</td>
                    <?php foreach($this->map['reven_room_laundry'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.equipment.]]</td>
                    <?php foreach($this->map['reven_room_equip'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.telephone.]]</td>
                    <?php foreach($this->map['reven_room_telephone'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.late_checkout.]]</td>
                    <?php foreach($this->map['reven_room_lo'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.early_checkin.]]</td>
                    <?php foreach($this->map['reven_room_ei'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.extra_bed.]]</td>
                    <?php foreach($this->map['reven_room_extra_bed'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.duadon.]]</td>
                    <?php foreach($this->map['reven_room_duadon'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.tour.]]</td>
                    <?php foreach($this->map['reven_room_tour'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">-[[.other.]]</td>
                    <?php foreach($this->map['reven_room_other'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr style="background-color: #EFEFEF;">
                    <th align="left">2. NHÀ HÀNG</th>
                    <?php foreach($this->map['reven_bar_total'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <?php if(BREAKFAST_SPLIT_PRICE){ ?>
                 <tr>
                    <th align="left">-[[.breakfast.]]</th>
                    <?php foreach($this->map['reven_breakfast'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <?php } ?>
                <tr>
                    <th align="left">-[[.income_by_reception.]]</th>
                    <?php foreach($this->map['reven_room_bar'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <?php 
                    foreach($this->map['bars'] as $bar_id => $bar){
                ?>
                <tr>
                    <th align="left">-<?php echo $bar['name']; ?></th>
                    <?php foreach($bar['total'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">+[[.food.]]</td>
                    <?php foreach($bar['food'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">+[[.drink.]]</td>
                    <?php foreach($bar['drink'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">+[[.other.]]</td>
                    <?php foreach($bar['other'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <?php } ?>
                <tr style="background-color: #EFEFEF;">
                    <th align="left">3. Massage-SPA</th>
                    <?php foreach($this->map['reven_spa_total']['total'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">+[[.income_by_reception.]]</td>
                    <?php foreach($this->map['reven_room_spa'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td align="left">+[[.not_room.]]</td>
                    <?php foreach($this->map['reven_spa_total']['not_room'] as $key => $value){ ?>
                    <td align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></td>
                    <?php } ?>
                </tr>
                <!--start:KID them phan ban hang-->
                <tr style="background-color: #EFEFEF;">
                    <th align="left">4. BÁN HÀNG</th>
                    <?php foreach($this->map['reven_vending_total']['not_room'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>
                <!--end:KID them phan ban hang-->
                <tr style="background-color: #EFEFEF;">
                    <th align="center">TOTAL</th>
                    <?php foreach($this->map['total'] as $key => $value){ ?>
                    <th align="right" style="padding-right: 5px;"><?php echo $value?System::display_number(round($value)):""; ?></th>
                    <?php } ?>
                </tr>   
            </table>
        </td>
    </tr>
<!--/REPORT-->

    <tr>
        <td colspan="<?php echo (count([[=category_childfoods_invi=]])+1+(count([[=category_childfoods_hidd=]])?1:0))*2 + 10 + count([[=categories_invi=]])+1+(count([[=categories_hidd=]])?1:0); ?>">
            <!--FOOTER-->
            <table  cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <td>
                  		<table width="100%" style="font-family:'Times New Roman', Times, serif">
                    		<tr>
                    			<td></td>
                    			<td></td>
                    			<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
                    		</tr>
                    		<tr valign="top">
                    			<!--td width="33%" align="center">[[.cashier.]]</td-->
                    			<td width="33%" align="center">K&#7871; to&aacute;n </td>
                    			<td width="33%" align="center">[[.seller.]]</td>
                    			<td width="33%" align="center">[[.creator.]]</td>
                    		</tr>
                  		</table>
                  		<p>&nbsp;</p>
                    </td>
                </tr>
            </table>
            <!--<DIV style="page-break-before:always;page-break-after:always;"></DIV>-->
            <!--/FOOTER-->
        </td>
    </tr>
</table>

<script>
jQuery("#date").datepicker();
</script>