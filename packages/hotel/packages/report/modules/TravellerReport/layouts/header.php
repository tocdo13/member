<div class="report-bound">
<div >
<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<link rel="stylesheet" href="skins/default/report.css"/>

<!--/IF:first_page-->
<!--IF:first_page([[=page_no=]]==[[=start_page=]])-->
<table id="tblExport" cellpadding="10" cellspacing="0" width="100%" >
<tr id="header_report">
	<td align="center">
		
		<table cellSpacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr>
            	<td align="left" width="60%" style="font-size:12px; font-weight:normal;"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
                <td align="right">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
                </td>
            </tr>
            <tr valign="top">
			  
				<td width="100%" align="center" valign="middle" colspan="2"><font class="report_title">[[.traveller_report.]]<br />
				  <span style="font-family:'Times New Roman', Times, serif; font-size:12px;">
			  		
                    <?php if([[=status=]]=='IN_HOUSE'){?>
                    [[.day.]]&nbsp;[[|from_day|]]&nbsp;
                    <?php }else{?>
					[[.from.]]&nbsp;[[|from_day|]]&nbsp;[[.to.]]&nbsp;[[|to_day|]]
				  	<?php }?>
					<!--IF:cond([[=status=]]!="0")-->
						<br/>
						[[.status.]]:&nbsp;[[|status|]]
					<!--/IF:cond-->
                    <br/>
                    <!-- Oanh add -->
                    <button id="export">[[.export_file_excel.]]</button>
                    <!-- Edn oanh -->
			      </span>
              </td>
			</tr>	
				
		</table>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"></div>
		<br />
		<!--/IF:first_page-->
