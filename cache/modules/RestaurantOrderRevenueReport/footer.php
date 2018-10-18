<tr><td>
<?php 
				if(($this->map['page_no']))
				{?><center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>
				<?php
				}
				?><br>
</td></tr>
<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
<tr>
<td>
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<!--td width="33%" align="center"><?php echo Portal::language('cashier');?></td-->
			<td width="33%" align="center"><?php echo Portal::language('accountant');?></td>
			<td width="33%" align="center"><?php echo Portal::language('treasurer_dn');?></td>
			<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
		</tr>
		</table>
		<p>&nbsp;</p>
		
 </td>
</tr>
</table>
</div>

				<?php
				}
				?>
<div style="page-break-before:always;page-break-after:always;"></div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            <?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
            jQuery("#export").remove();
            jQuery("#header_report").remove();
            jQuery('.change_num').each(function(){
                jQuery(this).html(to_numeric(jQuery(this).html()));
            })
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
            location.reload();
            
				<?php
				}
				?>
        });
    });
</script>