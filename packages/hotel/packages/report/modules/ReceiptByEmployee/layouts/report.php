<!---------REPORT----------->
<style>
	.report_table_column{
		width:65px;	
	}
	.simple-layout-middle{
		width:100%;	
	}
</style>
	<?php $user = ''; $total_revenue = 0;?>
    <!--LIST:users-->
    	<!--LIST:items-->
		<?php if([[=users.id=]] == [[=items.receptionist_id=]]){
        	 	if($user !='' && $user != [[=items.receptionist_id=]]){ ?>
                <tr>	
                    <td align="right" colspan="3" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
                    <td align="right"  class="report_table_column" ><strong><?php echo System::Display_number($total[$user]);?></strong></td>
                </tr>
                </table>
                <p>&nbsp;&nbsp;</p>
        		<?php }if($user =='' || $user != [[=items.receptionist_id=]]){?>
				<span style="float:left;font-weight:bold;margin-bottom:10px; width: 300px;">[[.employee.]]: [[|users.full_name|]]</span>
                <table cellpadding="5" cellspacing="0" width="300px" border="1" bordercolor="#CCCCCC" class="table-bound">
                <tr valign="middle" bgcolor="#EFEFEF">
                    <th align="center" class="report-table-header" style="width:10px !important; ">[[.stt.]]</th>
                    <th width="50px" class="report-table-header">[[.order_id.]]</th>
                    <!--<th width="100" class="report-table-header">[[.date.]]</th>-->
                    <th width="100px" class="report-table-header">[[.table_name.]]</th>  
                    <th width="100px" align="center" class="report-table-header">[[.total.]]</th>     
                </tr>
			<?php }
            $user = [[=items.receptionist_id=]];
			if(isset($total[$user])){	
				$total[$user] += [[=items.total=]];
			}else{
				$total[$user] = [[=items.total=]];
			}
            ?>
        <tr bgcolor="white">
                <td valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
                <td align="center" class="report_table_column">[[|items.code|]]</td>
                <!--<td align="center" class="report_table_column" ><?php echo date('h:i d/m/Y',[[=items.time_out=]]);?></td>-->
                <td align="center" class="report_table_column" >[[|items.table_name|]]</td>
                <td align="right" class="report_table_column" >
                <?php 
                    echo [[=items.total=]]?System::display_number([[=items.total=]]):'';
                    $total_revenue += [[=items.total=]]?[[=items.total=]]:0;
                ?>
                </td>
            </tr>
             <?php }
        ?>
        <!--/LIST:items-->
    <!--/LIST:users-->
     	<tr>	
            <td align="right" colspan="3" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <td align="right"  class="report_table_column" ><strong><?php if( isset($total[$user]) )echo System::Display_number($total[$user]); else echo 0;?></strong></td>
        </tr>
        </table>
        <br />
        <div><strong>[[.total.]]: <?php echo $total_revenue?System::display_number($total_revenue):0 ?></strong></div>
</div>
</div>
