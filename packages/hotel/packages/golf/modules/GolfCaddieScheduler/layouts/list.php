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
                <i class="fa fa-fw fa-calendar w3-text-pink"></i> [[.caddie_scheduler.]]
            </div>
            
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="location.href='?page=golf_caddie_scheduler&cmd=add';" style="font-weight: normal;">
                <i class="fa fa-fw fa-calendar-plus-o"></i> [[.add_scheduler.]]
            </div>
        </div>
        <div class="w3-row">
            <table>
                <tr class="w3-text-blue" style="font-weight: bold;">
                    <td>[[.view_by.]]:</td>
                    <td class="w3-text-blue"><label for="week">[[.week.]]</label></td>
                    <td class="w3-text-blue" style="display: none;"><input name="view_by" type="radio" id="week" value="week"/></td>
                    <td class="w3-text-blue" style="display: none;"><label for="month">[[.month.]]</label></td>
                    <td class="w3-text-blue" style="display: none;"><input name="view_by" type="radio" id="month" value="month"/></td>
                    <td><label for="start_date">[[.start_date.]]:</label></td>
                    <td class="w3-text-blue"><input name="start_date" type="text" id="start_date" class="w3-input w3-text-blue" style="width: 90px; text-align: center; background: none; border: none;" readonly="" /></td>
                    <td><label for="start_date" style="display: none;">[[.end_date.]]:</label></td>
                    <td class="w3-text-blue" style="display: none;"><input name="end_date" type="text" id="end_date" class="w3-input w3-text-blue" style="width: 90px; text-align: center; background: none; border: none;" readonly="" /></td>
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
                    <th colspan="2">[[.caddie.]]</th>
                    <!--LIST:timeline-->
                    <th style="width: [[|width|]]px; text-align: center; border-left: 1px solid #f2f2f2;">
                        [[|timeline.weekday|]]<br /><span style="font-size: 15px;" class="w3-text-amber"><b>[[|timeline.mday|]]</b></span><br />[[|timeline.month|]]
                    </th>
                    <!--/LIST:timeline-->
                </tr>
                <!--LIST:caddie-->
                <tr style="height: 80px;">
                    <td style="width: 50px; border-bottom: 1px solid #f2f2f2;"><img src="packages/hotel/packages/golf/modules/GolfCaddie/avata/[[|caddie.image_profile|]]" style="width: 45px; height: 45px; border-radius: 50%;" /></td>
                    <td style="border-bottom: 1px solid #f2f2f2;">[[|caddie.full_name|]]</td>
                    <!--LIST:caddie.timeline-->
                    <th style="width: [[|width|]]px; text-align: center; border-left: 1px dashed #f2f2f2; border-bottom: 1px dashed #f2f2f2; vertical-align: top;">
                        <!--LIST:caddie.timeline.scheduler-->
                            <div id="Scheduler_[[|caddie.timeline.scheduler.id|]]" style="width: <?php echo [[=width=]]-20; ?>px; height: 20px; margin: 3px auto 2px; padding: 0px 3px; background: rgba(76, 170, 83, 0.5);">
                                <span style="line-height: 20px; font-size: 11px;" class="w3-left">[[|caddie.timeline.scheduler.start_house|]] - [[|caddie.timeline.scheduler.end_house|]]</span>
                                <i onclick="DeleteScheduler([[|caddie.timeline.scheduler.id|]]);" style="line-height: 20px; font-size: 11px; cursor: pointer;" class="fa fa-fw fa-remove w3-text-pink w3-right"></i>
                            </div>
                        <!--/LIST:caddie.timeline.scheduler-->
                    </th>
                    <!--/LIST:caddie.timeline-->
                </tr>
                <!--/LIST:caddie-->
            </table>
        </div>
    </div>
</form>
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