<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}

</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('departure_room_list_new');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('day');?>&nbsp;<?php echo $this->map['day'];?>
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>
<!---------SEARCH----------->
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td><?php echo Portal::language('hotel');?></td>
                                	<td><select  name="portal_id" id="portal_id" style="width: 150px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <?php }?>
                                    <td><?php echo Portal::language('date');?></td>
                                	<td><input  name="date" id="date" style="width: 70px;" / type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
                                    <td><?php echo Portal::language('status');?></td>
                                	<td><select  name="status" id="status" style="width: 100px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('customer');?></td>
                                    <td><select  name="customer_id" id="customer_id" style="width: 150px;"><?php
					if(isset($this->map['customer_id_list']))
					{
						foreach($this->map['customer_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))
                    echo "<script>$('customer_id').value = \"".addslashes(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/></td>
                                    <td><button id="export"><?php echo Portal::language('export_excel');?></button></td>
                                
                                </tr>
                            </table>
                        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#date').datepicker();
    }
);
</script>
<!---------REPORT----------->	
<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
    <tr bgcolor="#EFEFEF">
        <th width="10px" style="text-align: center;"><?php echo Portal::language('stt');?></th>
        <th width="20px" style="text-align: center;"><?php echo Portal::language('reservation_room_code');?></th>
        <th width="100px" style="text-align: center;"><?php echo Portal::language('company');?></th> 
        <th width="100px" style="text-align: center;"><?php echo Portal::language('note');?></th> 
        <th width="30px" style="text-align: center;"><?php echo Portal::language('room');?></th>
        <th width="50px" style="text-align: center;"><?php echo Portal::language('room_level');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('arrival_date');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('departure_date');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('status');?></th>
        <th width="20px" style="text-align: center;"><?php echo Portal::language('night');?></th>
        <th width="150px" style="text-align: center;"><?php echo Portal::language('guest_name');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('arrival_date');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('departure_date');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('flight_code_departure');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('flight_departure_time');?></th>
        <th width="60px" style="text-align: center;"><?php echo Portal::language('car_note_departure');?></th>
    </tr>
    <?php $r_id = '';$rr_id='';
    ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <?php if(isset($this->map['items']['current']['room']) and is_array($this->map['items']['current']['room'])){ foreach($this->map['items']['current']['room'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['room']['current'] = &$item2;?>
            <?php if(isset($this->map['items']['current']['room']['current']['traveller']) and is_array($this->map['items']['current']['room']['current']['traveller'])){ foreach($this->map['items']['current']['room']['current']['traveller'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['room']['current']['traveller']['current'] = &$item3;?>
                <tr>
                    <?php if($this->map['items']['current']['id']!=$r_id){ $r_id=$this->map['items']['current']['id'];?>
                        <td rowspan="<?php echo $this->map['items']['current']['count'];?>"><?php echo $this->map['items']['current']['stt'];?></td>
                        <td rowspan="<?php echo $this->map['items']['current']['count'];?>" style="text-align: center;"><b><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>" target="_blank"><?php echo $this->map['items']['current']['id'];?></a></b></td>
                        <td rowspan="<?php echo $this->map['items']['current']['count'];?>" style="text-align: left;"><div style="font-size:13px;"><b><?php echo $this->map['items']['current']['note'];?></b><br/><i><?php echo $this->map['items']['current']['reservation_note'];?></i></div></td>
                    <?php }?>
                    <?php if($this->map['items']['current']['room']['current']['id']!=$rr_id){ $rr_id=$this->map['items']['current']['room']['current']['id'];?>
                    <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>" style="text-align: left;"><i><?php echo $this->map['items']['current']['room']['current']['note_room'];?></i></td>
                        <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>" style="text-align: center;"><b><a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['id'],'r_r_id'=>$this->map['items']['current']['room']['current']['id']));?>" target="_blank"><?php echo $this->map['items']['current']['room']['current']['room_name'];?></a></b></td>
                        <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>"><?php echo $this->map['items']['current']['room']['current']['room_level'];?></td>
                        <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>" style="text-align: center;"><?php echo date('d/m/Y H:i',$this->map['items']['current']['room']['current']['time_in_room']);?></td>
                        <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>" style="text-align: center;"><?php echo date('d/m/Y H:i',$this->map['items']['current']['room']['current']['time_out_room']);?></td>
                        <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>"><?php echo $this->map['items']['current']['room']['current']['status'];?></td>
                        <td rowspan="<?php echo $this->map['items']['current']['room']['current']['count'];?>" style="text-align: center;"><?php echo $this->map['items']['current']['room']['current']['night'];?></td>
                    <?php }?>
                    <td><a href="<?php echo Url::build('traveller',array('id'=>$this->map['items']['current']['room']['current']['traveller']['current']['traveller_id']));?>" target="_blank"><?php echo $this->map['items']['current']['room']['current']['traveller']['current']['fullname'];?></a></td>
                    <td style="text-align: center;"><?php echo ($this->map['items']['current']['room']['current']['traveller']['current']['time_in']!='')?date('d/m/Y H:i',$this->map['items']['current']['room']['current']['traveller']['current']['time_in']):'';?></td>
                    <td style="text-align: center;"><?php echo ($this->map['items']['current']['room']['current']['traveller']['current']['time_out']!='')?date('d/m/Y H:i',$this->map['items']['current']['room']['current']['traveller']['current']['time_out']):'';?></td>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['room']['current']['traveller']['current']['flight_code_departure'];?></td>
                    <td style="text-align: center;"><?php echo ($this->map['items']['current']['room']['current']['traveller']['current']['flight_departure_time']!='' and $this->map['items']['current']['room']['current']['traveller']['current']['flight_departure_time']!=0)?date('d/m/Y H:i',$this->map['items']['current']['room']['current']['traveller']['current']['flight_departure_time']):'';?></td>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['room']['current']['traveller']['current']['car_note_departure'];?></td>
                </tr>
            <?php }}unset($this->map['items']['current']['room']['current']['traveller']['current']);} ?>
        <?php }}unset($this->map['items']['current']['room']['current']);} ?>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <td colspan="4"><b><?php echo Portal::language('total');?></b></td>
        <td style="text-align: center;"><b><?php echo $this->map['total_room'];?></b></td>
        <td colspan="4"></td>
        <td style="text-align: center;"><b><?php echo $this->map['total_night'];?></b></td>
        <td colspan="12"></td>
    </tr>
</table>
<!---------FOOTER----------->
<br/>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
        <td></td>
	<td align="center" > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
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
