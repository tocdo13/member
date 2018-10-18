<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.product_require_list.]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            </td>
        </tr>
    </table>        
    
    <div class="content">
        <fieldset style="font-weight: normal !important;">
            <legend class="title">[[.search.]]</legend>
            [[.from_date.]]
            <input name="from_date" type="text" id="from_date"  style="width: 80px; height: 24px;"/>
            [[.to_date.]]
            <input name="to_date" type="text" id="to_date"  style="width: 80px; height: 24px;"/>
            [[.department.]]:
            <select name="department_id" id="department_id" style="width: 150px; height: 24px;">
            [[|department_list|]]
            </select> 
            <input name="search" type="submit" value="[[.search.]]" style=" height: 24px;" />
        </fieldset>
        
        <br />
        
        <table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr style="text-transform: uppercase; height: 40px; text-align: center;">
                <th>[[.stt.]]</th>
                <th>[[.product_id.]]</th>
                <th>[[.product_name.]]</th>
                <th>[[.department.]]</th>
                <th>[[.quantity_require.]]</th>
                <th>[[.status.]]</th>
                <th>[[.recommend_date.]]</th>
                <th>[[.delivery_date.]]</th>
                
                <th>[[.order_code.]]</th>
                <th>[[.quantity_confirm.]]</th>
                <th>[[.status.]] [[.order.]]</th>
                
                <th>[[.note.]]</th>
            </tr>         
            <!--LIST:items-->
            <?php $count_child = [[=items.count_child=]]==0?1:[[=items.count_child=]]; ?>
            <tr>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.stt|]]</td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.product_id|]]</td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: left;">[[|items.product_name|]]</td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.department_name|]]</td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.quantity|]]</td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">
                    <?php
                    switch ([[=items.order_status=]]) {
                        case 'CREATED':
                             echo 'Đã tạo đơn hàng';
                             break;
                        case 'HEAD_DEPARTMENT':
                             echo 'Trưởng BP đã duyệt';
                             break;
                        case 'MOVE':
                             echo 'Điều chuyển';
                             break;
                        case 'DEPARTMENT_MOVE':
                             echo 'Trưởng BP đã duyệt và điều chuyển';
                             break;
                        case 'COMPLETE':
                             echo 'Đã mua hàng';
                             break;
                        case 'CANCEL':
                             echo 'Hủy bỏ';
                             break;
                         default:
                            echo 'Chờ Trưởng BP duyệt';
                             break;
                     } 
                    ?>
                </td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.recommend_date|]]</td>
                <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.delivery_date|]]</td>
                <?php if([[=items.count_child=]]>0){ $child_id = ''; ?>
                <!--LIST:items.child--> 
                    <?php $child_id=[[=items.child.id=]]; ?>
                        <td style="text-align: center;">[[|items.child.order_code|]]</td>
                        <td style="text-align: center;">[[|items.child.quantity|]]</td>
                        <td style="text-align: center;">
                            <?php
                            switch ([[=items.child.order_status=]]) {
                                case 'CREATED':
                                     echo 'Đã tạo đơn hàng';
                                     break;
                                case 'ACCOUNTANT':
                                     echo 'KT trưởng đã duyệt';
                                     break;
                                case 'DIRECTOR':
                                     echo 'Giám đốc đã duyệt';
                                     break;
                                case 'HEAD_DEPARTMENT':
                                     echo 'Trưởng BP đã duyệt';
                                     break;
                                case 'MOVE':
                                     echo 'Điều chuyển';
                                     break;
                                case 'DEPARTMENT_MOVE':
                                     echo 'Trưởng BP đã duyệt và điều chuyển';
                                     break;
                                case 'COMPLETE':
                                     echo 'Đã mua hàng';
                                     break;
                                case 'CANCEL':
                                     echo 'Hủy bỏ';
                                     break;
                                 default:
                                     echo 'Đã tạo đơn hàng';
                                     break;
                             } 
                            ?>
                        </td>
                        <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.note|]]</td>
                    </tr>
                    <?php break; ?>
                <!--/LIST:items.child--> 
                <!--LIST:items.child--> 
                    <?php if($child_id!=[[=items.child.id=]]){ ?>
                    <tr>
                        <td style="text-align: center;">[[|items.child.order_code|]]</td>
                        <td style="text-align: center;">[[|items.child.quantity|]]</td>
                        <td style="text-align: center;">
                            <?php
                            switch ([[=items.child.order_status=]]) {
                                case 'CREATED':
                                     echo 'Đã tạo đơn hàng';
                                     break;
                                case 'ACCOUNTANT':
                                     echo 'KT trưởng đã duyệt';
                                     break;
                                case 'DIRECTOR':
                                     echo 'Giám đốc đã duyệt';
                                     break;
                                case 'HEAD_DEPARTMENT':
                                     echo 'Trưởng BP đã duyệt';
                                     break;
                                case 'MOVE':
                                     echo 'Điều chuyển';
                                     break;
                                case 'DEPARTMENT_MOVE':
                                     echo 'Trưởng BP đã duyệt và điều chuyển';
                                     break;
                                case 'COMPLETE':
                                     echo 'Đã mua hàng';
                                     break;
                                case 'CANCEL':
                                     echo 'Hủy bỏ';
                                     break;
                                 default:
                                     echo 'Đã tạo đơn hàng';
                                     break;
                             } 
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                <!--/LIST:items.child--> 
                <?php }else{ ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td rowspan="<?php echo $count_child ?>" style="text-align: center;">[[|items.note|]]</td>
                </tr>
                <?php } ?>
            
            <!--/LIST:items--> 
        </table>
        <br />
    </div>
</form> 
</div>
<script type="text/javascript">
    jQuery('#from_date').datepicker();
    jQuery('#to_date').datepicker();
</script>