    <tr>
        <td colspan="<?php echo (10 + count([[=categories_invi=]])+1+count([[=categories_hidd=]])+(Url::get('type_report')=='date'?0:2)); ?>">
            <!--FOOTER-->
            <table  cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <td>
                  		<table width="100%" style="font-family:'Times New Roman', Times, serif">
                    		<tr>
                    			<td></td>
                    			<td></td>
                    			<td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
                    		</tr>
                    		<tr valign="top">
                    			<!--td width="33%" align="center">[[.cashier.]]</td-->
                    			<td width="33%" align="center">K&#7871; to&aacute;n </td>
                    			<td width="33%" align="center">[[.seller.]]</td>
                    			<td width="33%" align="center">[[.creator.]]</td>
                    		</tr>
                  		</table>
                  		<p>&nbsp;</p>
                    </td>
                </tr>
            </table>
            <DIV style="page-break-before:always;page-break-after:always;"></DIV>
            <!--/FOOTER-->
        </td>
    </tr>
</table>