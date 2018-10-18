<style>
    div {
        margin: 0px;
        padding: 0px;
    }
    .show-print {
        display: none;
    }
    @media print {
        .show-print {
            display: block;
        }
        .no-print {
            display: none;
        }
    }
</style>

<div id="print_report">
    <table style="width: 98%; margin: 0px auto;" cellpadding="5" cellspacing="0">
        <tr>
            <th style="text-align: center;">
                <h3 style="text-transform: uppercase;"><?php echo Portal::language('history_change_room');?></h3>
            </th>
        </tr>
        <tr class="no-print">
            <th>
                <form name="ReportHistoryChangeRoomForm" method="POST">
                    <table style="margin: 0px auto;" cellpadding="5" cellspacing="0">
                        <tr>
                            <td><?php echo Portal::language('portal');?>: <select  name="portal_id" id="portal_id" style="padding: 5px;"  class="no-print"><?php
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
	</select><label class="show-print"><?php echo $this->map['portal_name'];?></label></td>
                            <td><?php echo Portal::language('area');?>: <select  name="room_level_id" id="room_level_id" style="padding: 5px;"  class="no-print"><?php
					if(isset($this->map['room_level_id_list']))
					{
						foreach($this->map['room_level_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_level_id',isset($this->map['room_level_id'])?$this->map['room_level_id']:''))
                    echo "<script>$('room_level_id').value = \"".addslashes(URL::get('room_level_id',isset($this->map['room_level_id'])?$this->map['room_level_id']:''))."\";</script>";
                    ?>
	</select><label class="show-print"><?php echo $this->map['room_level_name'];?></label></td>
                            <td><?php echo Portal::language('room');?>: <select  name="room_id" id="room_id" style="padding: 5px;"  class="no-print"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select><label class="show-print"><?php echo $this->map['room_name'];?></label></td>
                            <td><?php echo Portal::language('from_date');?>: <input  name="from_date" id="from_date" style="padding: 5px; width: 80px;"  class="no-print" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"><label class="show-print"><?php echo $this->map['from_date'];?></label></td>
                            <td><?php echo Portal::language('to_date');?>: <input  name="to_date" id="to_date" style="padding: 5px; width: 80px;"  class="no-print" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"><label class="show-print"><?php echo $this->map['to_date'];?></label></td>
                            <td>
                                <button onclick="ReportHistoryChangeRoomForm.submit();" style="padding: 5px;" class="no-print"><i class="fa fa-bar-chart fa-fw"></i><?php echo Portal::language('view_report');?></button>
                                <button onclick="var user ='<?php echo User::id(); ?>';printWebPart('printer',user);" style="padding: 5px;" class="no-print"><i class="fa fa-print fa-fw"></i><?php echo Portal::language('print_report');?></button>
                            </td>
                        </tr>
                    </table>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
            </th>
        </tr>
        <tr>
            <td>
                <table style="margin: 0px auto;" cellpadding="15" cellspacing="0" border="1" bordercolor="#555555">
                    <tr>
                        <th><?php echo Portal::language('in_date');?></th>
                        <th><?php echo Portal::language('room');?></th>
                        <th><?php echo Portal::language('description');?></th>
                        <!--<th><?php echo Portal::language('time_action');?></th>-->
                    </tr>
                    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                    <tr>
                        <?php $rows = sizeof($this->map['items']['current']['log']); ?>
                        <td rowspan="<?php echo $rows; ?>"><?php echo $this->map['items']['current']['in_date'];?></td>
                        <?php $child = ''; ?>
                        <?php if(isset($this->map['items']['current']['log']) and is_array($this->map['items']['current']['log'])){ foreach($this->map['items']['current']['log'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['log']['current'] = &$item2;?>
                        <?php $child = $this->map['items']['current']['log']['current']['id']; ?>
                            <td><?php echo $this->map['items']['current']['log']['current']['id'];?></td>
                            <td><?php echo $this->map['items']['current']['log']['current']['des'];?></td>
                            <!--<td rowspan="<?php //echo $rows; ?>"><?php echo $this->map['items']['current']['start_date'];?> - <?php //echo ($this->map['items']['current']['end_date']!='')?$this->map['items']['current']['end_date']:Portal::language('now'); ?></td>-->
                        </tr>
                        <?php break; ?>
                        <?php }}unset($this->map['items']['current']['log']['current']);} ?>
                        <?php if(isset($this->map['items']['current']['log']) and is_array($this->map['items']['current']['log'])){ foreach($this->map['items']['current']['log'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['log']['current'] = &$item3;?>
                        <?php if($child != $this->map['items']['current']['log']['current']['id']){ ?>
                        <tr>
                            <td><?php echo $this->map['items']['current']['log']['current']['id'];?></td>
                            <td><?php echo $this->map['items']['current']['log']['current']['des'];?></td>
                        </tr>
                        <?php } ?>
                        <?php }}unset($this->map['items']['current']['log']['current']);} ?>
                    <?php }}unset($this->map['items']['current']);} ?>
                </table>
            </td>
        </tr>
    </table>
</div>
<script>
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>