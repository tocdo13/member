<style>
    a:hover{
        text-decoration: none;
    }
</style>
<form name="ListPurchasesProposedForm" method="post">
    <table style="width: 98%; margin: 0px auto;">
        <tr>
            <td style="width: 45px;">
                <img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\iconarchive.png" style="width: 40px; height: auto; float: left; border-radius: 50%; border: 3px solid #09435b;" />
                
            </td>
            <td>
                <h3 style="float: left; line-height: 30px; font-size: 25px; text-transform: uppercase;">[[.list_purchases_detail.]]</h3>
            </td>
        </tr>
    </table>
    <fieldset style="width: 90%; margin: 0px auto; border: 1px solid #09435b;">
        <legend>[[.option_search.]]</legend>
        <table  cellpadding="5" cellspacing="5" width="90%">
            <tr>
                <td>[[.from_date.]]: <input name="from_date" type="text" id="from_date" style="border: 1px solid #09435b;" /></td>
                <td>[[.to_date.]]: <input name="to_date" type="text" id="to_date" style="border: 1px solid #09435b;" /></td>
                <td>[[.creater.]]: <select name="creater" id="creater" style="border: 1px solid #09435b;"></select></td>
                <td>[[.status.]]: <select name="status" id="status" style="border: 1px solid #09435b;"></select></td>
                <td>[[.confirm_user.]]: <select name="confirm_user" id="confirm_user" style="border: 1px solid #09435b;"></select></td>
                <td><input type="submit" name="do_search" value="[[.search.]]" style="color: #ffffff; background: #09435b; padding: 10px; font-weight: bold; border: none;" /></td>
            </tr>
        </table>
    </fieldset>
    <table cellpadding="5" cellspacing="5" width="90%" border="1" bordercolor="#646464" style="margin: 20px auto;">
        <tr style="background: #09435b; color: #ffffff; font-weight: bold;">
            <td style="text-align: center; color: #ffffff;">[[.stt.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.description.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.creater.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.create_time.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.status.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.confirm_user.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.confirm_time.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.adjustment.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.adjustment_time.]]</td>
            <td style="text-align: center; color: #ffffff;">[[.adjusted.]]</td>
        </tr>
        <!--LIST:items-->
        <tr style="text-align: center; height: 30px;">
            <td>[[|items.stt|]]</td>
            <td style="text-align: left;">[[|items.description|]]</td>
            <td>[[|items.creater|]]</td>
            <td>[[|items.create_time|]]</td>
            <td><?php if([[=items.status=]]=='CONFIRMING'){ ?> [[.confirming.]] <?php }elseif([[=items.status=]]=='CONFIRM'){ ?> [[.confirm_1.]] <?php }elseif([[=items.status=]]=='ADJUSTED'){ ?> [[.adjusted.]] <?php } ?></td>
            <td>[[|items.confirm_user|]]</td>
            <td>[[|items.confirm_time|]]</td>
            <td>[[|items.adjustment|]]</td>
            <td>[[|items.adjustment_time|]]</td>
            <td style="text-align: center;"><?php if((User::can_edit(false,ANY_CATEGORY) AND [[=items.status=]]=='CONFIRMING') OR (User::can_admin(false,ANY_CATEGORY))){ ?> <a href="?page=purchases_detail&cmd=edit&id=[[|items.id|]]" style="padding: 5px; background:; color: #ffffff;"><img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\file_setting.png" style="width: 20px; height: auto;" /></a> <?php } ?></td>
        </tr>
        <!--/LIST:items-->
    </table>
</form>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>