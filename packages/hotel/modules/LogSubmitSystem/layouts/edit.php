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
                    <td><input name="start_time" type="text" id="start_time" class="w3-input w3-border" style="width: 50px;" /></td>
                    <td><input name="start_date" type="text" id="start_date" class="w3-input w3-border" style="width: 150px;" /></td>
                    <td><label>Đến Ngày:</label></td>
                    <td><input name="end_time" type="text" id="end_time" class="w3-input w3-border" style="width: 50px;" /></td>
                    <td><input name="end_date" type="text" id="end_date" class="w3-input w3-border" style="width: 150px;" /></td>
                    <td><input name="search" type="submit" value="Tìm Kiếm" class="w3-button w3-blue" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div class="w3-container" style="margin-bottom: 50px;">
    <hr />
    <table cellpadding="5" cellspacing="5">
        <!--LIST:items-->
        <tr class="w3-grey"  <!--IF:cond([[=items.stt=]]!=1)-->style=" border-top: 1px solid #EEEEEE;"<!--/IF:cond-->>
            <td style="width: 50px"><i class="fa fa-fw fa-ellipsis-v"></i>[[|items.stt|]]<i class="fa fa-fw fa-ellipsis-v"></i></td>
            <td>Ngày: [[|items.date|]]</td>
            <td>Tài khoản: [[|items.user_id|]]</td>
            <td>Page: [[|items.page|]]</td>
            <td style="width: 30px;"><button class="w3-btn w3-white" style="box-shadow: none;" onclick="ShowHideTime(this,'[[|items.id|]]');"><i class="fa fa-fw fa-plus w3-text-pink"></i></button></td>
        </tr>
        <tr class="time_content" id="[[|items.id|]]" style="display: none;">
            <td colspan="5">
                <input id="Timecheck_[[|items.id|]]" type="checkbox" style="display: none;" />
                <?php System::debug([[=items.data_json=]]); ?>
            </td>
        </tr>
        <!--/LIST:items-->
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