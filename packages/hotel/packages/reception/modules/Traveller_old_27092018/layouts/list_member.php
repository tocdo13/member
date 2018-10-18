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
<form name="ListMemberTravellerForm" method="post">
    <div class="w3-row" style="max-width: 1200px; margin: 0px auto;">
        <div class="w3-row">
            <table class="w3-table">
                <tr>
                    <td rowspan="5" style="width: 300px;">
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
                                    <td colspan="2">[[.member_level.]]:<br /><select name="member_level" id="member_level" style="width: 257px;"></select></td>
                                    
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                    <td>[[.full_name.]]:</td>
                    <td><input name="full_name" type="text" id="full_name" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                    <td>[[.passport.]]:</td>
                    <td><input name="passport" type="text" id="passport" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                    <td rowspan="4" style="text-align: center;">
                        <input name="do_search" type="submit" id="do_search" value="[[.search.]]" class="w3-button w3-border w3-blue" /><br /><br />
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
                <tr>
                    <td>[[.group_traveller.]]:</td>
                    <td><select name="group_traveller" id="group_traveller" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"></select></td>
                    <td>[[.status_traveller.]]:</td>
                    <td><select name="status_traveller" id="status_traveller" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"></select></td>
                </tr>
                <tr>
                    <td>[[.traveller_code.]]:</td>
                    <td><input name="traveller_code" type="text" id="traveller_code" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" /></td>
                    <td>[[.card_type.]]:</td>
                    <td><select name="card_type" id="card_type" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"></select></td>
                </tr>
            </table>
        </div>
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <h3><i class="fa fa-fw fa-user"></i> [[.list_member.]]</h3>
            </div>
            
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="location.href='?page=traveller&cmd=add';" style="font-weight: normal;">
                <i class="fa fa-fw fa-plus"></i> [[.add.]]
            </div>
            <div id="nav_list" style="display: none;">
                <h1 style="float: left; margin-left: 10px;">[[.list_member.]]</h1>
                <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
                <a href="?page=traveller&cmd=add" style="float: right; margin-right: 100px;">
                <input name="add_traveller" type="button" id="add_traveller" value="[[.add.]]" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" />
                </a>
                <a href="?page=traveller&cmd=import_excel" style="float: right; margin-right: 100px;">
                <input name="import_excel" type="button" id="import_excel" value="[[.import_excel.]]" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" />
                </a>
                <input name="export_file_excel" type="submit" id="export_file_excel" value="[[.export_file_excel.]]" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" />
                <?php } ?>
            </div>
        </div>
        <div class="w3-row">
            <div style="width: 100%; margin: 10px auto; float: left;">[[|paging|]]</div>
            <table class="w3-table-all">    
                <tr>
                    <th style="width: 50px; text-align: center; display: none;"><input name="check_list_all" type="checkbox" id="check_list_all" onclick="fun_check_all();" /></th>
                    <th style="width: 50px; text-align: center;">[[.STT.]]</th>
                    <th style="width: 50px;"></th>
                    <th>[[.full_name.]]</th>
                    <th style="width: 50px; text-align: center;">[[.gender.]]</th>
                    <th style="text-align: center;">[[.country.]]</th>
                    <th style="text-align: center;">[[.email.]]</th>
                    <th style="text-align: center;">[[.phone_number.]]</th>
                    <th style="text-align: center; background: #fff8b9; color: #000000;">[[.member_code.]]</th>
                    <th style="text-align: center; background: #fff8b9; color: #000000;">[[.group_traveller.]]</th>
                    <th style="text-align: center;">[[.member_level.]]</th>
                    <th style="text-align: center;">[[.status_traveller.]]</th>
                    <th style="width: 50px; text-align: center;">[[.edit.]]</th>
                    <th style="width: 50px; text-align: center; display: none;">[[.delete.]]</th>
                </tr>         
                <?php $stt=1; ?>         
                <!--LIST:items-->
                <tr>
                    <td style="text-align: center; display: none;"><input name="check_list_[[|items.id|]]" type="checkbox" id="check_list_[[|items.id|]]" onclick="fun_check_id([[|items.id|]]);" /></td>
                    <td style="text-align: center;"><?php echo $stt++; ?></td>
                    <td style="width: 50px;"><img src="packages/hotel/packages/golf/modules/GolfCaddie/avata/[[|items.image_profile|]]" style="width: 45px; height: 45px; border-radius: 50%;" /></td>
                    <td style="text-align: left;">[[|items.full_name|]]</td>
                    <td style="text-align: center;"><?php if([[=items.gender=]]==1){?>[[.male.]] <?php }else{ ?>[[.female.]]<?php } ?></td>
                    <td style="text-align: center;">[[|items.name_2|]]</td>
                    <td style="text-align: center;">[[|items.email|]]</td>
                    <td style="text-align: center;">[[|items.phone|]]</td>
                    <td style="text-align: center; background: #fffcdf; font-weight: bold;"><?php if([[=items.member_code=]]!=''){?>[[|items.member_code|]]<?php }else{ ?>[[.no_code.]]<?php } ?></td>
                    <td style="text-align: center; background: #fffcdf; font-weight: bold;">[[|items.group_traveller_name|]]</td>
                    <td style="text-align: center;">[[|items.def_name|]]</td>
                    
                    <td style="text-align: center;">[[|items.status_traveller_name|]]</td>
                    <td style="text-align: center;"><?php if(User::can_edit(false,ANY_CATEGORY)){ ?><a href="?page=traveller&reservation_id=&cmd=edit&id=[[|items.id|]]"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_edit.png" style="width: 20px; height: auto;" /></a><?php } ?></td>
                    <td style="text-align: center; display: none;"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_delete.png" style="width: 20px; height: auto; cursor: pointer;" onclick="fun_delete_id([[|items.id|]]);" /></td>
                </tr>
                <!--/LIST:items-->      
            </table>
            <div style="width: 100%; margin: 10px auto; float: left;">[[|paging|]]</div>
        </div>
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
    function fun_check_id(id)
    {
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