<?php 
				if(($this->map['page_no']))
				{?><center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
				<?php
				}
				?><br>
		<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
			<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
			<td width="33%" align="center"><?php echo Portal::language('director');?></td>
		</tr>
		</table>
		<p>&nbsp;</p>
		<script>full_screen();</script>
		
				<?php
				}
				?>
 </td>
</tr>
</table>
<DIV style="page-break-before:always;page-break-after:always;"></DIV>
</div>
</div>