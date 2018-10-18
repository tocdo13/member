<style>
    table.cool tr{
        border-top: 1px dashed #dddddd;
    }
    table.cool tr:first-child{
        border-top: none;
    }
</style>
<table style="width: 100%; border: none;">
    <tr>
        <td style="width: 150px;">
            <?php echo HOTEL_NAME; ?><br />
            <?php echo HOTEL_ADDRESS; ?>
        </td>
        <td style="text-align: center;">
            <span style="font-size: 20px; font-weight: bold; text-transform: uppercase;">[[.breakfast_report.]]</span>
            <br />
            <?php if([[=today=]]==1){ ?>
            [[.to_day.]]
            <?php }else{ ?>
            [[.from_date.]]: [[|from_time|]] [[|date_from|]] - [[.to_date.]]: [[|to_time|]] [[|date_to|]]
            <?php } ?>
        </td>
        <td style="text-align: right; width: 150px;">
            <strong>[[.template_code.]]</strong>
            <br />
            Date:<?php echo date('d/m/Y H:i');?>
            <br />
            User:<?php echo User::id();?>
        </td>
    </tr>
</table>
<table style="width: 100%;" cellpadding="10" cellspacing="0" border="1">
    <tr>
	  <th rowspan="2" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header" >[[.guest_name.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.room.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header" >[[.adult.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header" >[[.num_child.]]</th>      
	  <th colspan="2" nowrap="nowrap" class="report_table_header" >[[.has_breakfast.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.arrival_date.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.departure_date.]]</th>
	  <th rowspan="2" nowrap="nowrap" class="report_table_header">[[.note.]]</th>
    </tr>
	<tr>
		<th width="60" nowrap="nowrap" class="report_table_header" >[[.yes.]]</th>
		<th width="60"  nowrap="nowrap" class="report_table_header">[[.No.]]</th>
	</tr>
    <!---------------------------->
    <!--LIST:items-->
    <tr>
        <td>[[|items.stt|]]</td>
        <td>
        <table style="width: 100%; text-align: center;" class="cool">
            <?php if([[=items.row=]]==0){}else{ 
                foreach([[=items.child_arr=]] as $key=>$value)
                    {    
            ?>
                    <tr>
                        <td><?php echo $value['full_name']; ?></td>
                    </tr>    
                <?php } ?>
            <?php } ?>
        </table>
        </td>
        <td>
            [[|items.room_name|]]
        </td>
        <td><?php if([[=items.adult=]]>0){ echo [[=items.adult=]]*[[=items.num=]]; }?></td>
        <td><?php if([[=items.child=]]>0){ echo [[=items.child=]]*[[=items.num=]]; }?></td>
        <td></td>
        <td></td>
        <td>[[|items.date_in|]]</td>
        <td>[[|items.date_out|]]</td>
        <td>[[|items.note|]]</td>
    </tr>
    <!--/LIST:items-->
</table>