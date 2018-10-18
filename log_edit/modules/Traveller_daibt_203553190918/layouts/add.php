<form name="AddTravellerForm" method="post">
    <div style="width: 100%; margin: 10px auto; clear: both; height: 50px; border-bottom: 1px solid #171717;">
        <h1 style="float: left; margin-left: 10px;">[[.add_traveller.]]</h1>
        <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
        <input name="save" type="button" id="save" value="[[.save.]]" onclick="check_confix();" style="padding: 10px; float: right; margin-right: 10px; background: #00b2f9; border: none; border: 2px solid #b8e9fd;" />
        <input name="save" type="button" id="save" value="[[.save_stay_1.]]" onclick="fun_check_edit();check_confix();" style="padding: 10px; float: right; margin-right: 10px; background: #00b2f9; border: none; border: 2px solid #b8e9fd;" />
        <?php } ?>
        <input name="check_edit" id="check_edit" type="checkbox" style="display: none;" />
        <?php if(User::can_view(false,ANY_CATEGORY)){ ?><a href="?page=traveller&cmd=list_member" style="float: right;"><input name="back" type="button" id="back" value="[[.back.]]" style="padding: 10px; float: right; margin-right: 10px; background: #00b2f9; border: none; border: 2px solid #b8e9fd;" /></a><?php } ?>
    </div>
    <div style="width: 100%; margin: 20px auto; clear: both;">
        <table style="width: 70%; margin: 20px auto;">
            <tr>
                <td style="text-align: right;">[[.first_name.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="first_name" type="text" id="first_name" style="width: 200px; height: 25px;" /></td>
                <td style="text-align: right;">[[.last_name.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="last_name" type="text" id="last_name" style="width: 200px; height: 25px;" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.gender.]]</td>
                <td style="text-align: center;">:</td>
                <td><select name="gender" id="gender" style="width: 205px; height: 25px;" ></select></td>
                <td style="text-align: right;">[[.birth_date.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="birth_date" type="text" id="birth_date" style="width: 200px; height: 25px;" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.country.]]</td>
                <td style="text-align: center;">:</td>
                <td><select name="nationality_id" id="nationality_id" style="width: 205px; height: 25px;" ></select></td>
                <td style="text-align: right;">[[.passport.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="passport" type="text" id="passport" style="width: 200px; height: 25px;" autocomplete="off" onchange="get_passport();" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.address.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="address" type="text" id="address" style="width: 200px; height: 25px;" /></td>
                <td style="text-align: right;">[[.email.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="email" type="text" id="email" style="width: 200px; height: 25px;" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.phone.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="phone" type="text" id="phone" style="width: 200px; height: 25px;" /></td>
                <td style="text-align: right;">[[.fax.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="fax" type="text" id="fax" style="width: 200px; height: 25px;" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.note.]]</td>
                <td style="text-align: center;">:</td>
                <td><textarea name="note" id="note" style="width: 200px;"></textarea></td>
                <td style="text-align: right;">[[.is_vn.]]</td>
                <td style="text-align: center;">:</td>
                <td><select name="is_vn" id="is_vn" style="width: 205px; height: 25px;"></select></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.traveller_level.]] <span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><select name="traveller_level_id" id="traveller_level_id" style="width: 205px; height: 25px;"></select></td>
                <td style="text-align: right;">[[.province.]]</td>
                <td style="text-align: center;">:</td>
                <td><select name="province_id" id="province_id" style="width: 205px; height: 25px;"></select></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.create_member_code.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="create_member_code" id="create_member_code" type="checkbox" onchange="fun_check_email();" />[[.register_member.]]</td>
                <td style="text-align: right;"></td>
                <td style="text-align: center;"></td>
                <td></td>
            </tr>
        </table>
    </div>
</form>
<script>
    jQuery("#birth_date").datepicker();
    var count_click = 0;
    function fun_check_email(){
        var email = jQuery("#email").val();
        if(document.getElementById("create_member_code").checked==true){
            if(email==''){
                alert('bạn chưa nhập mail! Nhập địa chỉ mail để sử dụng tính năng này.');
                document.getElementById("create_member_code").checked=false;
            }
        }
    }
    function get_passport(){
        var passport = jQuery("#passport").val();
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                check = to_numeric(otbjs['passport']['check']);
                if(check==1){
                    alert('trùng số hộ chiếu/ CMND!');
                    jQuery("#passport").val('');
                }
                
            }
        }
        xmlhttp.open("GET","get_member.php?data=get_passport&passport="+passport,true);
        xmlhttp.send();
    }
    function check_confix(){
            var first_name = jQuery("#first_name").val();
            var last_name = jQuery("#last_name").val();
            var passport = jQuery("#passport").val();
            if(first_name=='' || last_name==''){
                alert("bạn nhập thiếu thông tin!");
                if(first_name==''){
                    jQuery("#first_name").css('background','red');
                }else{
                    jQuery("#first_name").css('background','#ffffff');
                }
                if(last_name==''){
                    jQuery("#last_name").css('background','red');
                }else{
                    jQuery("#last_name").css('background','#ffffff');
                }
                if(passport==''){
                    jQuery("#passport").css('background','red');
                }else{
                    jQuery("#passport").css('background','#ffffff');
                }
                
            }else{
                count_click  = count_click +1;
                if(count_click==1)
                {
                    AddTravellerForm.submit();
                }
            }
    }
    function fun_check_edit(){
                document.getElementById("check_edit").checked = true;
            }
</script>