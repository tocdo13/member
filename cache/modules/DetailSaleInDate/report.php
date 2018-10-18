<style>
    *{
        line-height: 20px;
    }
    #report_content table tr{
        background: #fff;
    }
    #report_content table tr:hover{
        background: #eee;
    }
</style>
<table id="export" style="width: 100%;">
    <tr>
        <td>
        
        
<div id="header_report" style="margin: 5px auto; width: 90%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 55px; text-align: center;" class="class_none"><div style="border-radius: 50%; width: 50px; height: 50px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 50px; height: auto;" /></div></td>
            <td style="width: 200px; text-align: left;">
                <p style="text-transform: uppercase;" class="class_none"><span style="font-size: 13px; font-weight: bold;"><?php echo HOTEL_NAME; ?></span><br />
                    <i><?php echo Portal::language('room_sale');?></i>
                </p>
            </td>
            <td style="text-align: center;">
                <h1 style="text-transform: uppercase;"><?php echo Portal::language('detail_sale_in_date');?></h1>
                <i><?php echo Portal::language('date_from');?> <?php echo $_REQUEST['date_from']; ?> <?php echo Portal::language('date_to');?> <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 255px; text-align: right;" class="class_none">
                <p><?php echo Portal::language('saler');?>: <i><?php echo Portal::language('all');?></i>
                    <br />
                    <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                    <br />
                    <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                </p>
            </td>
        </tr>
    </table>
</div>
<form name="DetailSaleInDateFrom" method="post">

<div id="report_content" style="width: 90%; margin: 10px auto;">
    <div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></div>
    <table cellSpacing=0 border=1 style="width: 100%;" >
        <tr style="background: #000;">
            <th rowspan="2" style="text-align: center; color: #fff; width: 40px;"><?php echo Portal::language('stt');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('date');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('saler');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('room_type');?></th>
            <th colspan="3" style="text-align: center; color: #fff;"><?php echo Portal::language('quantity');?></th>
            <th rowspan="2" style="text-align: center; color: #fff;"><?php echo Portal::language('revenue');?></th>
        </tr>
        <tr style=" background: #000;">
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('room');?></th>
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('adult');?></th>
            <th style="text-align: center; color: #fff;"><?php echo Portal::language('child');?></th>
        </tr>
        <?php $i=0; $sum_room=0; $sum_adult=0; $sum_child=0; $sum_price=0; ?>
        <?php foreach($_REQUEST['in_date'] as $id=>$content){ ?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
               <?php if($content['in_date']==$this->map['items']['current']['in_date']){
                    if($i==0){ $i+=1; $sum_room +=$this->map['items']['current']['room_count']; $sum_adult += $this->map['items']['current']['sum_adult']; $sum_child += $this->map['items']['current']['sum_child']; $sum_price += $this->map['items']['current']['price'];
               ?>
                   <tr>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['stt'];?></td>
                        <td style="text-align: center;" rowspan="<?php echo $content['num']; ?>"><?php echo $content['in_date']; ?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['sale'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['room_type'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['room_count'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_adult'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_child'];?></td>
                        <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
                   </tr>
                   <?php if($content['num']==1){ ?>
                   <tr>
                        <th colspan="4" style="border-top: none; text-align: right;"><?php echo Portal::language('sum');?>: </th>
                        <th style="text-align: center;"><?php echo $sum_room; ?></th>
                        <th style="text-align: center;"><?php echo $sum_adult; ?></th>
                        <th style="text-align: center;"><?php echo $sum_child; ?></th>
                        <th style="text-align: right;" class="change_numTr"><?php echo System::display_number($sum_price); ?></th>
                   </tr>
                   <?php } ?>
               <?php
               }else{ $i+=1; $sum_room +=$this->map['items']['current']['room_count']; $sum_adult += $this->map['items']['current']['sum_adult']; $sum_child += $this->map['items']['current']['sum_child']; $sum_price += $this->map['items']['current']['price'];
                ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['stt'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['sale'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['room_type'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['room_count'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_adult'];?></td>
                        <td style="text-align: center;"><?php echo $this->map['items']['current']['sum_child'];?></td>
                        <td style="text-align: right;" class="change_numTr"><?php echo System::display_number($this->map['items']['current']['price']); ?></td>
                    </tr>
                    <?php if($i==$content['num']){ ?>
                        <tr>
                        <th colspan="4" style="border-top: none; text-align: right;"><?php echo Portal::language('sum');?>: </th>
                        <th style="text-align: center;"><?php echo $sum_room; ?></th>
                        <th style="text-align: center;"><?php echo $sum_adult; ?></th>
                        <th style="text-align: center;"><?php echo $sum_child; ?></th>
                        <th style="text-align: right;" class="change_numTr"><?php echo System::display_number($sum_price); ?></th>
                        </tr>
                    <?php } ?>
                <?php
               }}else{ 
                $i=0; $sum_room=0; $sum_adult=0; $sum_child=0; $sum_price=0;
               }?>
            <?php }}unset($this->map['items']['current']);} ?>
        <?php } ?>
        <tr style="background: #000;">
            <th colspan="4" style="text-align: right; color: #fff;"><?php echo Portal::language('total_amount');?></th>
            <th style="text-align: center; color: #fff;"><?php echo $_REQUEST['sammary']['total_room']; ?></th>
            <th style="text-align: center; color: #fff;"><?php echo $_REQUEST['sammary']['total_adult']; ?></th>
            <th style="text-align: center; color: #fff;"><?php echo $_REQUEST['sammary']['total_child']; ?></th>
            <th style="text-align: right; color: #fff;" class="change_numTr"><?php echo System::display_number($_REQUEST['sammary']['total_price']); ?></th>
        </tr>
    </table>

</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</td>
    </tr>
</table>
<script>
    jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        jQuery('.class_none').remove();
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })

</script>
<?php //System::debug($this->map['count_customer']); ?>