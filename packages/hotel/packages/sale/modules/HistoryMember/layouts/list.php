<style>
    a{
        text-decoration: none;
        color: #00b2f9;
    }
    a:hover{
        text-decoration: none;
        color: #00b2f9;
    }
    a:active{
        color: #00b2f9;
    }
</style>
<form name="HistoryMemberForm" method="post">
    <div id="search" style="width: 960px; margin: 0px auto;">
       <fieldset>
            <legend>[[.search.]]</legend>
            <table cellSpacing="0" cellpadding="5" style="width: 100%;">
                <tr>
                    <th style="width: 20%;">[[.invoice_code.]]:</th>
                    <th style="width: 20%;">[[.member_code.]]:</th>
                    <th style="width: 20%;">[[.member_name.]]:</th>
                    <th style="width: 20%;">[[.from_date.]]:</th>
                    <th style="width: 20%;">[[.to_date.]]:</th>
                </tr>
                <tr>
                    <td><input name="invoice_code" type="text" id="invoice_code" style="width: 100%; height: 25px;" /></td>
                    <td><input name="member_code" type="text" id="member_code" style="width: 100%; height: 25px;" /></td>
                    <td><input name="member_name" type="text" id="member_name" style="width: 100%; height: 25px;" /></td>
                    <td><input name="from_date" type="text" id="from_date" style="height: 25px; width: 120px;" /> <input name="from_time" type="text" id="from_time" style="height: 25px; width: 30px;" /></td>
                    <td><input name="to_date" type="text" id="to_date" style=" height: 25px; width: 120px;" /> <input name="to_time" type="text" id="to_time" style="height: 25px; width: 30px;" /></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;"><input name="do_search" type="submit" value="  [[.search.]]  " /></td>
                </tr>
            </table>
       </fieldset> 
    </div>
    <div id="print" style="width: 100%;">
        <div style="width: 100%; text-align: center; text-transform: uppercase;"><h1>[[.history_member.]]</h1></div>
        <div style="width: 100%; margin: 10px auto; float: left;">[[|paging|]]</div>
        <table cellSpacing="0" cellpadding="5" border="1" style="width: 98%; margin: 1%;">
            <tr style="background: #cccccc; text-transform: uppercase;">
                <th style="text-align: center; width: 20px; height: 35px;">[[.stt.]]</th>
                <th style="text-align: center;">[[.payment_type_id.]]</th>
                <th style="text-align: center;">[[.price.]]</th>
                <th style="text-align: center;">[[.change_price.]]</th>
                <th style="text-align: center;">[[.point.]]</th>
                <th style="text-align: center;">[[.detail_point.]]</th>
            </tr>
            <!--LIST:list_items-->
                <tr>
                    <td colspan="6" style="padding-left: 20px; font-size: 13px; font-weight: bold; text-align: left; background: #eeeeee; height: 30px;">[[.member_code.]]: [[|list_items.member_code|]] - [[.traveller_name.]]: [[|list_items.full_name|]] - [[.time.]]: [[|list_items.create_time|]] - [[.user_id.]]: [[|list_items.user_id|]]
                    <br />
                    [[.type.]]: [[|list_items.type|]] - <?php if([[=list_items.type=]]=='RESERVATION'){ ?>  recode: <?php }else{ ?> [[.recode.]] <?php } ?> [ <a href="[[|list_items.link_recode|]]" target="_blank">[[|list_items.bill_id|]]</a> ] - <?php if([[=list_items.type=]]=='RESERVATION'){ ?>  Folio/Group Folio: <?php }else{ ?> [[.view.]]: <?php } ?> [ <a href="[[|list_items.link_invoice|]]" target="_blank"><?php if([[=list_items.type=]]=='RESERVATION'){ ?>  [[|list_items.folio_id|]] <?php }else{ ?> [[.invoice.]] <?php } ?> </a> ]
                    </td>
                </tr>
                <?php $stt=0; ?>
                <!--LIST:list_items.child-->
                    <?php $stt++; ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $stt; ?></td>
                        <td>[[|list_items.child.payment_type_id|]]</td>
                        <td>[[|list_items.child.price|]]</td>
                        <td>[[|list_items.child.change_price|]]</td>
                        <td>[[|list_items.child.point|]]</td>
                        <td>[[|list_items.child.detail|]]</td>
                    </tr>
                <!--/LIST:list_items.child-->
            <!--/LIST:list_items-->
        </table>
        <div style="width: 100%; margin: 10px auto; float: left;">[[|paging|]]</div>
    </div>
</form>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>