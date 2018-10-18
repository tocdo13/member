<style>
	.guest-registration-card li{
		list-style:inside;
		margin-bottom:5px;
	}
	p{
		margin:0px;
		padding:0px;
	}
</style>
<!--LIST:guest-->
<table width="800" border="0" cellspacing="0" cellpadding="5" style="border:0px solid #000000;">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td colspan="2" align="center"  style="font-size:26px; font-family:'Times New Roman', Times, serif;"><br><br><br><br><br><br><br><br>THƯ CHÀO MỪNG QUÝ KHÁCH<br><br></td>
                </tr>
        	</table>
        </td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100%" colspan="2" style="font-size:20px; font-family:'Times New Roman', Times, serif;">Ngày  <?php echo date("d/m/y"); ?></td>
          </tr>
          <tr>
            <td width="100%" colspan="2" style="font-size:20px; font-family:'Times New Roman', Times, serif;">Kính thưa quý khách : <strong>[[|full_name|]]<br></strong></td>
          </tr>
          <tr>
            <td colspan="2" style="font-size:20px; font-family:'Times New Roman', Times, serif; line-height:30px;">Mỏm Đá Chim Resort ( tên nước ngoài là Lazi Beach Resort ) hân hoan chào đón Quý khách. Chân thành cảm ơn Quý khách đã tin tưởng và lựa chọn Mỏm Đá Chim Resort cho kỳ nghỉ của mình. Chúng tôi rất vui mừng được đón tiếp và phục vụ Quý khách như là những thượng khách đặc biệt của chúng tôi. <br><br>

Để giúp Quý khách thực sự thoải mái trong suốt kỳ nghỉ, chúng tôi rất vinh hạnh được Quý khách yêu cầu phục vụ và sẽ nỗ lực hết mình để mang lại sự hài lòng tuyệt đối cho Quý khách. <br><br>

Vui lòng bấm số 100 nếu Quý khách cần sự hỗ trợ để được phục vụ chu đáo nhất.<br><br>

Kính chúc Quý khách một kỳ nghỉ như ý trọn vẹn, và xin Quý khách nhớ rằng chúng tôi luôn ở đây để sẵn sàng phục vụ Quý khách <br><br>

Trân trọng,

            </td>
          </tr>
          <tr>
          	<td width="60%"></td>
            <td align="center" style="font-size:20px; font-family:'Times New Roman', Times, serif;">GIÁM ĐỐC<br><br><table><tr><td width="150">
                        <img src="resources/interfaces/images/default/signature.jpg" width="180" >
                    </td></tr></table><br>ĐỖ TUẤN HÙNG MẠNH (Mr)
            </td>
          </tr>
        </table></td>
        </tr>
    </table></td>
  </tr>
</table>
<!--/LIST:guest-->
<script>
	$('male').checked = ([[|gender|]]=='1')?true:false;
	$('female').checked = ([[|gender|]]=='10')?true:false;
</script>