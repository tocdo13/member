<?php
$i = 1;
$total_country = 0;
//System::debug($this->map['total_country']);
?>
<!---------------------- REPORT ----------------------------->
    <!----------------------HEADER-------------------------------->
    <table border="1" cellspacing="0" style="width: 98%; margin: 0px auto;">
        <tr style="background: #ddd;">
            <th style="text-align: center; text-transform: uppercase; width: 25px;" rowspan="2">
                <?php echo Portal::language('stt');?>
            </th>
            <th style="text-align: center; text-transform: uppercase;" rowspan="2">
                <?php echo Portal::language('nationality');?>
            </th>
            <th style="text-align: center; text-transform: uppercase;" colspan="<?php echo $this->map['full_day'];?>">
                <?php echo Portal::language('day');?>
            </th>
            <th style="text-align: center; text-transform: uppercase;" rowspan="2">
                <?php echo Portal::language('total');?>
            </th>
        </tr>
        <tr style="background: #ddd;">
            <?php
                for($col=1;$col<=$this->map['full_day'];$col++){
                    if($col<10){
                        $col='0'.$col;
                    }
                    echo "<th style='width: 20px; height: 20px; text-align: center;'>".$col."</th>";
                }
            ?>
            
        </tr>
    <!----------------------END HEADER-------------------------------->
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr>
                <td style="text-align: center;"><?php echo $i++; ?></td>
                <td style="text-align: center;"><?php if($this->map['items']['current']['country_name']!='country_other'){echo $this->map['items']['current']['country_name'];}else{?> <?php echo Portal::language('country_other');?> <?php } ?></td>
                <!--<td style="text-align: center;"><?php echo $this->map['items']['current']['country_name'];?></td>-->
                <?php
                    foreach($this->map['items']['current']['day'] as $traveller_day){
                        if($traveller_day>0)
                        echo "<td style='width: 20px; height: 20px; text-align: center;'>".$traveller_day."</td>";
                        else
                        echo "<td style='width: 20px; height: 20px; text-align: center;'></td>";
                    }
                ?>
                <td style="text-align: center; font-weight: bold; background: #ddd;"><?php echo $this->map['items']['current']['total_country'];?> <?php $total_country += $this->map['items']['current']['total_country']; ?></td>
            </tr>
        <?php }}unset($this->map['items']['current']);} ?>
            <tr style="background: #ddd; font-weight: bold;">
                <td style="text-align: center;" colspan="2"><?php echo Portal::language('summary');?></td>
                <?php
                    foreach($this->map['total_country'] as $total_day){
                        if($total_day>0)
                        echo "<td style='text-align: center;'>".$total_day."</td>";
                        else
                        echo "<td style='text-align: center;'></td>";
                    }
                ?>
                <td style="text-align: center;"><?php echo $total_country; ?></td>
            </tr>
    </table>