<?php 
				if(($this->map['page_no']))
				{?><center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
				<?php
				}
				?><br>
<?php 
				if(($this->map['real_page_no']==$this->map['real_total_page']))
				{?>
<table style="width: 100%; text-align: center;">
    <tr>
        <td width="30%" align="center"><?php echo Portal::language('general_accountant');?></td>
		<td width="30%" align="center"><?php echo Portal::language('accountancy');?></td>
		<td width="30%" align="center"><?php echo Portal::language('kitchen');?></td>
		<td width="30%" align="center"><?php echo Portal::language('cashier');?></td>
    </tr>
</table>

				<?php
				}
				?>