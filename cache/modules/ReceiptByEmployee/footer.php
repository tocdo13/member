<?php 
				if(($this->map['page_no']))
				{?><!--<center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>-->
				<?php
				}
				?><br/>
		<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td align="right" colspan="2"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
		</tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
		<tr valign="top">
			<td width="33%" align="center">&nbsp;</td>
			<td width="33%" align="center">&nbsp;</td>
			<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
		</tr>
		</table>
		<p>&nbsp;</p>
        
		
				<?php
				}
				?>
</td>
</tr>
</table>
<div style="page-break-before:always;page-break-after:always;"></div>
</div>