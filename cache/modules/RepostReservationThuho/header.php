<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
				{?>
<table style="width: 100%;">
    
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_NAME;?></td>
        <td style="text-align: right; font-weight: bold;"><?php echo Portal::language('creator');?> : <?php $user = Session::get('user_data'); echo $user['full_name'];?></td>
    </tr>
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_ADDRESS;?></td>
        <td style="text-align: right; font-weight: bold;"><?php echo Portal::language('department');?>: <?php echo Portal::language('restaurant');?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;" class="report_title"><?php echo Portal::language('restaurant_revenue_transfer_to_room');?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold;"><?php echo Portal::language('date_from');?> <?php echo $this->map['date_from'];?> <?php echo Portal::language('date_to');?> <?php echo $this->map['date_to'];?></td><br />
         
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold;"><?php echo Url::get('bar_name');?></td>
    </tr>
</table>

				<?php
				}
				?>