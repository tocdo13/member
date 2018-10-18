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
    a {
        text-decoration: none;
    }
</style>
<div style="text-align: center; width: 700px; margin: 0px auto; border: 1px dashed #DDDDDD; padding: 5px;">
    <form method="POST" name="AccessControlForm">
        <table style="text-align: center; width: 700px;">
            <tr>
                <td style="text-align: center;"><h1 style="text-transform: uppercase;">[[.access_control.]]</h1></td>
                <td rowspan="3" style="text-align: center;">
                    <?php if(isset([[=member_code=]])){ ?>
                        <a href="#access_control_history" style="text-decoration: none;"><div style="border: 1px solid #EEEEEE; border-bottom: 3px solid #555555; padding: 5px;">
                            <p><h3>[[.access_control_count.]]</h3></p>
                            <p><h1><?php echo sizeof([[=history=]]); ?></h1></p>
                        </div></a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;"><input name="member_code" type="text" id="member_code" onkeyup="check_member_code(1);" onkeypress="if(event.keyCode==13){return check_member_code(2);}" onblur="check_member_code(1);" style="width: 400px; text-align: center; padding: 10px; border: 3px solid #555555; font-weight: bold; line-height: 25px; font-size: 19px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                <td style="text-align: center;"><input type="button" value="[[.check_in.]]" onclick=" document.getElementById('check_in').checked=true; return check_member_code(2);" style="width: 300px; text-align: center; padding: 10px; font-size: 20px; font-weight: bold;" /><input name="check_in" type="checkbox" id="check_in" value="check_in" style="display: none;" /><input type="button" value="[[.refresh.]]" onclick="jQuery('#member_code').val('');jQuery('#member_code').focus();" style="text-align: center; padding: 10px; font-size: 20px; font-weight: bold; margin-left: 5px;" /></td>
            </tr>
        </table>
    </form>
</div>
<?php if(isset([[=member_code=]])){ ?>
    <div style="margin: 5px auto; width: 95%;">
        <table cellpadding="5" cellspacing="5" width="100%" border="1" bordercolor="#DDDDDD">
            <tr>
                <td rowspan="9" style="text-align: center; width: 250px;">
                    <p><h1>[[.avatar.]]</h1></p>
                    <?php if([[=image_profile=]]==''){ ?>
                        <?php if([[=gender=]]==1){ ?>
                        <img id="no_avata" src="packages/hotel/packages/reception/modules/Traveller/avata/no_avata_boy.jpg" />
                        <?php }else{ ?>
                        <img id="no_avata" src="packages/hotel/packages/reception/modules/Traveller/avata/no_avatar_girl.jpg" />
                        <?php } ?>
                    <?php }else{ ?>
                        <img id="avatar" src="packages/hotel/packages/reception/modules/Traveller/avata/[[|image_profile|]]" />
                    <?php } ?>
                </td>
                <td colspan="4"><h1>[[.infomation.]]</h1></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.first_name.]]:</td>
                <td style="font-weight: bold;">[[|first_name|]]</td>
                <td style="font-weight: bold;">[[.last_name.]]:</td>
                <td style="font-weight: bold;">[[|last_name|]]</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.member_code.]]:</td>
                <td style="font-weight: bold;">[[|member_code|]]</td>
                <td style="font-weight: bold;">[[.card_type.]]:</td>
                <td style="font-weight: bold;"><?php if([[=is_parent_id=]]==''){ ?>[[.parent_card.]]<?php }else{ ?>[[.son_card.]]<br />([[.parent_card_member_code.]]: [[|is_parent_id|]])<?php } ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.member_level.]]:</td>
                <td style="font-weight: bold;">[[|member_level_name|]] ([[|member_level_def_name|]])</td>
                <td style="font-weight: bold;">[[.member_create_date.]]:</td>
                <td style="font-weight: bold;">[[|member_create_date|]]</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.gender.]]:</td>
                <td style="font-weight: bold;"><?php if([[=gender=]]==1){ ?>[[.male.]] <?php }else{ ?>[[.female.]] <?php } ?></td>
                <td style="font-weight: bold;">[[.birth_date.]]:</td>
                <td style="font-weight: bold;">[[|birth_date|]]</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.passport.]]:</td>
                <td style="font-weight: bold;">[[|passport|]]</td>
                <td style="font-weight: bold;">[[.phone_number.]]:</td>
                <td style="font-weight: bold;">[[|phone|]]</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.email.]]:</td>
                <td style="font-weight: bold;">[[|email|]]</td>
                <td style="font-weight: bold;">[[.fax.]]:</td>
                <td style="font-weight: bold;">[[|fax|]]</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.address.]]:</td>
                <td style="font-weight: bold;">[[|address|]]</td>
                <td style="font-weight: bold;">[[.country.]]:</td>
                <td style="font-weight: bold;">[[|country_name|]]</td>
            </tr>
            <tr>
                <td colspan="4">
                    <fieldset>
                        <legend style="font-weight: bold;">[[.member_discount.]]</legend>
                        <table cellpadding="5" cellspacing="5" width="100%" border="1" bordercolor="#DDDDDD">
                            <tr  style="text-align: center;">
                                <th>[[.code.]]</th>
                                <th>[[.title.]]</th>
                                <th>[[.description.]]</th>
                                <th>[[.start_date.]]</th>
                                <th>[[.end_date.]]</th>
                            </tr>
                        <!--LIST:items-->
                            <tr style="font-weight: bold;">
                                <td style="text-align: center;">[[|items.code|]]</td>
                                <td>[[|items.title|]]</td>
                                <td>[[|items.description|]]</td>
                                <td style="text-align: center;">[[|items.start_date|]]</td>
                                <td style="text-align: center;">[[|items.end_date|]]</td>
                            </tr>
                        <!--/LIST:items-->
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr id="access_control_history">
                <td colspan="5">
                    <?php $stt = 1; ?>
                    <fieldset>
                        <legend style="font-weight: bold;">[[.access_control_history.]] (<?php echo sizeof([[=history=]]); ?>)</legend>
                        <table cellpadding="5" cellspacing="5" width="100%" border="1" bordercolor="#DDDDDD">
                            <tr  style="text-align: center;">
                                <th>[[.stt.]]</th>
                                <th>[[.time.]]</th>
                                <th>[[.creater.]]</th>
                            </tr>
                        <!--LIST:history-->
                            <tr style="font-weight: bold;">
                                <td style="text-align: center;"><?php echo $stt++; ?></td>
                                <td><?php echo date('H:i d/m/Y',[[=history.time=]]); ?></td>
                                <td>[[|history.creater|]]</td>
                            </tr>
                        <!--/LIST:history-->
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </div>
<?php } ?>

<script>
    <?php if(!isset([[=member_code=]])){ ?>
    jQuery('#member_code').focus();
    <?php } ?>
    function check_member_code(key)
    {
        var check=true;
        if(jQuery("#member_code").val()!='')
        {
            var member_code = validate_member_code(jQuery("#member_code").val());
            var cond = '';
            if (window.XMLHttpRequest)
            {
                xmlhttp=new XMLHttpRequest();
            }
            else
            {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    var text_reponse = xmlhttp.responseText;
                    var otbjs = jQuery.parseJSON(text_reponse);
                    if(to_numeric(otbjs)==1) // khong co the nay
                    {
                            check=false;
                            alert("[[.is_not_member_code.]]!");
                            return false;
                    }
                    else if(to_numeric(otbjs)==13)
                    {
                        check=false;
                        alert("[[.member_code_effective.]]!");
                        jQuery("#member_code").val('');
                        return false;
                    }
                    else if(to_numeric(otbjs)==44)
                    {
                        check=false;
                        alert("[[.member_code_expiration.]]!");
                        jQuery("#member_code").val('');
                        return false;
                    }
                    else
                    {
                        AccessControlForm.submit();
                    }
                    
                }
            }
            xmlhttp.open("GET","get_member.php?data=get_member_code&member_code="+member_code+cond,true);
            xmlhttp.send();
        }
        return check;
    }
</script>