<style>
    .simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #F2F2F2;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
        background: #F2F2F2!important;
    }
    body{
        background: #F2F2F2!important;
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
    /*.NgClick:hover{
        outline: 3px solid #2494D1;
    }
    */
</style>

<form name="UpdateVersionForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
    <div class="w3-container">
        <div class="w3-row" style="min-width: 900px!important; max-width: 1420px; margin: 0px auto 50px;">
            <div class="w3-row">
                <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    Update Version - Step 01: Restore Data 
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="window.location='?page=update_version&cmd=sync_system';" style="font-weight: normal;">
                    <i class="fa fa-fw fa-save"></i> Next Step 02
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="jQuery('#act').val('RESTORE'); UpdateVersionForm.submit();" style="font-weight: normal;">
                    <i class="fa fa-fw fa-save"></i> [[.save.]]
                </div>
            </div>
            <div class="w3-row">
                <table class="w3-table-all">
                    <tr>
                    	<th><input type="checkbox" class="SelectAll" onclick="if(jQuery(this).attr('checked')=='checked'){ jQuery('.SelectItems').attr('checked','checked'); }else{ jQuery('.SelectItems').removeAttr('checked'); }" /></th>
                    	<th>[[.table.]]</th>
                    	<th>[[.tablespace_name.]]</th>
                    	<th>[[.status.]]</th>
                    	<th>[[.num_rows.]]</th>
                    	<th>[[.blocks.]]</th>
                    	<th>[[.avg_row_len.]]</th>
                    	<th>[[.cache.]]</th>
                    	<th>[[.sample_size.]]	</th>
                    	<th>[[.buffer_pool.]]</th>
                    	<th>[[.min_extents.]]</th>
                    	<th>[[.max_extents.]]</th>
                    </tr>
                    <!--LIST:tables-->
                    <tr>
                        <td>
                            <!--IF:cond( [[=tables.id=]]!='WORD_HOTEL' and [[=tables.id=]]!='WORD' and [[=tables.id=]]!='PAGE_EFFECT' and [[=tables.id=]]!='PAGE_ACTION' and [[=tables.id=]]!='PAGE' and [[=tables.id=]]!='PACKAGE' and [[=tables.id=]]!='MODULE_TABLE' and [[=tables.id=]]!='MODULE_SETTING' and [[=tables.id=]]!='MODULE' and [[=tables.id=]]!='CATEGORY' and [[=tables.id=]]!='BLOCK' and [[=tables.id=]]!='BLOCK_SETTING' )-->
                            <input class="SelectItems" name="selected_ids[]" type="checkbox" value="[[|tables.id|]]" lang="[[|tables.id|]]" />
                            <!--/IF:cond-->
                        </td>
                        <td>[[|tables.id|]]</td>
                        <td>[[|tables.tablespace_name|]]</td>
                        <td>[[|tables.status|]]</td>
                        <td>[[|tables.num_rows|]] </td>
                        <td>[[|tables.blocks|]] </td>
                        <td>[[|tables.avg_row_len|]] </td>
                        <td>[[|tables.cache|]] </td>
                        <td>[[|tables.sample_size|]]</td>
                        <td>[[|tables.buffer_pool|]]'s</td>
                        <td>[[|tables.min_extents|]]</td>
                        <td>[[|tables.max_extents|]]</td>
                    </tr>
                    <!--/LIST:tables-->
        		</table>
            </div>
        </div>
    </div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 960px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
</form>

<script>
    //jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
    
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var windowscrollTop = 0;
    function OpenLightBox(){
        windowscrollTop = jQuery(window).scrollTop();
        jQuery("body").addClass('over_hidden');
        jQuery("#LightBoxCentral").css('display','');
    }
    function CloseLightBox(){
        $input_count_range = 100;
        jQuery("body").removeClass('over_hidden');
        document.getElementById('LightBoxCentralContent').innerHTML = '';
        jQuery("#LightBoxCentral").css('display','none');
        jQuery(window).scrollTop(windowscrollTop);
    }
</script>

