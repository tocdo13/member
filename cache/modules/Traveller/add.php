<form name="AddTravellerForm" method="post">
    <div style="width: 100%; margin: 10px auto; clear: both; height: 50px; border-bottom: 1px solid #171717;">
        <h1 style="float: left; margin-left: 10px;"><?php echo Portal::language('add_traveller');?></h1>
        <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
        <input name="save" type="button" id="save" value="<?php echo Portal::language('save');?>" onclick="check_confix();" style="padding: 10px; float: right; margin-right: 10px; background: #00b2f9; border: none; border: 2px solid #b8e9fd;" />
        <input name="save" type="button" id="save" value="<?php echo Portal::language('save_stay_1');?>" onclick="fun_check_edit();check_confix();" style="padding: 10px; float: right; margin-right: 10px; background: #00b2f9; border: none; border: 2px solid #b8e9fd;" />
        <?php } ?>
        <input name="check_edit" id="check_edit" type="checkbox" style="display: none;" />
        <?php if(User::can_view(false,ANY_CATEGORY)){ ?><a href="?page=traveller&cmd=list_member" style="float: right;"><input name="back" type="button" id="back" value="<?php echo Portal::language('back');?>" style="padding: 10px; float: right; margin-right: 10px; background: #00b2f9; border: none; border: 2px solid #b8e9fd;" /></a><?php } ?>
    </div>
    <div style="width: 100%; margin: 20px auto; clear: both;">
        <table style="width: 70%; margin: 20px auto;">
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('first_name');?><span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input  name="first_name" id="first_name" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('first_name'));?>"></td>
                <td style="text-align: right;"><?php echo Portal::language('last_name');?><span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input  name="last_name" id="last_name" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('last_name'));?>"></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('gender');?></td>
                <td style="text-align: center;">:</td>
                <td><select  name="gender" id="gender" style="width: 205px; height: 25px;" ><?php
					if(isset($this->map['gender_list']))
					{
						foreach($this->map['gender_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))
                    echo "<script>$('gender').value = \"".addslashes(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))."\";</script>";
                    ?>
	</select></td>
                <td style="text-align: right;"><?php echo Portal::language('birth_date');?></td>
                <td style="text-align: center;">:</td>
                <td><input  name="birth_date" id="birth_date" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('birth_date'));?>"></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('country');?></td>
                <td style="text-align: center;">:</td>
                <td><select  name="nationality_id" id="nationality_id" style="width: 205px; height: 25px;" ><?php
					if(isset($this->map['nationality_id_list']))
					{
						foreach($this->map['nationality_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))
                    echo "<script>$('nationality_id').value = \"".addslashes(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))."\";</script>";
                    ?>
	</select></td>
                <td style="text-align: right;"><?php echo Portal::language('passport');?><span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input  name="passport" id="passport" style="width: 200px; height: 25px;" autocomplete="off" onchange="get_passport();" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>"></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('address');?></td>
                <td style="text-align: center;">:</td>
                <td><input  name="address" id="address" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('address'));?>"></td>
                <td style="text-align: right;"><?php echo Portal::language('email');?></td>
                <td style="text-align: center;">:</td>
                <td><input  name="email" id="email" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('phone');?></td>
                <td style="text-align: center;">:</td>
                <td><input  name="phone" id="phone" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>"></td>
                <td style="text-align: right;"><?php echo Portal::language('fax');?></td>
                <td style="text-align: center;">:</td>
                <td><input  name="fax" id="fax" style="width: 200px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('fax'));?>"></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('note');?></td>
                <td style="text-align: center;">:</td>
                <td><textarea  name="note" id="note" style="width: 200px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea></td>
                <td style="text-align: right;"><?php echo Portal::language('is_vn');?></td>
                <td style="text-align: center;">:</td>
                <td><select  name="is_vn" id="is_vn" style="width: 205px; height: 25px;"><?php
					if(isset($this->map['is_vn_list']))
					{
						foreach($this->map['is_vn_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('is_vn',isset($this->map['is_vn'])?$this->map['is_vn']:''))
                    echo "<script>$('is_vn').value = \"".addslashes(URL::get('is_vn',isset($this->map['is_vn'])?$this->map['is_vn']:''))."\";</script>";
                    ?>
	</select></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('traveller_level');?> <span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><select  name="traveller_level_id" id="traveller_level_id" style="width: 205px; height: 25px;"><?php
					if(isset($this->map['traveller_level_id_list']))
					{
						foreach($this->map['traveller_level_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('traveller_level_id',isset($this->map['traveller_level_id'])?$this->map['traveller_level_id']:''))
                    echo "<script>$('traveller_level_id').value = \"".addslashes(URL::get('traveller_level_id',isset($this->map['traveller_level_id'])?$this->map['traveller_level_id']:''))."\";</script>";
                    ?>
	</select></td>
                <td style="text-align: right;"><?php echo Portal::language('province');?></td>
                <td style="text-align: center;">:</td>
                <td><select  name="province_id" id="province_id" style="width: 205px; height: 25px;"><?php
					if(isset($this->map['province_id_list']))
					{
						foreach($this->map['province_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('province_id',isset($this->map['province_id'])?$this->map['province_id']:''))
                    echo "<script>$('province_id').value = \"".addslashes(URL::get('province_id',isset($this->map['province_id'])?$this->map['province_id']:''))."\";</script>";
                    ?>
	</select></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('create_member_code');?></td>
                <td style="text-align: center;">:</td>
                <td><input name="create_member_code" id="create_member_code" type="checkbox" onchange="fun_check_email();" /><?php echo Portal::language('register_member');?></td>
                <td style="text-align: right;"></td>
                <td style="text-align: center;"></td>
                <td></td>
            </tr>
        </table>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
            if(first_name=='' || last_name=='' || passport==''){
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