<!--?php echo"tet11";?-->
<style>
    body {-webkit-print-color-adjust:exact;}
	BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P {font-size: 12pt;	}
.style1 {
	font-size: large; 
	font-weight: bold;
}
.styletable {
	font-family:"Times New Roman";
	vertical-align: top;	
}
.stylespace{
	margin-top: -12pt;
	line-height: 18pt;
	vertical-align: top;
	}
.styleli{
	margin-top: 0pt;
	line-height: 18pt;
	vertical-align: top;
	}
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" class="styletable">
    <tbody>
        <tr>
            <td colspan="9" valign="top"  style="height: 50px;background-image: url(<?php echo HOTEL_LOGO;?>);background-size: contain;background-repeat: no-repeat;padding-left: -12px;background-position: initial;background-position-x: 76px;">
			
                <p align="center">
                    <br /><strong><?php echo HOTEL_NAME;?></strong>                </p>
                <p align="center">Số/HĐ: [[|contract_code|]]</p>
          </td>
            <td colspan="9" valign="top">
                <p align="center">
                    Cộng Hòa Xã Hội Chủ Nghĩa Việt Nam<br>
                  Độc lập – Tự do – Hạnh phúc              </p>
          </td>
        </tr>
        <tr>
            <td width="669" colspan="18" valign="top">
                <p align="center"><strong>HỢP ĐỒNG PHỤC VỤ</strong></p>
                <p>
                    - Căn cứ Bộ Luật Dân sự đã được Quốc Hội Nước Cộng Hòa Xã Hội Chủ Nghĩa Việt Nam thông qua ngày 14 tháng 06 năm 2005 và có hiệu lực từ ngày
                    01 tháng 01 năm 2006.
                </p>
                <p>
                    - Căn cứ vào nhu cầu và khả năng của hai bên .
                </p>
              <p> Hôm  nay, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?>. Hai bên gồm:</p>
                <p>
                    <strong>BÊN A</strong>
                : <strong></strong><strong><?php echo HOTEL_NAME;?></strong></p>
               <blockquote>
               <p>Địa chỉ:       
                <?php echo HOTEL_ADDRESS;?></p>
               <p class="stylespace"> Điện thoại :             
                 <?php echo HOTEL_PHONE;?> </p>
                <p class="stylespace"> Fax : <?php echo HOTEL_FAX;?> </p>
                <p class="stylespace">  Mã số thuế :
                
                  <strong><?php echo HOTEL_TAXCODE;?> </strong> </p>
                <p class="stylespace">
                  Tài khoản :                </p>
                <p class="stylespace">VNĐ 102010002263533 tại vietinbank chi nhánh Đà Nẵng </p>
                
              <p><span class="stylespace">Đại diện bởi Ông/bà : <strong>[[|representative_hotel|]]</strong>. Chức vụ : <strong>[[|position_hotel|]]</strong>
              </span>
              </p>
              </blockquote>
                <p>
                    <strong>BÊN B:</strong><strong> [[|company_name|]]</strong>
                </p>
                <blockquote>
                <p>
                    Địa chỉ :
                [[|company_address|]]</p>
              <p>
                    Điện thoại :
              [[|company_phone|]]</p>
                <p>
                    Fax :
                </p>
                <p>
                    Mã số thuế :
                [[|company_tax_code|]]</p>
                <p>
                    Đại diện bởi Ông/Bà:
                
                      <strong>[[|representative_name|]]</strong>
                </p>
                <p>
                    Chức vụ :<strong> </strong><strong>[[|position|]]</strong>
                </p>
                </blockquote>
                <p>
                    Hai bên thỏa thuận ký hợp đồng phục vụ <strong>Tiệc Hội Nghị khách hàng</strong> với các điều khoản sau đây :
                </p>

                <p>
                    <strong><u>ĐIỀU I :</u></strong>
                    <strong>TRÁCH NHIỆM CỦA BÊN A </strong>
                </p>
                <p>
                    Chịu trách nhiệm phục vụ Bên B với chi tiết sau :
                </p>
              <p>
                    <strong>1. <u>PHÒNG HỌP :</u></strong>
              </p>
                <blockquote>
                <p>
                - Thời gian bắt đầu : <?php echo date('H:i',[[=meeting_checkin_hour=]]);?> ngày:  <?php echo date('d/m/20y',[[=meeting_checkin_hour=]]);?> </p>
                <p>- Thời gian kết thúc : <?php echo date('H:i',[[=meeting_checkout_hour=]]);?> ngày:  <?php echo date('d/m/20y',[[=meeting_checkout_hour=]]);?> </p>
                  <p>
                    - Địa điểm : Nhà hàng
                
                      <strong>[[|meeting_room_name|]]</strong>
                </p>
                <p>
                    - Địa chỉ : [[|meeting_room_address|]]
                </p>
                <p>
                    - Số khách :
                
                      <strong>[[|meeting_num_people|]]</strong>
                  Khách – Xếp ghế theo kiểu :[[|style_chairs|]]
              </p>
               <p>
                    - Giá thuê phòng họp : / nửa ngày ( Chưa bao gồm thuế VAT )
              </p>
              <p>
                    [[|meeting_room_price|]]/ <strong><em>ngày ( Chưa bao gồm thuế VAT )</em></strong>
              </p>
                <p>
                    ( Bao gồm :
                
                    -<strong> Phục vụ coffee break</strong> : <strong><em>( Đã bao gồm thuế VAT )</em></strong>
                </p>
                <p>
                    * Trà, cà phê : 26.000đ/người/lần
                </p>
              <p>
                    * Trà, cà phê, bánh ngọt hoặc trái cây : 39.000đ/ngươì/lần
              </p>
                <p>
                    - <strong>Phục vụ nước suối</strong> : 11.000đ/chai
                </p>
                
               <p>
                    <strong><u>ĐIỀU II:</u></strong>
                    <strong> TRÁCH NHIỆM CỦA BÊN B</strong>
              </p>
              <blockquote>
               <p>
                    1 - Thông báo cho Bên A số lượng khách chính thức trước 03 ngày để Bên A chuẩn bị.
              </p>
               <p>
                    2 - Thanh toán các chi phí trong hợp đồng như chi phí ăn uống , chi phí khác . Ngay sau khi Bên A thực hiện xong hợp đồng.
              </p>
              </blockquote>
                <p>
                    <strong><u>ĐIỀU III:</u></strong>
                    <strong> PHƯƠNG THỨC THANH TOÁN </strong>
                </p>
                <blockquote>
                 <p>
                    1 - Bên B phải tạm ứng trước cho Bên A số tiền là : <strong>[[|deposit_1|]]đ</strong>
                </p>
                <p>
2 - Sau khi kết thúc tiệc Bên B phải thanh toán cho bên A số tiền tiệc còn lại : [[|payment_info|]]                 
                </p>
                <p>
                    Nếu bên B thanh toán bằng thẻ thì sẽ chịu phí theo quy định của ngân hàng.
                </p>
                </blockquote>
                <p>
                    <strong><u>ĐIỀU IV:</u></strong>
                    <strong> ĐIỀU KHOẢN CHUNG</strong>
                </p>
               <blockquote>
               <p>
                   1. Hai bên cam kết thực hiện đúng các điều khoản đã ký trong hợp đồng. Trường hợp có thay đổi hoặc bổ sung thêm vào hợp đồng phải có sự bàn
                    bạc và nhất trí của cả hai bên bằng văn bản (Phụ lục hợp đồng).
              </p>
                <p>
                    2. Trường hợp Bên B hủy bỏ hợp đồng đã ký với Bên A thì Bên B phải có trách nhiệm bồi thường toàn bộ chi phí trên hợp đồng cho Bên A và
                    ngược lại.
                </p>
                <p>
                    3. Bên B có trách nhiệm thanh toán toàn bộ số tiền trong hợp đồng cho Bên A theo đúng thời gian quy định tại Điều III của hợp đồng. Nếu quá
                    thời hạn, bên B phải chịu phạt thêm lãi suất thanh toán chậm là 0,2%/ ngày trên tổng số tiền còn nợ.
                </p>
               <p>
                    4. Hợp đồng có hiệu lực từ ngày ký đến khi bên B thanh toán đầy đủ cho bên A thì hợp đồng mặc nhiên được thanh lý. Hợp đồng được lập hai
                    bản mỗi bên giữ một bản có giá trị như nhau để đối chiếu và thực hiện .
              </p>
              </blockquote>
              <p></p>
          </td>
        </tr>
        <tr>
            <td width="336" colspan="12" valign="top">
                <p align="center">
                    <strong>ĐẠI DIỆN BÊN B</strong>
                </p>
            </td>
            <td width="333" colspan="6" valign="top">
                <p align="center">
                    <strong>ĐẠI DIỆN BÊN A</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td width="336" colspan="12" valign="top">
                <p align="center">
                    <strong></strong>
                </p>
            </td>
            <td width="333" colspan="6" valign="top">
                <p align="center">
                    <strong></strong>
                </p>
            </td>
        </tr>
        <tr height="0">
            <td width="86">
            </td>
            <td width="37">
            </td>
            <td width="14">
            </td>
            <td width="14">
            </td>
            <td width="5">
            </td>
            <td width="38">
            </td>
            <td width="14">
            </td>
            <td width="36">
            </td>
            <td width="14">
            </td>
            <td width="12">
            </td>
            <td width="11">
            </td>
            <td width="55">
            </td>
            <td width="35">
            </td>
            <td width="25">
            </td>
            <td width="13">
            </td>
            <td width="41">
            </td>
            <td width="37">
            </td>
            <td width="181">
            </td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>
<table width="100%" border="0" align="LEFT" cellspacing="0" cols="11">
  <tr>
    <td width="508" height="26" align="CENTER" valign=TOP><p></p>
        <p><?php echo HOTEL_NAME;?> </p>
      <p><b><font face="Wingdings">¶¶¶¶</font></b></p></td>
    <td width="338" height="26" align="CENTER" valign=TOP><p>CÔNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p>Độc lập - Tự do - Hạnh phúc </p></td>
  </tr>
  <tr>
    <td colspan=2 height="26" align="CENTER" valign=TOP><div align="right"></div></td>
  </tr>
  <tr>
    <td colspan=2 height="561" align="left" valign=TOP><p align="right"><br>
            <em>Ngày.....tháng.....năm <?php echo date('Y');?>.</em></p>
        <p align="center"><strong>PHỤ LỤC HỢP ĐỒNG</strong></p>
      <p> Căn cứ hợp đồng số:  <?php echo date('Ym').'-'.Url::get('id');?>/HĐ Ngày <?php echo date('d');?> tháng<?php echo date('m');?>năm <?php echo date('Y');?> đã ký giữa <?php echo HOTEL_NAME;?> và  Ông/ Bà: <b><font>[[|full_name|]]</font></b></p>
      <p>Đại diện hai bên gồm:</p>
      <p><strong> Bên A: <?php echo HOTEL_NAME;?></strong></p>
      <p> Đại diện bởi Ông/Bà: [[|representative_hotel|]]</p>
      <p>Chức  vụ: [[|position_hotel|]]</p>
      <p><strong> Bên B: Đại diện bởi Ông / Bà:<b><font>[[|representative_name|]]</font></b></strong></p>
      <p>- Địa chỉ: <font>[[|company_address|]]</font></p>
      <p> - Điện thoại: <font>[[|home_phone|]]</font></p>
      <p> - Địa điểm tổ chức tiệc: <?php echo HOTEL_NAME;?> - [[|meeting_room_name|]]      </p>
      <p> - Thời gian: Từ <font face="VNI-Times"><?php echo date('H',[[=checkin_time=]]);?></font> giờ <font face="VNI-Times"><?php echo date('i',[[=checkin_time=]]);?></font> đến <font face="VNI-Times"><?php echo date('H',[[=checkout_time=]]);?></font> giờ <font face="VNI-Times"><?php echo date('i',[[=checkout_time=]]);?></font> ngày <b><font face="Vni-times"><?php echo date('d',[[=checkin_time=]]);?></font></b> tháng <b><font face="Vni-times"><?php echo date('m',[[=checkin_time=]]);?></font></b> năm <b><font face="Vni-times"><?php echo date('20y',[[=checkin_time=]]);?></font></b></p>
      <p> Sau khi bàn bạc thảo luận hai bên đồng ý ký phụ lục hợp đồng  với nội dung sau: </p>
      <p><strong> Tặng:</strong></p>
       <?php if(isset([[=promotions_list=]])){?>
      <?php
        foreach([[=promotions_list=]] as $value)
        {?>
      <p style="padding-left: 25px;">- <?php echo $value['name'];?></p>
      
      <?php 
        }}
      ?> 
  </tr>
  <tr>
    <td align="center" valign=TOP>ĐẠI DIỆN BÊN B </td>
    <td align="center" valign=TOP>ĐẠI DIỆN BÊN A </td>
  </tr>
</table>
<BR CLEAR=LEFT>
<!-- ************************************************************************** -->
<p style = "page-break-after: always; color: white;"></p>
<BR CLEAR=LEFT >
<!-- ************************************************************************** -->
<table width="100%" border="0" align="LEFT" cellspacing="0" cols="11">
  <tr>
    <td width="508" height="26" align="CENTER" valign=TOP><p></p>
        <p><?php echo HOTEL_NAME;?> </p>
      <p><b><font face="Wingdings">¶¶¶¶</font></b></p></td>
    <td width="338" height="26" align="CENTER" valign=TOP><p>CÔNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p>Độc lập - Tự do - Hạnh phúc </p></td>
  </tr>
  <tr>
    <td colspan=2 height="26" align="CENTER" valign=TOP><div align="right"></div></td>
  </tr>
  <tr>
    <td colspan=2 height="561" align="left" valign=TOP><p align="right"><br>
    </p>
        <p align="center"><strong>BẢN THỎA THUẬN GIỮ CHỖ</strong></p>
      <p align="center">Hôm nay, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?>. Chúng tôi gồm: </p>
      <p><strong>Bên A: <?php echo HOTEL_NAME;?></strong></p>
      <blockquote>
          <p>Địa chỉ: <?php echo HOTEL_ADDRESS;?> </p>
        <p>Điện thoại: <?php echo HOTEL_PHONE;?></p>
        <p>Đại diện: [[|representative_hotel|]]</p>
        <p>Chức vụ: [[|position_hotel|]]</p>
        <p><strong> Bên B: </strong></p>
        <p>- Đại diện bởi ông/bà: <b><i><font>[[|representative_name|]]</font></i></b></p>
        <p> - Địa chỉ: <font>[[|company_address|]]</font></p>
        <p> - Điện thoại: <font>[[|home_phone|]]</font></p>
      </blockquote>
      <p>Hai bên cùng thỏa thuận và thống nhất ký kết các điều khoản sau: </p>
      <p><strong>ĐIỀU 1: </strong>Bên B yêu cầu đặt trước chỗ cho phòng họp</p>
      <blockquote>
          <p>- Thời gian: Từ <font face="VNI-Times"><?php echo date('H',[[=checkin_time=]]);?></font> giờ <font face="VNI-Times"><?php echo date('i',[[=checkin_time=]]);?></font> đến <font face="VNI-Times"><?php echo date('H',[[=checkout_time=]]);?></font> giờ <font face="VNI-Times"><?php echo date('i',[[=checkout_time=]]);?></font> ngày <b><font face="Vni-times"><?php echo date('d',[[=checkin_time=]]);?></font></b> tháng <b><font face="Vni-times"><?php echo date('m',[[=checkin_time=]]);?></font></b> năm <b><font face="Vni-times"><?php echo date('20y',[[=checkin_time=]]);?></font></b></p>
        <p>- Địa  điểm :
          Khách sạn  Đệ Nhất - [[|meeting_room_group_name|]] - [[|meeting_room_name|]] </p>
        <p>- Địa  chỉ : [[|meeting_room_address|]]</p>
        <p>- Số lượng bàn: từ ..... bàn đến ..... bàn </p>
      </blockquote>
      <p><strong>ĐIỀU 2:</strong> Bên B ứng trước cho bên A số tiền:</p>
      <p><strong>ĐIỂU 3:</strong> Bên B có trách nhiệm đến khách sạn ký hợp đồng chính thức và đặt cọc 50% trên tổng giá trị hợp đồng tạm ứng tính trước 45 ngày tổ chức tiệc.</p>
      <p><strong>ĐIỀU 4: Các trường hợp hủy bỏ thỏa thuận:</strong></p>
      <blockquote>
          <p>a) Nếu Bên B hủy bỏ thỏa thuận này thỉ Bên A sẽ không hoàn trả lại số tiền ứng trước.</p>
        <p>b) Nếu Bên B không đến hợp đồng đúng như điều 3 quy định và trong vòng 05 ngày không thông báo cho Bên A thì đến ngày thứ 40 Bên A có quyền đơn phương hủy bò thỏa thuận này và không hoàn trả lại số tiền ứng trước cho bên B.</p>
        <p>c) Bên A chỉ giải quyết chuyển ngày cho Bên B 01 lần (nếu còn chỗ) trong vòng 30 ngày kể từ ngày giữ chỗ.</p>
        <p>d) Bản thỏa thuận này được thành lập thành hai bản, mỗi bên giữ 01 bản, có giá trị pháp lý như nhau để theo dõi thực hiện.</p>
      </blockquote>
      <p>&nbsp; </p>
  </tr>
  <tr>
    <td align="center" valign=TOP>ĐẠI DIỆN BÊN B </td>
    <td align="center" valign=TOP>ĐẠI DIỆN BÊN A </td>
  </tr>
</table>
<p>&nbsp;</p>
<div>
<p align=center>
<?php if(User::can_edit(false,ANY_CATEGORY) ) {?> <td nowrap><a target="blank" href="<?php echo Url::build_current(array(  'party_reservation')+array('cmd'=>Url::get('party_type'),'action'=>'edit','id'=>Url::iget('id'))); ?>"><font size="4">[[.edit.]]</font><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="20" height="20" border="0"></a></td><?php }else{?><td nowrap="nowrap">&nbsp;</td><?php }?>
</p>
</div>
</body>