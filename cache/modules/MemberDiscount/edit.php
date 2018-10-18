<form name="EditMemberDiscountForm" method="POST">
    <table cellpadding="15" cellspacing="15">
        <tr style="border-bottom: 1px dashed #CCCCCC;">
            <td><h1><?php if(Url::get('cmd')=='add'){ echo Portal::language('add_member_discount'); }else{ echo Portal::language('edit_member_discount'); } ?></h1></td>
            <td style="text-align: center;">
                <?php if((User::can_add(false,ANY_CATEGORY) AND Url::get('cmd')=='add') OR (Url::get('cmd')=='edit' AND User::can_edit(false,ANY_CATEGORY))){ ?> 
                    <input name="save_stay" type="submit" id="save_stay" value="<?php echo Portal::language('save_stay');?>" style="padding: 10px;" />
                    <input name="save" type="submit" id="save" value="<?php echo Portal::language('save');?>"  style="padding: 10px;" />
                    <?php } ?>
                    <input name="back" type="button" id="back" value="<?php echo Portal::language('back');?>" style="padding: 10px;" onclick="window.location.href='?page=member_discount';" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellpadding="15" cellspacing="15">
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('code');?>:</label></td>
                        <td><input name="code" type="text" value="<?php echo $this->map['code'];?>" id="code" readonly="" style="background: #EEEEEE; padding: 10px;" /></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('pin_servie');?>:</label></td>
                        <td><select  name="access_pin_service" id="access_pin_service" style=" padding: 10px;"><?php
					if(isset($this->map['access_pin_service_list']))
					{
						foreach($this->map['access_pin_service_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('access_pin_service',isset($this->map['access_pin_service'])?$this->map['access_pin_service']:''))
                    echo "<script>$('access_pin_service').value = \"".addslashes(URL::get('access_pin_service',isset($this->map['access_pin_service'])?$this->map['access_pin_service']:''))."\";</script>";
                    ?>
	</select></td>
                        <script>
                            jQuery("#access_pin_service").val('<?php echo $this->map['access_pin_service_code'];?>');
                        </script>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('card_type');?>:</label></td>
                        <td><select  name="is_parent" id="is_parent" style=" padding: 10px;"><?php
					if(isset($this->map['is_parent_list']))
					{
						foreach($this->map['is_parent_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('is_parent',isset($this->map['is_parent'])?$this->map['is_parent']:''))
                    echo "<script>$('is_parent').value = \"".addslashes(URL::get('is_parent',isset($this->map['is_parent'])?$this->map['is_parent']:''))."\";</script>";
                    ?>
	</select></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('start_date');?>:</label></td>
                        <td><input  name="start_date" id="start_date" style=" padding: 10px;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"><span>(Nếu bỏ trống được hiểu bắt đầu từ ngày nhỏ nhất)</span></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('end_date');?>:</label></td>
                        <td><input  name="end_date" id="end_date" style=" padding: 10px;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"><span>(Nếu bỏ trống được hiểu đến ngày lớn nhất)</span></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('num_people_attach');?>:</label></td>
                        <td><select  name="operator" id="operator" style=" padding: 10px;"><?php
					if(isset($this->map['operator_list']))
					{
						foreach($this->map['operator_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('operator',isset($this->map['operator'])?$this->map['operator']:''))
                    echo "<script>$('operator').value = \"".addslashes(URL::get('operator',isset($this->map['operator'])?$this->map['operator']:''))."\";</script>";
                    ?>
	</select><input name="num_people" type="text" value="<?php echo $this->map['num_people'];?>" id="num_people" style=" padding: 10px;" class="input_number" /></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;">% <?php echo Portal::language('percent');?>:</label></td>
                        <td><input  name="percent" id="percent" style=" padding: 10px;" / type ="text" value="<?php echo String::html_normalize(URL::get('percent'));?>"><span>%</span></td>
                    </tr>
                    <tr>
                        <td><label style="font-weight: bold;"><?php echo Portal::language('title');?>:</label></td>
                        <td><input name="title" type="text" value="<?php echo $this->map['title'];?>" id="title" style=" padding: 10px;" /></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><label style="font-weight: bold;"><?php echo Portal::language('description');?>:</label></td>
                        <td><textarea  name="description" id="description" style=" padding: 10px; width: 650px; height: 300px;"><?php echo String::html_normalize(URL::get('description',''.$this->map['description']));?></textarea></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
jQuery('#start_date').datepicker();
jQuery('#end_date').datepicker();
</script>
