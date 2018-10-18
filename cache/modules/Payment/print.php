<style>
	#genneral tr td{
        font-size:18px;
        line-height: 25px;
	}
    .genneral tr td{
        font-size:18px;
        line-height: 30px;	
    }
</style>
<div id="bound" style="width: 960px; margin:auto; margin-top:10px; padding-top:10px;">
    <table style="width: width: 100%; margin: 0px auto;">
        <tr style="width: 40%;"> 
            <td rowspan="3" style="text-align: left;"><img src="<?php echo HOTEL_LOGO; ?>" style="width: auto; height: 70px;" /></td>
        </tr>
        <tr>
            <td style="width: 50%;text-align: center;">
                <span style="font-size: 15pt; color: #ba1c00;" ><b><?php echo HOTEL_NAME; ?></b></span>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;font-size: 9pt; text-align: center;">
                <span style="color: #85200c;"><strong>Add: <?php echo HOTEL_ADDRESS; ?></strong></span><br /><span style="color: #85200c;"><strong>Tel: <?php echo HOTEL_PHONE; ?> - Fax: <?php echo HOTEL_FAX; ?></strong></span><br /><span style="color: #85200c;"><strong>Web: <?php echo HOTEL_WEBSITE; ?> - Email: <?php echo HOTEL_EMAIL; ?></strong></span>
            </td>
        </tr>
    </table>
    <br />
    <table style="width: 100%; margin: 0px auto;">
        <tr>
            <td style="text-align: center;font-size: 19px;"><b><?php echo Portal::language('DEPOSIT_INVOICE');?></b></td>
        </tr>
    </table>
    <!--IF:cond_customer($this->map['customers'] && !empty($this->map['customers']))-->
    <table cellpadding="0" width="100%" style="margin:auto; margin-top:15px;" cellspacing="0" id="genneral">
    <?php if(isset($this->map['customers']) and is_array($this->map['customers'])){ foreach($this->map['customers'] as $key1=>&$item1){if($key1!='current'){$this->map['customers']['current'] = &$item1;?>
    	<tr>    
        	<td width="121"><?php echo Portal::language('source');?>:</td>
            <td align="left"><?php echo $this->map['customers']['current']['full_name'];?></td>
            <td width="121"><?php echo Portal::language('reservation_room_and_reservation');?>:</td>
            <td align="left"><?php echo $this->map['booking_code'];?></td>
        </tr>
    <?php }}unset($this->map['customers']['current']);} ?>
        <tr>   
            <td width="111"><?php echo Portal::language('print_by');?>:</td>
            <td align="left"><?php echo $this->map['person_print'];?></td>
            <td width="111"><?php echo Portal::language('print_time');?>:</td>
            <td align="left"><?php echo date('h\h:i d/m/Y',time());?></td>
        </tr>
    </table>
    <br />
    <?php $total = 0; ?>
    <table style="width: 100%; margin: 0 auto; border-collapse: collapse;" border="1" class="genneral">
        <tr>
            <td width="350" align="center"><strong><?php echo Portal::language('description');?></strong></td>
            <td width="200" align="center"><strong><?php echo Portal::language('time_dn');?></strong></td>
            <td width="125" align="center"><strong><?php echo Portal::language('pmtt');?></strong></td>
            <td width="150" align="center"><strong><?php echo Portal::language('amount');?></strong></td>
            <td width="111" align="center"><strong><?php echo Portal::language('receiver');?></strong></td>
        </tr>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
        <tr>
        	<td><?php echo $this->map['items']['current']['note'];?></td> 
        	<td><?php echo date('h\h:i d/m/Y',$this->map['items']['current']['time']);?></td>
            <td align="center"><?php echo Portal::language(strtolower($this->map['items']['current']['payment_type_id']));?></td> 
            <td align="right"><?php echo System::display_number($this->map['items']['current']['amount']).' (VND)'; $total +=$this->map['items']['current']['amount'];?></td>
            <td width="50" align="center"><?php echo $this->map['items']['current']['full_name'];?></td>  
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
        <tr>
            <td colspan="3" style="text-align: right;"><strong><?php echo Portal::language('total');?></strong></td>
            <td style="text-align: right;"><strong><?php echo System::display_number($total) . ' (VND)'; ?></strong></td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <br />
    <table style="width: 100%; margin: 0 auto;" id="genneral">
        <tr>
            <td><strong><u><?php echo Portal::language('note_deposit');?>:</u></strong></td>
        </tr>
        <br />
        <tr>
            <td><i class="fa fa-fw fa-dot-circle-o"></i> <?php echo Portal::language('note_deposit_1');?>.</td>
        </tr>
        <tr>
            <td><i class="fa fa-fw fa-dot-circle-o"></i> <?php echo Portal::language('note_deposit_2');?>.</td>
        </tr>
    </table>
    <br /><br />
    <table style="width: 100%; margin: 0 auto;" id="genneral">
        <tr>
            <td style="width: 50%; text-align: center;"><strong><?php echo Portal::language('guest_deposit');?></strong></td>
            <td style="width: 50%; text-align: center;"><strong><?php echo Portal::language('receiver_deposit');?></strong></td>
        </tr>
    </table>
</div>