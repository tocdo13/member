<div >
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<link rel="stylesheet" href="skins/default/report.css">
<?php Form::$current->error_messages();?>
<?php $input_count = 0;?>
<?php System::set_page_title(HOTEL_NAME);?>
<script>full_screen();</script>

<table cellpadding="10" cellspacing="0" width="99%">
<tr>
	<td align="center">
	
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td  align="left" width="65%">
				<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?>
				</td>
			  <td  align="right" nowrap width="35%">
              <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
              </td>
			</tr>	
		</table>
		<div style="line-height:40px;"><font class="report_title" style="text-transform:uppercase"><?php echo Portal::language('housekeeping_equipment_damaged_report');?></font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		
		<?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['date_from'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['date_to'];?>
		<br />
		<?php echo URL::get('bar_id')?Portal::language('bar_id').DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?></div><br />
        <p style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></p>


				<?php
				}
				?>