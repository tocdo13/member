<style>
    #no_avata {
        width: 200px;
        height: auto;
        border: 3px solid #FFFFFF;
        box-shadow: 0px 0px 5px #555555;
    }
    #avatar {
        width: 200px;
        height: auto;
        border: 3px solid #FFFFFF;
        box-shadow: 0px 0px 5px #555555;
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
<form name="AddGolfCaddieForm" method="post" enctype="multipart/form-data">
    <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 0px auto;">
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <h3><img src="packages/hotel/packages/golf/includes/img/caddie.png" style="height: 40px; width: auto;" /> <?php if(Url::get('cmd')=='add'){ ?><?php echo Portal::language('caddie');?><?php }else{ ?><?php echo Portal::language('update_caddie');?><?php } ?></h3>
            </div>
            <div class="w3-button w3-margin w3-right w3-text-grey" onclick="location.href='?page=golf_caddie'" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    <i class="fa fa-fw fa-mail-reply"></i> <?php echo Portal::language('back');?>
                </div>
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="AddGolfCaddieForm.submit();" style="font-weight: normal;">
                <i class="fa fa-fw fa-save"></i> <?php echo Portal::language('save');?>
            </div>
        </div>
        <table class="w3-table">
            <tr>
                <td rowspan="6">
                    <input  name="image_profile" style="display: none;" type="text" id="image_profile" />
                    <?php if($this->map['image_profile']==''){ ?>
                        <img id="no_avata" src="packages/hotel/packages/golf/modules/GolfCaddie/avata/no_avata_boy.jpg" />
                    <?php }else{ ?>
                        <img id="no_avata" src="packages/hotel/packages/golf/modules/GolfCaddie/avata/<?php echo $this->map['image_profile'];?>" />
                    <?php } ?>
                    <br />
                    <input type="file" name="file" id="file" style="padding: 10px;" />
                </td>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('first_name');?><span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="first_name" type="text" id="first_name" style="width: 200px; height: 25px;" placeholder="First Name" value="<?php echo isset($this->map['first_name'])?$this->map['first_name']:'';?>" autocomplete="OFF" /></td>
                <td style="text-align: right;"><?php echo Portal::language('last_name');?><span style="color: red;"> (*)</span></td>
                <td style="text-align: center;">:</td>
                <td><input name="last_name" type="text" id="last_name" value="<?php echo isset($this->map['last_name'])?$this->map['last_name']:'';?>" placeholder="Last Name" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('email');?></td>
                <td style="text-align: center;">:</td>
                <td><input name="email" type="text" id="email" value="<?php echo isset($this->map['email'])?$this->map['email']:'';?>" placeholder="Email" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
                <td style="text-align: right;"><?php echo Portal::language('birth_date');?></td>
                <td style="text-align: center;">:</td>
                <td><input name="birth_date" type="text" id="birth_date" placeholder="Birth Date (DD/MM/YYYY)" value="<?php echo isset($this->map['birth_date'])?$this->map['birth_date']:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('gender');?></td>
                <td style="text-align: center;">:</td>
                <td><select  name="gender" id="gender" style="width: 205px; height: 25px;" onchange="change_no_avata();" ><?php
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
                <td style="text-align: right;"><?php echo Portal::language('passport');?></td>
                <td style="text-align: center;">:</td>
                <td><input name="passport" type="text" id="passport" placeholder="PassPort" value="<?php echo isset($this->map['passport'])?$this->map['passport']:'';?>" style="width: 200px; height: 25px;" autocomplete="off" onchange="get_passport();" /></td>
                
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('country');?></td>
                <td style="text-align: center;">:</td>
                <td><select  name="nationality_id" id="nationality_id"  style="width: 205px; height: 25px;" onchange="get_province();" ><?php
					if(isset($this->map['nationality_id_list']))
					{
						foreach($this->map['nationality_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))
                    echo "<script>$('nationality_id').value = \"".addslashes(URL::get('nationality_id',isset($this->map['nationality_id'])?$this->map['nationality_id']:''))."\";</script>";
                    ?>
	</select></td>
                <td style="text-align: right;"><?php echo Portal::language('address');?></td>
                <td style="text-align: center;">:</td>
                <td><input name="address" type="text" id="address" placeholder="Address" value="<?php echo isset($this->map['address'])?$this->map['address']:'';?>" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
            </tr>
            <tr>
                <td style="text-align: right;"><?php echo Portal::language('phone');?></td>
                <td style="text-align: center;">:</td>
                <td><input name="phone" type="text" id="phone" value="<?php echo isset($this->map['phone'])?$this->map['phone']:'';?>" placeholder="phone" style="width: 200px; height: 25px;" autocomplete="OFF" /></td>
                <td style="text-align: right;"></td>
                <td style="text-align: center;">:</td>
                <td></td>
            </tr>
        </table>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery("#gender").val('<?php echo isset($this->map['gender'])?$this->map['gender']:0; ?>');
    
    jQuery("#birth_date").datepicker();
    var count_click = 0;
    function change_no_avata()
    {
        <?php if($this->map['image_profile']==''){ ?>
        if(jQuery("#gender").val()==1)
            jQuery("#no_avata").attr('src','packages/hotel/packages/golf/modules/GolfCaddie/avata/no_avata_boy.jpg');
        else
            jQuery("#no_avata").attr('src','packages/hotel/packages/golf/modules/GolfCaddie/avata/no_avatar_girl.jpg');
        <?php } ?>
    }
    change_no_avata();
    jQuery("#file").change(function(){
        console.log(2);
        readURL(this);
    });
    
    function readURL(input) {
        if (input.files && input.files[0])
        {
            var Extension = input.value.substring(input.value.lastIndexOf('.') + 1).toLowerCase();
            if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg")
            {
                if(input.files[0].size <= (1024 * 1024 * 2))
                {
                    var reader = new FileReader();
                    reader.onload = function (e)
                    {
                        console.log(1);
                        jQuery('#no_avata').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }else
                {
                    alert("<?php echo Portal::language('capacity_does_not_exceed_2MB');?> !");
                }
            }
            else 
            {
                alert("wrong_image_format_allow_file_GIF_PNG_JPG_JPEG_BMP");
            }
        }
    }
</script>
