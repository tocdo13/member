<style>
.simple-layout-middle{width:100%;}
#content_member table tr td{
    height: 25px;
    border: 1px solid #b8e9fd;
}
#content_member table tr th{
    height: 35px;
    border: 1px solid #b8e9fd;
}
</style>
<form name="ListMemberTravellerForm" method="post">
    <div id="search_member" style="width: 100%; margin: 0px auto; height: auto;">
        <fieldset style="width: 960px; margin: 0px auto; background: #b8e9fd;">
            <legend style="font-size: 16px; text-transform: uppercase;"><?php echo Portal::language('search_traveller');?></legend>
            <table style="width: 100%; border: 1px solid #00b2f9; background: #fff;">
                <tr>
                    <td rowspan="3" style="width: 300px;">
                        <fieldset>
                            <legend><?php echo Portal::language('search_for_member');?></legend>
                            <table>
                                <tr>
                                    <td><?php echo Portal::language('member_code');?>:<br /><input  name="member_code" id="member_code" / type ="text" value="<?php echo String::html_normalize(URL::get('member_code'));?>"></td>
                                    <td><?php echo Portal::language('create_date');?>:<br /><input  name="create_date" id="create_date" / type ="text" value="<?php echo String::html_normalize(URL::get('create_date'));?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo Portal::language('point_from');?>:<br /><input  name="point_from" id="point_from" / type ="text" value="<?php echo String::html_normalize(URL::get('point_from'));?>"></td>
                                    <td><?php echo Portal::language('point_to');?>:<br /><input  name="point_to" id="point_to" / type ="text" value="<?php echo String::html_normalize(URL::get('point_to'));?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo Portal::language('member_level');?>:<br /><select  name="member_level" id="member_level"><?php
					if(isset($this->map['member_level_list']))
					{
						foreach($this->map['member_level_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('member_level',isset($this->map['member_level'])?$this->map['member_level']:''))
                    echo "<script>$('member_level').value = \"".addslashes(URL::get('member_level',isset($this->map['member_level'])?$this->map['member_level']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('email_confirm');?>:<br /><select  name="email_traveller" id="email_traveller"><?php
					if(isset($this->map['email_traveller_list']))
					{
						foreach($this->map['email_traveller_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('email_traveller',isset($this->map['email_traveller'])?$this->map['email_traveller']:''))
                    echo "<script>$('email_traveller').value = \"".addslashes(URL::get('email_traveller',isset($this->map['email_traveller'])?$this->map['email_traveller']:''))."\";</script>";
                    ?>
	</select></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                    <td><?php echo Portal::language('full_name');?>:</td>
                    <td><input  name="full_name" id="full_name" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                    <td><?php echo Portal::language('passport');?>:</td>
                    <td><input  name="passport" id="passport" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>"></td>
                    <td rowspan="3" style="text-align: center;">
                        <input name="do_search" type="submit" id="do_search" value="<?php echo Portal::language('search');?>" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer;" /><br /><br />
                        <input name="reset" type="button" id="do_search" value="<?php echo Portal::language('reset');?>" onclick="fun_reset();" style="padding: 10px; border: none; border: 2px solid #00b2f9; background: #b8e9fd; color: #171717; cursor: pointer; text-transform: uppercase;" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('country');?>:</td>
                    <td><select  name="country" id="country" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"><?php
					if(isset($this->map['country_list']))
					{
						foreach($this->map['country_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('country',isset($this->map['country'])?$this->map['country']:''))
                    echo "<script>$('country').value = \"".addslashes(URL::get('country',isset($this->map['country'])?$this->map['country']:''))."\";</script>";
                    ?>
	</select></td>
                    <td><?php echo Portal::language('gender');?>:</td>
                    <td><select  name="gender" id="gender" style="border: 1px solid #00b2f9; width: 150px; height: 25px;"><?php
					if(isset($this->map['gender_list']))
					{
						foreach($this->map['gender_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))
                    echo "<script>$('gender').value = \"".addslashes(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))."\";</script>";
                    ?>
	</select></td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('email');?>:</td>
                    <td><input  name="email" id="email" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
                    <td><?php echo Portal::language('phone');?>:</td>
                    <td><input  name="phone" id="phone" style="border: 1px solid #00b2f9; width: 150px; height: 25px;" / type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>"></td>
                </tr>
            </table>
        </fieldset>
    </div><!-- end search_member -->
    <div id="content_member" style="width: 100%; height: auto; margin: 20px auto;">
        <div id="nav_list">
            <h1 style="float: left; margin-left: 10px;"><?php echo Portal::language('list_member');?></h1>
            <input name="delete_traveller" type="button" id="delete_traveller" value="<?php echo Portal::language('delete_all_select');?>" onclick="fun_delete_traveller();" style="padding: 10px; display: none; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" />
            <input name="check_delete" id="check_delete" type="checkbox" style="display: none;" />
            <input name="list_delete" id="list_delete" type="text" style="display: none;" />
            <?php if(User::can_add(false,ANY_CATEGORY)){ ?><a href="?page=traveller&cmd=add" style="float: right; margin-right: 100px;"><input name="add_traveller" type="button" id="add_traveller" value="<?php echo Portal::language('add');?>" style="padding: 10px; border: none; border: 2px solid #b8e9fd; background: #00b2f9; color: #fff; cursor: pointer; float: right; margin-right: 10px;" /></a><?php } ?>
        </div>
        <div style="width: 100%; margin: 10px auto; float: left;"><?php echo $this->map['paging'];?></div>
        <table cellspacing="0" width="98%" style=" margin: 10px auto;">    
            <tr style="background: #00b2f9; color: #fff;">
                <th style="width: 50px; text-align: center;"><input  name="check_list_all" id="check_list_all" onclick="fun_check_all();" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('check_list_all'));?>">ALL</th>
                <th style="width: 50px; text-align: center;"><?php echo Portal::language('STT');?></th>
                <th style="text-align: center;"><?php echo Portal::language('full_name');?></th>
                <th style="text-align: center;"><?php echo Portal::language('passport');?></th>
                <th style="width: 50px; text-align: center;"><?php echo Portal::language('gender');?></th>
                <th style="text-align: center;"><?php echo Portal::language('country');?></th>
                <th style="text-align: center;"><?php echo Portal::language('email');?></th>
                <th style="text-align: center;"><?php echo Portal::language('phone_number');?></th>
                <th style="text-align: center;"><?php echo Portal::language('member_code');?></th>
                <th style="width: 100px; text-align: center;"><?php echo Portal::language('create_date');?></th>
                <th style="text-align: center;"><?php echo Portal::language('point');?></th>
                <th style="text-align: center;"><?php echo Portal::language('point_user');?></th>
                <th style="text-align: center;"><?php echo Portal::language('member_level');?></th>
                <th style="width: 50px; text-align: center;"><?php echo Portal::language('edit');?></th>
                <th style="width: 50px; text-align: center; display: none;"><?php echo Portal::language('delete');?></th>
            </tr>         
            <?php $stt=1; ?>         
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr>
                <td style="text-align: center;"><input  name="check_list_<?php echo $this->map['items']['current']['id'];?>" id="check_list_<?php echo $this->map['items']['current']['id'];?>" onclick="fun_check_id(<?php echo $this->map['items']['current']['id'];?>);" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('check_list_'.$this->map['items']['current']['id']));?>"></td>
                <td style="text-align: center;"><?php echo $stt++; ?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['full_name'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['passport'];?></td>
                <td style="text-align: center;"><?php if($this->map['items']['current']['gender']==1){?><?php echo Portal::language('male');?> <?php }else{ ?><?php echo Portal::language('female');?><?php } ?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['name_2'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['email'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['phone'];?></td>
                <td style="text-align: center;"><?php if($this->map['items']['current']['member_code']>0){?><?php echo $this->map['items']['current']['member_code'];?><?php }else{ ?><?php echo Portal::language('no_code');?><?php } ?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['member_create_date'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['point'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['point_user'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['def_name'];?></td>
                <td style="text-align: center;"><?php if(User::can_edit(false,ANY_CATEGORY)){ ?><a target="_blank" href="?page=traveller&reservation_id=&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_edit.png" style="width: 20px; height: auto;" /></a><?php } ?></td>
                <td style="text-align: center; display: none;"><img src="packages\hotel\packages\sale\skins\img\logo_member_level\icon_delete.png" style="width: 20px; height: auto; cursor: pointer;" onclick="fun_delete_id(<?php echo $this->map['items']['current']['id'];?>);" /></td>
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>      
        </table>
        <div style="width: 100%; margin: 10px auto; float: left;"><?php echo $this->map['paging'];?></div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery("#create_date").datepicker();
    function fun_check_all(){
        var list_items = <?php echo $this->map['list_items'];?>;
        var id="";
        var list_delete = "";
        if(document.getElementById("check_list_all").checked==true){
            for(item in list_items){
                id=list_items[item]['id'];
                document.getElementById("check_list_"+id).checked=true;
                list_delete = list_delete+"_"+id;
            }
        }else{
            for(item in list_items){
                id=list_items[item]['id'];
                document.getElementById("check_list_"+id).checked=false;
            }
            
        }
        jQuery("#list_delete").val(list_delete);
    }
    function fun_check_id(id){
        var list_delete = jQuery("#list_delete").val().split("_");
        var list_new = "";
        for(list_id in list_delete){
            if((id!=list_delete[list_id]) && (list_id!=0)){
                //console.log(list_delete[list_id]);\
                list_new = list_new+"_"+list_delete[list_id];
            }
        }
        jQuery("#list_delete").val(list_new);
        console.log(list_new);
    }
    function fun_delete_traveller(){
        document.getElementById("check_delete").checked=true;
        var list_delete = jQuery("#list_delete").val();
        if(list_delete==""){
            alert("chưa chọn khách để xóa");
        }else{
            ListMemberTravellerForm.submit();
        }
    }
    function fun_delete_id(id){
        document.getElementById("check_delete").checked=true;
        jQuery("#list_delete").val("_"+id);
        ListMemberTravellerForm.submit();
    }
    function fun_reset(){
        
    }
</script>