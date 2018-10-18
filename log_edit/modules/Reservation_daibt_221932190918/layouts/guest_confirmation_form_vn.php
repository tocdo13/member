<style>
.table_bound{
	border:1px solid #CCCCCC; 
margin-top:25px;
	}
#guest_name_lbl{
        display: none;
    }
	p{
		margin:0px;
		padding:0px;
	}
    @media print {
        #guest_name
        {
            display: none;
            
        }
        #guest_name_lbl
        {
            display: block;
        }
       .table_bound{border:0px;}
}
</style>
<!--LIST:guest--><table width="756" border="0" cellspacing="0" cellpadding="0" class="table_bound">
  <tr>
    <td align="center"><h1><img src="<?php echo HOTEL_LOGO;?>" width="150px" style="float:left;margin-bottom:10px;">PHIẾU XÁC NHẬN ĐẶT PHÒNG</h1></td>
  </tr>
  <tr>
    <td>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		  <tr>
			<td width="375" colspan="4" valign="top">Cơ quan: <strong>[[|customer_name|]]</strong></td>
			<td width="375" colspan="4" valign="top">Địa chỉ: <strong>[[|customer_address|]] </strong></td>
		  </tr>
          <tr>
			<td width="200" colspan="2" valign="top">
			 Điện thoại: <strong>[[|customer_phone|]]</strong></td>
			<td width="200" colspan="2" valign="top">Fax: <strong>[[|customer_fax|]]</td>
			<td width="200" colspan="2"  valign="top">Email: <strong>[[|customer_email|]]</strong></td>
            <td width="150" colspan="2"  valign="top">Mã đặt: <strong>[[|reservation_id|]]</strong></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">Rất cảm ơn Quý khách đã quan tâm đến Khách sạn Vĩnh Hoàng, chúng tôi xin được xác nhận thông tin đặt phòng của quý khách với những thông tin sau:</td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top"><strong>ĐẶT MỚI
			  <input name="Input312" type="checkbox" value="" />
			</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<strong>ĐIỀU CHỈNH
			<input name="Input313" type="checkbox" value="" />
			</strong>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>HỦY
			<input name="Input314" type="checkbox" value="" />
			</strong></td>
		  </tr>
          <tr>
			<td width="350" colspan="4" valign="top">
			  Tên khách: <strong><form name="GuestRegistrationCardForm" method="post">
                                            <label id="guest_name_lbl">[[|full_name|]]</label>
                                            <select name="guest_name" id="guest_name" onchange="check_traveller(this);"></select>
                                         </form></strong></td>
			<td width="350" colspan="4" valign="top">Giới tính: <strong><?php if([[=gender=]]!=2){echo [[=gender=]]?portal::language('male'):portal::language('female');}else{ echo portal::language('male').'/'.portal::language('female');} ?></strong></td>
			</tr>
		  <tr>
			<td width="375" colspan="4" valign="top">Ngày giờ đến: <strong>[[|arrival_time|]]</strong></td>
			<td width="375" colspan="4" valign="top">Ngày giờ đi: <strong>[[|departure_time|]]</strong></td>
			</tr>
          <tr>
          <tr>
			<td width="250" colspan="2" valign="top">Loại phòng: <strong>[[|room_level|]]</strong></td>
			<td width="250" colspan="2" valign="top">Số phòng: <strong>[[|room_name|]]</strong></td>
            <td width="250" colspan="4" valign="top" align="left">Giá: <input name="room_rate" type="text" style="border:0px;width:130px;font-weight:bold;" value="<?php echo [[=show_price=]]?[[=room_rate=]]:[[=reservation_type=]];?>" /><?php echo [[=show_price=]]?'('.HOTEL_CURRENCY.')':'';?></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">- ++ <i>Vui lòng cộng 10% VAT và 5% phí phục vụ</i><br />
            - <i>Giá phòng bao gồm ăn sáng buffet, sử dụng hồ bơi, phòng tập thể dục và wifi miễn phí</i></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">Yêu cầu đón: <strong>
			  <input name="Input312" type="checkbox" value="" />
			</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Phí đưa đón: .............................................</td>
		  </tr>
          <tr>
			<td width="375" colspan="4" valign="top">Số hiệu chuyến bay đến: <strong>[[|flight_code|]]</strong></td>
			<td width="375" colspan="4" valign="top">Giờ đáp/hạ cánh: <strong><?php echo [[=flight_arrival_time=]]?date('d/m/Y H:i',[[=flight_arrival_time=]]):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="300" colspan="4" valign="top">Số hiệu chuyến bay đi: <strong>[[|flight_code_departure|]]</strong></td>
			<td width="450" colspan="4" valign="top">Giờ bay/cất cánh: <strong><?php echo [[=flight_departure_time=]]?date('d/m/Y H:i',[[=flight_departure_time=]]):'';?></strong></td>
		  </tr>
		  <tr>
			<td width="375" colspan="3"  valign="top"><strong>Phương thức thanh toán:&nbsp; </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					<strong>Khách tự trả</strong>
			  
			  <input name="Input36" type="checkbox" value="" /></td>
			<td width="375" colspan="5" valign="top">
				<strong><br />
				Cơ quan thanh toán ngay khi khách trả phòng</strong>
			    <br />
		        <strong>
		        <input name="Input39" type="checkbox" value="" />
		        </strong>Tiền phòng &nbsp;&nbsp;
			    <strong>
			    <input name="Input310" type="checkbox" value="" />
			    </strong>Toàn bộ chi phí &nbsp;&nbsp;
			    <strong>
			    <input name="Input311" type="checkbox" value="" />
			    </strong>Khác ...............</td>
			
		  </tr>
		  <tr>
			<td width="756" colspan="8" valign="top">Yêu cầu đặc biệt: Nếu có yêu cầu xuất hóa đơn tài chính, đề nghị Quý vị điền đầy đủ chi tiết như sau:</td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top">- Tên công ty: ..........................................................................................................................................<br />
			  - Tên khách: ............................................................................................................................................<br />
			  - Địa chỉ: .................................................................................................................................................<br />
			  - Mã số thuế: ...........................................................................................................................................<br /></td>
		  </tr>
		  <tr>
			<td width="756" colspan="8" valign="top"><strong>Xin vui lòng lưu ý:</strong><br /><i>
            1. Giờ nhận phòng: 14.00, giờ trả phòng: 12.00. Nhận phòng sớm từ 6.00 đến trước 12.00 hay trả phòng trễ trước 18.00, khách sạn sẽ phụ thu 50% giá phòng. Nhận phòng trước 6.00 hay trả phòng sau 18.00, khách sạn sẽ phụ thu 100% giá phòng.<br />
            2. Mọi yêu cầu đặt phòng, trừ khi có đảm bảo, sẽ được giữ đến 4.00 chiều của ngày khách sự kiến đến. Trong thời gian cao điểm, khách sạn chỉ ưu tiên giữ các đặt phòng có đảm bảo.<br />
            3. Quý khách có thể đảm bảo đặt phòng bằng tiền mặt hay thẻ tín dụng với số tiền tương đương một đêm phòng x số phòng đặt là ............................ . Nếu sử dụng thẻ tín dụng, xin vui lòng photo hộ chiếu hay chứng minh nhân dân, photo 2 mặt thẻ và cung cấp các chi tiết thẻ như sau:</i></td>
		  </tr>
          <tr>
			<td width="375" colspan="4" valign="top"><i>- Tên chủ thẻ:</i></td>
			<td width="375" colspan="4" valign="top"><i>- Loại thẻ:</i></td>
		  </tr>
          <tr>
			<td width="375" colspan="4" valign="top"><i>- Số thẻ:</i></td>
			<td width="375" colspan="4" valign="top"><i>- Ngày hết hạn:</i></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top"><i>
            4. Mọi yêu cầu sửa đổi, giảm số lượng phòng đặt, hủy phòng phải được thực hiện ít nhất 72 giờ, trước 12 giờ trưa ngày khách dự kiến đến. Trường hợp hủy, điều chỉnh không theo quy định trên hoặc khách không đến, không mang theo đầy đủ giấy tờ, khách sạn sẽ tính phí tương đương một đêm phòng nhân với số lượng phòng đặt.<br />
            5. Nếu khách sạn không nhận được xác nhận của quý khách trước ............. giờ, ngày .................. thì yêu cầu đặt phòng này của quý khách sẽ tự động bị hủy bỏ.</i></td>
		  </tr>
          <tr>
			<td width="756" colspan="8" valign="top"><strong>Đề nghị quý khách ký xác nhận các thông tin trên và gửi lại cho khách sạn</strong></td>
		  </tr>
		  <tr>
			<td width="375" colspan="4" valign="top">Nhân viên đặt phòng:<br />
		      <br />
		      </td>
            <td width="375" colspan="4" valign="top" align="right">Người đặt/Chủ thẻ:<br />
		      <br />
		      </td>
			
		  </tr>
		</table>
		<p align="center"><?php echo HOTEL_ADDRESS;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel:&nbsp;<strong><?php echo HOTEL_PHONE;?></strong>&nbsp;&nbsp;&nbsp;&nbsp; Fax:&nbsp;<strong><?php echo HOTEL_FAX;?></strong>
        <br />
        Email: <?php echo HOTEL_EMAIL;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Website:&nbsp;<strong><?php echo HOTEL_WEBSITE;?></strong></p>
	
	</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!--/LIST:guest-->
<script>
	//$('male').checked = ([[|gender|]]=='1')?true:false;
	//$('female').checked = ([[|gender|]]=='10')?true:false;
    
    function check_traveller(obj){
         GuestRegistrationCardForm.submit();
    }
    function show_traveller(){
        jQuery("#traveller").css('display','block');
    }
    //console.log(list_items);
    
</script>