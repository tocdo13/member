<!--?php echo"tet18";?-->
<STYLE>
		<!-- 
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
		 -->
	</STYLE>
	<body>
	<div>
<table width="100%" class="styletable" >
  <tr>
    <td colspan="2" valign="top"><div align="center">
      <p class="stylespace">&nbsp;</p>
      <p class="stylespace"></p>
      <p class="stylespace"><?php echo HOTEL_NAME;?></p>
      <p>Số/HĐ: [[|contract_code|]]</p>
      </div></td>
    <td width="68%" valign="top"><div align="center">
      <p class="stylespace">&nbsp;</p>
      <p class="stylespace">CỘNG  HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
      <p class="stylespace"> Độc Lập  – Tự Do – Hạnh Phúc</p>
    </div></td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><blockquote>
      <p align="center"><span class="style1">HỢP ĐỒNG  ĐẶT TIỆC</span></p>
    </blockquote>
    <p> Hôm  nay, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?>. Hai bên gồm:</p></td>
  </tr>
  <tr>
    <td valign="top" class="styletable"><strong>Bên  A:</strong></td>
    <td colspan="2" valign="top" class="styletable"><strong><?php echo HOTEL_NAME;?></strong></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td colspan="2" valign="top"><p>- Địa  chỉ : <?php echo HOTEL_ADDRESS;?></p>
      <p class="stylespace">- Điện  thoại : <?php echo HOTEL_PHONE;?></p>
      <p class="stylespace"> - Email  : <?php echo HOTEL_EMAIL;?></p>
    <p class="stylespace"> - Đại diện bởi Ông/Bà : <strong>[[|representative_hotel|]]</strong>. Chức vụ : <strong>[[|position_hotel|]]</strong></p></td>
  </tr>
  <tr>
    <td valign="top"><strong>Bên B:</strong></td>
    <td colspan="2" valign="top">Đại diện bởi Ông/Bà: <strong>[[|representative_name|]]</strong> đặt   [[.wedding_party.]]</td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td colspan="2" valign="top">
	<p class="stylespace">&nbsp;</p>
	<p class="stylespace">- Chú rể  : [[|groom_name|]]. Cô dâu : [[|bride_name|]]</p>
	<p class="stylespace"> - Địa  chỉ : [[|address|]]</p>
    <p class="stylespace"> - Điện  thoại : [[|home_phone|]]</p>    </td>
  </tr>
  <tr>
    <td  colspan="3" valign="top"><p class="stylespace">Hai bên  cùng thỏa thuận và thống nhất ký kết các điều khoản sau :</p>    </td>
  </tr>
  <tr>
    <td valign="top"><strong>Điều 1:</strong></td>
    <td colspan="2" valign="top"><strong>Trách nhiệm Bên A</strong></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td colspan="2" valign="top"><p class="stylespace">&nbsp;</p>
      <p class="stylespace">Chịu  trách nhiệm phục vụ tiệc cưới cho Bên B với chi tiết sau :</p>
      <p class="stylespace"> - Thời  gian bắt đầu :
      từ <?php echo date('H:i',[[=checkin_time=]]);?> giờ <?php echo date('i',[[=checkin_time=]]);?> đến <?php echo date('H:i',[[=checkout_time=]]);?> giờ <?php echo date('i',[[=checkout_time=]]);?></p>
    <p class="stylespace"> - Ngày <?php echo date('d',[[=checkin_time=]]);?> tháng <?php echo date('m',[[=checkin_time=]]);?> năm <?php echo date('20y',[[=checkin_time=]]);?> dương lịch</p>
    <p class="stylespace"> - Địa  điểm :
      Khách sạn  Đệ Nhất -
        <?php if(isset([[=banquet_room_group_name=]])) echo [[=banquet_room_group_name=]]; ?>
      - [[|banquet_room_name|]] </p>
    <p class="stylespace"> - Địa  chỉ : [[|banquet_room_address|]]</p>
    <!--p class="stylespace"> - Giá phòng ăn :<strong> <?php echo System::display_number([[=banquet_room_price=]]);?> đ.</strong></p-->
    <p class="stylespace"> - Số  khách : [[|num_people|]] khách. 
      Số bàn  : [[|num_table|]] chính thức + [[|table_reserve|]]
      dự bị</p>
    <p class="stylespace"> - Giá  ăn : <?php echo System::display_number([[=giaan=]]);?> đ/bàn
      ( chưa  bao gồm thuế VAT )</p>
    <p class="stylespace">&nbsp;</p>
    <p class="stylespace"><strong>Thực  đơn</strong></p>
    <ol>
      
      <?php 
            for($j = 1; $j < [[=num_eating=]]; $j++)
            {
          ?>
      
      <li class="styleli">
      
        <?php 
                    if(isset([[=eating=]][$j])){ echo [[=eating=]][$j]; echo"."; }
                ?>
        <!--?php 
                    if(isset([[=eating=]][$j])){  echo" Giá: "; echo System::display_number([[=eating_price=]][$j]); echo" "; echo" đ.";}
                ?-->
        </li>
      
      <?php 
            }
          ?>
      <?php if (System::display_number([[=num_vegetarian=]])>0) {?>
    </ol>
    <p> <span class="stylespace"><strong>Món chay :</strong></span></p>
    <ol>
      
      <?php }?>
      <?php 
            for($h = 1; $h < [[=num_vegetarian=]]; $h++)
            {
          ?>
      
      <li class="styleli">

        <?php 
                    if(isset([[=vegetarian=]][$h])){ echo [[=vegetarian=]][$h]; echo" "; }
                ?>
        <!--?php 
                    if(isset([[=vegetarian=]][$h])){  echo" "; echo"giá: "; echo System::display_number([[=vegetarian_price=]][$h]); echo" "; echo" đ.";}
                ?-->
        </li>
      
      <?php 
            }
          ?>
    </ol>
    <p class="stylespace"><strong>Thức uống  :
      ( Theo  giá đại lý - Tính theo thực tế sử dụng )</strong></p>
    
    <!--blockquote>
          <p>          - Pepsi  chai 80.000đ/két x két </p>
          <p> - Nước suối  chai 84.000đ/ thùng x thùng</p>
        </blockquote-->
    
    <ol>
      
      <?php 
                for($i = 1; $i < [[=num_drinking=]]; $i++)
                {
              ?>
     
      <li class="styleli">
        <?php 
                        if(isset([[=drinking=]][$i])){ echo [[=drinking=]][$i]; echo" "; }
                    ?>
        <?php
                        if(isset([[=drinking=]][$i])){ echo" "; echo". Giá: "; echo System::display_number([[=drink_price=]][$i]); echo" ";echo" đ."; }                 
                    ?>
        </li>
      
      <?php 
            }
          ?>
    </ol>
    <p class="stylespace"> <strong>Lưu ý:</strong></p>
    <p class="stylespace"> Giá thức  uống có thể thay đổi vào thời điểm đãi tiệc.</p>
    <p class="stylespace"> Nhà  hàng sẽ thông báo cho quý khách để hai bên có sự thỏa thuận .</p>
    <p class="stylespace"> - Bên B  phải thanh toán cho bên A tiền nước đá là: 79.000 đ/bàn (Tính theo thực tế sử dụng)</p>
    <p class="stylespace"> - Nếu  khách mang rượu X.O, Cordon bleu, Blue Label, Chivas salute, Chivas 18Y.O vào  khách sẽ phải chịu phí là 330.000 đ/chai.</p>
    <p class="stylespace"> - Còn lại  rượu vang và các loại rượu khác khách sẽ phải chịu phí là 165.000 đ/chai.</p>
    <p> <strong>- Dịch  vụ khác </strong></p>
    <ol>
      <?php 
                for($k = 1; $k < [[=num_service=]]; $k++)
                {
              ?>
      <li class="styleli">
        <?php 
                        if(isset([[=service=]][$k])){ echo [[=service=]][$k]; echo".";}
                    ?>
        <?php 
                        if(isset([[=service=]][$k])){ echo" Giá: "; echo System::display_number([[=service_price=]][$k]);echo" ";echo" đ."; }
                    ?>
      </li>
  <?php 
                }
              ?>  </tr>
  <tr>
    <td valign="top"><strong>Điều 2:</strong></td>
    <td colspan="2" valign="top"><strong>Điều  khoản thanh toán</strong></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td colspan="2" valign="top"><p class="stylespace">&nbsp;</p>
      <p class="stylespace">Bên B  đã ứng trước cho Bên A số tiền : </p>
      <p class="stylespace">Lần 1 : <?php echo System::display_number([[=deposit_1=]]);?> đ</p>
      <p class="stylespace">Lần 2 : <?php echo System::display_number([[=deposit_2=]]);?> đ</p>
      <p class="stylespace">Lần 3 : <?php echo System::display_number([[=deposit_3=]]);?> đ</p>
      <p class="stylespace">Lần 4 : <?php echo System::display_number([[=deposit_4=]]);?> đ</p>
      <p class="stylespace">Tổng cộng  : <b><?php echo System::display_number([[=deposit_1=]] + [[=deposit_2=]] + [[=deposit_3=]] + [[=deposit_4=]]).' '; //echo System::display_number([[=total_before_tax=]]);?></b> đ</p>
      <p class="stylespace">Số tiền  bằng chữ : <strong>
        <?php
                                     require_once 'packages/core/includes/utils/currency.php';
                                     $total1=round([[=deposit_1=]] + [[=deposit_2=]] + [[=deposit_3=]] + [[=deposit_4=]]);
                                     $total2=vsprintf("%d",$total1);
                                     echo currency_to_text($total2);
                                ?>
        </strong> </p>
      <p class="stylespace"> Số tiền  còn lại bên B sẽ thanh toán bằng tiền mặt ngay sau khi kết thúc tiệc và bên A sẽ  ra Hóa Đơn Tiệc Cưới cho bên B.</p>
    <p class="stylespace"> Nếu bên  B thanh toán bằng thẻ thì sẽ chịu phí theo quy định của ngân hàng.</p>    </td>
  </tr>
  <tr>
    <td valign="top"><strong>Điều 3:</strong></td>
    <td colspan="2" valign="top"><strong>Các  trường hợp hủy bỏ thỏa thuận</strong></td>
  </tr>
  <tr>
    <td width="8%" valign="top">&nbsp;</td>
    <td colspan="2" valign="top"><p class="stylespace">&nbsp;</p>
      <p class="stylespace">a)&nbsp;Sau  khi ký hợp đồng này mà Bên B hủy bỏ thỏa thuận, Bên A sẽ không hoàn trả số tiền  ứng trước lại cho Bên B.</p>
      <p class="stylespace">b) Nếu  Bên B thay đổi về số lượng bàn ( nếu giảm thì không quá 10% so với số lượng bàn  đã hợp đồng), thực đơn và dịch vụ thì báo cho Bên A trước ngày đãi tiệc 05  ngày. Nếu Bên B báo sau 05 ngày thì Bên A sẽ không giải quyết - Riêng phần dịch  vụ, bên B muốn thay đổi thì phải chịu thêm phí 50% các dịch vụ đã đặt trước.</p>
      <p class="stylespace">c) Bản  hợp đồng này được lập thành 04 ( bốn ) bản, bên A giữ ( 03) ba bản, bên B giữ  (01) một bản, có giá trị pháp lý như nhau, để theo dõi thực hiện</p>    </td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><strong>Điều  khoản khác</strong></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td colspan="2" valign="top"><p class="stylespace">&nbsp;</p>
      <p class="stylespace">- Bàn dự  bị sẽ thay đổi món khác và tính giá theo thời điểm đãi tiệc</p>
      <p class="stylespace"> - Thực  đơn thay đổi sẽ được tính lại theo thời điểm</p>
    <p class="stylespace"> - Nếu  có thay đổi hình thức thanh toán vui lòng báo lại cho bên A trước 05 ngày đãi  tiệc</p>    </td>
  </tr>
</table>
<table width="100%" border="0" align="center" class="styletable">
  <tr>
    <td><p align="center"><strong>ĐẠI DIỆN  BÊN B</strong><br>
    </p>
      <p align="center">&nbsp;</p>
      <p align="center">[[|full_name|]] </p></td>
    <td><p align="center"><strong>ĐẠI DIỆN  BÊN A</strong><br>
       <p align="center">&nbsp;</p>
      <p align="center">[[|representative_hotel|]] 
        </p>    
      </td>
  </tr>
</table>
<p style = "page-break-after: always; color: white;"></p>
<BR CLEAR=LEFT >
  <!-- ************************************************************************** -->

<table width="100%" border="0" class="styletable">
  <tr>
    <td valign="top"><p align="center">Tổng công ty Du Lịch Sài Gòn</p>
      <p align="center" class="stylespace"><?php echo HOTEL_NAME;?> </p>
    <p align="center"><b><font face="Wingdings">¶¶¶</font></b></p></td>
    <td valign="top"><p align="center">CÔNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
    <p align="center" class="stylespace"> Độc lập - Tự do - Hạnh phúc </p></td>
  </tr>
</table>
<table width="100%" border="0" align="LEFT" cellspacing="0" cols="11" class="styletable">
  <tr>
    <td colspan=2  align="left" valign=TOP><p align="right"><em>Ngày.....tháng.....năm 2013.</em></p>
      <p align="center"><strong>PHỤ LỤC HỢP ĐỒNG</strong></p>
      <p> Căn cứ hợp đồng số:  <?php echo date('Ym').'-'.Url::get('id');?>/HĐ ngày <?php echo date('d');?> tháng<?php echo date('m');?> năm <?php echo date('Y');?> đã ký giữa <?php echo HOTEL_NAME;?> và  Ông/Bà: <b><font>[[|full_name|]]</font></b></p>
  <p>Đại diện hai bên gồm:</p>  </tr>
  <tr>
    <td width="76"  align="left" ><strong>Bên A</strong>:<td width="821"  align="left" ><p><strong> <?php echo HOTEL_NAME;?></strong></p>  </tr>
  <tr>
    <td  align="left" >  
    <td  align="left" ><p class="stylespace">Đại diện bởi Ông/Bà: [[|representative_hotel|]]</p>
  <p class="stylespace">Chức  vụ: [[|position_hotel|]]</p>  </tr>
  <tr>
    <td  align="left" valign=TOP><strong>Bên B:</strong>  
  <td  align="left" valign=TOP><strong>Đại diện bởi Ông/Bà: <b><font>[[|representative_name|]]</font></b></strong>    </tr>
  <tr>
    <td  align="left" valign=TOP>  
    <td  align="left" valign=TOP><p class="stylespace">&nbsp;</p>
      <p class="stylespace">- Chú rể: <b><i><font>[[|groom_name|]]</font></i></b>, Cô dâu: <b><i><font>[[|bride_name|]]</font></i></b></p>
      <p class="stylespace">- Địa chỉ: [[|representative_address|]]</p>
      <p class="stylespace"> - Điện thoại: [[|representative_phone|]]</p>
      <p class="stylespace"> - Địa điểm tổ chức tiệc: <?php echo HOTEL_NAME;?>- [[|banquet_room_group_name|]] - [[|banquet_room_name|]]</p>
      <p class="stylespace"> - Thời gian: Từ <?php echo date('H',[[=checkin_time=]]);?> giờ <?php echo date('i',[[=checkin_time=]]);?> đến <?php echo date('H',[[=checkout_time=]]);?> giờ <?php echo date('i',[[=checkout_time=]]);?> ngày <?php echo date('d',[[=checkin_time=]]);?> tháng <?php echo date('m',[[=checkin_time=]]);?> năm <?php echo date('20y',[[=checkin_time=]]);?></p>
      <p class="stylespace"> Sau khi bàn bạc thảo luận hai bên đồng ý ký phụ lục hợp đồng  với nội dung sau: </p>
      <p><strong> Tặng:</strong></p>
      <?php if(isset([[=promotions_list=]])){?>
      <?php
        foreach([[=promotions_list=]] as $value)
        {?>
      <p style="padding-left: 25px;">- <?php echo $value['name'];?></p>
  <?php 
        }}
      ?>  </tr>
</table>



<table width="100%" border="0" class="styletable"><!-- table chua chu ky 2 ben duoi chan trang -->
  <tr>
    <td valign="top"><div align="center">
      <p><strong>ĐẠI DIỆN BÊN B</strong></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div></td>  
    <td valign="top"><div align="center">
      <p><strong>ĐẠI DIỆN BÊN A </strong></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div></td>
  </tr>
</table>
<p style = "page-break-after: always; color: white;"></p>
<BR CLEAR=LEFT >
<!-- ************************************************************************** -->
<table width="100%" border="0" align="LEFT" cellspacing="0" cols="11" class="styletable">
  
  
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
    <td colspan=2 height="561" align="left" valign=TOP><p align="center"><strong>BẢN THỎA THUẬN GIỮ CHỖ</strong></p>
      <p align="center">Hôm nay, ngày <?php echo date('d');?> tháng <?php echo date('m');?> năm <?php echo date('Y');?>. Chúng tôi gồm: </p>
      <p><strong>Bên A: <?php echo HOTEL_NAME;?></strong></p>
      <blockquote>
        <p>Địa chỉ: <?php echo HOTEL_ADDRESS;?> </p>
        <p>Điện thoại: <?php echo HOTEL_PHONE;?></p>
        <p>Đại diện: [[|representative_hotel|]]</p>
        <p>Chức vụ: [[|position_hotel|]] </p>
      </blockquote>
      <p><strong>Bên B: </strong></p>
      <blockquote>
        <p>- Đại diện bởi ông/bà: <b><i><font>[[|representative_name|]]</font></i></b></p>
        <p> - Địa chỉ: <font>[[|representative_address|]]</font></p>
        <p> - Điện thoại: <font>[[|representative_phone|]]</font></p>
      </blockquote>
      <p>Hai bên cùng thỏa thuận và thống nhất ký kết các điều khoản sau: </p>
      <p><strong>ĐIỀU 1: </strong>Bên B yêu cầu đặt trước chỗ cho tiệc</p>
      <blockquote>
        <p>- Thời gian: Từ <font face="VNI-Times"><?php echo date('H',[[=checkin_time=]]);?></font> giờ <font face="VNI-Times"><?php echo date('i',[[=checkin_time=]]);?></font> đến <font face="VNI-Times"><?php echo date('H',[[=checkout_time=]]);?></font> giờ <font face="VNI-Times"><?php echo date('i',[[=checkout_time=]]);?></font> ngày <b><font face="Vni-times"><?php echo date('d',[[=checkin_time=]]);?></font></b> tháng <b><font face="Vni-times"><?php echo date('m',[[=checkin_time=]]);?></font></b> năm <b><font face="Vni-times"><?php echo date('20y',[[=checkin_time=]]);?></font></b></p>
        <p>- Địa  điểm :
          Khách sạn  Đệ Nhất - [[|banquet_room_group_name|]] - [[|banquet_room_name|]] </p>
        <p>- Địa  chỉ : [[|banquet_room_address|]]</p>
        <p>- Số lượng bàn: từ ..... bàn đến ..... bàn </p>
      </blockquote>
      <p><strong>ĐIỀU 2:</strong> Bên B ứng trước cho bên A số tiền:</p>
      <p class="stylespace"><strong>ĐIỂU 3:</strong> Bên B có trách nhiệm đến khách sạn ký hợp đồng chính thức và đặt cọc 50% trên tổng giá trị hợp đồng tạm ứng tính trước 45 ngày tổ chức tiệc.</p>
      <p><strong>ĐIỀU 4: Các trường hợp hủy bỏ thỏa thuận:</strong></p>
      <blockquote>
        <p>a) Nếu Bên B hủy bỏ thỏa thuận này thỉ Bên A sẽ không hoàn trả lại số tiền ứng trước.</p>
        <p class="stylespace">b) Nếu Bên B không đến hợp đồng đúng như điều 3 quy định và trong vòng 05 ngày không thông báo cho Bên A thì đến ngày thứ 40 Bên A có quyền đơn phương hủy bò thỏa thuận này và không hoàn trả lại số tiền ứng trước cho bên B.</p>
        <p class="stylespace">c) Bên A chỉ giải quyết chuyển ngày cho Bên B 01 lần (nếu còn chỗ) trong vòng 30 ngày kể từ ngày giữ chỗ.</p>
        <p class="stylespace">d) Bản thỏa thuận này được thành lập thành hai bản, mỗi bên giữ 01 bản, có giá trị pháp lý như nhau để theo dõi thực hiện.</p>
      </blockquote>
  <p>&nbsp;   </p>  
  </tr>
  <tr>
    <td align="center" valign=TOP><p><strong>ĐẠI DIỆN BÊN B</strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td align="center" valign=TOP><p><strong>ĐẠI DIỆN BÊN A</strong></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>
<BR CLEAR=LEFT>
<!-- ************************************************************************** -->

</body>
