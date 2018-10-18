<div class="report-bound">
<div >
<link rel="stylesheet" href="skins/default/report.css">

<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
				{?>
<table cellpadding="10" cellspacing="0" width="100%" id="export" >
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%" style="font-size:11px; font-family:Arial, Helvetica, sans-serif;" >
			<tr>
            	<td align="left" width="60%"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
            <td align="right">
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>
            </tr>
            <tr valign="top">
			  
				<td width="100%" align="center" valign="middle" colspan="2"><span style="font-size:20px;" class="report_title"><?php echo Portal::language('waiting_list');?></span><br /><br />
				  <span>
				  	
					<?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_day'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_day'];?>
				 
			      </span>
              </td>
				
			</tr>	
		</table>
		<br />
		
				<?php
				}
				?>
