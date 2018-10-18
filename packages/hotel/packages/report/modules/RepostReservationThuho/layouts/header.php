<!--IF:first_page([[=page_no=]]==[[=start_page=]] or [[=page_no=]] == 0 )-->
<table style="width: 100%;">
    
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_NAME;?></td>
        <td style="text-align: right; font-weight: bold;">[[.creator.]] : <?php $user = Session::get('user_data'); echo $user['full_name'];?></td>
    </tr>
    <tr>
        <td style="text-align: left; font-weight: bold;"><?php echo HOTEL_ADDRESS;?></td>
        <td style="text-align: right; font-weight: bold;">[[.department.]]: [[.restaurant.]]</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;" class="report_title">[[.restaurant_revenue_transfer_to_room.]]</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold;">[[.date_from.]] [[|date_from|]] [[.date_to.]] [[|date_to|]]</td><br />
         
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; font-weight: bold;"><?php echo Url::get('bar_name');?></td>
    </tr>
</table>
<!--/IF:first_page-->