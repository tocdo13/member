<style>
*{
    margin: 0px; padding: 0px;
}
.item{
    margin: 9px;
    width:240px;
    height: 130px;
    float: left;
    cursor: pointer;
    text-align: center;
    border: 3px solid #00b2f9;
    border-radius: 5px;
    transition: all .5s ease-out;
}
.item:hover{
    background: #eeeeee;
}
#nav_icon ul{
    list-style: none;
    width: 240px;
    height: 30px;
}
#nav_icon ul li{
    display: inline;
    float: left;
    width: 20px;
    height: 20px;
    overflow: hidden;
    position: relative;
    border: 1px solid #00b2f9;
    margin-left: 3px;
    transition: all .5s ease-out;
}
#nav_icon ul li:hover{
    width: 150px;
}
#nav_icon ul li img{
    width: 20px; height: auto;
    position: absolute; top: 0px; left: 0px;
}
#nav_icon ul li p{
    line-height: 20px;
    position: absolute; top: 0px; left: 20px;
}
table#table_from_level tr td{
    padding: 10px;
    font-size: 17px;
    line-height: 25px;
    text-transform: uppercase;
}
table#table_from_level tr td input.skin_input{
    width: 250px; height: 30px; font-size: 19px; color: #00b2f9;
    border: none; border-bottom: 1px dashed #00b2f9; border-left: 2px solid #00b2f9; padding-left: 5px;
}
</style>
<script>
    function select_item(id){
        if(document.getElementById("member_level_"+id).checked==true){
            document.getElementById("member_level_"+id).checked=false;
            jQuery("#level_id_"+id).css('border','3px solid #00b2f9');
            jQuery("#level_id_"+id).css('opacity','1');
            var delete_select_list = jQuery("#delete_select_list").val().split("_");
            var list_new='';
            for(list in delete_select_list){
                delete_select_list[list] = to_numeric(delete_select_list[list]); id=to_numeric(id);
                if(delete_select_list[list]!=id && list!=0){
                    list_new =list_new+"_"+delete_select_list[list]; 
                }
            }
             jQuery("#delete_select_list").val(list_new);
        }else{
            document.getElementById("member_level_"+id).checked=true;
            jQuery("#level_id_"+id).css('border','3px solid #000000');
            jQuery("#level_id_"+id).css('opacity','.7');
            var delete_select_list = jQuery("#delete_select_list").val();
            delete_select_list = delete_select_list+"_"+id;
            jQuery("#delete_select_list").val(delete_select_list);
        }
        
    }
</script>
<form name="MemberLevelForm" method="post" enctype="multipart/form-data">
<div style="width: 100%;">
    <div style="width: 100%; height: 50px; margin: 0px auto; clear: both; border-bottom: 2px double #eeeeee;">
        <h1 style="float: left; margin-left: 20px; text-transform: uppercase;">[[.member_level_list.]]</h1>
        <!--<?php if(User::can_delete(false,ANY_CATEGORY)){ ?><input style="float: right; margin-right: 20px; background: #00b2f9; padding: 10px; border: none; cursor: pointer;" type="button" name="delete_all" id="delete_all" onclick="delete_member_select();" value="[[.delete_all_select.]]" /><?php } ?>-->
        <?php if(User::can_add(false,ANY_CATEGORY)){ ?><input style="float: right; margin-right: 20px; background: #00b2f9; padding: 10px; border: none; cursor: pointer;" type="button" name="add" id="add" onclick="window.location.href='?page=member_level&cmd=add';" value="[[.add.]]" /><?php } ?>
        <input type="text" name="delete_select_list" id="delete_select_list" style="display: none;" />
    </div>
    <div>
        
    </div>
    <div style="width: 100%; margin: 20px auto; clear: both;">
    <!--<div style="width: 100%; height: 25px; margin: 10px auto;">
        <div style="width: 200px; height: 26px; margin-left: 15px; position: relative; border: 2px solid #00b2f9; border-radius: 5px; overflow: hidden;">
            <table style="width: 100%; position: absolute; top: 0px; left: 0px;">
                <tr>
                    <td style="height: 26px; text-align: center;">ON</td>
                    <td style="height: 26px; text-align: center;">OFF</td>
                </tr>
            </table>
            <input name="check_all" type="checkbox" id="check_all" style="display: none;" />
            <div onclick="select_all_items();" style="background: #00b2f9; cursor: pointer; transition: all .5s ease-out; width: 100px; height: 26px; line-height: 26px; text-align: center; color: #fff; border-radius: 5px; position: absolute; top: 0px; left: 0px; box-shadow: 0px 0px 5px #555555; cursor: pointer;" id="button_select_all">[[.select_all.]]</div>
        </div>
    </div>-->
    <input name="check_delete_member_level" id="check_delete_member_level" type="checkbox" style="display: none;" />
        <!--LIST:list_level_member-->
        <div class="item" id="level_id_[[|list_level_member.id|]]" onclick="select_item([[|list_level_member.id|]]);">
            <p style="text-transform: uppercase; color: #00b2f9; line-height: 30px; font-size: 20px;"><input style="display: none;" name="member_level_[[|list_level_member.id|]]" id="member_level_[[|list_level_member.id|]]" type="checkbox" />[[|list_level_member.name|]]</p>
            <table style="width: 100%; margin: 10px 0px;">
                <tr style="border-top: 1px dashed #dddddd; border-bottom: 1px dashed #dddddd;">
                    <td rowspan="2" style="text-align: center; width: 60px;"><img src="[[|list_level_member.logo|]]" alt="logo [[|list_level_member.name|]]" title="logo [[|list_level_member.name|]]" style="width: 50px; height: 50px;" /></td>
                </tr>
                <tr style="border-top: 1px dashed #dddddd; border-bottom: 1px dashed #dddddd;">
                    <td style="text-align: center;">
                        <h3>[[|list_level_member.def_name|]]</h3>
                        [[.point.]]:<?php if([[=list_level_member.min_point=]]==0){?> [[.min_point_to.]] [[|list_level_member.max_point|]] <?php }elseif([[=list_level_member.max_point=]]==0){ ?> [[.max_point_to.]] [[|list_level_member.min_point|]]<?php }else{ ?> [[.from.]] [[|list_level_member.min_point|]] - [[.to_than.]] [[|list_level_member.max_point|]] <?php } ?>
                    </td> 
                </tr>
            </table>
            <div id="nav_icon" style="width: 100%; height: 30px; margin: 0px; padding: 0px;">
            <ul>
                <?php if(User::can_edit(false,ANY_CATEGORY)){ ?><li id="edit_level_[[|list_level_member.id|]]" onclick="window.location.href='?page=member_level&cmd=edit&id=[[|list_level_member.id|]]';"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_edit.png" /><p>[[.edit_member_level.]]</p></li><?php } ?>
                <?php if(User::can_add(false,ANY_CATEGORY)){ ?><!--<li id="add_level_pin_[[|list_level_member.id|]]" onclick="window.location.href='?page=member_level_policies&level_id=[[|list_level_member.id|]]';"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_add.png" /><p>[[.add_discount_level_policies.]]</p></li>--><?php } ?>
                <?php if(User::can_add(false,ANY_CATEGORY)){ ?><!--<li id="add_level_[[|list_level_member.id|]]" onclick="window.location.href='?page=member_discount&cmd=add_level&level_id=[[|list_level_member.id|]]';"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_add.png" /><p>[[.add_discount_level.]]</p></li>--><?php } ?>
            </ul>
            </div>   
        </div>
        <!--/LIST:list_level_member-->
    </div>
</div>
<div id="window_from" style=" display: none; position: fixed; top: 0px; left: 0px; text-align: center; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7);">
    <div style="width: 600px; height: 400px; background: #ffffff; margin: 100px auto; border: 2px solid #00b2f9; border-radius: 5px; position: relative; box-shadow: 0px 0px 5px #000000;">
        <div onclick="close_form();" style="width: 30px; height: 30px; line-height: 30px; text-align: center; background: #000000; color: #ffffff; font-weight: bold; border: 2px solid #00b2f9; border-radius: 50%; position: absolute; top: -15px; right: -15px; cursor: pointer;">X</div>
        <div style="width: 535px; height: 350px; padding: 25px; position: absolute; top: 15px; left: 0px;">
            <input style="display: none;" name="check_id_member_level" type="text" id="check_id_member_level" />
            <table id="table_from_level" style="width: 100%;">
                <tr>
                    <td>[[.name_level_member.]]<br /><input class="skin_input" name="name_level" type="text" id="name_level" /></td>
                    <td>[[.name_def_level_member.]]<br /><input class="skin_input" name="name_def_level" type="text" id="name_def_level" /></td>
                </tr>
                <tr>
                    <td>[[.min_point_level.]]<br /><input class="skin_input" name="min_point_level" type="text" id="min_point_level" value="0" /><br /><span style="color: #cccccc; font-size: 12px; line-height: 20px;">Mặc định 0 là bắt đầu từ nhỏ nhất</span></td>
                    <td>[[.max_point_level.]]<br /><input class="skin_input" name="max_point_level" type="text" id="max_point_level" value="0" /><br /><span style="color: #cccccc; font-size: 12px; line-height: 20px;">Mặc định 0 là đến lớn nhất</span></td>
                </tr>
                <tr>
                    <td id="logo_level"></td>
                    <td>[[.logo.]]<br /><input type="file" name="file" id="file" /></td>
                </tr>
                <tr>
                    <td><input name="update_level" type="submit" id="update" value="[[.update.]]" style="background: #00b2f9; padding: 10px; font-size: 16px; border: none; box-shadow: 0px 0px 5px #000000;" /><input name="add_level" type="submit" id="add_level" value="[[.add.]]" style="background: #00b2f9; padding: 10px; font-size: 16px; border: none; box-shadow: 0px 0px 5px #000000;" /></td>
                    <td><input name="cancel" type="button" id="cancel" value="[[.cancel.]]" style="background: #eeeeee; padding: 10px; font-size: 16px; border: none; box-shadow: 0px 0px 5px #000000;" onclick="close_form();" /></td>
                </tr>
            </table>
        </div>
    </div>
</div><!-- end window_from -->
</form>
<script>
    function close_form(){
        location.reload();
    }
    function add_member(){
        jQuery("#name_level").val("");
        jQuery("#name_def_level").val("");
        jQuery("#min_point_level").val(0);
        jQuery("#max_point_level").val(0);
        jQuery("#update").css('display','none');
        jQuery("#add_level").css('display','');
        jQuery("#window_from").css('display','');
    }
    function edit_member(obj){
        var arr_id=obj.id.split("_");
        var id = arr_id[2];
        var list_level_member_js = [[|list_level_member_js|]];
        console.log(list_level_member_js[id]);
        jQuery("#check_id_member_level").val(id);
        jQuery("#name_level").val(list_level_member_js[id]['name']);
        jQuery("#name_def_level").val(list_level_member_js[id]['def_name']);
        jQuery("#min_point_level").val(list_level_member_js[id]['min_point']);
        jQuery("#max_point_level").val(list_level_member_js[id]['max_point']);
        var logo = '<img src="packages/hotel/packages/sale/skins/img/logo_member_level/'+list_level_member_js[id]['logo']+'" />';
        jQuery("#logo_level").html("");
        jQuery("#logo_level").append(logo);
        jQuery("#update").css('display','');
        jQuery("#add_level").css('display','none');
        jQuery("#window_from").css('display','');
    }
    function delete_member(obj){
        var ask = confirm("Bạn có thực sự muốn xóa?");
        if(!ask){
            location.reload();
        }else{
            var arr_id=obj.id.split("_");
            var id = arr_id[2];
            var delete_select_list = jQuery("#delete_select_list").val();
            delete_select_list = "_"+id;
            jQuery("#delete_select_list").val(delete_select_list);
            document.getElementById("check_delete_member_level").checked=true;
            MemberLevelForm.submit();
        }
    }
    function delete_member_select(){
        document.getElementById("check_delete_member_level").checked=true;
        MemberLevelForm.submit();
    }
    function select_all_items(){
        var list_level_member_js = [[|list_level_member_js|]];
        var id = "";
        var delete_select_list = "";
        if(document.getElementById("check_all").checked==true){
            jQuery("#button_select_all").css('left','0px');
            document.getElementById("check_all").checked=false;
            for(list in list_level_member_js){
                id = list_level_member_js[list]['id'];
                jQuery("#level_id_"+id).css('border','3px solid #00b2f9');
                jQuery("#level_id_"+id).css('opacity','1');
            }
            jQuery("#delete_select_list").val(delete_select_list);
        }
        else
        {
            document.getElementById("check_all").checked=true;
            jQuery("#button_select_all").css('left','100px');
            for(list in list_level_member_js){
                id = list_level_member_js[list]['id'];
                jQuery("#level_id_"+id).css('border','3px solid #000000');
                jQuery("#level_id_"+id).css('opacity','.7');
                delete_select_list = delete_select_list+"_"+id;
            }
            jQuery("#delete_select_list").val(delete_select_list);
        }
    }
</script>
