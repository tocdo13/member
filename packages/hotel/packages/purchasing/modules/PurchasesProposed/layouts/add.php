<style>
    table tr td{
        padding: 5px;
    }
</style>
<form name="AddPurchasesProposedForm" method="post">
    <fieldset style="width: 720px; margin: 20px auto; box-shadow: 0px 0px 5px #555555; border: 3px solid #2ab2be;">
        <legend style="width: 40px; background: #ffffff; height: 40px; overflow: hidden; text-align: center; border-radius: 50%; border: 3px solid #2ab2be; box-shadow: 0px 0px 5px #09435b;"><img src="packages\hotel\packages\purchasing\skins\default\images\purchases_proposed\iconarchive.png" style="width: 40px; height: auto;" /></legend>
        <table cellpadding="5" cellspacing="5" style="width: 98%; margin: 1% auto;">
            <tr>
                <td colspan="4" style="text-align: center;"><h3 style="font-size: 20px; text-transform: uppercase;">[[.purchases_proposed.]]</h3></td>
            </tr>
            <tr>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.date.]]:</td>
                <td><input name="create_date" type="text" id="create_date" readonly="" style="border: 1px solid #09435b;" /></td>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.department.]]:</td>
                <td><input name="department" type="text" id="department" readonly="" style="border: 1px solid #09435b;" /></td>
            </tr>
            <tr>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.creater.]]:</td>
                <td><input name="creater" type="text" id="creater" readonly="" style="border: 1px solid #09435b;" /></td>
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.confirm_user.]]:</td>
                <td><input name="confirm_user" type="text" id="confirm_user" readonly="" style="border: 1px solid #09435b;" /></td>
            </tr>
            <tr style="background: #9ddced;">
                <td style="width: 150px; font-size: 13px; font-weight: bold;">[[.status.]]:</td>
                <td><input name="status" id="status" type="text" value="[[.confirming.]]" style="border: none; color: red; font-weight: bold; background: #9ddced; text-transform: uppercase;" readonly="" /></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" style="font-size: 13px; font-weight: bold;">[[.content_pusrchases.]]:</td>
            </tr>
            <tr>
                <td colspan="4">
                    <textarea name="description" id="description" style="width: 700px; height: 150px; border: 1px solid #09435b;"></textarea>
                </td>
            </tr>
            
            <tr>
                <td colspan="4" style="text-align: center;">
                    <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
                    <input type="submit" name="save" id="save" style=" margin: 10px; padding: 15px; border: none; background: #facc39; color: #000000; font-weight: bold;" value="[[.save.]]" onclick="return fun_text();" />
                    <input type="submit" name="save_stay" id="save_stay" style=" margin: 10px; padding: 15px; border: none; background: #2ab2be; color: #000000; font-weight: bold;" value="[[.save_stay_add.]]" onclick="return fun_text();" />
                    <?php } ?>
                    <input type="submit" name="back" id="back" style=" margin: 10px; padding: 15px; border: none; background: #f04d4e; color: #000000; font-weight: bold;" value="[[.back.]]" onclick="return fun_text();" />
                </td>
            </tr>
        </table>
    </fieldset>
</form>
<script>
    function fun_text()
    {
        if(jQuery("#description").val()=='')
        {
            alert('Bạn chưa nhập nội dung đề xuất!');
            return false;
        }
        else
        {
            return true;
        }
    }
</script>