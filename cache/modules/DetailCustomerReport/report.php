<style>
    #report_content table tr{
        background: #fff;
    }
    #report_content table tr:hover{
        background: #eee;
    }
</style>
<div id="header_report" style="margin: 5px auto; width: 90%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 55px; text-align: center;"><div style="border-radius: 50%; width: 50px; height: 50px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 50px; height: auto;" /></div></td>
            <td style="width: 200px; text-align: left;">
                <p style="text-transform: uppercase;"><span style="font-size: 13px; font-weight: bold;"><?php echo HOTEL_NAME; ?></span><br />
                    <i><?php echo Portal::language('room_sale');?></i>
                </p>
            </td>
            <td style="text-align: center;">
                <h1 style="text-transform: uppercase;"><?php echo Portal::language('detail_customer_report');?></h1>
                <i><?php echo Portal::language('date_from');?> <?php echo $_REQUEST['date_from']; ?> <?php echo Portal::language('date_to');?> <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 255px; text-align: right;">
                <p> 
                    <?php echo Portal::language('saler');?>:<i> <?php echo Portal::language('all');?></i>
                </p>
                <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
            </td>
        </tr>
    </table>
</div>
<button id="export"><?php echo Portal::language('export');?></button>
<div id="report_content" style="width: 90%; margin: 10px auto;">
    <table id="tblExport" cellSpacing=0 border=1 style="width: 100%;">
        <tr style="background: #000;">
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('stt');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('company_name');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('date');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('saler');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('room_type');?></th>
            <th colspan="4" style="text-align: center; color: #fff;"><?php echo Portal::language('quantity');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('revenue');?></th>
        </tr>
        <tr style=" background: #000;">
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('room');?></th>
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('adult');?></th>
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('child');?></th>
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('child_under_five');?></th>
        </tr>
        <?php $company = ""; $a=0; $b=0; $sum_room = 0; $sum_adult = 0; $sum_child = 0; $sum_child_under_five = 0; $sum_price = 0; $company_date = ""; $company_date_room = "";
         ?>
        <?php if(isset($this->map['count_customer']) and is_array($this->map['count_customer'])){ foreach($this->map['count_customer'] as $key1=>&$item1){if($key1!='current'){$this->map['count_customer']['current'] = &$item1;?>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
            <?php if($this->map['count_customer']['current']['company']==$this->map['items']['current']['company_name']){
                        if($a==0){ $a+=1; $sum_room += $this->map['items']['current']['room_count']; $sum_adult += $this->map['items']['current']['sum_adult']; $sum_child += $this->map['items']['current']['sum_child']; $sum_child_under_five+=$this->map['items']['current']['sum_child_under_five']; $sum_price += $this->map['items']['current']['price'];
            ?>
            <tr>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['stt'];?></td>
                <td rowspan="<?php echo $this->map['count_customer']['current']['num']; ?>" style="text-align: left ; font-size: 15px; line-height: 20px; font-weight: bold;"><?php echo $this->map['count_customer']['current']['company'];?></td>
                <?php if( $company_date != $this->map['items']['current']['company_name'].'_'.$this->map['items']['current']['in_date'] ){ $company_date=$this->map['items']['current']['company_name'].'_'.$this->map['items']['current']['in_date'];  ?>
                <td rowspan="<?php echo $this->map['items']['current']['num'];?>" style="text-align: center;"><?php echo $this->map['items']['current']['in_date'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sale'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['room_type'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['room_count'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_adult'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_child'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_child_under_five'];?></td>
                <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
                <?php } ?>
            </tr>
                <?php if($this->map['count_customer']['current']['num']==1){ ?>
                  <tr style="background: #eee;">
                    <th></th>
                    <th colspan="4" style="text-align: right;"><?php echo Portal::language('sum');?>:</th>
                    <th style="text-align: center;"><?php echo $sum_room; ?></th>
                    <th style="text-align: center;"><?php echo $sum_adult; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child_under_five; ?></th>
                    <th style="text-align: right;"><?php echo System::display_number($sum_price); ?></th>
                </tr>  
                    
                <?php } ?>
            <?php }else{ $a+=1; $sum_room += $this->map['items']['current']['room_count']; $sum_adult += $this->map['items']['current']['sum_adult']; $sum_child += $this->map['items']['current']['sum_child']; $sum_child_under_five+= $this->map['items']['current']['sum_child_under_five']; $sum_price += $this->map['items']['current']['price'];
            ?>
            <tr>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['stt'];?></td>
                <td rowspan="<?php echo $this->map['items']['current']['num'];?>" style="text-align: center;"><?php echo $this->map['items']['current']['in_date'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sale'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['room_type'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['room_count'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_adult'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_child'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_child_under_five'];?></td>
                <td style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
               
            </tr>
            <?php if($a == $this->map['count_customer']['current']['num'] ){ ?>
                <tr style="background: #eee;">
                    <th></th>
                    <th colspan="4" style="text-align: right;"><?php echo Portal::language('sum');?>:</th>
                    <th style="text-align: center;"><?php echo $sum_room; ?></th>
                    <th style="text-align: center;"><?php echo $sum_adult; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child; ?></th>
                    <th style="text-align: center;"><?php echo $sum_child_under_five; ?></th>
                    <th style="text-align: right;"><?php echo System::display_number($sum_price); ?></th>
                </tr>
            <?php } ?>
            <?php  
            }
            ?>
            <?php
            }else{ ?>   
            <?php $a=0; $a=0; $b=0; $sum_room = 0; $sum_adult = 0; $sum_child = 0; $sum_child_under_five=0; $sum_price = 0; } ?>
        <?php }}unset($this->map['items']['current']);} ?>
        <?php }}unset($this->map['count_customer']['current']);} ?>
        <tr style="background: #000; height: 25px;">
            <th colspan="5" style="text-align: right; color:#fff;" ><?php echo Portal::language('total');?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_room']; ?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_adult']; ?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_child']; ?></th>
            <th style="text-align: center; color:#fff;"><?php echo $_REQUEST['total']['total_child_under_five']; ?></th>
            <th style="text-align: right; color:#fff;"><?php echo System::display_number($_REQUEST['total']['total_price']); ?></th>
        </tr>
    </table>

</div>
<?php //System::debug($this->map['count_customer']); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    });
</script>
