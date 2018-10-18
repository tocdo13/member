<style type="text/css">
a:visited{color:#003399}
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
-->
</style>
<div align="right"><em>&#272;&#417;n v&#7883; t&iacute;nh: <?php echo HOTEL_CURRENCY;?></em>&nbsp;</div>

<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th  width="100px" align="center"  class="report_table_header" >Mã hóa đơn</th>
		<th  width="150px" align="center"  class="report_table_header" >[[.date.]]</th>
		<th  class="report_table_header" width="300px" class="report_table_header">[[.description.]]</th>
		<th  class="report_table_header" width="100px" class="report_table_header">Phí dịch vụ(5%)</th>
		<th  width="150px" align="center" class="report_table_header">Tiền phòng</th>
        <th  align="center" width="150px" class="report_table_header">Tổng tiền(thuế và phí)</th>
        <th  align="center" width="150px" class="report_table_header">Cộng theo hóa đơn VAT</th>
	</tr>
    <?php
        foreach([[=items=]] as $key => $value)
        {
            //System::debug($value);
            //$count = count($value);
            $total_rela = $value['total_rela'];
            unset($value['total_rela']);
            //System::debug($value);
        ?>
        <tr bgcolor="white">
        <td><?php echo $key;?></td>
        <td align="left" valign="top" nowrap class="report_table_column1"><?php ?>
            <div style="display: inline-block; height: 100%; width: 100%;">
            <table style="height: 100%; width: 100%;">
            <?php 
                $i = 0;
                foreach($value as $v)
                {
                    $i += 1;
            ?>
                <tr style="height: <?php echo ($v['total_relative']/$total_rela*100).'%'; ?>;">
                    <td <?php if($i < count($value)) {?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="center"><?php echo $v['time']; ?></td>
                </tr>
    	    <?php
                }
            ?>
            </table>
            </div>
		</td>
        <td align="left" valign="top" nowrap class="report_table_column1">
            <div style="display: inline-block; height: 100%; width: 100%;">
            <table style="height: 100%; width: 100%;">
            <?php 
                $i = 0;
                foreach($value as $v)
                {
                    $i += 1;
                    $j = 0;
            ?>
                <?php
                        if($v['total_room'] > 0)
                        {
                            $j += 1;
                 ?>
                        <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;"> 
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">   
                                <?php
                                    echo  $v['decription_room'];
                                ?>
                            </td>
                         </tr>
                    <?php 
                        }
                        if($v['total_bar'] > 0)
                        {
                            $j += 1;
                    ?>
                        <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;">
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">    
                                <?php
                                   echo $v['decription_bar'];
                                ?>
                            </td>
                        </tr>
                    <?php 
                        }
                        if($v['total_minibar'] > 0)
                        {
                            $j += 1;
                     ?>
                    <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;">
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">   
                            <?php
                                echo  $v['decription_minibar'];
                            ?>
                        </td>
                    </tr>
                    <?php 
                        }
                        if($v['total_laundry'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;">
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">   
                            <?php
                                echo  $v['decription_laundry'];
                            ?>
                        </td>
                    </tr>
                    <?php 
                        }
                         if($v['total_equipment'] > 0)
                         {
                            $j += 1;
                    ?>
                    <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;">
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">   
                            <?php
                            
                                echo  $v['decription_equipment'];
                            ?>
                        </td>
                    </tr>
                    <?php } 
                        if($v['total_telephone'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;">
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">   
                            <?php
                             
                                echo  $v['decription_phone'];
                            ?>
                        </td>
                    </tr>
                    <?php }
                        if($v['total_extra_service'] > 0)
                        {
                            $j += 1;
                    ?> 
                    <tr style="height: <?php echo (1/$total_rela*100).'%'; ?>;">
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="left">   
                            <?php
                             
                                echo  $v['decription_extra_service'];
                            ?>
                        </td>
                    </tr>
                 <?php } ?>
    	    <?php
                }
            ?>
            </table>
            </div>
        </td>
        <td align="left" valign="top" nowrap class="report_table_column1">
            <div style="display: inline-block; height: 100%; width: 100%;">
            <table style="height: 100%; width: 100%;">
            <?php 
                $i = 0;
                foreach($value as $v)
                {
                    $j = 0;
                    $i += 1;
            ?>
                <?php
                        if($v['total_room'] > 1000000 )
                        {
                            $j += 1;
                 ?>
                        <tr> 
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                                <?php
                                    echo  System::display_number($v['5%']);
                                ?>
                            </td>
                         </tr>
                    <?php 
                        }
                        if($v['total_bar'] > 0)
                        {
                            $j += 1;
                    ?>
                        <tr>
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">    
                                &nbsp;
                            </td>
                        </tr>
                    <?php 
                        }
                        if($v['total_minibar'] > 0)
                        {
                            $j += 1;
                     ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php 
                        }
                        if($v['total_laundry'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php 
                        }
                         if($v['total_equipment'] > 0)
                         {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php } 
                        if($v['total_telephone'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php }
                        if($v['total_extra_service'] > 0)
                        {
                            $j += 1;
                    ?> 
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php }?>
               
    	    <?php
                }
            ?>
            </table>
            </div> 
        </td>
        <td align="left" valign="top" nowrap class="report_table_column1">
            <div style="display: inline-block; height: 100%; width: 100%;">
            <table style="height: 100%; width: 100%;">
            <?php 
                $i = 0;
                foreach($value as $v)
                {
                    $j = 0;
                    $i += 1;
            ?>
                <?php
                        if($v['total_room'] > 0)
                        {
                            $j += 1;
                 ?>
                        <tr> 
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                                <?php
                                    echo  System::display_number($v['total_room']);
                                ?>
                            </td>
                         </tr>
                    <?php 
                        }
                        if($v['total_bar'] > 0)
                        {
                            $j += 1;
                    ?>
                        <tr>
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">    
                                &nbsp;
                            </td>
                        </tr>
                    <?php 
                        }
                        if($v['total_minibar'] > 0)
                        {
                            $j += 1;
                     ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                           &nbsp;
                        </td>
                    </tr>
                    <?php 
                        }
                        if($v['total_laundry'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php 
                        }
                         if($v['total_equipment'] > 0)
                         {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php } 
                        if($v['total_telephone'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php }
                        if($v['total_extra_service'] > 0)
                        {
                            $j += 1;
                    ?> 
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            &nbsp;
                        </td>
                    </tr>
                    <?php }?>
    	    <?php
                }
            ?>
            </table>
            </div> 
        </td>
        <td align="left" valign="top" nowrap class="report_table_column1">
          <div style="display: inline-block; height: 100%; width: 100%;">
            <table style="height: 100%; width: 100%;">
            <?php 
                $i = 0;
                foreach($value as $v)
                {
                    $i += 1;
                    $j = 0;
            ?>
                <?php
                        if($v['total_room'] > 0)
                        {
                            $j += 1;
                 ?>
                        <tr> 
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                                <?php
                                    echo  System::display_number($v['amount_room']);
                                ?>
                            </td>
                         </tr>
                    <?php 
                        }
                        if($v['total_bar'] > 0)
                        {
                            $j += 1;
                    ?>
                        <tr>
                            <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">    
                                <?php
                                    echo  System::display_number($v['amount_bar']);
                                ?>
                            </td>
                        </tr>
                    <?php 
                        }
                        if($v['total_minibar'] > 0)
                        {
                            $j += 1;
                     ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            <?php
                                echo  System::display_number($v['amount_minibar']);
                            ?>
                        </td>
                    </tr>
                    <?php 
                        }
                        if($v['total_laundry'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            <?php
                                echo  System::display_number($v['amount_laundry']);
                            ?>
                        </td>
                    </tr>
                    <?php 
                        }
                         if($v['total_equipment'] > 0)
                         {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            <?php
                            
                                echo  System::display_number($v['amount_equipment']);
                            ?>
                        </td>
                    </tr>
                    <?php } 
                        if($v['total_telephone'] > 0)
                        {
                            $j += 1;
                    ?>
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            <?php
                             
                                echo  System::display_number($v['amount_telephone']);
                            ?>
                        </td>
                    </tr>
                    <?php }
                        if($v['total_extra_service'] > 0)
                        {
                            $j += 1;
                    ?> 
                    <tr>
                        <td <?php if($i < count($value) and $j == $v['total_relative']) { $j = 0;?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right">   
                            <?php
                             
                                echo  System::display_number($v['amount_extra_service']);
                            ?>
                        </td>
                    </tr>
                    <?php }?>
    	    <?php
                }
            ?>
            </table>
            </div>   
        </td>
        <td align="left" valign="top" nowrap class="report_table_column1"><?php ?>
            <div style="display: inline-block; height: 100%; width: 100%;">
            <table style="height: 100%; width: 100%;">
            <?php 
                $i = 0;
                foreach($value as $v)
                {
                    $i += 1;
            ?>
                <tr style="height: <?php echo ($v['total_relative']/$total_rela*100).'%'; ?>;">
                    <td <?php if($i < count($value)) {?>style="border-bottom: solid 1px #CCCCCC;"<?php } ?> align="right"><?php echo System::display_number($v['total_order']); ?></td>
                </tr>
      	  <?php
                }
            ?>
            </table>
            </div>
		</td>
        </tr>
    <?php
    
      }  
    ?>
 </table>
	
<br/><br />
<br/>
<br/>
</div>
</div>