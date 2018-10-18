<style>
    .simple-layout-middle{width:100%;}
    .simple-layout-content {
        background: #EEEEEE;
         padding: 0px; 
         min-height: 100%;
         margin: 0px;
         border: none;
    }
    div {
        margin: 0px;
        padding: 0px;
    }
    #MiceReservationBody {
        width: 90%;
        height: auto;
        padding: 10px;
        margin: 5px auto;
        background: #FFFFFF;
        box-shadow: 0px 1px 4px 0px rgba(0,0,0,0.2);
    }
    #MiceReservationHeader,#MiceReservationContainer,#MiceReservationFooter {
        width: 100%;
        height: auto;
        padding: 0px;
        margin: 1px auto;
        color: #656565;
        font-weight: normal;
    }
    #MiceReservationHeader {
        border-bottom: 1px solid #EEEEEE;
    }
    #MiceReservationContainer {
        border-bottom: 1px solid #EEEEEE;
        border-top: 1px solid #EEEEEE;
    }
    #MiceReservationFooter {
        border-top: 1px solid #EEEEEE;
    }
    #MiceReservationHeader table {
        width: 100%;
    }
    #MiceReservationHeader table tr td:first-child {
        text-align: left;
    }
    #MiceReservationHeader table tr td:last-child {
        text-align: right;
    }
    #MiceReservationInfoContact,#MiceReservationInfoService,#MiceReservationInfoPayment {
        width: 100%;
        height: auto;
        margin: 5px auto;
        clear: both;
    }
    #MiceReservationInfoContact table tr td,#MiceReservationInfoService table tr td,#MiceReservationInfoPayment table tr td {
        color: #656565;
    }
    #MiceReservationInfoContact table tr td input,#MiceReservationInfoPayment table tr td input {
        border: none;
        border-bottom: 1px dashed #CCCCCC;
        width: 150px;
        padding: 5px;
        height: 25px;
        color: #828282;
    }
    #MiceReservationInfoContact table tr td input:hover,#MiceReservationInfoPayment table tr td input:hover {
        border-bottom: 1px dashed #00B2F9;
    }
    .DivTitle {
        float: left;
        height: 50px;
        width: auto;
        padding: 5px;
    }
    .DivTitle h1 {
        line-height: 50px;
        padding: 0px;
        margin: 0px;
        text-transform: uppercase;
        margin-left: 5px;
        float: left;
        color: #ff5353;
    }
    .DivTitle img {
        width: 40px;
        height: 40px;
        padding: 0px;
        margin: 5px;
        float: left;
    }
    .DivButton {
        float: right;
        height: 30px;
        padding: 0px 10px 0px 0px;
        background: #FFFFFF;
        box-shadow: 0px 0px 3px #CCCCCC;
        margin-left: 10px;
        cursor: pointer;
        transition: all 0.3s ease-out;
        overflow: hidden;
    }
    .DivButton:hover {
        background: #ff9393;
    }
    .DivButton .DivButtonIcon {
        width: 30px;
        height: 30px;
        transition: all 0.3s ease-out;
        float: left;
        background: #F7F7F7;
    }
    .DivButton:hover .DivButtonIcon {
        background: #FFFFFF;
    }
    .DivButton .DivButtonIcon img {
        width: 15px;
        height: 15px;
        padding: 7.5px;
        margin: 0px;
    }
    .DivButton span {
        line-height: 30px;
        text-transform: uppercase;
        padding: 0px;
        margin: 0px;
        margin-left: 5px;
        float: left;
        transition: all 0.3s ease-out;
        color: #555555;
    }
    .DivButton:hover span {
        color: #ffffff;
    }
    .MiceReservationContainerTitle {
        color: #ff7b7b;
        line-height: 30px;
        height: 30px;
        margin: 0px;
        margin-top: 5px;
        padding: 0px;
        padding-left: 5px;
        width: 240px;
        text-transform: uppercase;
        font-weight: normal;
    }
    .Department {
        color: #656565;
        float: left;
        display: block;
        transition: all 0.2s ease-in-out;
    }
    .DepartmentHide {
        background: #FFFFFF;
        width: 230px;
        height: 30px;
        margin: 30px;
        padding: 10px;
        position: relative;
        box-shadow: 0px 1px 4px 0px rgba(0,0,0,0.7);
    }
    .DepartmentShow {
        background: #F7F7F7;
        width: 98%;
        margin: 10px auto;
        padding: 1%;
        border: 1px solid #EEEEEE;
        box-shadow: inset 0px 0px 3px #CCCCCC;
    }
    .DepartmentHide .DepartmentIcon, .DepartmentShow .DepartmentIcon {
        width: 30px;
        height: 30px;
        float: left;
        margin: 0px;
        padding: 0px;
        padding-right: 10px;
    }
    .DepartmentHide .DepartmentIcon img, .DepartmentShow .DepartmentIcon img {
        width: 30px;
        height: 30px;
        display: block;
        margin: 0px;
        padding: 0px;
    }
    .DepartmentHide .DepartmentName, .DepartmentShow .DepartmentName {
        width: 180px;
        height: 30px;
        line-height: 15px;
        color: #555555;
        float: left;
        margin: 0px;
        padding: 0px;
    }
    .DepartmentHide .DepartmentName span, .DepartmentShow .DepartmentName span {
        font-size: 10px;
        color: #828282;
    }
    .DepartmentHide .DepartmentControl, .DepartmentShow .DepartmentControl {
        width: 10px;
        height: 30px;
        line-height: 30px;
        font-size: 14px;
        font-weight: bold;
        float: right;
        margin: 0px;
        padding: 0px;
        text-align: center;
        cursor: pointer;
        background: #FFFFFF;
        transition: all 0.3s ease-out;
    }
    .DepartmentHide .DepartmentControl:hover, .DepartmentShow .DepartmentControl:hover {
        background: #EEEEEE;
    }
    .DepartmentHide .DepartmentMessenger, .DepartmentShow .DepartmentMessenger {
        width: auto;
        height: 15px;
        line-height: 15px;
        font-size: 9px;
        margin: 0px;
        padding: 0px 5px;
        text-align: center;
        background: #ff5353;
        color: #FFFFFF;
        border-radius: 7.5px;
        position: absolute;
        top: -7.5px;
        right: -7.5px;
        display: block;
    }
    .DepartmentHide .DepartmentItems {
        display: none;
    }
    .DepartmentShow .DepartmentItems {
        width: 100%;
        margin: 10px auto;
        float: left;
        clear: both;
    }
    .DepartmentItems .items {
        background: #FFFFFF;
        margin: 10px;
        padding: 0px;
        float: left;
        border: 1px solid #EEEEEE;
    }
    .StatusRed {
        background: #ff4848!important;
    }
    .StatusBlue {
        background: #379dfc!important;
    }
    .StatusWhite {
        background: #FFFFFF!important;
    }
</style>
<form name="ListMiceReservationForm" method="POST">
    <input id="act" name="act" type="text" value="" style="display: none;" />
    <input id="save" name="save" type="checkbox" value="save" style="display: none;" />
    <div id="MiceReservationBody">
        <div id="MiceReservationHeader">
            <table>
                <tr>
                    <td>
                        <div class="DivTitle">
                            <h1>[[.mice_reservation_list.]]</h1>
                        </div>
                    </td>
                    <td style="width: 250px;">
                        <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation&cmd=add'">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                            <span>[[.add_mice.]]</span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="height: 35px; text-align: right;">
                        <table>
                            <tr>
                                <td><label>[[.code.]]:</label><br /><select name="code" id="code" style="width: 80px; padding: 3px;"></select></td>
                                <td><label>[[.contact_phone.]]:</label><br /> <input name="contact_phone" type="text" id="contact_phone" style="width: 80px; padding: 3px;" /></td>
                                <td><label>[[.contact_person.]]:</label><br /> <input name="contact_person" type="text" id="contact_person" style="width: 80px; padding: 3px;" /></td>
                                <td><label>[[.start_date.]]:</label><br /> <input name="start_date" type="text" id="start_date" style="width: 80px; padding: 3px;" /></td>
                                <td><label>[[.end_date.]]:</label><br /> <input name="end_date" type="text" id="end_date" style="width: 80px; padding: 3px;" /></td>
                                <td><label>[[.user_status.]]:</label><br />
                                    <select style="width: 100px !important;padding: 3px" name="user_status" id="user_status">
                                    <option value="1">Active</option>
                                    <option value="0">All</option>
                                  </select>
                                </td>
                                <td><label>[[.account_last_editer.]]:</label><br /> <select name="last_editer" id="last_editer" style="width: 80px; padding: 3px;">[[|all_editer|]]</select></td>
                                <td><label>[[.status.]]:</label><br /> <select name="status" id="status" style="width: 80px; padding: 3px;">[[|all_status|]]</select></td>
                                <td><label>[[.note.]]:</label><br /> <input name="note" type="text" id="note" style="width: 80px; padding: 3px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 120px;">
                        <div class="DivButton" onclick="ListMiceReservationForm.submit();">
                            <label> <span><i class="fa fa-search fa-fw"></i> [[.search.]]</span></label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="MiceReservationContainer">
            <table style="width: 100%;" cellpadding="10" border="1" bordercolor="#EEEEEE">
                <tr style="text-align: center;">
                    <th>[[.stt.]]</th>
                    <td>[[.code.]]</td>
                    <th>[[.create_date.]]</th>
                    <th>[[.start_date.]]</th>
                    <th>[[.end_date.]]</th>
                    <th>[[.contact_person.]]</th>
                    <th>[[.contact_phone.]]</th>
                    <th>[[.contact_email.]]</th>
                    <th>[[.status.]]</th>
                    <th>[[.note.]]</th>
                    <th>[[.account_last_editer.]]</th>
                    <th>[[.view_beo.]]</th>
                    <th>[[.payment.]]</th>
                    <th>[[.edit.]]</th>
                </tr>
                <!--LIST:items-->
                <tr>
                    <td style="text-align: center;">[[|items.stt|]]</td>
                    <td style="text-align: center;"><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.id|]]">MICE+[[|items.id|]]</a></td>
                    <td style="text-align: center;">[[|items.code_mice|]]</td>
                    <td>[[|items.start_date|]]</td>
                    <td>[[|items.end_date|]]</td>
                    <td>[[|items.contact_name|]]</td>
                    <td>[[|items.contact_phone|]]</td>
                    <td>[[|items.contact_email|]]</td>
                    <td style="text-align: center; width: 180px;">
                        <?php if([[=items.status=]]==1){ ?>[[.mice_confirm.]]<?php }else{ ?>[[.mice_confirming.]]<?php } ?>
                        <br />
                        <div class="[[|items.status_rec|]]" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">REC</div>
                        <div class="[[|items.status_res|]]" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">BAR</div>
                        <!--<div class="[[|items.status_vending|]]" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">VEND</div>-->
                        <div class="[[|items.status_banquet|]]" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">PARTY</div>
                        <!--<div class="[[|items.status_ticket|]]" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">TICKET</div>-->
                    </td>
                    <td>[[|items.note|]]</td>
                    <td>[[|items.last_editer|]]</td>
                    <td style="text-align: center;"><a href="?page=mice_reservation&cmd=beoform&id=[[|items.id|]]"><img src="packages/hotel/packages/mice/skins/img/report.png" /></a></td>
                    <td style="text-align: center;"><?php if([[=items.check_un_confirm=]]==0 or User::can_add(false,ANY_CATEGORY)){ ?><img onclick="openinvoice('[[|items.id|]]');" src="packages/hotel/packages/mice/skins/img/invoice.png" /><?php } ?></td>
                    <td style="text-align: center;"><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.id|]]"><img src="packages/hotel/packages/mice/skins/img/edit.png" /></a></td>
                </tr>
                <!--/LIST:items-->
            </table>
        </div>
        <div id="MiceReservationFooter">
            <p>&copy; Copy Right <?php echo date('Y'); ?> By Newway</p>
        </div>
    </div>
</form>
<script>
    var windownskey = 0;
    jQuery("#last_editer").val('[[|last_editer|]]');
    jQuery("#status").val('[[|status|]]');
    jQuery(document).ready(function(){
        jQuery(window).scroll(function(){
            //if(jQuery(this).scrollTop()>110){ CloseMenu(); }else{ OpenMenu() }
        });
    });
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    // 7211
    var users = <?php echo String::array2js([[=users=]]);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#last_editer').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
    function FunOpenWindowns(WinSrc,Wintitle,WinWidth,WinHeight,WinTop,WinLeft)
    {
        windownskey++;
        jQuery("body").append('<div id="container_window-'+windownskey+'" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(27,24,48,0.95);"></div>');
        jQuery.newWindow({
                        id:"window-"+windownskey,
                        posx:WinLeft,
                        posy:WinTop,
                        width:WinWidth,
                        height:WinHeight,
                        title:Wintitle,
                        type:"iframe"
                         });
        jQuery.updateWindowContent("window-"+windownskey,'<iframe src="'+WinSrc+'" width="'+WinWidth+'px" height="'+WinHeight+'px" />');
    }
    function openinvoice(id)
    {
        var WinSrc = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>?page=mice_reservation&cmd=invoice&id='+id;
        var Wintitle = '[[.invoice.]] MICE';
        FunOpenWindowns(WinSrc,Wintitle,1000,500,50,200);
    }
    function CloseMenu()
    {
        jQuery('#testRibbon').css('display','none');
        jQuery("#sign-in").css('display','none');
        jQuery("#chang_language").css('display','none');
    }
    function OpenMenu()
    {
        jQuery('#testRibbon').css('display','');
        jQuery("#sign-in").css('display','');
        jQuery("#chang_language").css('display','');
    }
    
</script>