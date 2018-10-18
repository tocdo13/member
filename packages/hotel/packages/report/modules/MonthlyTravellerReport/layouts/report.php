<?php
$i = 1;
$total_country = 0;
//System::debug([[=total_country=]]);
?>
<!---------------------- REPORT ----------------------------->
    <!----------------------HEADER-------------------------------->
    <table border="1" cellspacing="0" style="width: 98%; margin: 0px auto;">
        <tr style="background: #ddd;">
            <th style="text-align: center; text-transform: uppercase; width: 25px;" rowspan="2">
                [[.stt.]]
            </th>
            <th style="text-align: center; text-transform: uppercase;" rowspan="2">
                [[.nationality.]]
            </th>
            <th style="text-align: center; text-transform: uppercase;" colspan="[[|full_day|]]">
                [[.day.]]
            </th>
            <th style="text-align: center; text-transform: uppercase;" rowspan="2">
                [[.total.]]
            </th>
        </tr>
        <tr style="background: #ddd;">
            <?php
                for($col=1;$col<=[[=full_day=]];$col++){
                    if($col<10){
                        $col='0'.$col;
                    }
                    echo "<th style='width: 20px; height: 20px; text-align: center;'>".$col."</th>";
                }
            ?>
            
        </tr>
    <!----------------------END HEADER-------------------------------->
        <!--LIST:items-->
            <tr>
                <td style="text-align: center;"><?php echo $i++; ?></td>
                <td style="text-align: center;"><?php if([[=items.country_name=]]!='country_other'){echo [[=items.country_name=]];}else{?> [[.country_other.]] <?php } ?></td>
                <!--<td style="text-align: center;">[[|items.country_name|]]</td>-->
                <?php
                    foreach([[=items.day=]] as $traveller_day){
                        if($traveller_day>0)
                        echo "<td style='width: 20px; height: 20px; text-align: center;'>".$traveller_day."</td>";
                        else
                        echo "<td style='width: 20px; height: 20px; text-align: center;'></td>";
                    }
                ?>
                <td style="text-align: center; font-weight: bold; background: #ddd;">[[|items.total_country|]] <?php $total_country += [[=items.total_country=]]; ?></td>
            </tr>
        <!--/LIST:items-->
            <tr style="background: #ddd; font-weight: bold;">
                <td style="text-align: center;" colspan="2">[[.summary.]]</td>
                <?php
                    foreach([[=total_country=]] as $total_day){
                        if($total_day>0)
                        echo "<td style='text-align: center;'>".$total_day."</td>";
                        else
                        echo "<td style='text-align: center;'></td>";
                    }
                ?>
                <td style="text-align: center;"><?php echo $total_country; ?></td>
            </tr>
    </table>