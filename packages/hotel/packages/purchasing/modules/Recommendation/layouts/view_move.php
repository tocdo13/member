<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong>
        <br />
        
        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
        <br />
        [[.user_print.]]:<?php echo User::id();?>
       
         </td>
      </tr>
      
    </table></td>
  </tr>
</table>
<table style="margin: 10px auto;width: 800px; border: 1px solid #CCCCCC; border-collapse: collapse;" cellspacing="" cellpadding="2">
    
    <tr>
        <td colspan="4" style="text-align: center;">
            <h3 style="text-transform: uppercase; line-height: 25px; font-size: 20px;">[[.order_request.]]</h3>
        </td>
    </tr>
    
    <tr>
        <td style="width: 100px;">Họ tên</td>
        <td colspan="3">[[|person_recommend|]]</td>
    </tr>
    <tr>
        <td >Ngày đề xuất</td>
        <td colspan="3">[[|recommend_date|]] &nbsp;[[|recommend_time|]]
        </td>
    </tr>
    <tr>
        <td >Bộ phận</td>
        <td colspan="3">[[|department_name|]]</td>
    </tr>
    <tr>
        <td colspan="4">
            <table style="width: 100%; margin: 10px auto; border-collapse: collapse;" border="1" bordercolor="#DDDEEE" cellspacing="5" cellpadding="5">
                
                <tr style="text-align: center;">
                    <th style="text-align: center;">[[.stt.]]</th>
                    <th style="text-align: center;">[[.product_code.]]</th>
                    <th style="text-align: center;">[[.product_name.]]</th>
                    <th style="text-align: center;">[[.unit_name.]]</th>
                    <th style="text-align: center;">[[.quantity.]]</th>
                    <th style="text-align: center;">[[.remain_product.]]</th>
                    <th style="text-align: center;">[[.remain_total_other.]]</th>
                </tr>
                    
                <!--LIST:products-->
                <tr>
                    <td style="text-align: center;">[[|products.index|]]</td>
                    
                    <td>[[|products.id|]]</td>
                    <td>[[|products.product_name|]]</td>
                    <td>[[|products.unit|]]</td>
                    <td style="text-align: right;">[[|products.quantity|]]</td>
                    <td style="text-align: right;">[[|products.remain_department|]]</td>
                    <td style="text-align: right;">[[|products.remain_total|]]</td>
                </tr>
                <!--/LIST:products-->
                
            </table>
        </td>
    </tr>
</table>
<table style="margin: 10px auto;width: 800px;" cellspacing="" cellpadding="2">
    <tr style="text-align: center;">
        <td style="width: 25%;">Người lập phiếu</td>
        <td style="width: 25%;">Thủ kho</td>
        <td style="width: 25%;">Kế toán trưởng</td>
        <td style="width: 25%;">Giám đốc</td>
    </tr>
</table>
<script type="text/javascript">
    
</script>