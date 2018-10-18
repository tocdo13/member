<style>
.simple-layout-middle{width:100%;}
#content_member table tr td{
    height: 25px;
    border: 1px solid #b8e9fd;
}
#content_member table tr th{
    height: 35px;
    border: 1px solid #b8e9fd;
}
</style>
<form name="ListMemberTravellerForm" method="post">
    <div id="search_member" style="width: 100%; margin: 0px auto; height: auto;">
        <fieldset style="width: 960px; margin: 0px auto; background: #b8e9fd;">
            <legend style="font-size: 16px; text-transform: uppercase;">[[.search_traveller.]]</legend>
            <table style="width: 100%; border: 1px solid #00b2f9; background: #fff;">
                <tr>
                    <td rowspan="3" style="width: 300px;">
                        <fieldset>
                            <legend>[[.search_for_member.]]</legend>
                            <table>
                                <tr>
                                    <td>[[.member_code.]]:<br /><input name="member_code" type="text" id="member_code" /></td>
                                    <td>[[.create_date.]]:<br /><input name="create_date" type="text" id="create_date" /></td>
                                </tr>
                                <tr>
                                    <td>[[.point_from.]]:<br /><input name="point_from" type="text" id="point_from" /></td>
                                    <td>[[.point_to.]]:<br /><input name="point_to" type="text" id="point_to" /></td>
                                </tr>
                                <tr>
                                    <td>[[.member_level.]]:<br /><select name="member_level" id="member_level"></select></td>
                                    <td>[[.email_confirm.]]:<br /><select name="email_traveller" id="email_traveller"></select></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                    <td>[[.full_name.]]:</td>
                    <td><input name="full_name" type="text" id="full_name" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                    <td>[[.passport.]]:</td>
                    <td><input name="passport" type="text" id="passport" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                    <td rowspan="3" style="text-align: center;">
                        <input name="do_search" type="submit" id="do_search" value="[[.search.]]" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer;" /><br /><br />
                        <input name="reset" type="button" id="do_search" value="[[.reset.]]" onclick="fun_reset();" style="padding: 10px; border: none; border: 2px solid #00b2f9; background: #b8e9fd; color: #171717; cursor: pointer; text-transform: uppercase;" />
                    </td>
                </tr>
                <tr>
                    <td>[[.country.]]:</td>
                    <td><select name="country" id="country" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"></select></td>
                    <td>[[.gender.]]:</td>
                    <td><select name="gender" id="gender" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"></select></td>
                </tr>
                <tr>
                    <td>[[.email.]]:</td>
                    <td><input name="email" type="text" id="email" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                    <td>[[.phone.]]:</td>
                    <td><input name="phone" type="text" id="phone" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                </tr>
            </table>
        </fieldset>
    </div><!-- end search_member -->
    <div id="content_member" style="width: 100%; height: auto; margin: 20px auto;">
        <div id="nav_list">
            <h1 style="float: left; margin-left: 10px;">[[.list_member.]]</h1>
            <input name="delete_traveller" type="button" id="delete_traveller" value="[[.delete_all_select.]]" onclick="fun_delete_traveller();" style="padding: 10px; display: none; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" />
            <input name="check_delete" id="check_delete" type="checkbox" style="display: none;" />
            <input name="list_delete" id="list_delete" type="text" style="display: none;" />
            <?php if(User::can_add(false,ANY_CATEGORY)){ ?><a href="?page=traveller&cmd=add" style="float: right; margin-right: 100px;"><input name="add_traveller" type="button" id="add_traveller" value="[[.add.]]" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" /></a><?php } ?>
        </div>
        <div style="width: 100%; margin: 10px auto; float: left;">[[|paging|]]</div>
        <table cellspacing="0" width="98%" style=" margin: 10px auto;">    
            <tr style="background: #00b2f9; color: #fff;">
                <th style="width: 50px; text-align: center;"><input name="check_list_all" type="checkbox" id="check_list_all" onclick="fun_check_all();" />ALL</th>
                <th style="width: 50px; text-align: center;">[[.STT.]]</th>
                <th style="text-align: center;">[[.full_name.]]</th>
                <th style="text-align: center;">[[.passport.]]</th>
                <th style="width: 50px; text-align: center;">[[.gender.]]</th>
                <th style="text-align: center;">[[.country.]]</th>
                <th style="text-align: center;">[[.email.]]</th>
                <th style="text-align: center;">[[.phone_number.]]</th>
                <th style="text-align: center;">[[.member_code.]]</th>
                <th style="width: 100px; text-align: center;">[[.create_date.]]</th>
                <th style="text-align: center;">[[.point.]]</th>
                <th style="text-align: center;">[[.point_user.]]</th>
                <th style="text-align: center;">[[.member_level.]]</th>
                <th style="width: 50px; text-align: center;">[[.edit.]]</th>
                <th style="width: 50px; text-align: center; display: none;">[[.delete.]]</th>
            </tr>         
            <?php $stt=1; ?>         
            <!--LIST:items-->
            <tr>
                <td style="text-align: center;"><input name="check_list_[[|items.id|]]" type="checkbox" id="check_list_[[|items.id|]]" onclick="fun_check_id([[|items.id|]]);" /></td>
                <td style="text-align: center;"><?php echo $stt++; ?></td>
                <td style="text-align: center;">[[|items.full_name|]]</td>
                <td style="text-align: center;">[[|items.passport|]]</td>
                <td style="text-align: center;"><?php if([[=items.gender=]]==1){?>[[.male.]] <?php }else{ ?>[[.female.]]<?php } ?></td>
                <td style="text-align: center;">[[|items.name_2|]]</td>
                <td style="text-align: center;">[[|items.email|]]</td>
                <td style="text-align: center;">[[|items.phone|]]</td>
                <td style="text-align: center;"><?php if([[=items.member_code=]]>0){?>[[|items.member_code|]]<?php }else{ ?>[[.no_code.]]<?php } ?></td>
                <td style="text-align: center;">[[|items.member_create_date|]]</td>
                <td style="text-align: center;">[[|items.point|]]</td>
                <td style="text-align: center;">[[|items.point_user|]]</td>
                <td style="text-align: center;">[[|items.def_name|]]</td>
                <td style="text-align: center;"><?php if(User::can_edit(false,ANY_CATEGORY)){ ?><a target="_blank" href="?page=traveller&reservation_id=&cmd=edit&id=[[|items.id|]]"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_edit.png" style="width: 20px; height: auto;" /></a><?php } ?></td>
                <td style="text-align: center; display: none;"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_delete.png" style="width: 20px; height: auto; cursor: pointer;" onclick="fun_delete_id([[|items.id|]]);" /></td>
            </tr>
            <!--/LIST:items-->      
        </table>
        <div style="width: 100%; margin: 10px auto; float: left;">[[|paging|]]</div>
    </div>
</form>
<script>
    jQuery("#create_date").datepicker();
    function fun_check_all(){
        var list_items = [[|list_items|]];
        var id="";
        var list_delete = "";
        if(document.getElementById("check_list_all").checked==true){
            for(item in list_items){
                id=list_items[item]['id'];
                document.getElementById("check_list_"+id).checked=true;
                list_delete = list_delete+"_"+id;
            }
        }else{
            for(item in list_items){
                id=list_items[item]['id'];
                document.getElementById("check_list_"+id).checked=false;
            }
            
        }
        jQuery("#list_delete").val(list_delete);
    }
    function fun_check_id(id){
        var list_delete = jQuery("#list_delete").val().split("_");
        var list_new = "";
        for(list_id in list_delete){
            if((id!=list_delete[list_id]) && (list_id!=0)){
                //console.log(list_delete[list_id]);\
                list_new = list_new+"_"+list_delete[list_id];
            }
        }
        jQuery("#list_delete").val(list_new);
        console.log(list_new);
    }
    function fun_delete_traveller(){
        document.getElementById("check_delete").checked=true;
        var list_delete = jQuery("#list_delete").val();
        if(list_delete==""){
            alert("chưa chọn khách để xóa");
        }else{
            ListMemberTravellerForm.submit();
        }
    }
    function fun_delete_id(id){
        document.getElementById("check_delete").checked=true;
        jQuery("#list_delete").val("_"+id);
        ListMemberTravellerForm.submit();
    }
    function fun_reset(){
        
    }
</script>