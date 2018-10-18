		<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
        <!--IF:payment(isset([[=payment=]]))-->
        <div align="left"></div>
        <!--/IF:payment-->        
        
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
        
		
 </td>
</tr>
</table>
<!--/IF:first_page-->

<DIV style="page-break-before:always;page-break-after:always;"></DIV>
</div>
