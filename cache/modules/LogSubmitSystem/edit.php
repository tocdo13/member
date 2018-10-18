<style>
    .simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #FFFFFF;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
    }
</style>
<div class="w3-container">
    <div class="w3-row-padding">
        <h3><i class="fa fa-fw fa-connectdevelop"></i> KIỂM TRA TÍNH ĐÚNG ĐẮN CỦA DỮ LIỆU</h3>
    </div>
    <div class="w3-row-padding">
        <form name="LogSubmitSystemForm" method="POST">
            <table cellpadding="5" cellspacing="5">
                <tr>
                    <td><label>Từ Ngày:</label></td>
                    <td><input  name="start_time" id="start_time" class="w3-input w3-border" style="width: 50px;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_time'));?>"></td>
                    <td><input  name="start_date" id="start_date" class="w3-input w3-border" style="width: 150px;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
                    <td><label>Đến Ngày:</label></td>
                    <td><input  name="end_time" id="end_time" class="w3-input w3-border" style="width: 50px;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_time'));?>"></td>
                    <td><input  name="end_date" id="end_date" class="w3-input w3-border" style="width: 150px;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
                    <td><input  name="search" value="Tìm Kiếm" class="w3-button w3-blue" / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>"></td>
                </tr>
            </table>
        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    </div>
</div>

<div class="w3-container" style="margin-bottom: 50px;">
    <hr />
    <table cellpadding="5" cellspacing="5">
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr class="w3-grey"  <?php 
				if(($this->map['items']['current']['stt']!=1))
				{?>style=" border-top: 1px solid #EEEEEE;"
				<?php
				}
				?>>
            <td style="width: 50px"><i class="fa fa-fw fa-ellipsis-v"></i><?php echo $this->map['items']['current']['stt'];?><i class="fa fa-fw fa-ellipsis-v"></i></td>
            <td>Ngày: <?php echo $this->map['items']['current']['date'];?></td>
            <td>Tài khoản: <?php echo $this->map['items']['current']['user_id'];?></td>
            <td>Page: <?php echo $this->map['items']['current']['page'];?></td>
            <td style="width: 30px;"><button class="w3-btn w3-white" style="box-shadow: none;" onclick="ShowHideTime(this,'<?php echo $this->map['items']['current']['id'];?>');"><i class="fa fa-fw fa-plus w3-text-pink"></i></button></td>
        </tr>
        <tr class="time_content" id="<?php echo $this->map['items']['current']['id'];?>" style="display: none;">
            <td colspan="5">
                <input id="Timecheck_<?php echo $this->map['items']['current']['id'];?>" type="checkbox" style="display: none;" />
                <?php System::debug($this->map['items']['current']['data_json']); ?>
            </td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
    </table>
</div>

<script>
    function ShowHideTime(obj,Time_id){
        if(document.getElementById('Timecheck_'+Time_id).checked==false){
            document.getElementById('Timecheck_'+Time_id).checked = true;
            jQuery('#'+Time_id).css('display','');
            jQuery(obj).html('<i class="fa fa-fw fa-window-minimize w3-text-pink"></i>');
        }else{
            document.getElementById('Timecheck_'+Time_id).checked = false;
            jQuery('#'+Time_id).css('display','none');
            jQuery(obj).html('<i class="fa fa-fw fa-plus w3-text-pink"></i>');
        }
    }
</script>