<div class="report-bound">
<div >
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<link rel="stylesheet" href="skins/default/report.css"/>


				<?php
				}
				?>
<?php 
				if(($this->map['page_no']==$this->map['start_page']))
				{?>
<table id="tblExport" cellpadding="10" cellspacing="0" width="100%" >
<tr id="header_report">
	<td align="center">
		
		<table cellSpacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr>
            	<td align="left" width="60%" style="font-size:12px; font-weight:normal;"><strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></strong></td>
                <td align="right">
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                </td>
            </tr>
            <tr valign="top">
			  
				<td width="100%" align="center" valign="middle" colspan="2"><font class="report_title"><?php echo Portal::language('traveller_report');?><br />
				  <span style="font-family:'Times New Roman', Times, serif; font-size:12px;">
			  		
                    <?php if($this->map['status']=='IN_HOUSE'){?>
                    <?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['from_day'];?>&nbsp;
                    <?php }else{?>
					<?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_day'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_day'];?>
				  	<?php }?>
					<?php 
				if(($this->map['status']!="0"))
				{?>
						<br/>
						<?php echo Portal::language('status');?>:&nbsp;<?php echo $this->map['status'];?>
					
				<?php
				}
				?>
                    <br/>
                    <!-- Oanh add -->
                    <button id="export"><?php echo Portal::language('export_file_excel');?></button>
                    <!-- Edn oanh -->
			      </span>
              </td>
			</tr>	
				
		</table>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;"></div>
		<br />
		
				<?php
				}
				?>
