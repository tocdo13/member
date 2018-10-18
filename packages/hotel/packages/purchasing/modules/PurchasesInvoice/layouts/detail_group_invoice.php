<style>
    table.tital tr td{
        color: #71cce1;
        font-size: 15px;
        font-weight: bold;
    }
    table.tital tr td span{
        color: #ffffff;
        font-size: 15px;
        font-weight: normal;
        text-transform: uppercase;
    }
    table#repot tr td{
        color: #71cce1;
        font-weight: bold;
    }
    table#repot tr td span{
        color: #ffffff;
        font-weight: normal;
        text-transform: uppercase;
    }
    a
    {
        text-decoration: none;
        color: #71cce1;
        font-weight: bold;
    }
    a:hover
    {
        text-decoration: none;
        color: #71cce1;
        font-weight: bold;
    }
    a:active
    {
        text-decoration: none;
        color: #71cce1;
        font-weight: bold;
    }
    a:visited
    {
        text-decoration: none;
        color: #71cce1;
        font-weight: bold;
    }
    img.desaturate{
        filter : grayscale(100%);
        -moz-filter: grayscale(100%);
        -webkit-filter:grayscale(100%);
        -ms-filter:grayscale(100%);
        -o-filter:grayscale(100%);
        transition: all 0.5s ease-out;
    }
    #report_order:hover img.desaturate{
        filter : grayscale(0%);
        -moz-filter: grayscale(0%);
        -webkit-filter:grayscale(0%);
        -ms-filter:grayscale(0%);
        -o-filter:grayscale(0%);
    }
</style>
<fieldset id="report_order" style="text-align: center; width: 800px; background: #ffffff; margin: 40px auto; border: 3px solid #2ab2be; background: #09435b; box-shadow: 0px 0px 5px #09435b;">
    <legend style="width: 150px; height: 150px; overflow: hidden; text-align: center; border-radius: 50%; border: 3px solid #2ab2be; box-shadow: 0px 0px 5px #09435b;"><img class="desaturate" src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\shopping.png" style="width: 150px; height: auto; margin: 0px auto; overflow: hidden; border-radius: 50%;" /></legend>
    <div style="width: 100%; margin: 0px auto; height: 35px; text-align: center; border-bottom: 1px dashed #FFFFFF;"><h3 style="color: #ffffff; text-transform: uppercase; font-size: 30px; text-shadow: 0px 5px 5px #171717;">[[.purchases_order_group_from.]]</h3></div>
    <table class="tital" cellpadding="5" cellspacing="5" style="width: 95%; margin: 0px auto; text-align: left; color: #ffffff; font-size: 14px;">
        <tr>
            <td colspan="2"><span>[[.no_1.]] :</span> [[|id|]]</td>
        </tr>
        <tr>
            <td><span>[[.creater.]] : </span> [[|creater|]]</td>
            <td><span>[[.create_time.]]: </span> [[|create_time|]]</td>
        </tr>
        <tr>
            <td><span>[[.confirm_user.]] :</span> [[|confirm_user_name|]]</td>
            <td><span>[[.confirm_time.]] :</span> [[|confirm_time|]]</td>
        </tr>
        <tr>
            <td colspan="2"><span style="border-bottom: 1px solid #ffffff;">[[.list_detail.]]</span></td>
        </tr>
    </table>
    <table id="repot" cellpadding="5" cellspacing="5" border="3" bordercolor="#f04d4e" style="width: 95%; margin: 10px auto; text-align: left; color: #ffffff; font-size: 12px;">
        <tr style="text-align: center;">
            <td><span>[[.stt.]]</span></td>
            <td><span>[[.proposed_code.]]</span></td>
            <td><span>[[.product_code.]]</span></td>
            <td><span>[[.product_name.]]</span></td>
            <td><span>[[.unit.]]</span></td>
            <td><span>[[.category.]]</span></td>
            <td><span>[[.supplier.]]</span></td>
            <td><span>[[.quantity.]]</span></td>
            <td><span>[[.price.]]</span></td>
            <td><span>[[.total.]]</span></td>
            <td><span>[[.note.]]</span></td>
        </tr><?php $stt=1; ?>
        <!--LIST:child-->
        <tr style="text-align: center;">
            <td><?php echo $stt; ?></td>
            <td colspan="6"></td>
            <td>[[|child.quantity|]]</td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><?php echo System::display_number([[=child.total=]]); ?></td>
            <td></td>
        </tr>
        <!--LIST:child.child-->
        <tr style="text-align: center;">
            <td style="background: #f04d4e;"></td>
            <td><a href="?page=purchases_detail&cmd=edit&id=[[|child.purchases_id|]]">ĐX_[[|child.child.purchases_id|]]</a></td>
            <td>[[|child.child.product_code|]]</td>
            <td>[[|child.child.product_name|]]</td>
            
            <td>[[|child.child.unit_name|]]</td>
            <td>[[|child.child.product_category_name|]]</td>
            <td>[[|child.child.supplier_name|]]</td>
            <td>[[|child.child.quantity|]]</td>
            <td style="text-align: right;"><?php echo System::display_number([[=child.child.price=]]); ?></td>
            <td style="text-align: right;"><?php echo System::display_number([[=child.child.total=]]); ?></td>
            <td>[[|child.child.note|]]</td>
        </tr>
        <!--/LIST:child.child-->
        <!--/LIST:child-->
    </table>
    <table class="tital" cellpadding="5" cellspacing="5" style="width: 95%; margin: 0px auto; text-align: left; color: #ffffff; font-size: 14px;">
        <tr>
            <td style="text-align: right;">
                <span>[[.total_quantity.]] : </span> [[|quantity|]]
                <span>[[.total_amount.]] : </span> <?php echo System::display_number([[=total=]]) ?>
            </td>
        </tr>
    </table>
</fieldset>