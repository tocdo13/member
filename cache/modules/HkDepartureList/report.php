<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px;">
                    <td align="left" width="85%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                     <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
                    </td>
                 </tr>
                 <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title" ><?php echo Portal::language('departure_customer_list');?><br /></font>
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

<!---------SEARCH----------->

<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search"> 
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto">
                                <tr>        
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td><?php echo Portal::language('hotel');?></td>
                                	<td><select  name="portal_id" id="portal_id"><?php
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
                                	<td><input  name="date" id="date"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
                                    <td><?php echo Portal::language('order_by_list');?></td>
                                    <td><select  name="order_by" id="order_by"><?php
					if(isset($this->map['order_by_list']))
					{
						foreach($this->map['order_by_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('order_by',isset($this->map['order_by'])?$this->map['order_by']:''))
                    echo "<script>$('order_by').value = \"".addslashes(URL::get('order_by',isset($this->map['order_by'])?$this->map['order_by']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><input type="submit" name="do_search" value="<?php echo Portal::language('report');?>" /></td>
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
<!--/IF:first_page-->
<!---------REPORT----------->	
<table class="table_border" cellpadding="5" cellspacing="0" width="100%" border="1px">
	<tr>
		<th class="report_table_header" width="20px"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" width="30px"><?php echo Portal::language('reservation_room_code');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('tour');?>, <?php echo Portal::language('company');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('note_group');?></th>
        <th class="report_table_header" width="40px"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('room_level');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('note_guest');?></th>
		<th class="report_table_header" width="150px"><?php echo Portal::language('guest_name');?></th>
		<th class="report_table_header" width="70px"><?php echo Portal::language('country');?></th>
		<th class="report_table_header" width="100px"><?php echo Portal::language('arrival_date');?></th>
		<th class="report_table_header" width="100px"><?php echo Portal::language('departure_date');?></th>
        <th class="report_table_header" width="30px"><?php echo Portal::language('night');?></th>
	</tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr>
        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><?php echo $this->map['items']['current']['stt'];?></td>
        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>"><a href="?page=reservation&cmd=edit&id=<?php echo $this->map['items']['current']['recode'];?>"><?php echo $this->map['items']['current']['recode'];?></a></td>
        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>" align="left"><?php echo $this->map['items']['current']['customer_name'];?></td>
        <td rowspan="<?php echo $this->map['items']['current']['count_child'];?>" align="left"><?php echo $this->map['items']['current']['reservation_note'];?></td>
        <?php $items_child = ''; ?>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
            <?php $items_child = $this->map['items']['current']['child']['current']['id']; ?>
            <?php if($this->map['items']['current']['child']['current']['count_child']==0){ $this->map['items']['current']['child']['current']['count_child']=1; } ?>
            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>" align="left"><?php echo $this->map['items']['current']['child']['current']['room_level_name'];?></td>
            <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>" align="left"><?php echo $this->map['items']['current']['child']['current']['note'];?></td>
            <?php if(sizeof($this->map['items']['current']['child']['current']['child_child'])==0){ ?>
                <td></td>
                <td></td>
                <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
            <?php }else{ ?>
                <?php $items_child_childchild = '';  ?>
                <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item3;?>
                    <?php $items_child_childchild = $this->map['items']['current']['child']['current']['child_child']['current']['id']; ?>
                    <td align="left"><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['fullname'];?></td>
                    <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['nationality'];?></td>
                    <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                    <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                    <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                    <?php break; ?>
                <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
                <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item4;?>
                    <?php if($items_child_childchild != $this->map['items']['current']['child']['current']['child_child']['current']['id']){ ?>
                    <tr>
                        <td align="left"><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['fullname'];?></td>
                        <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['nationality'];?></td>
                        <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                        <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                        <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                    </tr>
                    <?php } ?>
                <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
            <?php } ?>
            <?php break; ?>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
        </tr>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current']['child']['current'] = &$item5;?>
            <?php if($items_child != $this->map['items']['current']['child']['current']['id']){ ?>
            <tr>
            <?php if($this->map['items']['current']['child']['current']['count_child']==0){ $this->map['items']['current']['child']['current']['count_child']=1; } ?>
                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>"><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>" align="left"><?php echo $this->map['items']['current']['child']['current']['room_level_name'];?></td>
                <td rowspan="<?php echo $this->map['items']['current']['child']['current']['count_child'];?>" align="left"><?php echo $this->map['items']['current']['child']['current']['note'];?></td>
                <?php if(sizeof($this->map['items']['current']['child']['current']['child_child'])==0){ ?>
                    <td></td>
                    <td></td>
                    <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                    <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                    <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                <?php }else{ ?>
                    <?php $items_child_childchild = '';  ?>
                    <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key6=>&$item6){if($key6!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item6;?>
                        <?php $items_child_childchild = $this->map['items']['current']['child']['current']['child_child']['current']['id']; ?>
                        <td align="left"><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['fullname'];?></td>
                        <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['nationality'];?></td>
                        <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                        <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                        <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                        <?php break; ?>
                    <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
                    <?php if(isset($this->map['items']['current']['child']['current']['child_child']) and is_array($this->map['items']['current']['child']['current']['child_child'])){ foreach($this->map['items']['current']['child']['current']['child_child'] as $key7=>&$item7){if($key7!='current'){$this->map['items']['current']['child']['current']['child_child']['current'] = &$item7;?>
                        <?php if($items_child_childchild != $this->map['items']['current']['child']['current']['child_child']['current']['id']){ ?>
                        <tr>
                            <td align="left"><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['fullname'];?></td>
                            <td><?php echo $this->map['items']['current']['child']['current']['child_child']['current']['nationality'];?></td>
                            <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_in']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['arrival_time'];?></td>
                            <td><?php echo date('H:i',$this->map['items']['current']['child']['current']['time_out']);  ?> &nbsp;&nbsp;<?php echo $this->map['items']['current']['child']['current']['departure_time'];?></td>
                            <td><?php echo $this->map['items']['current']['child']['current']['night'];?></td>
                        </tr>
                        <?php } ?>
                    <?php }}unset($this->map['items']['current']['child']['current']['child_child']['current']);} ?>
            <?php } ?>
            <?php } ?>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
    </tr>
<?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <th colspan="4" align="right"><?php echo Portal::language('total');?></th>
        <th><?php echo System::display_number($this->map['total_room']); ?></th>
        <th colspan="2"></th>
        <th><?php echo System::display_number($this->map['total_traveller']); ?></th>
        <th colspan="4"></th>
    </tr>
</table>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td >&nbsp;</td>
	<td > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" ><?php echo Portal::language('general_accountant');?></td>
</tr>
</table>
<br /><br /><br />
<script>full_screen();</script>
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
#printer{
    height: auto;
}
</style>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_day').datepicker();
        jQuery('#to_day').datepicker();
    }
);
</script>
<style>
</style>
