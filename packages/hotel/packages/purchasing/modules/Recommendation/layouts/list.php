<style>
.simple-layout-middle{
    width: 100%;
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.require_list.]]</td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                <a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none;">[[.Add.]]</a>
                <!--<a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListSupplierForm.cmd.value='delete_group';ListSupplierForm.submit();"  class="button-medium-delete">[[.Delete.]]</a>-->
            </td>
        </tr>
    </table>        
    
    <div class="content">
        <fieldset style="font-weight: normal !important;">
            <legend class="title">[[.search.]]</legend>
            [[.recomment_date.]]
            <input name="date" type="text" id="date"  style="width: 80px; height: 24px;"/>
            [[.department.]]:
            <select name="department_id" id="department_id" style="width: 150px; height: 24px;">
            [[|department_list|]]
            </select>
            [[.status.]]
            <select name="status" id="status" style="width: 100px; height: 24px;">
            [[|status_list|]]
            </select>
            [[.user_status.]]
            <select style="width: 100px; height: 24px;" name="user_status" id="user_status">
                <option value="1">Active</option>
                <option value="0">All</option>
            </select>
            [[.person_recomment.]]
            <select name="person_recomment" id="person_recomment" style="width: 100px; height: 24px;"></select>
            [[.who_confirm.]]
            <select name="who_confirm" id="who_confirm" style="width: 100px; height: 24px;"></select>
            <input name="search" type="submit" value="[[.search.]]" style=" height: 24px;" />
        </fieldset>
        
        <br />
        <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30">[[.stt.]]</th>
                <th width="100" align="center">[[.recomment_date.]]</th>
                <th width="150" align="center">[[.person_recomment.]]</th>
                <th width="100" align="center">[[.department.]]</th>
                <!--<th width="10%" align="center">[[.status.]]</th>-->
                <th width="200" align="center">[[.description.]]</th>
                <th width="150" align="center" style="white-space: nowrap;">[[.last_edit_user.]]</th>
                <th width="100" align="center">[[.head_of_department.]]</th>
                <th width="150" align="center" style="white-space: nowrap;">[[.who_confirm.]]</th>
                <th width="100" align="center">[[.unhead_of_department.]]</th>
                <th width="150" align="center">[[.who_unconfirm.]]</th>
                <th width="150" align="center">[[.reason_cancellation.]]</th>
                <th width="40" align="center">[[.copy.]]</th>
                <th width="40" align="center">[[.view.]]</th>
                <th width="40" align="center">[[.edit.]]</th>
                <th width="40" align="center">[[.delete.]]</th>
            </tr>
            <!--LIST:items-->
            <tr <?php echo [[=items.index=]]%2==0?' style="background-color: #E8F3FF;"':''?>>
                <td align="center">
                    <input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/>
                </td>
                <td align="center">[[|items.index|]]</td>
                <td align="center">[[|items.recommend_date|]]</td>
                <td>[[|items.recommend_person|]]</td>
                <td>[[|items.department|]]</td>
                <!--
                <td>
                <?php
                switch([[=items.status=]])
                {
                    case 'MOVE':
                    {
                        echo 'ĐIỀU CHUYỂN';
                        break;
                    }
                    default:
                    {
                        echo '';
                        break;
                    }
                } 
                ?>
                </td>
                -->
                <td style="cursor: pointer;">[[|items.description|]]</td>
                <td style="cursor: pointer; text-align: center;">[[|items.last_use|]]</td>
                <td style="text-align: center;">
                <?php
                if(User::can_admin(2071,'1182000000000000000')) 
                {
                    ?>
                    <input type="button" class="w3-btn w3-gray" name="confirm_[[|items.id|]]" id="confirm_[[|items.id|]]"  onclick="confirm_head_of_department(this.name);" <?php echo empty([[=items.confirm=]])==false? ' value="Đã xác nhận" disabled="disabled" style="background: lime;"':' value="Xác nhận" style="cursor: pointer;"';?>  /> 
                    <?php 
                }
                ?>
                </td>
                <td align="center" style="white-space: nowrap;">
                    <?php echo ([[=items.time_confirm=]])?date('d/m/Y H:i', [[=items.time_confirm=]]). ' <br /><b>' . [[=items.person_confirm=]]:''. ' ' . [[=items.person_confirm=]].'</b>'; ?>
                </td>
                <td style="text-align: center;">
                <?php
                if(User::can_admin(2071,'1182000000000000000')) 
                {
                    ?>
                    <input type="button" class="w3-btn w3-red" name="unconfirm_[[|items.id|]]" id="unconfirm_[[|items.id|]]"  onclick="var answer = prompt('Vui lòng nhập lý do hủy xác nhận đơn hàng này!', ''); if(answer ==''){ alert('Bạn chưa nhập lý do hủy đơn hàng này!');}else{var str= this.name.split('_');var id = str[1];document.getElementById('id_unconfirm').value=id;document.getElementById('reason_cancellation').value=answer;ListSupplierForm.submit();};" value="Hủy" style="cursor: pointer; display: <?php echo (empty([[=items.confirm=]])==true or (empty([[=items.confirm=]])==false && [[=items.order_id=]] !=''))?'none;':'';?>;"  /> 
                    <?php 
                }
                ?>
                </td>
                <td align="center" style="white-space: nowrap;">
                    <?php echo ([[=items.time_unconfirm=]])?date('d/m/Y H:i', [[=items.time_unconfirm=]]). ' <br /><b>' . [[=items.person_unconfirm=]]:''. ' ' . [[=items.person_unconfirm=]].'</b>'; ?>
                </td>
                <td align="left">
                    [[|items.reason_cancellation|]]
                </td>
                <td align="center" >

                <?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'copy','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/copy.png" /></a><?php }?></td>

                <td align="center"><?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'view','id'=>[[=items.id=]]));?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 20px; padding-top: 2px;"></i></a><?php }?></td>
                
                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){ if(empty([[=items.confirm=]])==true){?><a  target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top: 2px;"></i></a><?php }}?></td>
                <td align="center"><?php if(User::can_delete(false,ANY_CATEGORY)){ if(empty([[=items.confirm=]])==true){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a><?php }}?></td>
            </tr>
          <!--/LIST:items-->            
        </table>
        <br />
        <div class="paging">[[|paging|]]</div>
    </div>
    <input name="cmd" type="hidden" value="" />
    <input name="id_confirm" id="id_confirm" type="hidden" value="0" />
    <input name="person_confirm" id="person_confirm" type="hidden" value="<?php echo [[=person_confirm=]]; ?>" />
    <input name="id_unconfirm" id="id_unconfirm" type="hidden" value="0" />
    <input name="reason_cancellation" id="reason_cancellation" type="hidden" value="" />
</form> 
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        <!--LIST:items-->
        var confirm = jQuery('#confirm_'+[[|items.id|]]).val();
        if(confirm == 'Đã xác nhận')
        {
            jQuery('#edit_'+[[|items.id|]]).css('display','none');                            
        }
        <!--/LIST:items-->
    })
    jQuery("#delete_button").click(function (){
        ListCustomerForm.cmd.value = 'delete';
        ListCustomerForm.submit();
    });
    jQuery(".delete-one-item").click(function (){
        if(!confirm('[[.are_you_sure.]]')){
            return false;
        }
    });
    jQuery("#all_item_check_box").click(function (){
        var check  = this.checked;
        jQuery(".item-check-box").each(function(){
            this.checked = check;
        });
    });
    
    jQuery("#date").datepicker();
    function confirm_head_of_department(name)
    {
        var conf = confirm('Bạn chắc chắn muốn xác nhận đề xuất mua hàng ?');
        if(conf == true)
        {
            var str= name.split('_');
            var id = str[1];
            document.getElementById('id_confirm').value=id;
            ListSupplierForm.submit();
        }else
        {
            return false;
        }
    }
    // 7211
    var users = <?php echo String::array2js([[=users=]]);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#person_recomment').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>'); 
                  jQuery('#who_confirm').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>'); 
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
</script>
