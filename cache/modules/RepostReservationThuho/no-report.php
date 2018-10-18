<table style="width: 100%;">
    <?php //system::Debug($_REQUEST) ?>
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_NAME;?></td>
        <td style="text-align: right; font-weight: bold;"><?php echo Portal::language('creator');?>: <?php $user = Session::get('user_data'); echo $user['full_name'];?></td>
    </tr>
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_ADDRESS;?></td>
        <td style="text-align: right; font-weight: bold;"><?php echo Portal::language('department');?>: <?php echo Portal::language('restaurant');?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;" class="report_title">BÁO CÁO LỄ TÂN THU HỘ</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold;"><?php echo Portal::language('date_from');?> <?php echo $this->map['date_from'];?> <?php echo Portal::language('date_to');?> <?php echo $this->map['date_to'];?><br />
        <?php echo Url::get('bar_name');?>
        </td>
        
    </tr>
</table>
<div style="border: 1px solid #00b9f2; background: #ffffff; text-align: center; width: 500px; height: 50px; line-height: 50px; margin: 20px auto;">
    <?php echo Portal::language('no_record');?>
</div>