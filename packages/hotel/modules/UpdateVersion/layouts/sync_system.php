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

<form name="UpdateVersionSyncSystemForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<input name="sync_table" id="sync_table" type="hidden" />
    <div class="w3-container">
        <div class="w3-row" style="min-width: 900px!important; max-width: 1420px; margin: 0px auto 50px;">
            <div class="w3-row">
                <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    Update Version - Step 02: Sync System
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="window.location='?page=update_version&cmd=sync_module';" style="font-weight: normal;">
                    <i class="fa fa-fw fa-save"></i> Next Step 03
                </div>
            </div>
            <div class="w3-row">
                <table class="w3-table-all">
                    <tr>
                        <th colspan="2">Get Data Table : </th>
                        <th><input name="link_api" type="text" id="link_api" class="w3-input w3-border" /></th>
                    </tr>
                    <!--LIST:list_table-->
                    <tr>
                        <th style="width: 30px;"></th>
                        <th>[[|list_table.id|]]</th>
                        <th style="text-align: right;">
                            <!--IF:cond(!isset($_SESSION['list_table_user'][[[=list_table.id=]]]))-->
                            <input type="button" class="w3-button w3-blue" id="[[|list_table.id|]]" onclick="GetData('[[|list_table.id|]]');" value="Get Data" />
                            <!--ELSE-->
                            <i class="fa fa-fw fa-check w3-text-pink"></i>
                            <!--/IF:cond-->
                        </th>
                    </tr>
                    <!--/LIST:list_table-->
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
    function GetData($table){
        jQuery("#sync_table").val($table);
        jQuery("#act").val('GETDATA');
        UpdateVersionSyncSystemForm.submit();
    }
</script>

