<!---------REPORT----------->
				<table cellpadding="0" cellspacing="0" style="border-collapse:collapse;" bordercolor="#000000" border="1" width="100%">
					<tr valign="middle" bgcolor="#EFEFEF" style="font-size:11px;">
						<th nowrap="nowrap" class="report_table_header" align="center">[[.stt.]]</th>
						<th nowrap class="report_table_header" align="center" width="100px">
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='country_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'country_name','year','month'));?>">
							[[.nationality.]]							</a>						</th>
                        <th><?php echo 'Lượng khách đến ngày: '.Url::get_value('arrival_date');?></th>    
						<th style="display:<?php echo $this->map['available']==1?'none':'';?>"><?php echo Url::get_value('arrival_date').'&nbsp&nbsp-&nbsp&nbsp'.Url::get_value('departure_date');?>  </th>
						<th align="right" class="report_table_header" width="20px">[[.total.]]</th>
					</tr>
                    <!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
                    <!--IF:cond([[=items.country_name=]] != '')-->
					<tr bgcolor="white" style="font-size:11px;">
						<td nowrap="nowrap" valign="top" align="center" class="report_table_column">
							[[|items.stt|]]						</td>
						<td align="left" class="report_table_column">
								[[|items.country_name|]]</td>
                        <td align="right" class="report_table_column" style="text-align: center">[[|items.r2|]]</td>
                        <td align="right" class="report_table_column" style="text-align: center;display:<?php echo $this->map['available']==1?'none':'';?>">[[|items.r1|]]</td>
                        <td style="text-align: center"><?php $sum = [[=items.r2=]] + [[=items.r1=]]; echo $sum; ?></td>
					</tr>
                    <!--/IF:cond-->
					<!--/LIST:items-->
                    </table>
		</tr>
		</table>
		<BR />
		<table width="100%" cellpadding="0" cellspacing="0" border="0" ondblclick="this.style.display='none';">
		<tr>
			<td width="20%" align="center">&nbsp;</td>
			<td width="60%">&nbsp;</td>
			<td align="center">Ch&#7919; k&yacute; c&#7911;a nh&acirc;n vi&ecirc;n <br>
			  (<em>k&yacute;, ghi r&otilde; h&#7885; t&ecirc;n</em>)</td>
		</tr>
		<tr height="100">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		</table>