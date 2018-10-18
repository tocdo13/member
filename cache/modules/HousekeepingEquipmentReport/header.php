<div >
<?php 
				if(($this->map['page_no']==1))
				{?>
<link rel="stylesheet" href="skins/default/report.css">
<?php Form::$current->error_messages();?><?php $input_count = 0;?>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>


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
				<td align="left" width="79%">
				<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
		
			  <td width="21%" align="right" nowrap><strong><?php echo Portal::language('template_code');?></strong><br />
              <i><?php echo Portal::language('promulgation');?></i>
              <br />
              <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
              </td>
			</tr>	
		</table>
		<div style="line-height:40px;"><font class="report_title"><?php echo Portal::language('housekeeping_equipment_report');?></font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		<?php echo Portal::language('from_date');?>: <?php echo(Url::get('from_date_tan'));?>&nbsp;&nbsp;<?php echo Portal::language('to_date');?>:<?php echo(Url::get('to_date_tan'));?>
		<br />
		<?php echo URL::get('bar_id')?Portal::language('bar_id').DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?></div><br />
		
				<?php
				}
				?>