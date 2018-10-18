<style>
    .ul_room_list div div.room_status{
        border : 1px solid transparent;
        text-align: center;
        font-weight: bold;
        line-height: 20px;
        -webkit-border-radius : 3px;
        background: url(packages/hotel/packages/reception/skins/default/images/123.png) no-repeat left top;
        background-size: 55px 42px;
    }
    .ul_room_list p{
        text-align: left;
        padding-left : 14px;
        margin : 0px;
        color : blue;
    }
    .ul_room_list{
        opacity : 0.9;
    }
    .ui-selected{
        opacity : 1;
    }
    .hight_light{
        display: none;
    }
    .hight_light_room_type{
        display: none;
    }
    .ui-selected div.room_status{
        border : 1px solid #D7FFFF !important;
        background: #D7FFFF;
    }
    .ui-selected div.room_statusss{
        background: #D7FFFF;
    }
</style>
<div id="left_page">
    <div style="display: none;" class="room_avaiable">
        <table class="table_price">
            <tr>
                <td width="30%" style="font-weight: bold;">[[.price.]]</td>
                <td class="price"></td>
            </tr>
        </table>
    </div>
    <div style="display: none;" class="reservation_room">
        <table>
            <tr>
                <td style="width : 50px !important; font-weight: bold;">[[.no.]]<span class="reservation_room_id" style="font-weight: bold;"></span></td>
                <td>:   <a class="view_reservation" style="color : red">[[.view_detail.]]</a><br/>
                    :  <a href="" class="add_minibar_invoice" style="color : red">[[.add_minibar_invoice.]]</a><br/>
                    :  <a href="" class="add_landry_invoice" style="color : red">[[.add_laundry_invoice.]]</a><br/>
                </td>
            </tr>
            <tr>
                <td width="30%" style="font-weight: bold;">[[.create_user.]]</td>
                <td class="create_user"> </td>
            </tr>
            <tr>
                <td width="30%" style="font-weight: bold;">[[.status.]]</td>
                <td class="reservation_status"></td>
            </tr>
            <tr>
                <td width="30%" style="font-weight: bold;">[[.price.]]</td>
                <td class="price"></td>
            </tr>
            <tr>
                <td width="30%" style="font-weight: bold;">[[.company.]]</td>
                <td class="company_name"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="arrival_time"></span>
                    <span class="time_in"></span> - 
                    <span class="departure_time"></span>
                    <span class="time_out"></span>
                    <span class="duration"></span>
                </td>
            </tr>
            <tr>
                <td width="30%" style="font-weight: bold;">[[.guest_name.]]</td>
                <td class="guest_name"></td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold;">[[.note.]]</td>
            </tr>
            <tr>
                <td class="reservation_note" colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"><input class="submit_note_reservation" type="button" value="[[.change.]]" /></td>
            </tr>
        </table>
    </div>
    <div style="display: none;" class="housekeeping">
    <form action="" method="POST" id="submit_room_status">
        <table class="table_housekeeping">
            <tr>
                <td style="font-weight: bold;">[[.housekeeping.]] : </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">[[.note.]]</td>
            </tr>
            <tr>
                <td class="housekeeping_note" ></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;">[[.room_status.]]</span>
                    <span class="window_room_status"></span>
                    <span><input class="submit_room_status" type="button" value="[[.change.]]" /></span>
                </td>
            </tr>
        </form>
        </table>
    </div>
</div>
<div id="right_page">
    <div id="right_mouse_menu" style="display: none; position: absolute;">
        <ul>
            <li class="li_agent"><div><img src="resources/default/accounting_report.png" width="20" height="20" style="margin: 0px;" /></div><strong>[[.reserve_for_agent.]]</strong></li>
            <li class="li_agent"><div><img src="resources/default/accounting_report.png"width="20" height="20" style="margin: 0px;" /></div><strong>[[.reserve_for_walk_in.]]</strong></li>
            <li class="li_agent"><div><img src="resources/default/1303290976_checkin.png"width="20" height="20" style="margin: 0px;" /></div><strong>[[.checkin.]]</strong></li>
            <?php if(User::is_admin()){ ?>
            <li onclick="jQuery('#right_mouse_menu').hide();location.href='<?php echo Url::build_current(array('cmd' => 'room_setting'));?>'"><div><img src="resources/default/configuration.png"width="20" height="20" style="margin: 0px;" /></div><strong>[[.room_setting.]]</strong></li>
            <?php }?>
            <li id="explantion"><div></div><strong>[[.explantion.]]</strong></li>
        </ul>
    </div>
    <div id="show_room_map" style="background: url(<?php echo BACKGROUND_ROOM_MAP ?>); height : <?php echo HEIGHT_ROOM_MAP ?>px; background-size: 975px <?php echo HEIGHT_ROOM_MAP; ?>px;">
        <!--LIST:room_list-->
            <!--LIST:room_list.room-->
                <!--IF:cond1234([[=room_list.room.left=]] != '' && [[=room_list.room.top=]] != '')-->
                    <li class="ul_room_list show_room_map_li draggable_li" id="room_[[|room_list.room.id|]]" group="[[|room_list.floor_id|]]" status="[[|room_list.room.room_status|]]" room_type="room_type_[[|room_list.room.room_type_id|]]" style="left : [[|room_list.room.left|]]px ; top :[[|room_list.room.top|]]px; ">
                        <div class="[[|room_list.room.room_status|]] room_statusss" style="height: 93%;">
                            <div class="room_status" style="height: 96%;">
                                <p>[[|room_list.room.name|]]</p>
                                <!--IF:cond1111([[=room_list.room.house_status=]] == "DIRTY")-->
                                    <p>[[|room_list.room.house_status|]]</p>
                                <!--/IF:cond1111-->
                                <?php $i = 1; ?>
                                <!--LIST:room_list.room.status-->
                                    <?php if($i != 1 ){ ?>
                                    <div class="last_status_[[|room_list.room.status.status|]] last_status" title="[[.code.]] : [[|room_list.room.status.reservation_id|]] , [[.status.]] : <?php echo Portal::language([[=room_list.room.status.status=]]) ?> , [[.price.]] : [[|room_list.room.status.price|]] <?php echo HOTEL_CURRENCY ?>"></div>
                                    <?php } $i++;?>
                                <!--/LIST:room_list.room.status-->
                            </div>
                        </div>
                    </li>
                <!--/IF:cond1234-->
            <!--/LIST:room_list.room-->
        <!--/LIST:room_list-->
    </div>
    <div class="reservation_room_window reservation_room_windows" style="display: none;">
            <ul id="draggable_window">
                <li style="font-weight: bold;">[[.room_information.]]</li>
                <li class="close_window" style="float: right;"><img src="packages/hotel/packages/reception/skins/default/images/icon_close_window.gif" title="[[.close_window.]]" /></li>
            </ul>
            <div class="reservation_room_window_footer">
                <div class="reservation_room_information">
                    <table style="margin-bottom: 20px;" >
                        <tr>
                            <td style="text-align: right; font-weight: bold; width : 45%; font-size: 1.2em;">[[.room.]]</td>
                            <td class="window_room_name" style="font-weight: bold; font-size: 1.2em;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; font-weight: bold; font-size: 1.2em;">[[.room_type.]]</td>
                            <td class="window_brief_name" style="font-weight: bold; font-size: 1.2em;"></td>
                        </tr>
                    </table>
                </div>
                <div class="booked_room" >
                    <a class="booked_room" style="font-weight: bold; position: absolute; top: 260px;left: 5px;">[[.booked.]]</a>
                </div>
            </div>
    </div>
    <div id="expantion" style=" position: absolute; top : 0px; left : 0px; z-index : 1; display: none;">
        <div class="close_explantion">
            <img src="packages/hotel/packages/reception/skins/default/images/close.png" width="13" height="13" style="float: right;" />
        </div>
        <ul class="explanation">
            <li class="explanation_li"><h3>[[.date_select.]]</h3>
                <ul  style="display: block;">
                    <li>
                        <label>[[.view_date.]]</label>
                        <input name="current_date" id="current_date" value="<?php echo Url::get('current_date') ?>" />
                    </li>
                </ul>
            </li>
            <li class="explanation_li"><h3>[[.room_status.]]</h3>
                <ul>
                    <li class="explanation_li2 selected"><div class="all_explanation"></div><span style="font-size: 0.9em;padding-left : 12px;">[[.all.]]( [[|total_room|]] )</span></li>
                    <!--LIST:explanation-->
                        <li class="explanation_li2"><div class="[[|explanation.name|]]"></div><span style="font-size: 0.9em;">[[|explanation.name|]] ( [[|explanation.value|]] )</span></li>
                    <!--/LIST:explanation-->
                </ul>
            </li>
            <li class="explanation_li"><h3>[[.room_type.]]</h3>
                <ul>
                    <li class="room_type_li2 selected" room_type="all_room_type"><div></div><span style="font-size: 0.9em;padding-left : 12px;">[[.all.]]( [[|total_room|]] )</span></li>
                    <!--LIST:room_type-->
                        <li class="room_type_li2" room_type="room_type_[[|room_type.id|]]"><div></div><span>[[|room_type.brief_name|]] ( [[|room_type.total_room|]] )</span></li>
                    <!--/LIST:room_type-->
                </ul>
            </li>
        </ul>
    </div>
</div>
<script>
    var room_list = [[|room_list_js|]];
    var floor_information = [[|room_information|]];
    var room_information = new Array();
    for(key in floor_information){
        for(k in floor_information[key]['room']){
            room_information[k] = floor_information[key]['room'][k]; 
        }
    }
    jQuery(document).ready(function(){
        jQuery('.close_window').click(function(){
            jQuery('.reservation_room_window').hide();
        });
       jQuery('#show_room_map').bind('contextmenu' , function(e){
            left_right_menu = e.pageX;
            top_right_menu = e.pageY ;
            width_right_menu = jQuery('#right_mouse_menu').width();
            height_right_menu = jQuery('#right_mouse_menu').height();
            left_show_room_map = jQuery('#show_room_map').offset().left;
            top_show_room_map = jQuery('#show_room_map').offset().top - 1;
            width_show_room_map = jQuery('#show_room_map').width();
            height_show_room_map = jQuery('#show_room_map').height();
            if((width_show_room_map + left_show_room_map) < (width_right_menu + left_right_menu)){
                left_menu = left_right_menu - width_right_menu - left_show_room_map;
            }else{
                left_menu = left_right_menu - left_show_room_map;
            }
            if((height_show_room_map+top_show_room_map) < (height_right_menu + top_right_menu)){
                top_menu = top_right_menu - height_right_menu -top_show_room_map;
            }else{
                top_menu = top_right_menu - top_show_room_map;
            }
            jQuery('#right_mouse_menu').css({'left' : left_menu , 'top' : top_menu}).show();
            return false; 
        });
        jQuery('#explantion').click(function(){
            if(jQuery(this).children('div').children().length){
                jQuery('#expantion').animate({'left' : 0},200,function(){
                   jQuery('#expantion').hide(); 
                });
                jQuery('#right_mouse_menu').hide();
                jQuery(this).children('div').children().remove();
            }else{
                jQuery('#expantion').show().animate({'left' : -180},200);
                jQuery('#right_mouse_menu').hide();
                jQuery(this).children('div').append('<img src="packages/hotel/packages/reception/skins/default/images/check.png" width="13" height="13" />');    
            }
            });
        jQuery('.close_explantion').children().click(function(){
            jQuery('#expantion').animate({'left' : 0},200,function(){
               jQuery('#expantion').hide(); 
               jQuery('#explantion').children('div').children().remove();
            });
        });
        jQuery('.explanation_li').click(function(){
            if(jQuery(this).children().is(':hidden')){
                jQuery('.explanation_li').children('ul').slideUp();
                jQuery(this).children().slideDown();
            }
        });
        jQuery('.explanation_li2').click(function(){
            jQuery('.explanation_li2').removeClass('selected');
            jQuery(this).addClass('selected');
            hight_light(jQuery(this).children().attr('class'));
        });
        jQuery('.room_type_li2').click(function(){
            jQuery('.room_type_li2').removeClass('selected');
            jQuery(this).addClass('selected');
            hight_light(jQuery(this).attr('room_type') , 'room_type'); 
        });
        function hight_light(string , hight_light_type){
            if(string == 'all_room_type'){
                jQuery('.show_room_map_li').removeClass('hight_light_room_type');
                return;
            }
            if(string == 'all_explanation'){
                jQuery('.show_room_map_li').removeClass('hight_light');
                return;
            }
            li_lengths = jQuery('.show_room_map_li').length;
            for(t = 1 ; t <= li_lengths ; t++){
                if(hight_light_type == 'room_type'){
                        if(jQuery('.show_room_map_li:nth-child('+ t +')').attr('room_type') != string){
                            jQuery('.show_room_map_li:nth-child('+ t +')').addClass('hight_light_room_type');
                        }else{
                            jQuery('.show_room_map_li:nth-child('+ t +')').removeClass('hight_light_room_type');
                        }
                }else{
                    if(jQuery('.show_room_map_li:nth-child('+ t +')').attr('status') != string){
                        jQuery('.show_room_map_li:nth-child('+ t +')').addClass('hight_light');
                    }else{
                        jQuery('.show_room_map_li:nth-child('+ t +')').removeClass('hight_light');
                    }
                }
            }
        }
       jQuery(init);
       function init(){
            jQuery('#show_room_map').selectable({
                start : function(event , ui){
                    jQuery('#right_mouse_menu').hide();
                },
                cancel : '.reservation_room_window',
                stop : room_select
            });
            jQuery('.reservation_room_window').draggable({
                handle : '#draggable_window',
                contaiment : 'window',
                cursor : 'default'
            });
       }
       function room_select( event , ui ){
            if(jQuery('#show_room_map li.ui-selected').length == 1){
                jQuery('.reservation_room_windows').show();
                id = jQuery('#show_room_map li.ui-selected').attr('id');
                jQuery('.reservation_room_information').children('div').remove();
                jQuery('.reservation_room_information table.table_housekeeping').remove();
                jQuery('.reservation_room_information table.table_price').remove();
                room_id = id.split('_');
                room_id = room_id[1];
                room_name = room_information[room_id]['name'];
                room_price = room_information[room_id]['price'];
                room_type_id = room_information[room_id]['room_type_id'];
                jQuery('.reservation_room_windows a.booked_room').click(function(){
                    location.href = '?page=reservation&cmd=add&time_in=13:00&time_out=12:00&rooms='+ room_id + ',<?php echo date('d/m/Y',time()) ?>,<?php echo date('d/m/Y',time()) ?>&room_prices[' + room_type_id + ']=' + to_numeric(room_price);
                });
                jQuery('.window_room_name').html(': ' + room_name + ' ( ' + room_information[room_id]['floor'] + ' ) ');
                jQuery('.window_brief_name').html(': ' + room_information[room_id]['brief_name']);
                jQuery('.room_avaiable td.price').html(' : ' + room_price + ' <?php echo HOTEL_CURRENCY; ?>');
                if(jQuery('#' + id).attr('status') == 'AVAILABLE' || jQuery('#' + id).attr('status') == 'REPAIR'){
                    jQuery('.reservation_room_information').append(jQuery('.room_avaiable').html());
                }else{
                    for(key in room_list){
                        if(room_list[key]['room_id'] == room_id){
                            jQuery('.reservation_room_information').append('<div class="reservation_room_detail" id=' + room_list[key]['id'] + '></div>');
                            jQuery('.reservation_room_information div.room_avaiable').remove();
                            jQuery('.reservation_room td span.reservation_room_id').html(' : ' + room_list[key]['reservation_room_id']);
                            jQuery('.reservation_room td.create_user').html(' : ' + room_list[key]['user_id']);
                            jQuery('.reservation_room td.company_name').html(' : ' + room_list[key]['company_name']);
                            jQuery('.reservation_room td.price').html(' : ' + room_list[key]['price'] + ' <?php echo HOTEL_CURRENCY ?>');
                            jQuery('.reservation_room td span.arrival_time').html(room_list[key]['arrival_time']);
                            jQuery('.reservation_room td span.departure_time').html(room_list[key]['departure_time']);
                            jQuery('.reservation_room td span.time_in').html('(' + room_list[key]['time_in'] + ')');
                            jQuery('.reservation_room td span.time_out').html('(' + room_list[key]['time_out'] + ')');
                            jQuery('.reservation_room td span.duration').html('(' + room_list[key]['duration'] + ')');
                            jQuery('.reservation_room td.reservation_note').html('<textarea id="'+ room_list[key]['reservation_room_id'] +'">' + room_list[key]['reservation_note'] + '</textarea>');
                            jQuery('.reservation_room td a.view_reservation').attr('id' , room_list[key]['reservation_room_id'] + '_' + room_list[key]['reservation_id'] + '_' + room_id );
                            jQuery('.reservation_room td a.add_minibar_invoice').attr('id' , room_list[key]['reservation_room_id'] + '_' + room_list[key]['reservation_id'] + '_' + room_id );
                            jQuery('.reservation_room td a.add_landry_invoice').attr('id' , room_list[key]['reservation_room_id'] + '_' + room_list[key]['reservation_id'] + '_' + room_id );
                            jQuery('.reservation_room input.submit_note_reservation').attr('id' , room_list[key]['reservation_room_id'] + '_' + room_list[key]['reservation_id'] + '_' + room_id);
                            jQuery('.reservation_room td.reservation_status').html(room_list[key]['status']);
                            
                            traveller_name = '';
                            for(k in room_list[key]['traveller']){
                                if(room_list[key]['traveller'][k]['gender'] == 1){
                                    traveller_name += '<a  href="?page=traveller&id=' + room_list[key]['traveller'][k]['traveller_id'] + '" target="_blank"> : + Mr ';
                                }else{
                                    traveller_name += '<a href="?page=traveller&id=' + room_list[key]['traveller'][k]['traveller_id'] + '" target="_blank"> : + Ms ';
                                }
                                traveller_name += room_list[key]['traveller'][k]['full_name'] + '</a><br>';
                            }
                            jQuery('.reservation_room td:textarea.reservation_room_note').val(room_list[key]['note']);
                            jQuery('.reservation_room td.guest_name').html(traveller_name);
                            jQuery('.reservation_room_information div#' + room_list[key]['id']).append(jQuery('.reservation_room').html());
                        }
                    }
                    jQuery('.reservation_room_information td a.view_reservation').click(function(){
                        id = jQuery(this).attr('id');
                        id = id.split('_');
                        location.href = '?page=reservation&cmd=edit&r_r_id='+ id[0] +'&id='+ id[1];
                    });
                    jQuery('.reservation_room_information td a.add_minibar_invoice').click(function(){
                        id = jQuery(this).attr('id');
                        id = id.split('_');
                        location.href = '?page=minibar_invoice&cmd=add&reservation_room_id='+ id[0];
                        return false;
                    });
                    jQuery('.reservation_room_information td a.add_landry_invoice').click(function(){
                        id = jQuery(this).attr('id');
                        id = id.split('_');
                        location.href = '?page=laundry_invoice&cmd=add&reservation_room_id='+ id[0];
                        return false;
                    });
                    jQuery('.submit_note_reservation').click(function(){
                        id = jQuery(this).attr('id');
                        id = id.split('_');
                        location.href = '<?php echo Url::build_current(); ?>&reservation_room_id=' + id[0] + '&room_id=' + id[2] + '&reservation_room_note=' + jQuery(this).parents('tr').prev().children().children().val();
                        return false;
                    });
                }
                jQuery('.reservation_room_information ').append(jQuery('.housekeeping').html());
                jQuery('.reservation_room_information td span.window_room_status').html('<SELECT name="change_room_status" id="change_room_status"><option value="READY">READY</option><option value="DIRTY">DIRTY</option><option value="REPAIR">REPAIR</option><option value="HOUSEUSE">HOUSEUSE</option></SELECT>');
                jQuery('.reservation_room_information td.housekeeping_note').html('<textarea>'+ room_information[room_id]['note'] +'</textarea>');
                if((event.pageX - jQuery('#show_room_map').offset().left - jQuery('#show_room_map').width()) > - jQuery('.reservation_room_windows').width()){
                    leftss = (jQuery('#show_room_map').width() - jQuery('.reservation_room_windows').width()) + 'px';
                }else{
                    leftss = event.pageX - jQuery('#show_room_map').offset().left;
                }
                if((event.pageY - jQuery('#show_room_map').offset().top - jQuery('#show_room_map').height()) > - jQuery('.reservation_room_windows').height()){
                    topss = (jQuery('#show_room_map').height() - jQuery('.reservation_room_windows').height()) + 'px';
                }else{
                    topss = event.pageY - jQuery('#show_room_map').offset().top;
                }
                jQuery('.reservation_room_windows').css({
                    'left' : leftss,
                    'top' : topss
                });
                jQuery('.submit_room_status').click(function(){
                    location.href = '<?php echo Url::build_current();?>&room_id=' + room_id + '&house_status=' + jQuery('#change_room_status').val() + '&housekeeping_note=' + jQuery('.housekeeping_note').children().val();
                });
            }else if(jQuery('#show_room_map li.ui-selected').length > 1){
                jQuery('.reservation_room_windows').hide();
            }
       }
       function booked_rooms_selected(){
            if(jQuery('#show_room_map li.ui-selected').length >= 1){
                roomss = '';
                room_prices = '';
                for( count = 0 ; count <= jQuery('#show_room_map li.ui-selected').length-1 ; count++ ){
                    id = jQuery('#show_room_map li.ui-selected:eq('+ count +')').attr('id');
                    room_id = id.split('_');
                    room_id = room_id[1];
                    room_price = room_information[room_id]['price'];
                    room_type_id = room_information[room_id]['room_type_id'];
                    if(count == 0){
                        roomss += 'rooms=' + room_id + ',<?php echo date('d/m/Y') ?>,<?php echo date('d/m/Y') ?>';
                    }else{
                        roomss += '|' + room_id + ',<?php echo date('d/m/Y') ?>,<?php echo date('d/m/Y') ?>';
                    }
                    room_prices += '&room_prices[' + room_type_id + ']=' + to_numeric(room_price);
                }
                location.href = '?page=reservation&cmd=add&time_in=13:00&time_out=12:00&' + roomss + room_prices;
            }else{
                location.href = '?page=reservation&cmd=add';
            }
       }
       jQuery('#right_mouse_menu li.li_agent').click(function(){
            jQuery('#right_mouse_menu').hide();
            booked_rooms_selected();
       })
    });
</script>
<script>
jQuery('#current_date').datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1) ,yearRange: '-100:+4'});
jQuery('#current_date').change(function(){
    location.href = '<?php echo Url::build_current() ?>&current_date=' + jQuery(this).val();
})
</script>
