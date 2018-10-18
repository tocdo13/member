<table cellSpacing=0 width="98%" style=" margin: 10px auto;" id="export">
	<tr>
    	 <td align="left" width="100%"><strong><?php echo HOTEL_NAME;?></strong><br />
	<?php echo HOTEL_ADDRESS;?></strong></td>
    </tr>
    <tr valign="top">
		<td width="100%" align="center" valign="middle">
          <font class="report_title"><?php echo Portal::language('<font class="report-title"></font>');?><?php echo Portal::language('reservation_summary_by_seller');?><br /><br />
		  <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
			<?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>
  		<br>
	      </span>
      </td>
	</tr>	
</table>
