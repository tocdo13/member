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
.simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #f9f9f9;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
        background: #f9f9f9!important;
    }
    body{
        background: #f9f9f9!important;
    }
    .over_hidden{
        overflow: hidden!important;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    #ui-datepicker-div{
        z-index: 999999;
    }
    .position_fixed {
        position: fixed; 
        top: 0px; 
        left: 0px; 
        
    }
</style>
<form name="ListGolfCaddieForm" method="post">
    <div class="w3-row" style="max-width: 1200px; margin: 0px auto;">
        <table style="margin: 0px auto;" cellspacing="5" cellpadding="5">
                <tr>
                    <td><?php echo Portal::language('full_name');?>:</td>
                    <td><input  name="full_name" id="full_name" class="w3-input w3-border"  / type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td>
                    <td><?php echo Portal::language('passport');?>:</td>
                    <td><input  name="passport" id="passport" class="w3-input w3-border" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>"></td>
                    <td rowspan="4" style="text-align: center;">
                        <input name="do_search" type="submit" id="do_search" value="<?php echo Portal::language('search');?>" class="w3-button w3-border w3-blue" /><br /><br />
                    </td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('country');?>:</td>
                    <td><select  name="country" id="country" class="w3-input w3-border"><?php
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
                    <td><select  name="gender" id="gender" class="w3-input w3-border"><?php
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
                    <td><input  name="email" id="email" class="w3-input w3-border" / type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>"></td>
                    <td><?php echo Portal::language('phone');?>:</td>
                    <td><input  name="phone" id="phone" class="w3-input w3-border" / type ="text" value="<?php echo String::html_normalize(URL::get('phone'));?>"></td>
                </tr>
            </table>
    </div>
    <div class="w3-row" style="max-width: 1200px; margin: 0px auto;">
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <h3><img src="packages/hotel/packages/golf/includes/img/caddie.png" style="height: 40px; width: auto;" /> <?php echo Portal::language('list');?> <?php echo Portal::language('caddie');?></h3>
            </div>
            <div class="w3-button w3-pink w3-hover-pink w3-margin w3-right" onclick="location.href='?page=golf_caddie_scheduler';" style="font-weight: normal;">
                <i class="fa fa-fw fa-calendar"></i> <?php echo Portal::language('caddie_scheduler');?>
            </div>
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="location.href='?page=golf_caddie&cmd=add';" style="font-weight: normal;">
                <i class="fa fa-fw fa-plus"></i> <?php echo Portal::language('add');?>
            </div>
        </div>
        <div style="max-width: 1200px; margin: 10px auto; float: left;"><?php echo $this->map['paging'];?></div>
        <table class="w3-table-all">    
            <tr>
                <th style="width: 50px; text-align: center;"><?php echo Portal::language('STT');?></th>
                <th style="width: 50px;"></th>
                <th style=""><?php echo Portal::language('full_name');?></th>
                <th style="width: 50px; text-align: center;"><?php echo Portal::language('gender');?></th>
                <th style="text-align: center;"><?php echo Portal::language('country');?></th>
                <th style="text-align: center;"><?php echo Portal::language('email');?></th>
                <th style="text-align: center;"><?php echo Portal::language('phone_number');?></th>
                <th style="width: 50px; text-align: center;"><?php echo Portal::language('edit');?></th>
                <th style="width: 50px; text-align: center; display: none;"><?php echo Portal::language('delete');?></th>
            </tr>
            <?php $stt=1; ?>         
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr>
                <td style="text-align: center;"><?php echo $stt++; ?></td>
                <td style="width: 50px;"><img src="packages/hotel/packages/golf/modules/GolfCaddie/avata/<?php echo $this->map['items']['current']['image_profile'];?>" style="width: 45px; height: 45px; border-radius: 50%;" /></td>
                <td style="text-align: left;"><?php echo $this->map['items']['current']['full_name'];?></td>
                <td style="text-align: center;"><?php if($this->map['items']['current']['gender']==1){?><?php echo Portal::language('male');?> <?php }else{ ?><?php echo Portal::language('female');?><?php } ?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['name_2'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['email'];?></td>
                <td style="text-align: center;"><?php echo $this->map['items']['current']['phone'];?></td>
                <td style="text-align: center;"><?php if(User::can_edit(false,ANY_CATEGORY)){ ?><a href="?page=golf_caddie&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><i class="fa fa-fw fa-pencil w3-text-blue"></i><?php } ?></td>
                <td style="text-align: center; display: none;"><?php if(User::can_delete(false,ANY_CATEGORY)){ ?><a href="?page=golf_caddie&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><i class="fa fa-fw fa-remove w3-text-red"></i><?php } ?></td>
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>      
        </table>
        <div style="max-width: 1200px; margin: 10px auto; float: left;"><?php echo $this->map['paging'];?></div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    //jQuery("#create_date").datepicker();
</script>