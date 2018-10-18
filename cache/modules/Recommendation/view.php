<style>
#main{
    width: 960px; 
    height: 595px;
    margin: 0 auto;
}
#main table tr td{
    line-height: 20px;
}
#main table tr th{
    line-height: 20px;
}
@media print{
    #hidden_print{
        display: none;
    }
    #payment_type{
        display: none;
    }
    #payment_type_lb{
        display: block;
    }
}
select:hover{
    border: 1px dashed #888;
}
</style>
<div id="main">
<table width="100%">
    <tr>
        <td width="85%">&nbsp;</td>
        <td align="right" style="vertical-align: bottom;" id="hidden_print">
            <a onclick="print_recommendation();" title="In">
                <img src="packages/core/skins/default/images/printer.png" height="40" />
            </a>
        </td>
    </tr>
</table>
    <table>
        <tr>
            <td width="320px" align="center"><img src="<?php echo HOTEL_LOGO; ?>" style="width: auto; height: 70px;" /></td>
            <td width="320px" align="center" style="font-size: 20px;"><strong>PHIẾU YÊU CẦU MUA HÀNG</strong><br /><span style="font-size: 12px;"><?php $date_create = explode("/",$this->map['recommend_date']); echo Portal::language('date')." ".$date_create[0]." ".Portal::language("month")." ".$date_create[1]." ".Portal::language('year')." ".$date_create[2]; ?></span></td>
            <td width="320px" align="center" style="font-size: 20px;"><strong><?php echo HOTEL_NAME;?><br /><span style="font-size: 12px;">Tel: <?php echo HOTEL_PHONE; ?> - MST: <?php echo HOTEL_TAXCODE; ?></strong></span><br /></strong></td>
        </tr>
    </table>
    <br />
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px;"><strong>Người yêu cầu: </strong><?php echo $this->map['person_recommend'];?></td>
            <td style="font-size: 12px;"><strong>Ngày yêu cầu: </strong><?php echo $this->map['recommend_date'] .' ' . $this->map['recommend_time'] ?></td>
        </tr>
        <tr>
            <td style="font-size: 12px;"><strong>Bộ phận: </strong><?php echo $this->map['department_name'];?></td>
            <td style="font-size: 12px;"><strong>Ngày giao hàng mong muốn: </strong><?php echo $this->map['delivery_date'];?></td>            
        </tr>
    </table>
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px;"><strong>Diễn giải: </strong><?php echo $this->map['description'];?></td>
        </tr>
    </table>
    <br />
    <table border="1" style="width: 100%; margin: 0px auto; border-collapse: collapse;">
        <tr style="text-align: center;font-size:12px;">
                <th rowspan="2">STT</th>
                <th rowspan="2">Mã hàng</th>
                <th rowspan="2">Tên hàng</th>
                <th rowspan="2">Tồn kho</th>
                <th colspan="2">Số lượng</th>
                <th rowspan="2">Ngày giao đề xuất</th>
                <th colspan="3">Báo giá của nhà cung cấp</th>
                <th rowspan="2">Số tiền</th>
                <th rowspan="2">Ghi chú</th>
            </tr>
            <tr style="text-align: center;font-size:12px;">
                <th>Yêu cầu</th>
                <th>Duyệt</th>
                <th>Giá cuối cùng</th>
                <th>Giá mong muốn</th>
                <th>Tên nhà cung cấp</th>
            </tr>
            <?php if(isset($this->map['products']) and is_array($this->map['products'])){ foreach($this->map['products'] as $key1=>&$item1){if($key1!='current'){$this->map['products']['current'] = &$item1;?>
            <tr style="font-size:12px;">
                <td style="text-align: center; font-size:12px;"><?php echo $this->map['products']['current']['index'];?></td>
                <td style="font-size:12px;"><?php echo $this->map['products']['current']['product_id'];?></td>
                <td style="font-size:12px;"><?php echo $this->map['products']['current']['product_name'];?></td>
                <td style="font-size:12px; text-align: right;"><?php echo $this->map['products']['current']['remain_department'];?></td>
                <td style="font-size:12px; text-align: right;"><?php echo $this->map['products']['current']['quantity'];?></td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"><?php echo $this->map['products']['current']['delivery_date'];?></td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"><?php echo $this->map['products']['current']['note'];?></td>
            </tr>
            <?php }}unset($this->map['products']['current']);} ?>
            <tr>
                <td colspan="10" style="font-size:12px; font-weight: bold;">TỔNG:</td>
                <td style="font-size:12px;"></td>
                <td style="font-size:12px;"></td>
            </tr>
            <tr>
                <td colspan="12" style="font-size:12px; font-weight: bold;">Bằng chữ:</td>
            </tr>
    </table>
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="text-align: left; width: 158px; font-size: 12px;"><strong>Phương thức thanh toán: </strong></td>
            <td style="text-align: left;font-size: 12px;">
                <select  name="payment_type" id="payment_type" style="height: 25px;" onchange="change_payment()"><?php
					if(isset($this->map['payment_type_list']))
					{
						foreach($this->map['payment_type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_type',isset($this->map['payment_type'])?$this->map['payment_type']:''))
                    echo "<script>$('payment_type').value = \"".addslashes(URL::get('payment_type',isset($this->map['payment_type'])?$this->map['payment_type']:''))."\";</script>";
                    ?>
	</select>
                <label id="payment_type_lb" style="display: none;"></label>
            </td>
        </tr>
    </table>
    <br /><br /><br /><br /><br />
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="font-size: 12px; widtth: 25%; text-align: center;"><strong>Người yêu cầu</strong></td>
            <td style="font-size: 12px; widtth: 25%; text-align: center;"><strong>Trưởng phòng thu mua</strong></td>
            <td style="font-size: 12px; widtth: 25%; text-align: center;"><strong>Kế toán trưởng</strong></td>
            <td style="font-size: 12px; widtth: 25%; text-align: center;"><strong>Giám đốc</strong></td>
        </tr>
    </table>
    <div id="footer">
        <span style="font-size: 12px; font-style: italic; position: fixed; bottom: 0;">Lưu ý: Báo giá phải đính kèm theo phiếu mua hàng và lưu bởi bộ phận Kế toán</span>
    </div>
</div>
<script>
jQuery("#chang_language").css('display','none');
jQuery(document).ready(function(){
    var payment_type = jQuery('select[name=payment_type]').val();
    jQuery('#payment_type_lb').html(payment_type);
})
function change_payment()
{
    var payment_type = jQuery('select[name=payment_type]').val();
    jQuery('#payment_type_lb').html(payment_type);
}
function print_recommendation()
{
    var user ='<?php echo User::id(); ?>';
    jQuery('#payment_type_lb').css('display','block');
    printWebPart('printer',user);
    window.location.reload();
}
</script>