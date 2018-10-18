<style>
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
<form name="ListGolfCaddieSchedulerForm" method="post">
    <div class="w3-row" style="max-width: 1200px; margin: 0px auto 40px;">
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left w3-text-pink" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <i class="fa fa-fw fa-calendar w3-text-pink"></i> <?php echo Portal::language('caddie_scheduler');?>
            </div>
            
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="location.href='?page=golf_caddie_scheduler&cmd=add';" style="font-weight: normal;">
                <i class="fa fa-fw fa-calendar-plus-o"></i> <?php echo Portal::language('add_scheduler');?>
            </div>
        </div>
        <div class="w3-row">
            <table>
                <tr class="w3-text-blue" style="font-weight: bold;">
                    <td><?php echo Portal::language('view_by');?>:</td>
                    <td class="w3-text-blue"><label for="week"><?php echo Portal::language('week');?></label></td>
                    <td class="w3-text-blue" style="display: none;"><input  name="view_by" id="week" value="week"/ type ="radio" value="<?php echo String::html_normalize(URL::get('view_by'));?>"></td>
                    <td class="w3-text-blue" style="display: none;"><label for="month"><?php echo Portal::language('month');?></label></td>
                    <td class="w3-text-blue" style="display: none;"><input  name="view_by" id="month" value="month"/ type ="radio" value="<?php echo String::html_normalize(URL::get('view_by'));?>"></td>
                    <td><label for="start_date"><?php echo Portal::language('start_date');?>:</label></td>
                    <td class="w3-text-blue"><input  name="start_date" id="start_date" class="w3-input w3-text-blue" style="width: 90px; text-align: center; background: none; border: none;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
                    <td><label for="start_date" style="display: none;"><?php echo Portal::language('end_date');?>:</label></td>
                    <td class="w3-text-blue" style="display: none;"><input  name="end_date" id="end_date" class="w3-input w3-text-blue" style="width: 90px; text-align: center; background: none; border: none;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
                    <td>
                        <div class="w3-button w3-blue w3-hover-blue" onclick="ListGolfCaddieSchedulerForm.submit();" style="font-weight: normal;">
                            <i class="fa fa-fw fa-search"></i>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="w3-row">
            <table style="width: 100%; background: rgba(255,255,255,1); box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); border: 5px solid #FFF; border-radius: 5px; margin: 15px;" cellspacing="0" cellpadding="5">
                <tr style="height: 50px; border-bottom: 1px solid #f2f2f2;">
                    <th colspan="2"><?php echo Portal::language('caddie');?></th>
                    <?php if(isset($this->map['timeline']) and is_array($this->map['timeline'])){ foreach($this->map['timeline'] as $key1=>&$item1){if($key1!='current'){$this->map['timeline']['current'] = &$item1;?>
                    <th style="width: <?php echo $this->map['width'];?>px; text-align: center; border-left: 1px solid #f2f2f2;">
                        <?php echo $this->map['timeline']['current']['weekday'];?><br /><span style="font-size: 15px;" class="w3-text-amber"><b><?php echo $this->map['timeline']['current']['mday'];?></b></span><br /><?php echo $this->map['timeline']['current']['month'];?>
                    </th>
                    <?php }}unset($this->map['timeline']['current']);} ?>
                </tr>
                <?php if(isset($this->map['caddie']) and is_array($this->map['caddie'])){ foreach($this->map['caddie'] as $key2=>&$item2){if($key2!='current'){$this->map['caddie']['current'] = &$item2;?>
                <tr style="height: 80px;">
                    <td style="width: 50px; border-bottom: 1px solid #f2f2f2;"><img src="packages/hotel/packages/golf/modules/GolfCaddie/avata/<?php echo $this->map['caddie']['current']['image_profile'];?>" style="width: 45px; height: 45px; border-radius: 50%;" /></td>
                    <td style="border-bottom: 1px solid #f2f2f2;"><?php echo $this->map['caddie']['current']['full_name'];?></td>
                    <?php if(isset($this->map['caddie']['current']['timeline']) and is_array($this->map['caddie']['current']['timeline'])){ foreach($this->map['caddie']['current']['timeline'] as $key3=>&$item3){if($key3!='current'){$this->map['caddie']['current']['timeline']['current'] = &$item3;?>
                    <th style="width: <?php echo $this->map['width'];?>px; text-align: center; border-left: 1px dashed #f2f2f2; border-bottom: 1px dashed #f2f2f2; vertical-align: top;">
                        <?php if(isset($this->map['caddie']['current']['timeline']['current']['scheduler']) and is_array($this->map['caddie']['current']['timeline']['current']['scheduler'])){ foreach($this->map['caddie']['current']['timeline']['current']['scheduler'] as $key4=>&$item4){if($key4!='current'){$this->map['caddie']['current']['timeline']['current']['scheduler']['current'] = &$item4;?>
                            <div id="Scheduler_<?php echo $this->map['caddie']['current']['timeline']['current']['scheduler']['current']['id'];?>" style="width: <?php echo $this->map['width']-20; ?>px; height: 20px; margin: 3px auto 2px; padding: 0px 3px; background: rgba(76, 170, 83, 0.5);">
                                <span style="line-height: 20px; font-size: 11px;" class="w3-left"><?php echo $this->map['caddie']['current']['timeline']['current']['scheduler']['current']['start_house'];?> - <?php echo $this->map['caddie']['current']['timeline']['current']['scheduler']['current']['end_house'];?></span>
                                <i onclick="DeleteScheduler(<?php echo $this->map['caddie']['current']['timeline']['current']['scheduler']['current']['id'];?>);" style="line-height: 20px; font-size: 11px; cursor: pointer;" class="fa fa-fw fa-remove w3-text-pink w3-right"></i>
                            </div>
                        <?php }}unset($this->map['caddie']['current']['timeline']['current']['scheduler']['current']);} ?>
                    </th>
                    <?php }}unset($this->map['caddie']['current']['timeline']['current']);} ?>
                </tr>
                <?php }}unset($this->map['caddie']['current']);} ?>
            </table>
        </div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    jQuery("#start_date").datepicker();
    <?php if($_REQUEST['view_by']=='week'){ ?>
        document.getElementById('week').checked=true;
    <?php }else{ ?>
        document.getElementById('month').checked=true;
    <?php } ?>
    function DeleteScheduler($schduler_id){
        if(confirm('Bạn chắc chắn xóa ?')){
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
    					url:"form.php?block_id="+block_id,
    					type:"POST",
    					data:{status:'DELETE',schduler_id:$schduler_id},
    					success:function(html)
                        {
                            if(html.trim()!=''){
                                alert(html.trim());
                            }else{
                                jQuery("#Scheduler_"+$schduler_id).hide(1500);
                                jQuery("#Scheduler_"+$schduler_id).remove();
                            }
    					}
    		});
        }
    }
</script>