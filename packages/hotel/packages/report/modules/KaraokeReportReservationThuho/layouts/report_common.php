<link rel="stylesheet" href="skins/default/report.css">
<table width="100%" >
    <tr>
        <td align="center" >
            <table cellpadding="5" cellspacing="0" width="50%" border="1" bordercolor="#CCCCCC" class="table-bound" align="center" >
                <tr>
                    <th width="4%" align="center" >[[.stt.]]</th>
                    <th width="40%" >[[.category.]]</th>
                    <th width="15%" >Quantity</th>
                    <th >[[.total_before_tax.]]</th>
                </tr>
                <!--LIST:items_commons-->
                <tr>
                    <td align="center" width="4%" style="padding-left: 15px;" >[[|items_commons.stt|]]</td>
                    <td width="40%" style="padding-left: 15px;" >[[|items_commons.name|]]</td>
                    <td width="15%" style="padding-right: 15px;" align="right" >[[|items_commons.quantity|]]</td>
                    <td style="padding-right: 15px;" align="right" ><?php echo System::display_number_report([[=items_commons.total=]]);?></td>
                </tr>
                <!--/LIST:items_commons-->
            </table>
        </td>
    </tr>
</table>
<br />
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
        
        
