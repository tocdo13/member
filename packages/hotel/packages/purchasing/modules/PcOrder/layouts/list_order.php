<style>
@media print{
    #note, #searchForm{
        display: none;
    }
}

#searchForm{
    width: 100%;
    margin: 0 auto;
}

#searchForm table tr td{
    line-height: 20px;
}

#searchForm input{
    height: 25px;
    border: 1px solid #888;
}
#searchForm select{
    height: 25px;
    border: 1px solid #888;
}
#searchForm input:hover{
    border: 1px dashed #000;
}
#searchForm select:hover{
    border: 1px dashed #000;
}

#search{
    background-color: #009688;
    color: #FFF;
}

#note{
    width: 80%;
    margin: 0 auto;
}

#note span{
    font-size: 14px;
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.order_created.]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            </td>
        </tr>
    </table>
    <div id="searchForm">
        <fieldset style="border: 1px solid #333;">
            <legend>[[.search.]]</legend>
            <table>
                <tr style="text-align: center;">
                    <td>[[.invoice_code.]]</td>
                    <td><input name="invoice_code" type="text" id="invoice_code" style="width: 80px;" /></td>
                    <td>[[.invoice_name.]]</td>
                    <td><input name="invoice_name" type="text" id="invoice_name" style="width: 120px;"/></td>
                    <td  align="left" nowrap="nowrap">[[.user_status.]]</td>
    			    <td>
                      <!-- 7211 -->  
                      <select style="width: 130px;" name="user_status" id="user_status">
                        <option value="1">Active</option>
                        <option value="0">All</option>
                      </select>
                      <!-- 7211 end--> 
                    </td>
                    <td>[[.creater.]]</td>
                    <td><select name="create_user" id="create_user" style="width: 130px;"></select></td>
                    <td>[[.status.]]</td>
                    <td><select name="status" id="status" style="width: 100px;"></select></td>
                    <td>[[.from_date.]]</td>
                    <td><input name="from_date" type="text" id="from_date" style="width: 70px;"/></td>
                    <td>[[.to_date.]]</td>
                    <td><input name="to_date" type="text" id="to_date" style="width: 70px;" /></td>
                    <td>[[.supplier.]]</td>
                    <td><input name="supplier_name" type="text" id="supplier_name" onfocus="Autocomplete();" style="width: 150px;" /></td>
                    <td></td>
                    <td><input name="search" type="submit" id="search" value="[[.search.]]" /></td>
                </tr>

            </table>
        </fieldset>
    </div>
    <br /> 
    <?php 
        $create = 0;$accountant = 0;$director = 0;$complete = 0;$apart_close = 0; $close = 0; $cancel = 0;
    ?>
    <!--LIST:items-->
    <?php
        if([[=items.status=]] =='CREATED'){$create++;}if([[=items.status=]] =='ACCOUNTANT'){$accountant++;}if([[=items.status=]] =='DIRECTOR'){$director++;}if([[=items.status=]] =='COMPLETE'){$complete++;}if([[=items.status=]] =='APART_CLOSE'){$apart_close++;}if([[=items.status=]] =='CLOSE'){$close++;}if([[=items.status=]] =='CANCEL'){$cancel++;}
    ?>
    <!--/LIST:items--> 
    <div id="note" style="text-align: center;">      
        <input name="CREATE" value="Đã tạo đơn hàng (<?php echo $create; ?>)" readonly="" style="width: 145px; height: 25px; background-color: #92d050; border: none; padding-left: 2px;"/>
        <input name="ACCOUNTANT" value="KT trưởng đã duyệt (<?php echo $accountant; ?>)" readonly="" style="width: 160px; height: 25px; background-color: #b1a0c7; border: none;padding-left: 2px;"/>
        <input name="DIRECTOR" value="Giám đốc đã duyệt (<?php echo $director; ?>)" readonly="" style="width: 158px; height: 25px; background-color: #ff0000; border: none;padding-left: 2px;"/>
        <input name="COMPLETE" value="Đã mua hàng (<?php echo $complete; ?>)" readonly="" style="width: 123px; height: 25px; background-color: #ffc000; border: none;padding-left: 2px;"/>
        <input name="APART_CLOSE" value="Đã đóng 1 phần (<?php echo $apart_close; ?>)" readonly="" style="width: 138px; height: 25px; background-color: #e26b0a; border: none;padding-left: 2px;"/>
        <input name="CLOSE" value="Đã đóng (<?php echo $close; ?>)" readonly="" style="width: 88px; height: 25px; background-color: #974706; border: none;padding-left: 2px;"/>
        <input name="CANCEL" value="Hủy bỏ (<?php echo $cancel; ?>)" readonly="" style="width: 88px; height: 25px; background-color: #8db4e2; border: none;padding-left: 2px;"/>
    </div>
    <div class="content">
        <br />
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border-collapse: collapse;">
            <tr class="w3-light-gray" style="line-height: 24px; text-transform: uppercase;">
                <th width="30">[[.stt.]]</th>
                <th width="100" align="center">[[.invoice_code.]]</th>
                <th width="150" align="center">[[.invoice_name.]]</th>
                <th width="150" align="center">[[.creater.]]</th>
                <th width="150" align="center">[[.last_edit.]]</th>
                <th width="100" align="center">[[.status.]]</th>
                <th width="150" align="center">[[.user_cancel.]]</th>
                <th width="250" align="center">[[.supplier.]]</th>
                <th width="100" align="center">[[.total_amount.]]</th>
                <th width="250" align="center">[[.description.]]</th>
                <th width="40" align="center">[[.view.]]</th>
                <th width="40" align="center">[[.edit.]]</th>
            </tr>
            <!--LIST:items-->
            <tr <?php echo [[=items.index=]]%2==0?' style="background-color: #E8F3FF;"':''?>>
                
                <td align="center">[[|items.index|]]</td>
                <td align="center">[[|items.code|]]</td>
                <td>[[|items.name|]]</td>
                <td align="center">[[|items.creater|]] [[|items.create_time|]]</td>
                <td align="center">[[|items.last_edit_user|]] [[|items.last_edit_time|]]</td>
                <td align="center" style="background-color: #<?php if([[=items.status=]]=='CREATED'){echo '92d050';}if([[=items.status=]]=='ACCOUNTANT'){echo 'b1a0c7';}if([[=items.status=]]=='DIRECTOR'){echo 'ff0000';}if([[=items.status=]]=='COMPLETE'){echo 'ffc000';}if([[=items.status=]]=='APART_CLOSE'){echo 'e26b0a';}if([[=items.status=]]=='CLOSE'){echo '974706';}if([[=items.status=]]=='CANCEL'){echo '8db4e2';} ?>">
                <?php
                switch ([[=items.status=]]) {
                    case 'CREATED':
                         echo 'Đã tạo đơn hàng';
                         break;
                    case 'ACCOUNTANT':
                         echo 'KT trưởng đã duyệt';
                         break;
                    case 'DIRECTOR':
                         echo 'Giám đốc đã duyệt';
                         break;
                    
                    case 'COMPLETE':
                         echo 'Đã mua hàng';
                         break;
                    case 'CANCEL':
                         echo 'Hủy bỏ';
                         break;
                    case 'CLOSE':
                        echo 'Đã đóng';
                         break;
                    case 'APART_CLOSE':
                        echo 'Đã đóng 1 phần';
                         break;
                     default:
                        echo '';
                         break;
                 } 
                ?>
                </td>

                <td>
                    [[|items.cancel_user|]]
                    <br/>
                    [[|items.time_cancel|]]
                    <br/>
                    [[|items.note_cancel|]]
                </td>
                <td>[[|items.supplier_name|]]</td>
                <td align="right">[[|items.total|]]</td>
                <td>[[|items.description|]]</td>
                <td style="text-align: center;"><?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'print_order','id'=>[[=items.id=]]));?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a><?php }?></td>
                <td style="text-align: center;">
                <?php 
                if([[=items.status=]]=='CANCEL')
                {
                    ?>
                    <a href="?page=pc_order&cmd=edit_cancel&id=[[|items.id|]]"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
                    <?php 
                }
                ?>
                </td>
            </tr>
          <!--/LIST:items-->            
        </table>
        <br />  
        <div class="paging">[[|paging|]]</div> 
    </div>
</form> 
</div>
<script>
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();
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
                  jQuery('#create_user').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
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
function Autocomplete()
{
    jQuery("#supplier_name").autocomplete({
       url:'get_customer1.php?supplier=1' 
    });
}
</script>