<?php
$date_now = getdate();
?>
<?php 
				if(($this->map['page_no']))
				{?><center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
				<?php
				}
				?><br>
		<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
        <?php 
				if((isset($this->map['payment'])))
				{?>
        <div align="left"></div>
        
				<?php
				}
				?>        
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;"><?php echo Portal::language('creator');?></td>
                <td style="text-align: center;"><?php echo Portal::language('general_accountant');?></td>
                <td style="text-align: center;"><?php echo Portal::language('date');?> <?php echo $date_now["mday"]; ?> <?php echo Portal::language('month');?> <?php echo $date_now["mon"]; ?> <?php echo Portal::language('year');?> <?php echo $date_now["year"]; ?></td>
            </tr>
        </table>
        
		
				<?php
				}
				?>
 </td>
</tr>
</table>
<!--<DIV style="page-break-before:always;page-break-after:always;"></DIV>-->
</div>
