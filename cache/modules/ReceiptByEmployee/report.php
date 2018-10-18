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
    <?php if(isset($this->map['users']) and is_array($this->map['users'])){ foreach($this->map['users'] as $key1=>&$item1){if($key1!='current'){$this->map['users']['current'] = &$item1;?>
    	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
		<?php if($this->map['users']['current']['id'] == $this->map['items']['current']['receptionist_id']){
        	 	if($user !='' && $user != $this->map['items']['current']['receptionist_id']){ ?>
                <tr>	
                    <td align="right" colspan="3" class="report_sub_title" ><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
                    <td align="right"  class="report_table_column" ><strong><?php echo System::Display_number($total[$user]);?></strong></td>
                </tr>
                </table>
                <p>&nbsp;&nbsp;</p>
        		<?php }if($user =='' || $user != $this->map['items']['current']['receptionist_id']){?>
				<span style="float:left;font-weight:bold;margin-bottom:10px; width: 300px;"><?php echo Portal::language('employee');?>: <?php echo $this->map['users']['current']['full_name'];?></span>
                <table cellpadding="5" cellspacing="0" width="300px" border="1" bordercolor="#CCCCCC" class="table-bound">
                <tr valign="middle" bgcolor="#EFEFEF">
                    <th align="center" class="report-table-header" style="width:10px !important; "><?php echo Portal::language('stt');?></th>
                    <th width="50px" class="report-table-header"><?php echo Portal::language('order_id');?></th>
                    <!--<th width="100" class="report-table-header"><?php echo Portal::language('date');?></th>-->
                    <th width="100px" class="report-table-header"><?php echo Portal::language('table_name');?></th>  
                    <th width="100px" align="center" class="report-table-header"><?php echo Portal::language('total');?></th>     
                </tr>
			<?php }
            $user = $this->map['items']['current']['receptionist_id'];
			if(isset($total[$user])){	
				$total[$user] += $this->map['items']['current']['total'];
			}else{
				$total[$user] = $this->map['items']['current']['total'];
			}
            ?>
        <tr bgcolor="white">
                <td valign="top" align="right" class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
                <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['code'];?></td>
                <!--<td align="center" class="report_table_column" ><?php echo date('h:i d/m/Y',$this->map['items']['current']['time_out']);?></td>-->
                <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['table_name'];?></td>
                <td align="right" class="report_table_column" >
                <?php 
                    echo $this->map['items']['current']['total']?System::display_number($this->map['items']['current']['total']):'';
                    $total_revenue += $this->map['items']['current']['total']?$this->map['items']['current']['total']:0;
                ?>
                </td>
            </tr>
             <?php }
        ?>
        <?php }}unset($this->map['items']['current']);} ?>
    <?php }}unset($this->map['users']['current']);} ?>
     	<tr>	
            <td align="right" colspan="3" class="report_sub_title" ><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <td align="right"  class="report_table_column" ><strong><?php if( isset($total[$user]) )echo System::Display_number($total[$user]); else echo 0;?></strong></td>
        </tr>
        </table>
        <br />
        <div><strong><?php echo Portal::language('total');?>: <?php echo $total_revenue?System::display_number($total_revenue):0 ?></strong></div>
</div>
</div>
