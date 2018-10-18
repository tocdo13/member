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
<form name="ListGolfCaddieForm" method="post">
    <div class="w3-row" style="max-width: 1200px; margin: 0px auto;">
        <table style="margin: 0px auto;" cellspacing="5" cellpadding="5">
                <tr>
                    <td>[[.full_name.]]:</td>
                    <td><input name="full_name" type="text" id="full_name" class="w3-input w3-border"  /></td>
                    <td>[[.passport.]]:</td>
                    <td><input name="passport" type="text" id="passport" class="w3-input w3-border" /></td>
                    <td rowspan="4" style="text-align: center;">
                        <input name="do_search" type="submit" id="do_search" value="[[.search.]]" class="w3-button w3-border w3-blue" /><br /><br />
                    </td>
                </tr>
                <tr>
                    <td>[[.country.]]:</td>
                    <td><select name="country" id="country" class="w3-input w3-border"></select></td>
                    <td>[[.gender.]]:</td>
                    <td><select name="gender" id="gender" class="w3-input w3-border"></select></td>
                </tr>
                <tr>
                    <td>[[.email.]]:</td>
                    <td><input name="email" type="text" id="email" class="w3-input w3-border" /></td>
                    <td>[[.phone.]]:</td>
                    <td><input name="phone" type="text" id="phone" class="w3-input w3-border" /></td>
                </tr>
            </table>
    </div>
    <div class="w3-row" style="max-width: 1200px; margin: 0px auto;">
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <h3><img src="packages/hotel/packages/golf/includes/img/caddie.png" style="height: 40px; width: auto;" /> [[.list.]] [[.caddie.]]</h3>
            </div>
            <div class="w3-button w3-pink w3-hover-pink w3-margin w3-right" onclick="location.href='?page=golf_caddie_scheduler';" style="font-weight: normal;">
                <i class="fa fa-fw fa-calendar"></i> [[.caddie_scheduler.]]
            </div>
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="location.href='?page=golf_caddie&cmd=add';" style="font-weight: normal;">
                <i class="fa fa-fw fa-plus"></i> [[.add.]]
            </div>
        </div>
        <div style="max-width: 1200px; margin: 10px auto; float: left;">[[|paging|]]</div>
        <table class="w3-table-all">    
            <tr>
                <th style="width: 50px; text-align: center;">[[.STT.]]</th>
                <th style="width: 50px;"></th>
                <th style="">[[.full_name.]]</th>
                <th style="width: 50px; text-align: center;">[[.gender.]]</th>
                <th style="text-align: center;">[[.country.]]</th>
                <th style="text-align: center;">[[.email.]]</th>
                <th style="text-align: center;">[[.phone_number.]]</th>
                <th style="width: 50px; text-align: center;">[[.edit.]]</th>
                <th style="width: 50px; text-align: center; display: none;">[[.delete.]]</th>
            </tr>
            <?php $stt=1; ?>         
            <!--LIST:items-->
            <tr>
                <td style="text-align: center;"><?php echo $stt++; ?></td>
                <td style="width: 50px;"><img src="packages/hotel/packages/golf/modules/GolfCaddie/avata/[[|items.image_profile|]]" style="width: 45px; height: 45px; border-radius: 50%;" /></td>
                <td style="text-align: left;">[[|items.full_name|]]</td>
                <td style="text-align: center;"><?php if([[=items.gender=]]==1){?>[[.male.]] <?php }else{ ?>[[.female.]]<?php } ?></td>
                <td style="text-align: center;">[[|items.name_2|]]</td>
                <td style="text-align: center;">[[|items.email|]]</td>
                <td style="text-align: center;">[[|items.phone|]]</td>
                <td style="text-align: center;"><?php if(User::can_edit(false,ANY_CATEGORY)){ ?><a href="?page=golf_caddie&cmd=edit&id=[[|items.id|]]"><i class="fa fa-fw fa-pencil w3-text-blue"></i><?php } ?></td>
                <td style="text-align: center; display: none;"><?php if(User::can_delete(false,ANY_CATEGORY)){ ?><a href="?page=golf_caddie&cmd=edit&id=[[|items.id|]]"><i class="fa fa-fw fa-remove w3-text-red"></i><?php } ?></td>
            </tr>
            <!--/LIST:items-->      
        </table>
        <div style="max-width: 1200px; margin: 10px auto; float: left;">[[|paging|]]</div>
    </div>
</form>
<script>
    //jQuery("#create_date").datepicker();
</script>