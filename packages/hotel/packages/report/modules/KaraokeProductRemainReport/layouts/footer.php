<!--IF:page_no([[=page_no=]])--><center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center><!--/IF:page_no--><br>
		<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
        <!--IF:payment(isset([[=payment=]]))-->
        <div align="left"></div>
        <!--/IF:payment-->        
        <div class="notice">Chú ý: Báo cáo trên tính doanh số theo món ăn mà chưa bao gồm phí dịch vụ và thuế.<br />Để đối chiều phần dịch vụ và thuế xin vui lòng xem báo cáo doanh thu nhà hàng theo hóa đơn.</div>
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center">[[.day.]] <?php echo date('d');?>[[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center">[[.creator.]]</td>
			<td width="33%" align="center">[[.general_accountant.]]</td>
			<td width="33%" align="center">[[.director.]]</td>
		</tr>
		</table>
		<p>&nbsp;</p>
        
		<!--/IF:first_page-->
 </td>
</tr>
</table>
<DIV style="page-break-before:always;page-break-after:always;"></DIV>
</div>