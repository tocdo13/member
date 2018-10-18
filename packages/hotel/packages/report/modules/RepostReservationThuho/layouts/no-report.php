<table style="width: 100%;">
    <?php //system::Debug($_REQUEST) ?>
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_NAME;?></td>
        <td style="text-align: right; font-weight: bold;">[[.creator.]]: <?php $user = Session::get('user_data'); echo $user['full_name'];?></td>
    </tr>
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_ADDRESS;?></td>
        <td style="text-align: right; font-weight: bold;">[[.department.]]: [[.restaurant.]]</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;" class="report_title">BÁO CÁO LỄ TÂN THU HỘ</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold;">[[.date_from.]] [[|date_from|]] [[.date_to.]] [[|date_to|]]<br />
        <?php echo Url::get('bar_name');?>
        </td>
        
    </tr>
</table>
<div style="border: 1px solid #00b9f2; background: #ffffff; text-align: center; width: 500px; height: 50px; line-height: 50px; margin: 20px auto;">
    [[.no_record.]]
</div>