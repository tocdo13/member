<tr><td>
<!--IF:page_no([[=page_no=]])--><center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center><!--/IF:page_no--><br/>
</td>
</tr>
		<!--IF:first_page([[=real_page_no=]]==[[=real_total_page=]])-->
<tr>
<td>
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center"></td>
			<td width="33%" align="center"></td>
			<td width="33%" align="center">[[.creator.]]</td>
		</tr>
		</table>
		<p>&nbsp;</p>
		
		

</td>
</tr>
</table>
</div>
<!--/IF:first_page-->
<div style="page-break-before:always;page-break-after:always;"></div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            <!--IF:pt([[=page_no=]]==[[=total_page=]])-->
            jQuery("#export").remove();
            //jQuery("#header_report").remove();
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
            <!--/IF:pt-->
        });
    });
</script>