<style>
    @media print {
      #searchform { display: none; }
    }
</style>
<div id="export">
    <table style="width: 100%;">
        <tr>
            <th>
                [[.hotel.]]: <?php echo HOTEL_NAME;?><br />
                [[.address.]]: <?php echo HOTEL_ADDRESS;?>
            </th>
            <th style="text-align: right;">
                [[.template_code.]] <br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?> <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">
                <h3 style="text-transform: uppercase;">[[.foc_restaurant.]]</h3>
                <p>[[.start_date.]]&nbsp;[[|start_date|]] - [[.end_date.]]&nbsp;[[|end_date|]]</p>
            </th>
        </tr>
    </table>
    <form name="SummaryOfRoomsTurnsForm" method="POST" id="searchform">
        <table style="margin: 0px auto;">
            <tr>
                <td><fieldset>
                    <legend>[[.bar_list.]]</legend>
                    <!--LIST:bar_list-->
                    <input  name="bar_list[[[|bar_list.id|]]]['id']" type="checkbox" value="[[|bar_list.id|]]" <!--IF:cond(isset([[=bar_list.checked=]]))-->checked="checked"<!--/IF:cond-->  />
                    [[|bar_list.name|]]
                    <!--/LIST:bar_list-->
                </fieldset></td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    [[.portal.]]
                    <select name="portal_id" id="portal_id" class="w3-border" style="padding: 5px; width: inherit;"></select>
                    [[.start_date.]]
                    <input name="start_date" type="text" id="start_date" class="w3-border" readonly="" style="padding: 5px; width: 100px; text-align: center;" />
                    [[.end_date.]]
                    <input name="end_date" type="text" id="end_date" class="w3-border" readonly="" style="padding: 5px; width: 100px; text-align: center;" />
                    <input type="submit" name="do_search" value="[[.search.]]" style="padding: 5px;" />
                    <input type="button" id="export_repost" value="[[.export_excel.]]" style="padding: 5px;" />
                </td>
            </tr>
        </table>
    </form>
    
    <table id="data-report" style="background: #FFF; margin: 0px auto;" cellpadding="10" cellspacing="0" border="1" bordercolor="#CCCCCC">
        <tr>
            <th>[[.stt.]]</th>
            <th>[[.code.]]</th>
            <th>[[.table_name.]]</th>
            <th>[[.date.]]</th>
            <th>[[.customer.]]</th>
            <th>[[.guest.]]</th>
            <th>[[.product_code.]]</th>
            <th>[[.product_name.]]</th>
            <th>[[.quantity.]]</th>
            <th>[[.total_bill.]]</th>
            <th>[[.total_foc.]]</th>
            <th>[[.note_foc.]]</th>
            <th>[[.cashier.]]</th>
        </tr>
        <?php $stt = 1; ?>
        <!--LIST:items-->
        <tr>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->"><?php echo $stt++; ?></td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->"><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail<?php echo '&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1'; ?>&id=[[|items.id|]]&bar_id=[[|items.bar_id|]]">[[|items.code|]]</a></td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->">[[|items.table_name|]]</td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->">[[|items.time|]]</td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->">[[|items.customer_name|]]</td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->">[[|items.receiver_name|]]</td>
            <!--IF:row([[=items.count_child=]]==0)-->
                <td></td>
                <td></td>
                <td></td>
            <!--ELSE-->
                <?php $child = ''; ?>
                <!--LIST:items.child-->
                <?php $child = [[=items.child.id=]]; ?>
                <td>[[|items.child.product_id|]]</td>
                <td>[[|items.child.name|]]</td>
                <td>[[|items.child.quantity|]]</td>
                <?php break; ?>
                <!--/LIST:items.child-->
            <!--/IF:row-->
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->" style="text-align: right;"><?php echo System::display_number([[=items.total=]]); ?></td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->" style="text-align: right;"><?php echo System::display_number([[=items.total_foc=]]); ?></td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->">[[|items.note|]]</td>
            <td rowspan="<!--IF:row([[=items.count_child=]]==0)-->1<!--ELSE-->[[|items.count_child|]]<!--/IF:row-->">[[|items.reception_name|]]</td>
        </tr>
        <!--IF:row([[=items.count_child=]]!=0)-->
            <!--LIST:items.child-->
            <?php if($child != [[=items.child.id=]]){ ?>
            <tr>
                <td>[[|items.child.product_id|]]</td>
                <td>[[|items.child.name|]]</td>
                <td>[[|items.child.quantity|]]</td>
            </tr>
            <?php } ?>
            <!--/LIST:items.child-->
        <!--/IF:row-->
        <!--/LIST:items-->
        <tr>
            <th colspan="8">[[.total.]]</th>
            <th>[[|total_quantity|]]</th>
            <th style="text-align: right;"><?php echo System::display_number([[=total_bill=]]); ?></th>
            <th style="text-align: right;"><?php echo System::display_number([[=total_foc=]]); ?></th>
            <th colspan="2"></th>
        </tr>
    </table>
</div>
<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    jQuery('#export_repost').click(function(){
        jQuery("#searchform").remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
        });
        location.reload();
    });
</script>