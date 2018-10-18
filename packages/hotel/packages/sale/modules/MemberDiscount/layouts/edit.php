<form name="EditMemberDiscountForm" method="POST">
    <table cellpadding="15" cellspacing="15">
        <tr style="border-bottom: 1px dashed #CCCCCC;">
            <td><h1><?php if(Url::get('cmd')=='add'){ echo Portal::language('add_member_discount'); }else{ echo Portal::language('edit_member_discount'); } ?></h1></td>
            <td style="text-align: center;">
                <?php if((User::can_add(false,ANY_CATEGORY) AND Url::get('cmd')=='add') OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY))){ ?> 
                    <input name="save_stay" type="submit" id="save_stay" value="[[.save_stay.]]" style="padding: 10px;" />
                    <input name="save" type="submit" id="save" value="[[.save.]]"  style="padding: 10px;" />
                    <?php } ?>
                    <input name="back" type="button" id="back" value="[[.back.]]" style="padding: 10px;" onclick="window.location.href='?page=member_discount';" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellpadding="15" cellspacing="15">
                    <tr>
                        <td><label style="font-weight: bold;">[[.code.]]:</label></td>
                        <td><input name="code" type="text" value="[[|code|]]" id="code" readonly="" style="background: #EEEEEE; padding: 10px;" /></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">[[.pin_servie.]]:</label></td>
                        <td><select name="access_pin_service" id="access_pin_service" style=" padding: 10px;"></select></td>
                        <script>
                            jQuery("#access_pin_service").val('[[|access_pin_service_code|]]');
                        </script>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">[[.card_type.]]:</label></td>
                        <td><select name="is_parent" id="is_parent" style=" padding: 10px;"></select></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">[[.start_date.]]:</label></td>
                        <td><input name="start_date" type="text" id="start_date" style=" padding: 10px;" /><span>(Nếu bỏ trống được hiểu bắt đầu từ ngày nhỏ nhất)</span></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">[[.end_date.]]:</label></td>
                        <td><input name="end_date" type="text" id="end_date" style=" padding: 10px;" /><span>(Nếu bỏ trống được hiểu đến ngày lớn nhất)</span></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">[[.num_people_attach.]]:</label></td>
                        <td><select name="operator" id="operator" style=" padding: 10px;"></select><input name="num_people" type="text" value="[[|num_people|]]" id="num_people" style=" padding: 10px;" class="input_number" /></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">% [[.percent.]]:</label></td>
                        <td><input name="percent" type="text" id="percent" style=" padding: 10px;" /><span>%</span></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">[[.title.]]:</label></td>
                        <td><input name="title" type="text" value="[[|title|]]" id="title" style=" padding: 10px;" /></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><label style="font-weight: bold;">[[.description.]]:</label></td>
                        <td><textarea name="description" id="description" style=" padding: 10px; width: 650px; height: 300px;">[[|description|]]</textarea></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<script>
jQuery('#start_date').datepicker();
jQuery('#end_date').datepicker();
</script>
