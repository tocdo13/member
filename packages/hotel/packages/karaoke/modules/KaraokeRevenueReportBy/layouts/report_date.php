<!--REPORT-->
    <!---TITLE--->
	<tr valign="middle" bgcolor="#EFEFEF">
        <th width="10px" class="report-table-header">[[.stt.]]</th>
        <th width="100px" class="report-table-header">[[.date.]]</th>
        <!--LIST:categories_invi-->
        <th width="50px" class="report-table-header">[[|categories_invi.name|]]</th>
        <!--/LIST:categories_invi-->
        <?php if(count([[=categories_hidd=]])){ ?>
        <th width="50px" class="report-table-header"><?php echo count([[=categories_invi=]])?Portal::language('other'):Portal::language('karaoke & spa'); ?></th>
        <?php } ?>
        <th width="50px" class="report-table-header">[[.total.]]</th>
        <th width="50px" class="report-table-header">[[.deposit.]]</th>
        <th width="50px" class="report-table-header">[[.cash.]]</th>
        <th width="50px" class="report-table-header">[[.credit.]]</th>
        <th width="50px" class="report-table-header">[[.with_room.]]</th>
        <th width="50px" class="report-table-header">[[.free.]]</th>
        <th width="50px" class="report-table-header">[[.debit.]]</th>
        <th width="50px" class="report-table-header">[[.total.]]</th>
	</tr>
    <!---/TITLE--->
    
    <!---GROUP ABOVE--->
    <!--IF:page_no([[=page_no=]]>1)-->
    <!--LIST:last_group_function_params-->
    <tr>
        <th colspan="2">[[.last_page_summary.]]</th>
        <!--LIST:last_group_function_params.categories_invi-->
        <th align="right"><?php echo [[=last_group_function_params.categories_invi.total_amount=]]?System::display_number(round([[=last_group_function_params.categories_invi.total_amount=]])):""; ?></th>
        <!--/LIST:last_group_function_params.categories_invi-->
        <?php if(count([[=categories_hidd=]])){ ?>
        <th align="right"><?php echo [[=last_group_function_params.total_amount_nonfood_other=]]?System::display_number(round([[=last_group_function_params.total_amount_nonfood_other=]])):""; ?></th>
        <?php } ?>
        <th align="right" ><?php echo [[=last_group_function_params.total_p=]]?System::display_number(round([[=last_group_function_params.total_p=]])):""; ?></th>
        <th align="right"><?php echo [[=last_group_function_params.deposit=]]?System::display_number([[=last_group_function_params.deposit=]]):""; ?></th>
        <th align="right"><?php echo [[=last_group_function_params.cash=]]?System::display_number([[=last_group_function_params.cash=]]):""; ?></th>
        <th align="right"><?php echo [[=last_group_function_params.credit=]]?System::display_number([[=last_group_function_params.credit=]]):""; ?></th>
        <th align="right"><?php echo [[=last_group_function_params.with_room=]]?System::display_number([[=last_group_function_params.with_room=]]):""; ?></th>
        <th align="right"><?php echo [[=last_group_function_params.free=]]?System::display_number([[=last_group_function_params.free=]]):""; ?></th>
        <th align="right"><?php echo [[=last_group_function_params.debit=]]?System::display_number([[=last_group_function_params.debit=]]):""; ?></th>
        <th align="right" ><?php echo [[=last_group_function_params.total=]]?System::display_number([[=last_group_function_params.total=]]):""; ?></th>
    </tr>
    <!--/LIST:last_group_function_params-->
    <!--/IF:page_no-->
    <!---/GROUP ABOVE--->
        
    <!---CELL--->
    <!--LIST:items-->
    <tr>
        <td align="center">[[|items.stt|]]</td>
        <td align="center">[[|items.day|]]</td>
        <!--LIST:items.categories_invi-->
        <td align="right"><?php echo [[=items.categories_invi.total_amount=]]?System::display_number(round([[=items.categories_invi.total_amount=]])):""; ?></td>
        <!--/LIST:items.categories_invi-->
        <?php if(count([[=categories_hidd=]])){ ?>
        <td align="right"><?php echo [[=items.total_amount_nonfood_other=]]?System::display_number(round([[=items.total_amount_nonfood_other=]])):""; ?></td>
        <?php } ?>
        <td align="right" style="font-weight: bold;"><?php echo [[=items.total_p=]]?System::display_number(round([[=items.total_p=]])):""; ?></td>
        <td align="right"><?php echo [[=items.deposit=]]?System::display_number([[=items.deposit=]]):""; ?></td>
        <td align="right"><?php echo [[=items.cash=]]?System::display_number([[=items.cash=]]):""; ?></td>
        <td align="right"><?php echo [[=items.credit=]]?System::display_number([[=items.credit=]]):""; ?></td>
        <td align="right"><?php echo [[=items.with_room=]]?System::display_number([[=items.with_room=]]):""; ?></td>
        <td align="right"><?php echo [[=items.free=]]?System::display_number([[=items.free=]]):""; ?></td>
        <td align="right"><?php echo [[=items.debit=]]?System::display_number([[=items.debit=]]):""; ?></td>
        <td align="right" style="font-weight: bold;"><?php echo [[=items.total=]]?System::display_number([[=items.total=]]):""; ?></td>
    </tr>
    <!--/LIST:items-->
    <!---CELL--->
    
    <!--GROUP TOTAL-->
    <!--LIST:group_function_params-->
    <tr>
        <th colspan="2"><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></th>
        <!--LIST:group_function_params.categories_invi-->
        <th align="right"><?php echo [[=group_function_params.categories_invi.total_amount=]]?System::display_number(round([[=group_function_params.categories_invi.total_amount=]])):""; ?></th>
        <!--/LIST:group_function_params.categories_invi-->
        <?php if(count([[=categories_hidd=]])){ ?>
        <th align="right"><?php echo [[=group_function_params.total_amount_nonfood_other=]]?System::display_number(round([[=group_function_params.total_amount_nonfood_other=]])):""; ?></th>
        <?php } ?>
        <th align="right" style="font-weight: bold;"><?php echo [[=group_function_params.total_p=]]?System::display_number(round([[=group_function_params.total_p=]])):""; ?></th>
        <th align="right"><?php echo [[=group_function_params.deposit=]]?System::display_number([[=group_function_params.deposit=]]):""; ?></th>
        <th align="right"><?php echo [[=group_function_params.cash=]]?System::display_number([[=group_function_params.cash=]]):""; ?></th>
        <th align="right"><?php echo [[=group_function_params.credit=]]?System::display_number([[=group_function_params.credit=]]):""; ?></th>
        <th align="right"><?php echo [[=group_function_params.with_room=]]?System::display_number([[=group_function_params.with_room=]]):""; ?></th>
        <th align="right"><?php echo [[=group_function_params.free=]]?System::display_number([[=group_function_params.free=]]):""; ?></th>
        <th align="right"><?php echo [[=group_function_params.debit=]]?System::display_number([[=group_function_params.debit=]]):""; ?></th>
        <th align="right" style="font-weight: bold;"><?php echo [[=group_function_params.total=]]?System::display_number([[=group_function_params.total=]]):""; ?></th>
    </tr>
    <!--/LIST:group_function_params-->
    <!--/GROUP TOTAL-->
    
    <!--IF:page_no([[=page_no=]])-->
    <tr>
        <th colspan="<?php echo (10 + count([[=categories_invi=]])+1+count([[=categories_hidd=]])); ?>">
        <center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center>
        <!--IF:br([[=page_no=]]<[[=total_page=]])-->
        <br />
        <br />
        <!--/IF:br-->
        </th>
    </tr>
    <!--/IF:page_no-->
<!--/REPORT-->
    
