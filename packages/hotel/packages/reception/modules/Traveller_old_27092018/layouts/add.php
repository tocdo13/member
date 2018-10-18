<style>
    #no_avata {
        width: 200px;
        height: auto;
        border: 3px solid #FFFFFF;
        box-shadow: 0px 0px 5px #555555;
    }
    #avatar {
        width: 200px;
        height: auto;
        border: 3px solid #FFFFFF;
        box-shadow: 0px 0px 5px #555555;
    }
    .simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #f9f9f9;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
        background: #f9f9f9!important;
    }
    body{
        background: #f9f9f9!important;
    }
    .over_hidden{
        overflow: hidden!important;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    #ui-datepicker-div{
        z-index: 999999;
    }
    .position_fixed {
        position: fixed; 
        top: 0px; 
        left: 0px; 
        
    }
</style>
<form name="AddTravellerForm" method="post" enctype="multipart/form-data">
    <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 0px auto;">
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <h3><i class="fa fa-fw fa-user"></i> <?php if(Url::get('cmd')=='add'){ ?>[[.add_member.]]<?php }else{ ?>[[.edit_member.]]<?php } ?></h3>
            </div>
            <div class="w3-button w3-margin w3-right w3-text-grey" onclick="location.href='?page=traveller&cmd=list_member'" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    <i class="fa fa-fw fa-mail-reply"></i> [[.back.]]
                </div>
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="AddTravellerForm.submit();" style="font-weight: normal;">
                <i class="fa fa-fw fa-save"></i> [[.save.]]
            </div>
        </div>
        <table class="w3-table">
            <tr>
                <td rowspan="11">
                    <!-- avata -->
                    <?php if([[=image_profile=]]==''){ ?>
                        <img id="no_avata" src="packages/hotel/packages/reception/modules/Traveller/avata/no_avata_boy.jpg" />
                    <?php }else{ ?>
                        <img id="no_avata" src="packages/hotel/packages/reception/modules/Traveller/avata/[[|image_profile|]]" />
                    <?php } ?>
                    <br />
                    <input type="file" name="file" id="file" style="padding: 10px;" />
                    <!--/ avata -->
                </td>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.first_name.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="first_name" type="text" id="first_name" style="width: 200px; height: 25px;" placeholder="First Name" value="<?php echo isset([[=first_name=]])?[[=first_name=]]:'';?>" autocomplete="OFF" /></td>
                <td style="text-align: right;">[[.last_name.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="last_name" type="text" id="last_name" value="<?php echo isset([[=last_name=]])?[[=last_name=]]:'';?>" placeholder="Last Name" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.email.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="email" type="text" id="email" value="<?php echo isset([[=email=]])?[[=email=]]:'';?>" placeholder="Email" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
                <td style="text-align: right;">[[.birth_date.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="birth_date" type="text" id="birth_date" placeholder="Birth Date (DD/MM/YYYY)" value="<?php echo isset([[=birth_date=]])?[[=birth_date=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                <td style="text-align: right;">[[.gender.]]</td>
                <td style="text-align: center;">:</td>
                <td><select name="gender" id="gender" style="width: 205px; height: 25px;" onchange="change_no_avata();" ></select></td>
                <td style="text-align: right;">[[.passport.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="passport" type="text" id="passport" placeholder="PassPort" value="<?php echo isset([[=passport=]])?[[=passport=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="off" onchange="get_passport();" /></td>
                
            </tr>
            <tr>
                <td style="text-align: right;">[[.country.]]</td>
                <td style="text-align: center;">:</td>
                <td><select name="nationality_id" id="nationality_id"  style="width: 205px; height: 25px;" onchange="get_province();" ></select></td>
                <td style="text-align: right;">[[.address.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="address" type="text" id="address" placeholder="Address" value="<?php echo isset([[=address=]])?[[=address=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                
                <td style="text-align: right;">[[.phone.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="phone" type="text" id="phone" placeholder="Phone Number" value="<?php echo isset([[=phone=]])?[[=phone=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
                <td style="text-align: right;">[[.fax.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="fax" type="text" id="fax" placeholder="Fax" value="<?php echo isset([[=fax=]])?[[=fax=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr style="display: none;">
                <td style="text-align: right;">[[.plot_code.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="plot_code" type="text" id="plot_code" value="<?php echo isset([[=plot_code=]])?[[=plot_code=]]:'';?>" placeholder="Plot Code" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background: rgba(91, 165, 255, 0.49)">
                <td style="text-align: right;">[[.member_code.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td>
                <input name="member_code" type="text" id="member_code" style="width: 200px; height: 25px;" onblur="fun_check_confix();" onkeyup="fun_check_confix();" value="<?php echo isset([[=member_code=]])?[[=member_code=]]:'';?>" <?php echo isset([[=member_code=]])?'readonly="readonly"':'';?> placeholder="Member Code" autocomplete="OFF" />
                <?php if(isset([[=member_code=]]) AND User::can_admin(false,ANY_CATEGORY) ){ ?>
                    <input type="button" value="[[.change_code.]]" onclick=" return func_change_code();" />
                <?php } ?>
                </td>
                 <td style="text-align: right;">[[.status_traveller.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><select name="status_traveller_id" id="status_traveller_id" style="width: 205px; height: 25px;"></select></td>
                
            </tr> 
            <tr style="background: rgba(91, 165, 255, 0.49)">
                <td style="text-align: right;">[[.member_level.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><select  name="member_level_id" id="member_level_id" style="width: 205px; height: 25px;">[[|lever_member_id_option|]]</select></td>
                <td style="text-align: right;">[[.group_traveller.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><select name="group_traveller_id" id="group_traveller_id" style="width: 205px; height: 25px;"></select></td>
            </tr>
            <tr style="background: rgba(91, 165, 255, 0.49); display: none;">
                <td style="text-align: right;">[[.is_parent.]]</td>
                <td style="text-align: center;">:</td>
                <td>
                    <input name="is_parent_id" type="text" id="is_parent_id" placeholder="Parent Member Code" style="width: 205px; height: 25px;" list="is_parent_list" autocomplete="OFF" value="[[|is_parent_id|]]" />
                    <datalist id="is_parent_list">[[|is_parent_id_option|]]</datalist>
                </td>
                <td style="text-align: right;">[[.releases_date.]]<span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="releases_date" type="text" id="releases_date" placeholder="Releases Date (DD/MM/YYYY)" value="<?php echo isset([[=releases_date=]])?[[=releases_date=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr style="background: rgba(91, 165, 255, 0.49); display: none;">
                
                <td style="text-align: right;">[[.effective_date.]]</td>
                <td style="text-align: center;">:</td>
                <td><input name="effective_date" type="text" id="effective_date" placeholder="Effective Date (DD/MM/YYYY)" value="<?php echo isset([[=effective_date=]])?[[=effective_date=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
                <td style="text-align: right;">[[.expiration_date.]]</td>
                <td style="text-align: center;"></td>
                <td><input name="expiration_date" type="text" id="expiration_date" placeholder="Expiration Date (DD/MM/YYYY)" value="<?php echo isset([[=expiration_date=]])?[[=expiration_date=]]:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            
            <tr style="background: rgba(91, 165, 255, 0.49); display: none;">
                <td style="text-align: right;">[[.traveller_code.]]</td>
                <td style="text-align: center;">:</td>
                <td style=""><input name="traveller_code" type="text" id="traveller_code" style="width: 200px; height: 25px;" placeholder="Traveller Code" onblur="fun_check_confix_traveller_code();" onkeyup="fun_check_confix_traveller_code();" value="<?php echo isset([[=traveller_code=]])?[[=traveller_code=]]:'';?>" autocomplete="OFF" /></td>
                <td style="text-align: right;">[[.note.]]</td>
                <td style="text-align: center;">:</td>
                <td><textarea name="note" id="note" placeholder="Description" style="width: 200px;"><?php echo isset([[=note=]])?[[=note=]]:'';?></textarea></td>
            </tr>
        </table>
    </div>
</form>
<script>
    jQuery("#member_level_id").val('[[|member_level_id|]]');
    jQuery("#is_parent_id").val('[[|is_parent_id|]]');
    jQuery("#gender").val('<?php echo isset([[=gender=]])?[[=gender=]]:0; ?>');
    <?php if(Url::get('cmd')=='add'){ ?>
    jQuery("#member_code").focus();
    <?php } ?>
    
    jQuery("#birth_date").datepicker();
    jQuery("#releases_date").datepicker();
    jQuery("#effective_date").datepicker();
    jQuery("#expiration_date").datepicker();
    var count_click = 0;
    
    function get_passport()
    {
        
    }
    function fun_check_confix()
    {
        
    }
    function fun_check_confix_traveller_code()
    {
        
    }
    function check_confix()
    {
        count_click  = count_click +1;
        if(count_click==1)
        {
            AddTravellerForm.submit();
        }
    }
    function fun_check_edit()
    {
        document.getElementById("check_edit").checked = true;
    }
    function get_province()
    {
        
    }
    function func_change_code()
    {
        if( confirm('[[.are_you_change_code.]]') )
        {
            jQuery("#member_code").val("");
            jQuery("#member_code").removeAttr("readonly");
            jQuery("#member_code").focus();
        }
    }
    function change_no_avata()
    {
        <?php if([[=image_profile=]]==''){ ?>
        if(jQuery("#gender").val()==1)
            jQuery("#no_avata").attr('src','packages/hotel/packages/reception/modules/Traveller/avata/no_avata_boy.jpg');
        else
            jQuery("#no_avata").attr('src','packages/hotel/packages/reception/modules/Traveller/avata/no_avatar_girl.jpg');
        <?php } ?>
    }
    change_no_avata();
</script>
