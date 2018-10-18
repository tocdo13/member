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
    			<td colspan="2" align="center" style="font-size:24px; font-family:'Times New Roman', Times, serif;"><br><br><br><br><br><br><br>WELCOME LETTER<br><br></td>
  			</tr>
            </table>
        </td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100%" colspan="2" style="font-size:20px; font-family:'Times New Roman', Times, serif;">Date: <?php echo date("d/m/y"); ?><br></td>
          </tr>
          <tr>
            <td width="100%" colspan="2" style="font-size:20px; font-family:'Times New Roman', Times, serif;">Dear: <strong>[[|full_name|]]<br>
            </strong></td>
          </tr>
          <tr>
            <td colspan="2" style="font-size:20px; font-family:'Times New Roman', Times, serif; line-height:30px;">I would like to take a moment to thank you for your continued selection of Lazi beach Resort as your accommodations of choice<br><br>

You are truly one of our most loyal guests, and it is always a pleasure and an honor to serve you. It is patrons such as yourself to whom we owe the success of this establishment<br><br>

My sincere appreciation of your patronage cannot be overstated. I want to assure you that we will continue to do all we can to justify your confidence in Lazi beach resort<br><br>

Sincerely, 
<br><br><table><tr><td width="150">
                        <img src="resources/interfaces/images/default/signature.jpg" width="180" >
                    </td></tr></table><br>ĐỖ TUẤN HÙNG MẠNH (Mr)<br>General Manager
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