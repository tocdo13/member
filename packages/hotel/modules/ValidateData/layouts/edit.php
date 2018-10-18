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
        <h3><i class="fab fa-connectdevelop"></i> KIỂM TRA TÍNH ĐÚNG ĐẮN CỦA DỮ LIỆU</h3>
    </div>
</div>
<div class="w3-container">
    <hr />
    <div class="w3-half">
        <h5><i class="fa fa-fw fa-folder-open w3-text-amber"></i>THƯ MỤC PORTAL RÁC</h5>
        <hr />
        <table cellpadding="5" cellspacing="5">
            <!--LIST:portal_folder_error-->
            <tr>
                <td style="width: 30px;" class="w3-padding"></td>
                <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-folder w3-text-amber"></i></td>
                <td class="w3-padding w3-text-dark-grey">[[|portal_folder_error.id|]]</td>
            </tr>
            <tr>
                <td colspan="3">
                    <table>
                        <!--LIST:portal_folder_error.cache-->
                        <tr >
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-folder w3-text-amber"></i></td>
                            <td class="w3-padding w3-text-grey">[[|portal_folder_error.cache.link|]]</td>
                            <td class="w3-padding">
                                <button class="w3-btn w3-white" style="box-shadow: none;" onclick="DeleteDirectory('[[|portal_folder_error.cache.link|]]');"><i class="fa fa-fw fa-recycle w3-text-pink"></i></button>
                            </td>
                        </tr>
                        <!--/LIST:portal_folder_error.cache-->
                        <!--LIST:portal_folder_error.resources-->
                        <tr >
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-folder w3-text-amber"></i></td>
                            <td class="w3-padding w3-text-grey">[[|portal_folder_error.resources.link|]]</td>
                            <td class="w3-padding">
                                <button class="w3-btn w3-white" style="box-shadow: none;" onclick="DeleteDirectory('[[|portal_folder_error.resources.link|]]');"><i class="fa fa-fw fa-recycle w3-text-pink"></i></button>
                            </td>
                        </tr>
                        <!--/LIST:portal_folder_error.resources-->
                        <!--LIST:portal_folder_error.data-->
                        <tr >
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-folder w3-text-amber"></i></td>
                            <td class="w3-padding w3-text-grey">[[|portal_folder_error.data.link|]]</td>
                            <td class="w3-padding">
                                <button class="w3-btn w3-white" style="box-shadow: none;" onclick="DeleteDirectory('[[|portal_folder_error.data.link|]]');"><i class="fa fa-fw fa-recycle w3-text-pink"></i></button>
                            </td>
                        </tr>
                        <!--/LIST:portal_folder_error.data-->
                    </table>
                </td>
            </tr>
            <!--/LIST:portal_folder_error-->
        </table>
    </div>
    <div class="w3-half">
        <h5><i class="fa fa-fw fa-user w3-text-blue"></i>TÀI KHOẢN RÁC</h5>
        <hr />
        <table cellpadding="5" cellspacing="5">
            <!--LIST:account_file_error-->
            <tr>
                <td style="width: 30px;" class="w3-padding"></td>
                <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-folder w3-text-amber"></i></td>
                <td class="w3-padding w3-text-dark-grey">[[|account_file_error.id|]]</td>
            </tr>
            <tr>
                <td colspan="3">
                    <table>
                        <!--LIST:account_file_error.user-->
                        <tr >
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"></td>
                            <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-file-code w3-text-blue"></i></td>
                            <td class="w3-padding w3-text-grey">([[|account_file_error.user.id|]]) [[|account_file_error.user.link|]]</td>
                            <td class="w3-padding">
                                <button class="w3-btn w3-white" style="box-shadow: none;" onclick="DeleteDirectory('[[|account_file_error.user.link|]]');"><i class="fa fa-fw fa-receipt w3-text-pink"></i></button>
                            </td>
                        </tr>
                        <!--/LIST:account_file_error.user-->
                    </table>
                </td>
            </tr>
            <!--/LIST:account_file_error-->
        </table>
    </div>
</div>
<div class="w3-container">
    <hr />
    <div class="w3-half">
        <h5><i class="fa fa-fw fa-database w3-text-green"></i>ĐỒ MIỄN PHÍ CHO PHÒNG</h5>
        <hr />
        <table cellpadding="5" cellspacing="5">
            <!--LIST:amenities_error-->
            <tr>
                <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-firstdraft w3-text-blue"></i></td>
                <td class="w3-padding w3-text-grey">ID Phòng: [[|amenities_error.room_id|]] - Mã Sản Phẩm: [[|amenities_error.product_id|]] không tồn tại</td>
                <td class="w3-padding">
                    <button class="w3-btn w3-white" style="box-shadow: none;" onclick="DeleteDatabase([[|amenities_error.id|]],'amenities_used_detail');"><i class="fa fa-fw fa-receipt w3-text-pink"></i></button>
                </td>
            </tr>
            <!--/LIST:amenities_error-->
        </table>
    </div>
    <div class="w3-half">
        <h5><i class="fa fa-fw fa-database w3-text-green"></i>KHU PHÒNG</h5>
        <hr />
        <table cellpadding="5" cellspacing="5">
            <!--LIST:area_group_error-->
            <tr>
                <td style="width: 30px;" class="w3-padding"><i class="fa fa-fw fa-firstdraft w3-text-blue"></i></td>
                <td class="w3-padding w3-text-grey">Mã: [[|area_group_error.code|]] - Tên: [[|area_group_error.name_1|]] không tồn tại</td>
                <td class="w3-padding">
                    <button class="w3-btn w3-white" style="box-shadow: none;" onclick="DeleteDatabase([[|area_group_error.id|]],'area_group');"><i class="fa fa-fw fa-receipt w3-text-pink"></i></button>
                </td>
            </tr>
            <!--/LIST:area_group_error-->
        </table>
    </div>
</div>

<div id="mice_loading" style="display: none; width: 100%; height: 100%; z-index: 99; background: rgba(17,64,59,0.7); position: fixed; top: 0px; left: 0px;">
    <div style="width: 50px; height: 50px; margin: 250px auto;"><i class="fa fa-5x fa-spin fa-cog" style="color: #ff9393;"></i></div>
</div>


<script>
    function DeleteDirectory(link_uri)
    {
        jQuery("#mice_loading").css('display','');
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id(); ?>,
					type:"POST",
					data:{action:'delete_directory',link_uri:link_uri},
					success:function(html)
                    {
                        alert(html);
                        jQuery("#mice_loading").css('display','none');
                        location.reload();
					}
		});
    }
    function DeleteDatabase(field_id,table_name){
        jQuery("#mice_loading").css('display','');
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id(); ?>,
					type:"POST",
					data:{action:'delete_database',field_id:field_id,table_name:table_name},
					success:function(html)
                    {
                        alert(html);
                        jQuery("#mice_loading").css('display','none');
                        location.reload();
					}
		});
    }
</script>