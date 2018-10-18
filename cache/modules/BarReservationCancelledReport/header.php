<?php 
				if(($this->map['page_no']==1))
				{?>
<div >
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('Bar_reservation_cancelled_report'));?>
<link rel="stylesheet" href="skins/default/report.css">
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>

				<?php
				}
				?>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
				{?>
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td align="left" width="60%">
				<strong><?php echo HOTEL_NAME;?></strong><br /><?php echo HOTEL_ADDRESS;?>
				</td>
				<td align="right" nowrap>
				<strong><?php echo Portal::language('template_code');?></strong>
                <br />
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
				</td>
			</tr>	
		</table>
		<div style="line-height:40px;"><font class="report_title"><?php echo Portal::language('Bar_reservation_cancelled_report');?></font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		<?php echo Portal::language('from_date');?>: <?php echo(Url::get('from_date_tan'));?>&nbsp;&nbsp;<?php echo Portal::language('to_date');?>:<?php echo(Url::get('to_date_tan'));?>
		<br />
		
				<?php
				}
				?>
