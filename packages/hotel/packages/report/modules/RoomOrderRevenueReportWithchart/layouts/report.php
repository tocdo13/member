<?php 
$array_total = array();
$array_total[1]=[[=group_function_params=]]['total_cash'];
$array_total[2]=[[=group_function_params=]]['CREDIT_CARD_1_USD']
                +[[=group_function_params=]]['CREDIT_CARD_1_VND']
                +[[=group_function_params=]]['CREDIT_CARD_2_USD']
                +[[=group_function_params=]]['CREDIT_CARD_2_VND']
                +[[=group_function_params=]]['CREDIT_CARD_3_USD']
                +[[=group_function_params=]]['CREDIT_CARD_3_VND'];
$array_total[3]=[[=group_function_params=]]['debit'];
$array_total[4]=[[=group_function_params=]]['foc'];
$array_total[5]=[[=group_function_params=]]['total_bank'];
?>

<style>
	.simple-layout-middle{
		width:100%;	
	}
</style>
<!---------REPORT----------->
<?php $i=0;$j=0;$k=0;?>
<!--LIST:payment_types--><?php $i++;?><!--/LIST:payment_types-->
<!--LIST:currencies--><?php $j++;?><!--/LIST:currencies-->
<!--LIST:credit_card--><?php $k++;?><!--/LIST:credit_card-->
<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="1%" rowspan="3" align="left" nowrap="nowrap" class="report-table-header">[[.stt.]]</th>
	  <th width="30px" rowspan="3" class="report-table-header">[[.order_id.]]</th>
      <th width="30px" rowspan="3" class="report-table-header">[[.order_code.]]</th>
	  <th width="150px" rowspan="3" class="report-table-header">[[.room.]]</th>
	  <th rowspan="3" class="report-table-header" width="50px">[[.date.]]</th>
	  <th rowspan="3" class="report-table-header" width="65px">[[.pre_deposit.]]</th>
	  <th colspan="<?php echo (($j==1)?($i*$j):(($i*$j)-3));?>" class="report-table-header">[[.payment.]]</th>
	  <th rowspan="3" align="center" class="report-table-header" width="100px">[[.total.]]</th>     
	  <th rowspan="3" class="report-table-header">[[.user.]]</th>
  </tr>
	<tr valign="middle" bgcolor="#EFEFEF">
    <!--LIST:payment_types-->
        <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	<th class="report-table-header">[[|payment_types.name|]]</th>
        <?php }elseif([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        	<th class="report-table-header" colspan="<?php echo $j;?>">[[|payment_types.name|]]</th>
         <?php }else{?>
         	<th class="report-table-header">[[|payment_types.name|]]</th>
         <?php }?>
    <!--/LIST:payment_types-->
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
     <!--LIST:payment_types-->
      	<?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
                <th class="report-table-header">&nbsp;</th>
        <?php }else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        	 <!--LIST:currencies-->
				<th class="report-table-header">[[|currencies.id|]]</th>
   			 <!--/LIST:currencies-->
         <?php }else{?>
         		<th class="report-table-header"></th>
         <?php }?>
      <!--/LIST:payment_types-->    
     </tr> 
    <!--LIST:items-->
    <?php if([[=items.group=]]==1){
		$cond = ' id='.[[=items.r_id=]].'&cmd=group_invoice&customer_id='.[[=items.customer_id=]];
	}else{
		$cond = ' traveller_id='.[[=items.traveller_id=]];
	}
	?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap align="center" class="report_table_column" width="30px" onclick="window.open('?page=view_traveller_folio&folio_id=[[|items.id|]]&<?php echo $cond;?>');">[[|items.code|]]</td>
        <td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.vat_code|]]</td>
        <td align="left" class="report_table_column" ><div style="float:left;width:150px;font-size:11px;">
            <!--IF:cond(isset([[=items.booking_code=]]) and trim([[=items.booking_code=]]))-->[[.booking_code.]]: [[|items.booking_code|]]<br><!--/IF:cond-->[[|items.guest_name|]]<br> [[.recode.]]: [[|items.r_id|]]<br>
           <!--IF:cond_r([[=items.room_name=]]!='')--><strong>P:[[|items.room_name|]]</strong> <!--/IF:cond_r-->
         </div></td>
      <td nowrap align="center" class="report_table_column" ><?php echo date('d/m/Y',[[=items.time=]]);?></td>
        <td align="right" nowrap class="report_table_column" >[[|items.deposit|]]</td>
        <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){ $card = 0;?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$cr])){
						//echo '<td align="right" nowrap class="report_table_column" >'.System::Display_number($this->map['items']['current'][$cr]).'</td>';	
						$card += $this->map['items']['current'][$cr];
					}else{
						//echo '<td align="right" nowrap class="report_table_column" >&nbsp;</td>';
					}?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php echo '<td align="right" nowrap class="report_table_column1" >'.System::Display_number($card).'</td>';	
			}else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$tt])){
					echo '<td align="right" nowrap class="report_table_column2" >'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}else{
					echo '<td align="right" nowrap class="report_table_column3" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column4" ><?php echo System::Display_number([[=items.debit=]]);?></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column5" ><?php echo System::Display_number([[=items.foc=]]);?></td>
         <?php }?>
        <!--/LIST:payment_types-->
        <td nowrap align="right"><span class="report_table_column6"><?php echo System::display_number([[=items.total=]]);?></span></td>
        <td nowrap align="center">[[|items.user_id|]]</td>
	</tr>
   	<!--/LIST:items-->
    <?php $total_card = 0;?>
    <tr>
    	<td colspan="5" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
    	<td align="right" class="report_table_column_test1"><strong><?php echo System::display_number([[=group_function_params=]]['deposit']);?></strong></td>
     <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$cr])){
						//echo '<td align="right" nowrap class="report_table_column_test2" ><strong>'.System::Display_number([[=group_function_params=]][$cr]).'</strong></td>';	
						$total_card += [[=group_function_params=]][$cr];
					}else{
						//echo '<td align="right" nowrap class="report_table_column_test3" >&nbsp;</td>';
					}?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php echo '<td align="right" nowrap class="report_table_column_test4" ><strong>'.System::Display_number($total_card).'</strong></td>';
			}else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
					echo '<td align="right" nowrap class="report_table_column_test5" ><strong>'.System::Display_number([[=group_function_params=]][$tt]).'</strong></td>';  
				}else{
					echo '<td align="right" nowrap class="report_table_column_test6" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column_test7" ><strong><?php echo System::Display_number([[=group_function_params=]]['debit']);  ?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column_test8" ><strong><?php echo System::Display_number([[=group_function_params=]]['foc']); ?></strong></td>
         <?php }?>
        <!--/LIST:payment_types-->
         	<td align="right" nowrap class="report_table_column_test9" ><strong><?php echo System::Display_number([[=group_function_params=]]['total']);?></strong></td>
          	<td align="right" nowrap class="report_table_column_test10" >&nbsp;</td>
       </tr>
    	<!--/IF:cond_d-->
        
</table>

<!--Datkn ch?nh s?a-->
<br /><!--IF:page_no([[=page_no=]])--><center><b>[[.page.]] [[|page_no|]]/[[|total_page|]]</b></center><!--/IF:page_no-->

</div>
</div>
<br/><br/><br/><br/>
<table width="100%" >
    <tr align="center" >
        <td  ><div id="chart" style="height: 400; margin: 0 auto; " ></div></td>
    </tr>
    <tr >
        <td align="center" style="font-weight: bold;" > [[.price_unit.]]: 1000 VND</td>
    </tr>
</table>
<br/><br/><br/>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var chart;
        var items = <?php
                        echo json_encode($array_total);
                    ?>;
        var data_chart = [];
        
        data_chart[0]=[];
        data_chart[0][0] = '[[.cash.]]';
        data_chart[0][1] = items[1]/1000;
        
        data_chart[1]=[];
        data_chart[1][0] = '[[.credit_card.]]';
        data_chart[1][1] = items[2]/1000;
        
        data_chart[2]=[];
        data_chart[2][0] = '[[.debit.]]';
        data_chart[2][1] = items[3]/1000;
        
        data_chart[3]=[];
        data_chart[3][0] = '[[.free.]]';
        data_chart[3][1] = items[4]/1000;
        
        data_chart[4]=[];
        data_chart[4][0] = '[[.Bank tranfer.]]';
        data_chart[4][1] = items[5]/1000;
        
        console.log(data_chart);
        var tong = 0;
        
        chart = new Highcharts.Chart(
        {
            chart:{
                renderTo:'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            colors: [
               '#890d43', 
               '#ff0000', 
               '#0099ff', 
               '#99ff00', 
               '#003322', 
               '#001d59',
               '#fc5403'
            ],
            title:{
                text: '[[.room_revenue_comparing_bar_chart_by_invoice.]] '
                ,style: {
               	    fontSize: '28px'
                }     
            },
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000',
						formatter: function() {
                            if(this.y>0)
                            {
                                return this.point.name + '(' + roundNumber(this.percentage,1) + ' %) '+ number_format(this.y);
                            }
                            else
                                enabled: false
						},
                        style: {
                        	fontSize: '12px'
                        }
					},
					showInLegend: true,
				}
			},
            tooltip:{
                formatter: function() {
                    tong = (this.y/this.percentage)*100;
                    if(this.y > 0)
                        return '<b>'+this.point.name+'</b>:'+ roundNumber(this.percentage,1) +' % <br/>'
                            +'[[.revenue.]] :<b>'+number_format(this.y)+'</b><br/>'
                            +'[[.total.]] :<b>'+number_format(tong)+'</b>';
                    else
                        enable: false
				}
            },
            series: [{
				type: 'pie',
				name: '[[.piechart_room_revenue.]]',
				data:data_chart
			}]
        });
    });
    
    
</script>