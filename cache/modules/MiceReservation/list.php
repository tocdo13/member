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
                            <h1><?php echo Portal::language('mice_reservation_list');?></h1>
                        </div>
                    </td>
                    <td style="width: 250px;">
                        <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
                        <div class="DivButton" onclick="window.location.href='?page=mice_reservation&cmd=add'">
                            <div class="DivButtonIcon"><img src="packages/hotel/packages/mice/skins/img/add.png" /></div>
                            <span><?php echo Portal::language('add_mice');?></span>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="height: 35px; text-align: right;">
                        <table>
                            <tr>
                                <td><label><?php echo Portal::language('code');?>:</label><br /><select  name="code" id="code" style="width: 80px; padding: 3px;"><?php
					if(isset($this->map['code_list']))
					{
						foreach($this->map['code_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('code',isset($this->map['code'])?$this->map['code']:''))
                    echo "<script>$('code').value = \"".addslashes(URL::get('code',isset($this->map['code'])?$this->map['code']:''))."\";</script>";
                    ?>
	</select></td>
                                <td><label><?php echo Portal::language('contact_phone');?>:</label><br /> <input  name="contact_phone" id="contact_phone" style="width: 80px; padding: 3px;" / type ="text" value="<?php echo String::html_normalize(URL::get('contact_phone'));?>"></td>
                                <td><label><?php echo Portal::language('contact_person');?>:</label><br /> <input  name="contact_person" id="contact_person" style="width: 80px; padding: 3px;" / type ="text" value="<?php echo String::html_normalize(URL::get('contact_person'));?>"></td>
                                <td><label><?php echo Portal::language('start_date');?>:</label><br /> <input  name="start_date" id="start_date" style="width: 80px; padding: 3px;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
                                <td><label><?php echo Portal::language('end_date');?>:</label><br /> <input  name="end_date" id="end_date" style="width: 80px; padding: 3px;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
                                <td><label><?php echo Portal::language('user_status');?>:</label><br />
                                    <select  style="width: 100px !important;padding: 3px" name="user_status" id="user_status">
                                    <option value="1">Active</option>
                                    <option value="0">All</option>
                                  </select>
                                </td>
                                <td><label><?php echo Portal::language('account_last_editer');?>:</label><br /> <select  name="last_editer" id="last_editer" style="width: 80px; padding: 3px;"><?php
					if(isset($this->map['last_editer_list']))
					{
						foreach($this->map['last_editer_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('last_editer',isset($this->map['last_editer'])?$this->map['last_editer']:''))
                    echo "<script>$('last_editer').value = \"".addslashes(URL::get('last_editer',isset($this->map['last_editer'])?$this->map['last_editer']:''))."\";</script>";
                    ?>
	<?php echo $this->map['all_editer'];?></select></td>
                                <td><label><?php echo Portal::language('status');?>:</label><br /> <select  name="status" id="status" style="width: 80px; padding: 3px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	<?php echo $this->map['all_status'];?></select></td>
                                <td><label><?php echo Portal::language('note');?>:</label><br /> <input  name="note" id="note" style="width: 80px; padding: 3px;" / type ="text" value="<?php echo String::html_normalize(URL::get('note'));?>"></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 120px;">
                        <div class="DivButton" onclick="ListMiceReservationForm.submit();">
                            <label> <span><i class="fa fa-search fa-fw"></i> <?php echo Portal::language('search');?></span></label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="MiceReservationContainer">
            <table style="width: 100%;" cellpadding="10" border="1" bordercolor="#EEEEEE">
                <tr style="text-align: center;">
                    <th><?php echo Portal::language('stt');?></th>
                    <td><?php echo Portal::language('code');?></td>
                    <th><?php echo Portal::language('create_date');?></th>
                    <th><?php echo Portal::language('start_date');?></th>
                    <th><?php echo Portal::language('end_date');?></th>
                    <th><?php echo Portal::language('contact_person');?></th>
                    <th><?php echo Portal::language('contact_phone');?></th>
                    <th><?php echo Portal::language('contact_email');?></th>
                    <th><?php echo Portal::language('status');?></th>
                    <th><?php echo Portal::language('note');?></th>
                    <th><?php echo Portal::language('account_last_editer');?></th>
                    <th><?php echo Portal::language('view_beo');?></th>
                    <th><?php echo Portal::language('payment');?></th>
                    <th><?php echo Portal::language('edit');?></th>
                </tr>
                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                <tr>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['stt'];?></td>
                    <td style="text-align: center;"><a target="_blank" href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>">MICE+<?php echo $this->map['items']['current']['id'];?></a></td>
                    <td style="text-align: center;"><?php echo $this->map['items']['current']['code_mice'];?></td>
                    <td><?php echo $this->map['items']['current']['start_date'];?></td>
                    <td><?php echo $this->map['items']['current']['end_date'];?></td>
                    <td><?php echo $this->map['items']['current']['contact_name'];?></td>
                    <td><?php echo $this->map['items']['current']['contact_phone'];?></td>
                    <td><?php echo $this->map['items']['current']['contact_email'];?></td>
                    <td style="text-align: center; width: 180px;">
                        <?php if($this->map['items']['current']['status']==1){ ?><?php echo Portal::language('mice_confirm');?><?php }else{ ?><?php echo Portal::language('mice_confirming');?><?php } ?>
                        <br />
                        <div class="<?php echo $this->map['items']['current']['status_rec'];?>" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">REC</div>
                        <div class="<?php echo $this->map['items']['current']['status_res'];?>" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">BAR</div>
                        <!--<div class="<?php echo $this->map['items']['current']['status_vending'];?>" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">VEND</div>-->
                        <div class="<?php echo $this->map['items']['current']['status_banquet'];?>" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">PARTY</div>
                        <!--<div class="<?php echo $this->map['items']['current']['status_ticket'];?>" style="width: auto; height: 15px; border: 1px solid #555555; float: left; margin: 0px 5px; padding: 0px 2px; font-size: 7px;">TICKET</div>-->
                    </td>
                    <td><?php echo $this->map['items']['current']['note'];?></td>
                    <td><?php echo $this->map['items']['current']['last_editer'];?></td>
                    <td style="text-align: center;"><a href="?page=mice_reservation&cmd=beoform&id=<?php echo $this->map['items']['current']['id'];?>"><img src="packages/hotel/packages/mice/skins/img/report.png" /></a></td>
                    <td style="text-align: center;"><?php if($this->map['items']['current']['check_un_confirm']==0 or User::can_add(false,ANY_CATEGORY)){ ?><img onclick="openinvoice('<?php echo $this->map['items']['current']['id'];?>');" src="packages/hotel/packages/mice/skins/img/invoice.png" /><?php } ?></td>
                    <td style="text-align: center;"><a target="_blank" href="?page=mice_reservation&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><img src="packages/hotel/packages/mice/skins/img/edit.png" /></a></td>
                </tr>
                <?php }}unset($this->map['items']['current']);} ?>
            </table>
        </div>
        <div id="MiceReservationFooter">
            <p>&copy; Copy Right <?php echo date('Y'); ?> By Newway</p>
        </div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    var windownskey = 0;
    jQuery("#last_editer").val('<?php echo $this->map['last_editer'];?>');
    jQuery("#status").val('<?php echo $this->map['status'];?>');
    jQuery(document).ready(function(){
        jQuery(window).scroll(function(){
            //if(jQuery(this).scrollTop()>110){ CloseMenu(); }else{ OpenMenu() }
        });
    });
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    // 7211
    var users = <?php echo String::array2js($this->map['users']);?>;
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
        var Wintitle = '<?php echo Portal::language('invoice');?> MICE';
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