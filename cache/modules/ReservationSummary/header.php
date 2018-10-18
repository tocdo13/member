<style>
.te{margin-left: 10px;
    font-size: 13px;
    padding: 2px 0;
    font-weight: normal;}

    *{padding:2px; border:none; line-height: 12px; margin-bottom:2px}
    table.table1{ width: 100%; margin: 0px auto; border: 2px solid #999; }
    table.table1 tr.title{background: #ddd;}
    table.table1 tr.title td{padding: 10px; border: 1px solid #999;}
    table.table1 tr.row td{ border: 1px solid #ccc; font-size: 11px; }
   
</style>
<table cellpadding="10" cellspacing="0" width="100%" id="export" >
<tr>
	<td align="center">
<table cellSpacing=0 width="98%" style=" margin: 10px auto;">
	<tr>
    	 <td align="left" width="100%"><strong><?php echo HOTEL_NAME;?></strong><br />
	<?php echo HOTEL_ADDRESS;?></strong></td>
    </tr>
    <tr valign="top">
		<td width="100%" align="center" valign="middle">
          <font class="report_title"><?php echo Portal::language('<font class="report-title"></font>');?><?php echo Portal::language('reservation_summary');?><br /><br />
		  <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
			<?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>
  		<br>
	      </span>
      </td>
	</tr>	
</table>
<div class="te">
<?php echo Portal::language('te');?>:<?php echo $this->map['te'];?>
</div>
<div class="te" style="margin-bottom: 4px;">
<?php echo Portal::language('cf');?>:<?php echo $this->map['cf'];?>
</div>
