<style>
    .ul_room_list{
        border : 1px solid #FFF;
        text-align: center;
        font-weight: bold;
        line-height: 20px;
        -webkit-border-radius : 3px;
        background: url(packages/hotel/packages/reception/skins/default/images/123.png) no-repeat center left;
        background-size: 63px 54px;
    }
    .ul_room_list div{
        color : #03C;
        width : 90%;
        margin : 8px auto;
    }
    .ui-selected{
        opacity : 1;
    }
	.simple-layout-content{
		min-height : 660px;
	}
</style>
<div id="right_page">
    <div id="show_room_map" style="background: url(<?php echo BACKGROUND_ROOM_MAP ?>); height : <?php if( HEIGHT_ROOM_MAP > 508 ) echo HEIGHT_ROOM_MAP; else echo 508;?>px; backgroup-size : 975px <?php echo HEIGHT_ROOM_MAP; ?>;">
        <!--LIST:room_list-->
            <!--LIST:room_list.room-->
                <!--IF:cond1234([[=room_list.room.left=]] != '' && [[=room_list.room.top=]] != '')-->
                    <li class="ul_room_list show_room_map_li draggable_li" id="room_[[|room_list.room.id|]]" group="[[|room_list.floor_id|]]" style="left : [[|room_list.room.left|]]px ; top :[[|room_list.room.top|]]px; ">
                        <div style="width : 15px;height : 15px;float:right;margin : 3px 3px 0 0 ;"><img class="close_drag" style="display: none;" title="close" src="packages/hotel/packages/reception/skins/default/images/icon_close.gif" width="10" height="10" /></div>
                        <div>
                            [[|room_list.room.name|]]
                        </div>
                    </li>
                <!--/IF:cond1234-->
            <!--/LIST:room_list.room-->
        <!--/LIST:room_list-->
    </div>
    <div id="right_mouse_menu" style="display: none; position: absolute;">
        <ul>
            <li onclick="location.href='<?php echo Url::build_current(); ?>' ; jQuery('#right_mouse_menu').hide();"><div></div><span>[[.back_to_room_map.]]</span></li>
            <li id="align_to_grid"><div><?php if(ALIGN_TO_GRID == 1){ ?><img  src="packages/hotel/packages/reception/skins/default/images/check.png" width="13" height="13" /><?php }?></div><span>[[.align_to_grid.]]</span></li>
            <li><div></div><span>[[.show_room_list.]]</span>
                <ul class="right_mouse_menu_ul_li_ul">
                    <!--LIST:room_list-->
                        <li id="right_menu_[[|room_list.floor_id|]]" class="floor_right_menu"><div></div><span>[[|room_list.id|]]</span></li>
                    <!--/LIST:room_list-->
                </ul>
                <div style="float : right;"><img src="packages/hotel/packages/reception/skins/default/images/icon_arrow_right.gif" /></div>
            </li>
            <li id="hide_all_room"><div></div><span>[[.hide_rooms.]]</span></li>
            <li id="change_background"><div></div><span>[[.change_background.]]</span></li>
        </ul>
    </div>
    <div id="change_background_option" style="display: none; z-index : 20000;">
        <form method="POST" enctype="multipart/form-data">
            <h3>[[.change_background.]]</h3>
            <div class="change_images">
                <img src="<?php echo BACKGROUND_ROOM_MAP ;?>" width="250" height="150" />
                <input type="file" name="background_room_map"  />
            </div>
            <div class="submit_change_bakground">
                <input type="submit" value="[[.ok.]]" />
                <input class="cancel" type="button" value="[[.cancel.]]" />
            </div>
        </form>
    </div>
    <div id="show_list_room">
        <img src="packages/hotel/packages/reception/skins/default/images/show_room_list.png" title="[[.show.]]" width="15px" height="30px" />
    </div>
    <div id="list_room" style="display: none;">
        <div id="room_floor" style="display: none;;">
            <ul>
                <?php $i = 1; ?>
                <!--LIST:room_list-->
                    <li <?php if($i == 1) echo 'class="room_list_active room_floor"'; else echo 'class="room_floor"'; $i++; ?> id="[[|room_list.floor_id|]]" >[[|room_list.id|]]</li>
                <!--/LIST:room_list-->
                <li class="hide_room_list"><img src="packages/hotel/packages/reception/skins/default/images/minimize.png" width="20px" height="15px" title="hide" /></li>
            </ul>
        </div> 
        <div id="room_list_floor">
            <div id="abcd">
                <div class="arrow arrow_left" id="abcde">
                    <div>
                        <img src="packages/hotel/packages/reception/skins/default/images/arrow_left.png" />
                    </div>
                </div>
                <div id="list_ul">
                <?php $j = 1; ?>
                <!--LIST:room_list-->
                    <ul class="ul_rooms_list" id="ul_[[|room_list.floor_id|]]" <?php if($j != 1) echo 'style="display : none"'; $j++; ?>>
                        <?php $i = 1; ?>
                        <!--LIST:room_list.room-->
                        <!--IF:cond123([[=room_list.room.left=]] == '' || [[=room_list.room.top=]] == '')-->
                            <?php if( $i <= 9 ){ ?>
                                <li class="ul_room_list draggable_li" id="room_[[|room_list.room.id|]]" group="[[|room_list.floor_id|]]">
                            <?php } else {?>
                                <li style="display: none;" class="ul_room_list draggable_li" id="room_[[|room_list.room.id|]]" group="[[|room_list.floor_id|]]">
                            <?php } $i++;?>
                                <div style="width : 15px;height : 15px;float:right;margin : 4px 4px 0 0 ;"><img style="display: none;" class="close_drag" title="close" src="packages/hotel/packages/reception/skins/default/images/icon_close.gif" width="10" height="10" /></div>
                                <div>
                                    [[|room_list.room.name|]]
                                </div>
                            </li>
                            <!--/IF:cond123-->
                        <!--/LIST:room_list.room-->
                    </ul>
                <!--/LIST:room_list-->
                </div>
                <div class="arrow arrow_right">
                    <div>
                        <img src="packages/hotel/packages/reception/skins/default/images/arrow_right.png" />
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        function save_position( id , position_left , position_top){
            id = id.split('_');
            jQuery.ajax({
                method : "POST",
                url : 'form.php?block_id=<?php echo Module::block_id(); ?>',
                data :{
                    cmd : 'update_position',
                    room_id : id[1],
                    left : position_left,
                    top : position_top
                },
                success: function(content){
                   if(content){
                        //alert(content);
                   }
                }    
            });
        }
        function save_position_all_rooms(string){
            jQuery.ajax({
                method : "POST",
                url : 'form.php?block_id=<?php echo Module::block_id(); ?>',
                data :{
                    cmd : 'update_position_all_room',
                    room_list : string
                },
                success : function (content){
                    if(content){
                        //alert(content);
                    }
                }
            });
        }
        function close_drag(id){
            group = jQuery('#' + id).attr('group');
            jQuery('#' + id).appendTo('#ul_' + group );
            jQuery('#' + id).removeAttr('style');
            jQuery('#' + id).css({
                'position' : 'relative'
            });
            jQuery('#' + id).removeClass('box_shadow');
            jQuery('#' + id).removeClass('show_room_map_li');
            jQuery('#' + id).children().children().children().children('span.close_drag').hide();
            li_length = jQuery('#ul_' + group + ' li').length;
            if(li_length >= (i*9 + 1) && li_length < ((i+1)*9)){
                jQuery('#' + id).show();
            }else{
                jQuery('#' + id).hide();
            }
            id = id.split('_');
            jQuery.ajax({
                method : "POST",
                url : 'form.php?block_id=<?php echo Module::block_id(); ?>',
                data : {
                    cmd : 'update_position',
                    room_id : id[1]
                },
                success:function(content){
                }
            });
        }
        function save_height_room_map(event , ui){
            height_room_map = jQuery(this).height();
            jQuery.ajax({
                method : "POST",
                url : 'form.php?block_id=<?php echo Module::block_id(); ?>',
                data : {
                    cmd : 'update_height_room_map',
                    height : height_room_map
                },
                success : function(content){
                    if(content){
                        //alert(content);
                    }
                }
            });
        }
        function save_align_to_grid(string){
            jQuery.ajax({
                method : "POST",
                url : 'form.php?block_id=<?php echo Module::block_id(); ?>',
                data : {
                    cmd : 'update_align_to_grid',
                    grid : string
                },
                success : function(content){
                    if(content){
                        alert(content);
                    }
                }
            });
        }
        jQuery('#show_room_map').bind('contextmenu' , function(e){
            left_right_menu = e.pageX;
            top_right_menu = e.pageY ;
            width_right_menu = jQuery('#right_mouse_menu').width();
            height_right_menu = jQuery('#right_mouse_menu').height();
            left_show_room_map = jQuery('#show_room_map').offset().left;
            top_show_room_map = jQuery('#show_room_map').offset().top - 25;
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
        jQuery('#align_to_grid').click(function(){
            <?php if(ALIGN_TO_GRID == 1){ ?>
                grid = 2;
                location.href = '<?php echo Url::build_current(); ?>&cmd=room_setting';
            <?php }else{?>
                grid = 1;
                location.href = '<?php echo Url::build_current(); ?>&cmd=room_setting';
            <?php }?>
            save_align_to_grid(grid);
            jQuery('#right_mouse_menu').hide();
        });
        jQuery('.floor_right_menu').click(function(){
            if(jQuery(this).children('div').children().length){
                jQuery('#list_room').fadeOut();
                jQuery('#right_mouse_menu').hide();
                jQuery('.floor_right_menu').children('div').children('img').remove();
            }else{
                jQuery('#list_room').fadeIn();
                id = jQuery(this).attr('id');
                id = id.split('_');
                jQuery('#' + id[2]).click();
                jQuery('#right_mouse_menu').hide();
                jQuery('.floor_right_menu').children('div').children('img').remove();
                jQuery(this).children('div').append('<img src="packages/hotel/packages/reception/skins/default/images/check.png" width="13" height="13" />');
            }
        });
        jQuery('#show_room_map').click(function(){
            jQuery('#right_mouse_menu').hide();
        });
        jQuery('#hide_all_room').click(function(){
            if(confirm('[[.Are_you_sure_?.]]')){
                location.href = '<?php echo Url::build_current(); ?>&hide_all_rooms=1';
            }
        });
        jQuery('#change_background').click(function(){
            jQuery('#change_background_option').slideDown('fast');
            jQuery('#right_mouse_menu').hide();
        });
        jQuery('#change_background_option .cancel').click(function(){
            jQuery('#change_background_option').slideUp('fast');
        });
        var i = 0;
        var drag_count = 0;
        left = 0;
        current_id = jQuery('.room_list_active').attr('id');
        if(jQuery('#ul_' + current_id + ' li').length <= 9 ){
            jQuery('.arrow_right div').hide();
        }
        jQuery('.room_floor').click(function(){
            class_array = jQuery(this).attr('class');
            class_array = class_array.split(' ');
            check_class = false;
            for(x in class_array){
                if(class_array[x] == 'room_list_active'){
                    check_class = true;
                }
            }
            if(check_class == false){
                i = 0 ;
                left = 0;
                drag_count = 0;
                jQuery('.arrow_left div').hide();
                jQuery('.arrow_right div').show();
                jQuery('.room_floor').removeClass('room_list_active');
                jQuery(this).addClass('room_list_active');
                id = jQuery(this).attr('id');
                jQuery('.ul_rooms_list').hide();
                jQuery('#ul_' + id).show();
                if(jQuery('#ul_' + id + ' li').length <= 9 ){
                    jQuery('.arrow_right div').hide();
                }
            }
        });
        jQuery('.arrow_left div').hide();
        jQuery('.arrow_right').click(function(){
            id = jQuery('.room_list_active').attr('id');
            if(i <= (Math.ceil(jQuery('#ul_' + id + ' li').length/9) - 2)){
                jQuery('#list_ul').addClass('hidden');
                for( j = 1 ; j <= 9 ; j++ ){
                    jQuery('#ul_' + id + ' li:nth-child('+ ( j + 9*(i+1) ) +')').show();
                }
                left = -900;
                jQuery('#ul_' + id).stop(true).animate({left : left},250 , function(){
                    jQuery('.arrow_left div').show();
                    for( j = 1 ; j <= 9 ; j++ ){
                        jQuery('#ul_' + id + ' li:nth-child('+ ( j + 9*i) +')').hide();
                    }
                    jQuery('#ul_' + id).css({'left' : '0'});
                    jQuery('#list_ul').removeClass('hidden');
                    i++;
                });
                if(i == (Math.ceil(jQuery('#ul_' + id + ' li').length/9) - 2)){
                    jQuery('.arrow_right div').hide();
                }
            }
        });
        jQuery('.arrow_left').click(function(){
            id = jQuery('.room_list_active').attr('id');
            if(i >= 1 ){
                jQuery('#list_ul').addClass('hidden');
                jQuery('#ul_' + id).css({'left' : '-900px'});
                for( j = 1 ; j <= 9 ; j++ ){
                    jQuery('#ul_' + id + ' li:nth-child('+ ( j + 9*(i-1) ) +')').show();
                }
                jQuery('#ul_' + id).stop(true).animate({left : 0},250 , function(){
                    for( j = 1 ; j <= 9 ; j++ ){
                        jQuery('#ul_' + id + ' li:nth-child('+ ( j + 9*i) +')').hide();
                    }
                    jQuery('#list_ul').removeClass('hidden');
                    i--;
                });
                if( i <= 1){
                        jQuery('.arrow_left div').hide();
                    }
            }
            jQuery('.arrow_right div').show();
        });
    jQuery('#show_room_map li.show_room_map_li').hover(function(){
        id = jQuery(this).attr('id');
        if(jQuery(this).parents('#show_room_map').length){
            jQuery(this).children().children('img.close_drag').show();
            jQuery(this).css({
                'border' : '1px dotted #C2BEF6'
            });
        }
        jQuery(this).children().children('img.close_drag').click(function(event){
            close_drag(id);
            event.stopPropagation();
        });
    },function(){
        jQuery(this).css({
                'border' : '1px solid #FFF'
            });
        jQuery('img.close_drag').hide();
    }).click(function(){
    });
    var last_left = 0;
    var last_top = 0;
    jQuery(init);
    function init(){
        jQuery('.draggable_li').draggable({
            start : get_offset,
            containment: 'document',
            stack : '.draggable_li',
            cursor: 'default',
            revert: true,
            stop : stop_draggable
        });
        <!--IF:grip(ALIGN_TO_GRID == 1)-->
            if(jQuery('.show_room_map_li:nth-child(1)').length){
                widthhhh = jQuery('.show_room_map_li:nth-child(1)').width() + 10;
                heightttt = jQuery('.show_room_map_li:nth-child(1)').height() + 10;
            }else{
                widthhhh = jQuery('.draggable_li:nth-child(1)').width() + 10;
                heightttt = jQuery('.draggable_li:nth-child(1)').height() + 10;
            }
            max_show_room_map_li_width = Math.ceil(jQuery('#show_room_map').width()/(widthhhh)) - 1 ;
            max_show_room_map_li_height = Math.ceil(jQuery('#show_room_map').height() / (heightttt)) - 1 ;
            max_show_room_map_li = max_show_room_map_li_width * max_show_room_map_li_height;
            for(i = 1 ; i<= max_show_room_map_li ; i++){
                jQuery('#show_room_map').append('<li style="border : 1px solid transparent;" class="align_to_grid"></li>');
            }
            jQuery('#show_room_map li.align_to_grid').width(widthhhh);
            jQuery('#show_room_map li.align_to_grid').height(heightttt);
            jQuery('#show_room_map li.align_to_grid').droppable({
                drop : handleCardDrop
            });
            width_grid = (jQuery('li.align_to_grid').width() + 2);
            height_grid = (jQuery('li.align_to_grid').height() + 2);
            count_show_room_map_li = jQuery('#show_room_map li.show_room_map_li').length;
            leftsss_show_room_map = jQuery('#show_room_map').offset().left;
            topsss_show_room_map = jQuery('#show_room_map').offset().top;
            update_rooms_positon = '';
            for(count = 0 ; count < count_show_room_map_li ; count++){
                leftsss_li = jQuery('#show_room_map li.show_room_map_li:eq('+ count +')').offset().left;
                topsss_li = jQuery('#show_room_map li.show_room_map_li:eq('+ count +')').offset().top;
                widthsss_li = jQuery('#show_room_map li.show_room_map_li:eq('+ count +')').width();
                heightsss_li = jQuery('#show_room_map li.show_room_map_li:eq('+ count +')').height();
                colspans = Math.ceil((leftsss_li - leftsss_show_room_map)/width_grid);
                rowspans = Math.ceil((topsss_li - topsss_show_room_map)/height_grid);
                if((leftsss_li - leftsss_show_room_map)%width_grid > (width_grid/2)){
                    colspans++;
                }
                if((topsss_li - topsss_show_room_map)%height_grid > (height_grid/2)){
                    rowspans++;
                }
                id = jQuery('#show_room_map li.show_room_map_li:eq('+ count +')').attr('id');
                id = id.split('_');
                jQuery('#show_room_map li.show_room_map_li:eq('+ count +')').css({
                    'left' : (width_grid*(colspans - 1) + 5 ),
                    'top' :  (height_grid*(rowspans-1) + 4)
                });
                if(count == 0){
                    update_rooms_positon += id[1] + ',' + (width_grid*(colspans - 1) + 5 ) + ',' + (height_grid*(rowspans-1) + 4);
                }else{
                    update_rooms_positon += '|' + id[1] + ',' + (width_grid*(colspans - 1) + 5 ) + ',' + (height_grid*(rowspans-1) + 4); 
                }
            }
            if(update_rooms_positon){
                save_position_all_rooms(update_rooms_positon);
            }
        <!--ELSE-->
            jQuery('#show_room_map').droppable({
                drop : handleCardDrop
            });
        <!--/IF:grip-->
        
        jQuery('#show_room_map').resizable({
            contaiment : 'document',
            minWidth : '975',
            maxWidth : '976',
			minHeight : '518',
            stop : save_height_room_map
        });
    }
    function get_offset(event , ui){
        jQuery(this).children().children().children().children('span').hide();
        jQuery(this).addClass('box_shadow');
        last_left = jQuery(this).offset().left - jQuery('#show_room_map').offset().left;
        last_top = jQuery(this).offset().top - jQuery('#show_room_map').offset().top;
    }
    function handleCardDrops(event , ui){
        if(!jQuery(this).children().length){
            ui.draggable.draggable('option' , 'revert' , false);
            leftsss = jQuery(this).offset().left;
            topsss = jQuery(this).offset().top;
            show_room_map_left = jQuery('#show_room_map').offset().left;
            show_room_map_top = jQuery('#show_room_map').offset().top;
            id = ui.draggable.attr('id');
            jQuery('#' + id).css({
                'left' : (leftsss - show_room_map_left + 5 ),
                'top' : ( topsss - show_room_map_top + 4 ) 
            });
        }
    }
    function stop_draggable( event , ui){
        show_room_map_left = jQuery('#show_room_map').offset().left;
        show_room_map_top = jQuery('#show_room_map').offset().top;
        current_element_left = jQuery(this).offset().left;
        current_element_top = jQuery(this).offset().top;
        current_element_width = jQuery(this).width();
        current_element_height = jQuery(this).height();
        show_room_map_width = jQuery('#show_room_map').width();
        show_room_map_height = jQuery('#show_room_map').height();
        right = (current_element_width+current_element_left) - (show_room_map_left+show_room_map_width);
        bottom = (current_element_height+current_element_top) - (show_room_map_top+show_room_map_height);
        jQuery(this).removeClass('box_shadow');
        if(current_element_left < show_room_map_left || current_element_top < show_room_map_top || right > 0 || bottom > 0 ){
            if(jQuery(this).parents('#show_room_map').length){
                jQuery(this).animate({left : last_left , top : last_top} , 500);
                return;
            }
        }else{
            
        }
    }
    function handleCardDrop( event , ui ){
        current_element_left = jQuery('#' + ui.draggable.attr('id')).offset().left;
        current_element_width = jQuery('#' + ui.draggable.attr('id')).width();
        current_element_height = jQuery('#' + ui.draggable.attr('id')).height();
        current_element_top = jQuery('#' + ui.draggable.attr('id')).offset().top;
        show_room_map_left = jQuery('#show_room_map').offset().left;
        show_room_map_top = jQuery('#show_room_map').offset().top;
        show_room_map_width = jQuery('#show_room_map').width();
        show_room_map_height = jQuery('#show_room_map').height();
        right = (current_element_width+current_element_left) - (show_room_map_left+show_room_map_width);
        bottom = (current_element_height+current_element_top) - (show_room_map_top+show_room_map_height);
        if(current_element_left >= show_room_map_left && current_element_top >= show_room_map_top && right <= 0 && bottom <= 0 ){
            ui.draggable.draggable( 'option', 'revert', false );
            if(!jQuery('#show_room_map').children('li#' + ui.draggable.attr('id')).length){
                drag_count++;
                id = jQuery('.room_list_active').attr('id');
                jQuery('#ul_' + id + ' li:nth-child('+ ( (i+1)*9 + 1) +')').show();
                if(jQuery('#ul_' + id + ' li').length <= ((i+1)*9+1) ){
                    jQuery('.arrow_right div').hide();
                }
            }
            jQuery('#' + ui.draggable.attr('id')).appendTo('#show_room_map');
            jQuery('#' + ui.draggable.attr('id')).removeClass();
            jQuery('#' + ui.draggable.attr('id')).removeAttr('style');
            jQuery('#' + ui.draggable.attr('id')).addClass('show_room_map_li');
            jQuery('#' + ui.draggable.attr('id')).addClass('ul_room_list');
            <!--IF:con(ALIGN_TO_GRID == 1)-->
                leftsss = jQuery(this).offset().left;
                topsss = jQuery(this).offset().top;
                show_room_map_left = jQuery('#show_room_map').offset().left;
                show_room_map_top = jQuery('#show_room_map').offset().top;
                id = ui.draggable.attr('id');
                jQuery('#' + id).css({
                    'left' : (leftsss - show_room_map_left + 5 ),
                    'top' : ( topsss - show_room_map_top + 4 ) 
                });
                save_position( ui.draggable.attr('id') , (leftsss - show_room_map_left + 5) , ( topsss - show_room_map_top + 4 ) ); 
            <!--ELSE-->
                jQuery('#' + ui.draggable.attr('id')).css({
                    'left' : current_element_left - show_room_map_left,
                    'top' : current_element_top - show_room_map_top
                });
                save_position( ui.draggable.attr('id') , current_element_left - show_room_map_left , current_element_top - show_room_map_top ); 
            <!--/IF:con-->
            jQuery('#' + ui.draggable.attr('id')).hover(function(){
                id = ui.draggable.attr('id');
                if(jQuery(this).parents('#show_room_map').length){
                    jQuery(this).children().children('img.close_drag').show();
                    jQuery(this).css({
                        'border' : '1px dotted #C2BEF6'
                    });  
                }
                jQuery(this).children().children('img.close_drag').click(function(){
                    close_drag(id);
                });
            },function(){
                jQuery(this).css({
                        'border' : '1px solid #FFF'
                    });
                jQuery('span.close_drag').hide();
            });
            
        }
    }
});
</script>
