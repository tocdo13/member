//ham ready tu layout chuyen qua
function init_monthly_room_report()
{
    width = (width_window-90-num_days)/num_days;
	width = Math.floor(width);
	jQuery('#slideMenu').css('margin-left','0px');
	jQuery('.revenue_tr').css('width',(width+1)*num_days);
	jQuery('#menu_bound').css('width',(width+1)*num_days);
	jQuery('.revenue_td').css('width',width);
	jQuery('.revenue_td_blank,.td_blank_past').css({'width':width});
	var change = false;	
	for(var key in items_js)
    {
		for(var k in items_js[key]['days'])
        {
			if(items_js[key]['days'][k]['reservation_room_id'] != undefined)
            {
				var string = items_js[key]['days'][k]['room_id']+'_'+items_js[key]['days'][k]['start_time']+'_'+items_js[key]['days'][k]['end_time']+'_'+items_js[key]['days'][k]['reservation_room_id'];
				if(items_js[key]['days'][k]['nights'] == 1)
                {
					nights = to_numeric(items_js[key]['days'][k]['nights']);
					jQuery('#reservation_'+string).css('width',nights*width);
					if(items_js[key]['days'][k]['start_time']<=time_from)
                    {
						jQuery('#reservation_'+string).css('left',90);
					}
                    else
                    {
						jQuery('#reservation_'+string).css('left',(width+1)*Math.floor((to_numeric(items_js[key]['days'][k]['start_time']) - time_from)/86400)+90);
					}
					jQuery('#customer_name_'+string).css('width',(width-5)*nights);
				}
                else if(items_js[key]['days'][k]['nights'] > 1)
                {
					nights = to_numeric(items_js[key]['days'][k]['nights']);
					if(items_js[key]['days'][k]['start_time']<=time_from)
                    {
						jQuery('#reservation_'+string).css('left',90);
					}
                    else
                    {
						jQuery('#reservation_'+string).css('left',(width+1)*Math.floor((to_numeric(items_js[key]['days'][k]['start_time']) - time_from)/86400)+90);
					}
					jQuery('#reservation_'+string).css('width',(width+1)*nights-1);
					jQuery('#customer_name_'+string).css('width',(width-5)*nights);
				}
                if(items_js[key]['days'][k]['reservation_status'] != "REPAIR")
				   jQuery('#reservation_'+string).css({'float':'left','height':'30px','border-right':'1px solid #BEBEBE','position':'absolute','z-index':1000});
                else
                    jQuery('#reservation_'+string).css({'float':'left','height':'30px','border-right':'1px solid #BEBEBE','position':'absolute','z-index':1000,'opacity':0.6});
			}
		}
	}
	jQuery('#revenue').css('display','block');
	
	jQuery('.revenue_td_blank').bind('contextmenu' , function(e)
    {
		mouseX = e.pageX; 
		mouseY = e.pageY;
		jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer','pointer-events':'auto','color':'#000000'});	
		jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer','pointer-events':'auto','color':'#000000'});
        
        var bol = 0;
        for(var key in flag)
        {
            if(flag[key] == 1)
            {
                bol = 1;
                break;
            }
        }
        if(bol > 0)
        {
            jQuery('#repair').css({'display':''});
        }
        else
        {
            jQuery('#repair').css({'display':'none'});
        }
        
        jQuery('#un_repair').css({'display':'none'});
        jQuery('#assign').css({'display':'none'});
        jQuery('#check_in').css({'display':'none'});
        jQuery('#un_assign').css({'display':'none'});
		jQuery('#edit td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
		jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
		jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'}); 
		jQuery('#view td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'}); 
		jQuery('#change_room td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'}); 
        jQuery('#extra_service td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
		//alert(222);
        jQuery('#myMenu').css({'left' : mouseX , 'top' : mouseY}).show();
        return false; 
    });
    jQuery('#myMenu tr.li_menu').click(function()
    {
    	var obj = this.id;
        //alert(obj);
        jQuery('#myMenu').hide();
        booked_rooms_selected(obj);
    });
   jQuery('.revenue_tr').mousedown(function()
   {
	   jQuery('#myMenu').hide();  
	});
	var width_obj = 0; 
    var left_obj=0; 
    var right_limit=0; 
    var left_limit=0;
	for(var key in items_js)
    {
		for(var k in items_js[key]['days'])
        {
			if(items_js[key]['days'][k]['reservation_room_id'] != undefined)
            {
                reservation_status = items_js[key]['days'][k]['reservation_status'];
				var string = items_js[key]['days'][k]['room_id']+'_'+items_js[key]['days'][k]['start_time']+'_'+items_js[key]['days'][k]['end_time']+'_'+items_js[key]['days'][k]['reservation_room_id'];
				top_limit = to_numeric(jQuery('#menu_bound').position().top)+to_numeric(jQuery('#menu_bound').height());
				bottom_limit=to_numeric(jQuery('.booking-toolbar').position().top);
				if(to_numeric(items_js[key]['days'][k]['start_time']) <= to_day && items_js[key]['days'][k]['reservation_status'] != 'REPAIR' && items_js[key]['days'][k]['reservation_status'] != 'HOUSEUSE' && items_js[key]['days'][k]['reservation_status'] != 'BOOKED' && items_js[key]['days'][k]['reservation_status'] != 'LT' && to_numeric(items_js[key]['days'][k]['end_time'])>to_day)
                {
						jQuery('#reservation_'+string).hover(function()
                        {
						width_obj = to_numeric(jQuery(this).width());
						left_obj =  to_numeric(jQuery(this).position().left);
						right_limit = left_obj;//+ width_obj;
						bottom_limit = bottom_limit+to_numeric(jQuery(this).height());
						jQuery(this).draggable(
                        {
							start: function()
                            {
								jQuery(this).css('z-index',1500);
								position_top = to_numeric(jQuery(this).position().top);
								position_left = to_numeric(jQuery(this).position().left);
								position_id = jQuery(this).attr('id');	
							}
							,stop: function()
                            {
								var action = false;
								var id = (this.id).substr((this.id).lastIndexOf("_")+1,(this.id).length-1);
								var start_time = (this.lang).substr(0,(this.lang).lastIndexOf("_"));
								var nights = (this.lang).substr((this.lang).lastIndexOf("_")+1,(this.lang).length);
								var left = this.offsetLeft;
								var top = this.offsetTop;
                                var reservation_status = jQuery('#'+this.id+'_in').val();
								jQuery('.td_blank_past').each(function()
                                {
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);
									if(left==obj_left && top==obj_top)
                                    {
										obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
										if(change == false)
                                        {
											jQuery('#mask').css('z-index',2400);
											jQuery('#mask').show();
											save_position(id,room_id,day,nights,action,reservation_status);
										}
                                        else
                                        {
											return false;	
										}
									}
								});	
								jQuery('.revenue_td_blank').each(function()
                                {
									obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);
									if(left==obj_left && top==obj_top)
                                    {
										obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
										if(change == false)
                                        {
											jQuery('#mask').css('z-index',2400);
											jQuery('#mask').show();
											save_position(id,room_id,day,nights,action,reservation_status);
										}
                                        else
                                        {
											return false;	
										}
									}
								});
							},
							containment: [left_obj,top_limit,right_limit,bottom_limit]
							,revert: function()
                            {
							var id = jQuery(this).attr('id');
							var left = to_numeric(this.offset().left);
							var top = to_numeric(this.offset().top);
							var count = Math.floor(left/width);
							var width = to_numeric(jQuery(this).width()); 
                            var kt=0;
							top_limit = to_numeric(jQuery('#menu_bound').position().top)+to_numeric(jQuery('#menu_bound').height());
							lef = 0; tp = 0;
							jQuery('.td_blank_past').each(function()
                            {
									obj_top = to_numeric(this.offsetTop); 
                                    obj_left = to_numeric(this.offsetLeft);
									if((obj_left+width) > left && left>=obj_left && lef<obj_left)
                                    {
										lef = obj_left;	
										obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
									}
									if((obj_top+30)>top && obj_top<=top)
                                    {
										tp = obj_top;	
										obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
									}
								});
							jQuery('.revenue_td_blank').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
								obj_left = to_numeric(this.offsetLeft);
								obj_id = this.lang; day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
								room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
								if(day == to_day)
                                {
									// left_limit = obj_left;		
								}
								if((obj_left+width) > left && left>=obj_left && lef<obj_left)
                                {
									lef = obj_left;	
								}
								if((obj_top+30)>top && obj_top<=top)
                                {
									tp = obj_top;	
								}
								
							});
							jQuery('.reservation_BOOKED').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
                            jQuery('.reservation_TENTATIVE').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
                            jQuery('.reservation_REPAIR').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
                            jQuery('.reservation_LT').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
							jQuery('.reservation_CHECKIN').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
							jQuery('.reservation_CHECKOUT').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
                                obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
							if(to_numeric(items_js[key]['days'][k]['start_time'])==to_day)
                            {
								lef = left_obj;
								return true;
							}
							if(left_limit <= lef && tp >= top_limit && kt == 0)
                            {
								jQuery(this).css('left',lef);
								jQuery(this).css('top',tp);
								change = false;
								return false;
							}
                            else
                            { 
								change = true;
								return true;	
							}
						}
						});							
					});	
				}
                else if((to_numeric(items_js[key]['days'][k]['start_time'])>to_day || items_js[key]['days'][k]['reservation_status'] == 'LT' || items_js[key]['days'][k]['reservation_status'] == 'BOOKED') && items_js[key]['days'][k]['reservation_status'] != 'REPAIR'&& items_js[key]['days'][k]['reservation_status'] != 'HOUSEUSE')
                {
					if(items_js[key]['days'][k]['status'] == 'BOOKED')
                    {
					jQuery('#reservation_'+string).draggable(
                    {
						start: function() 
                        {
							jQuery(this).css('z-index',1500);
							position_top = to_numeric(jQuery(this).position().top);
							position_left = to_numeric(jQuery(this).position().left);
							position_id = jQuery(this).attr('id');	
                            start_left = to_numeric(this.offsetLeft);
							start_top = to_numeric(this.offsetTop); 
						},
                        //drag: function()
//                            {
//                                var left = this.offsetLeft;
//								var top = this.offsetTop;
//                                jQuery('#at_ts').val(left + '_' + top);
//                            },
						stop: function() 
                        {
							var action = true;
							var id = (this.id).substr((this.id).lastIndexOf("_")+1,(this.id).length-1);
							var start_time = (this.lang).substr(0,(this.lang).lastIndexOf("_"));
							var nights = (this.lang).substr((this.lang).lastIndexOf("_")+1,(this.lang).length);
							var left = this.offsetLeft;
							var top = this.offsetTop;
                            var div_width = to_numeric(jQuery(this).width())/nights; 
                            var div_height = to_numeric(jQuery(this).height()); 
                            var div_id = '\''+this.id+'\'';
                            var reservation_status = jQuery('#'+this.id+'_in').val();
                            var from_room_name = jQuery('#'+this.id+'_room_name').val();  
							//if(Math.abs(left - start_left) > 0 || Math.abs(top - start_top) > 0)
                            {
                                jQuery('.revenue_td_blank').each(function()
                                {
									obj_top = to_numeric(this.offsetTop); 
                                    obj_left = to_numeric(this.offsetLeft);
									if(left==obj_left && top==obj_top)
                                    {
										obj_id = this.lang; 
										day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
										room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
                                        obj_title = this.title;
                                        room_name =  obj_title.substring(5,9);
										if(change == false)
                                        {
											jQuery('#mask').css('z-index',2400);
											jQuery('#mask').show();
											save_position(id,room_id,day,nights,action,reservation_status,div_id,from_room_name,room_name,start_time);
										}
                                        else
                                        {
											return false;	
										}
									}
								});
                            }
						},
						revert: function()
                        {
							var id = jQuery(this).attr('id');
							var left = to_numeric(this.offset().left);
							var top = to_numeric(this.offset().top);
							var width = to_numeric(jQuery(this).width());
                            var height = to_numeric(jQuery(this).height());
                            var nights = (this.attr('lang')).substr((this.attr('lang')).lastIndexOf("_")+1,(this.attr('lang')).length);
                            var count = Math.floor(left/width); 
                            var kt=0;
							top_limit = to_numeric(jQuery('#menu_bound').position().top)+to_numeric(jQuery('#menu_bound').height());
							lef = 0; 
                            tp = 0;
							jQuery('.revenue_td_blank').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
								obj_left = to_numeric(this.offsetLeft);
								obj_id = this.lang; 
                                day = obj_id.substr(obj_id.lastIndexOf("_")+1,obj_id.length-1);
								room_id = obj_id.substr(0,obj_id.lastIndexOf("_"));
								if(day == to_day)
                                {
									left_limit = obj_left;		
								}
								//if((obj_left + width) > left && left >= obj_left && lef < obj_left)
                                if(Math.abs(obj_left - left) <= 2*width/nights/3 && lef < obj_left)
                                {
									lef = obj_left;	
								}
								//if((obj_top+height) > top && obj_top <= top)
                                if(Math.abs(obj_top - top) <= 2*height/3)
                                {
									tp = obj_top;	
								}
								
							});
							jQuery('.reservation_BOOKED').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
                                obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef == obj_left && tp == obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp == obj_top && (((obj_left + obj_width) > left && obj_left < left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
                            jQuery('.reservation_TENTATIVE').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
                                obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef == obj_left && tp == obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp == obj_top && (((obj_left + obj_width) > left && obj_left < left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
                            jQuery('.reservation_REPAIR').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
                                obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef == obj_left && tp == obj_top)
                                {
									kt = 1;	
								}
                                else 
                                if(tp == obj_top && (((obj_left + obj_width) > left && obj_left < left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
							jQuery('.reservation_CHECKIN').each(function()
                            {
								obj_top = to_numeric(this.offsetTop); 
                                obj_left = to_numeric(this.offsetLeft);	
								var obj_width = to_numeric(jQuery(this).width());
								if(lef==obj_left && tp==obj_top)
                                {
									kt = 1;	
								}
                                else if(tp==obj_top && (((obj_left+obj_width)>left && obj_left<left)||((obj_left+obj_width)>(left+width)&&obj_left<(left+width) ) || ((obj_left>left) && obj_left<left+width)))
                                {
									kt = 1;		
								}
							});
							if(left_limit <= lef && tp >= top_limit && kt==0)
                            {
								jQuery(this).css('left',lef);
								jQuery(this).css('top',tp);
								change = false;
								return false;
							}
                            else
                            { 
								change = true;
								return true;	
							}
							
						},
						containment: [to_numeric(jQuery('#menu_bound').position().left),top_limit,to_numeric(jQuery('#menu_bound').width()),bottom_limit]
					});	
				}
			}
		}
	}
  }
}
function check_from_date()
{
    var from_date = $('from_date').value.split("/");
    from_date = from_date[1]+"/"+from_date[0]+"/"+from_date[2]; 
    var from_time = Date.parse(from_date.toString());
    //Cong 1 tuan le (ms nen * 1000)
    var to_time = to_numeric(from_time) + 2592000000;
    var to_date = new Date(to_time);
    to_date = to_date.getDate()+"/"+(to_date.getMonth()+1)+"/"+to_date.getFullYear();
    $('to_date').value = to_date;
}
function check_to_date()
{
	var from_date = $('from_date').value.split("/");
	var to_date = $('to_date').value.split("/");
	if((to_date[1] < from_date[1] && to_date[2] <= from_date[2]) || ( to_date[2] < from_date[2]))
    {
		$('from_date').value = $('to_date').value;
	}
    else
    {
		if((to_date[0] < from_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2]))
        {
			$('from_date').value = $('to_date').value;
		}
	}
}
function change_status(act,rr_id)
{
	jQuery.ajax({
			url:"form.php?block_id="+block_id,
			type:"POST",
			data:{status:act,id:rr_id},
			success:function(html){
				HideDialog('dialog');
				window.open(location.reload(true));
			}
	});
}
function select_room(room_id,day)
{
    if(event.shiftKey)
    {
        if(orgDate != -1 && orgRoom_id == room_id)
        {
            deSelectRow(orgRoom_id);
            var minD,maxD;
            if(orgDate < day)
            {
                minD = orgDate;maxD = day;
            }
            else
            {
                minD = day;maxD = orgDate;
            }
            for (var i = to_numeric(minD); i <= maxD; i += 86400)
            {
                if(flag[room_id + '_'+i] == 0)
                {
                    bgr_room(room_id,i,false);
                }
            }
            if(flag[room_id + '_'+day] == 0)
            {
                bgr_room(room_id,day,false);
            }
        }
    }
    else if(event.ctrlKey)
    {
        bgr_room(room_id,day,false);
    }
    else{
        var bol = 0;
        if(flag[room_id + '_'+day] == 0)
        {
            bol = 1;
        }
        deSelectAll();
        if(bol)
        {
            bgr_room(room_id,day,false);
            orgRoom_id = room_id;
            orgDate = day;
        }
    }
    //console.log(orgDate);
}
function reservation_form(r_id, rr_id)
{
	location.href = '?page=reservation&cmd=edit&id='+r_id+'r_r_id='+rr_id;	
}
function HideDialog(obj)
{
  jQuery("#"+obj).fadeOut(300);
  jQuery('#mask').hide();
  //location.reload(true);
} 
function show_menu_repair()
{
    jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer','pointer-events':'auto','color':'#000000'});	
	jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer','pointer-events':'auto','color':'#000000'});
    jQuery('#repair').css({'display':'none'});
    
    var bol = 0;
    for(var key in flag)
    {
        if(flag[key] == 1)
        {
            bol = 1;
            break;
        }
    }
    if(bol > 0)
        jQuery('#un_repair').css({'display':''});
    else
        jQuery('#un_repair').css({'display':'none'});
    jQuery('#assign').css({'display':'none'});
    jQuery('#un_assign').css({'display':'none'});
    jQuery('#add td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
	jQuery('#edit td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
	jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
	jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'}); 
	jQuery('#view td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'}); 
	jQuery('#change_room td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'}); 
    jQuery('#extra_service td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
}
// ham tuy chinh show/hide contextmenu cua tung dat phong
function check_invisible()
{
	for(var j in reser_act)
    {
			if(reser_act[j]=='BOOKED')
            {
				jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer','pointer-events':'auto','color':'#000000'});	
				jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer','pointer-events':'auto','color':'#000000'});				
				jQuery('#add td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#repair').css({'display':'none'});
                jQuery('#un_repair').css({'display':'none'});
                jQuery('#assign').css({'display':'none'});
                jQuery('#un_assign').css({'display':''});
                jQuery('#check_in').css({'display':''});
			}
            else
            if(reser_act[j]=='LT')
            {
				jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer','pointer-events':'auto','color':'#000000'});	
				jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer','pointer-events':'auto','color':'#000000'});				
				jQuery('#add td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#repair').css({'display':'none'});
                jQuery('#un_repair').css({'display':'none'});
                jQuery('#un_assign').css({'display':'none'});
                jQuery('#assign').css({'display':''});
                jQuery('#check_in').css({'display':'none'});
			}
            else 
            if(reser_act[j]=='CHECKIN')
            {
				jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer','pointer-events':'auto','color':'#000000'});	
				jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer','pointer-events':'auto','color':'#000000'});
				jQuery('#repair').css({'display':'none'});
                jQuery('#un_repair').css({'display':'none'});
                jQuery('#add td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#assign').css({'display':'none'});
                jQuery('#un_assign').css({'display':'none'});
                jQuery('#check_in').css({'display':'none'});
			}
            else 
            if(reser_act[j]=='CHECKOUT')
            {
				jQuery('.td_img').css({'background':'#E7F2F8','cursor':'pointer','pointer-events':'auto','color':'#000000'});	
				jQuery('.td_title').css({'background':'#FFFFFF','cursor':'pointer','pointer-events':'auto','color':'#000000'});
                jQuery('#repair').css({'display':'none'});
                jQuery('#un_repair').css({'display':'none'});
				jQuery('#add td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#cancel td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
				jQuery('#checkin td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
                jQuery('#extra_service td').css({'background':'#D6D6D6','cursor':'text','pointer-events':'none','color':'#6E6E6E'});
                jQuery('#assign').css({'display':'none'});
                jQuery('#un_assign').css({'display':'none'});
                jQuery('#check_in').css({'display':'none'});
			}
		}
}

function under_repair(room_id,x, y)
{
    //console.log(room_id+","+x+","+y);
    for(var day=time_from; day<=time_to; day+=86400)
    {  
        if(jQuery('#room_'+room_id+'_'+day) && jQuery('#room_'+room_id+'_'+day).position())
        {
            var org_x = jQuery('#room_'+room_id+'_'+day).position().left;
            var org_y = jQuery('#room_'+room_id+'_'+day).position().top;
            var width_e = jQuery('#room_'+room_id+'_'+day).width();
            var height_e = jQuery('#room_'+room_id+'_'+day).height();
            if((x >= org_x && x <= org_x+width_e)&&(y >= org_y && y <= org_y+height_e))
            {
                //console.log(jQuery('#room_'+room_id+'_'+day).position());
                select_room(room_id,day);
            }
        }
   }
}

//b? ch?n t?t c? các ô
function deSelectAll()
{
    for(var room_id in rooms)
    {
        //console.log(room_id);
        for(var day=time_today; day<=time_to; day+=86400)
        {   
            //console.log(day);
            jQuery('#room_'+room_id+'_'+day).css('background','#FFFFFF');	
			flag[room_id+'_'+day] =0;
			if(rooms[room_id] != undefined)
            {
				delete rooms[room_id];
			}
        }
    }	
}

//b? ch?n 1 dòng
function deSelectRow(room_id)
{
    for(var day=time_today; day<=time_to; day+=86400)
    {   
        //console.log(day);
        jQuery('#room_'+room_id+'_'+day).css('background','#FFFFFF');	
        flag[room_id+'_'+day] =0;
        if(rooms[room_id] != undefined)
        {
            delete rooms[room_id];
        }
   }	
}
function bgr_room(room_id,day,kt)
{
	if(flag[room_id+'_'+day] == 1 && kt==false)
    {
		jQuery('#room_'+room_id+'_'+day).css('background','#FFFFFF');
		flag[room_id+'_'+day] =0;
		if(rooms[room_id] != undefined)
        {
            //n?u không còn ô nào c?a phòng dc ch?n thì xóa phòng kh?i m?ng
            var check_flag = false;
            for(var t = time_from; t <= time_to; t += 86400)
            {
                if(flag[room_id + '_'+t] == 1)
                {
                    check_flag = true;
                    break;
                }
            }
            if(!check_flag)
                delete rooms[room_id];
		}
	}
    else if(flag[room_id+'_'+day] ==0)
    {
		jQuery('#room_'+room_id+'_'+day).css('background','#A6BFE5');	
		flag[room_id+'_'+day] =1;
		rooms[room_id] = room_id;
	}	
}
function get_date(time)
{
    var a = new Date(time*1000);
    var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = date+'/'+month+'/'+year;
    return time;
}
//rr_id : 
// room_id: room_id chuyen toi
// start: ngay bat dau cua chuyen toi
// nights: so dem
// action:
function save(rr_id,room_id, start, nights,action,div_id,reservation_status,original_start_time)
{
    for_space = false;
    for_sign = 0;
    if(jQuery('#for_assign').is(':checked'))
    {
        for_sign = 1;
    }
    else
    {
        if(jQuery('#for_space_only').is(':checked'))
        {
            for_space = true;
        }
    }
    if(for_space)
    {
        HideDialog('dialog');
        return false;  
    }
	var reason_change = jQuery('#reason_change').val();
    var use_old_price = jQuery('#use_old_price').is(":checked")?1:0;
    
	if(reason_change != '')
    {
        /** START Gán phòng trên THSD phòng, doi phong , chon gan phong Sau khi nh?n OK-> load l?i thì phòng du?c gán tr? v? v? trí cu**/
        if(!for_sign && reservation_status=='LT'){
            returnOldPosition();
    		HideDialog('dialog');
    		return false;	
        }
        /** END Gán phòng trên THSD phòng, doi phong , chon gan phong Sau khi nh?n OK-> load l?i thì phòng du?c gán tr? v? v? trí cu**/
		
        jQuery('#loading-layer').fadeIn(100);
        jQuery.ajax({
						url:"form.php?block_id="+block_id,
						type:"POST",
                        /** START Gán phòng trên THSD phòng, doi phong , chon gan phong Sau khi nh?n OK-> load l?i thì phòng du?c gán tr? v? v? trí cu**/
						data:{rr_id:rr_id,for_assign:for_sign,room_id:room_id,start_time:start,nights:nights,cmd:'change_room',act:action,change_room_reason:reason_change,use_old_price:use_old_price,reservation_status:reservation_status},
						/** START Gán phòng trên THSD phòng, doi phong , chon gan phong Sau khi nh?n OK-> load l?i thì phòng du?c gán tr? v? v? trí cu**/
                        success:function(html)
                        {
                            //alert(html);                                
                            if(html != 'success')
                            {
                                returnOldPosition();
                                alert(html);
                            }
                            else 
                            {
                                var end_time = start + 86400 * nights;
                                var original_end_time = original_start_time + 86400 * nights;
                                for (var i = start; i < end_time; i += 86400)
                                {
                                    var inner_html = jQuery('#date_'+i).html();
                                    var num_used_room = to_numeric(inner_html.substring(0,inner_html.indexOf('/'))) + 1;
                                    var num_empty_room = to_numeric(inner_html.substring(inner_html.indexOf('/') + 1, inner_html.length)) - 1;
                                    jQuery('#date_'+i).html(num_used_room+'/'+num_empty_room);
                                }
                                for (var i = original_start_time; i < original_end_time; i += 86400)
                                {
                                    var inner_html = jQuery('#date_'+i).html();
                                    var num_used_room = to_numeric(inner_html.substring(0,inner_html.indexOf('/'))) - 1;
                                    var num_empty_room = to_numeric(inner_html.substring(inner_html.indexOf('/') + 1, inner_html.length)) + 1;
                                    jQuery('#date_'+i).html(num_used_room+'/'+num_empty_room);
                                }
                                jQuery('#'+div_id).attr('lang',start+'_'+nights);
                                if(reservation_status == 'LT' && for_sign == 1)   
                                {
                                    jQuery('#'+div_id).removeClass('reservation_LT');
                                    jQuery('#'+div_id+' label').removeClass('reservation_LT');
                                    jQuery('#'+div_id).addClass('reservation_TENTATIVE');
                                    jQuery('#'+div_id+' label').addClass('reservation_TENTATIVE');
                                }
                            }
							jQuery('#loading-layer').fadeOut(0);
							HideDialog('dialog');
							//window.open(location.reload(true));
                            location.reload(true);
						}
			});
	}
    else
    {
		alert('Reason_change_empty !\nChange_unsuccessful.');	
		returnOldPosition();
		HideDialog('dialog');
		return false;
	}
}
function returnOldPosition()
{
	if(position_id != '')
    {
		jQuery('#'+position_id).css('top',position_top);
		jQuery('#'+position_id).css('left',position_left);	
		setBeginPosition();
	}
}
function setBeginPosition()
{
	var position_id = '';
	var position_top = 0;
	var position_left = 0;
}
function submitForm()
{
    var url = '?page=monthly_room_report&manager=0';
	url += '&from_date='+($('from_date').value);
	url += '&to_date='+($('to_date').value);
    //alert(url);
	window.open(url,'_self');
}
function booked_rooms_selected(act)
{
	 var rooms_arr = '';
	 var rooms_prices = '';                   
	 var y=0;
	 if(act == 'add' || act == 'repair' || act == 'un_repair' )
     {
		for(var j in rooms_array)
        {
			if(rooms[rooms_array[j]['id']] != undefined)
            {
				if(y==0)
                {
					rooms_arr = rooms_array[j]['id'];
				}
                else
                {
					rooms_arr += '|'+rooms_array[j]['id'];
				} 
				var start_date = 0;
				var end_date = 0;
				var count= 0;
				for(var t = time_from; t <= time_to; t += 86400)
                {
					if(flag[rooms_array[j]['id']+'_'+t] == 1)
                    {
						if((start_date==0  && end_date==0) || (count==0 && start_date!=0  && end_date!=0))
                        {
							if(count==0 && start_date!=0  && end_date!=0)
                            {
								rooms_arr += '|' + rooms_array[j]['id'];
							}
							start_date = t;
							end_date = t + 86400;	
							count = 1;
						}
                        else if(end_date!=0 && t==end_date)
                        {
							end_date = t+86400;	
						}
                        else if(end_date!=0 && end_date < t && t != end_date)
                        {
							end_date = 0; start_date = 0; ;	
						}
					}
                    else
                    {
						if(end_date == t)
                        {
						    if(act != 'add')
                                end_date -= 86400;
							rooms_arr += ',' + get_date(start_date) + ',' + get_date(end_date); 
							count = 0;
						}	
					}
				}
				y = y+1;	
			}
		}
        if(act == 'add')
        {
            var y=1;
			for(var s in room_types_js)
            {
				rooms_prices += '&room_prices['+room_types_js[s]['id']+']='+room_types_js[s]['price'];	y=y+1;	
			}	
			var d = new Date();
			var h=d.getHours();
			var m = d.getMinutes();
            m = m.length==1?'0'+m:m;
			window.open('?page=reservation&cmd=add&time_in='+default_checkin_time+'&time_out='+default_checkout_time+'&rooms='+rooms_arr+rooms_prices+'&from_room_using_status=true');
        }
		else 
            if(act == 'repair')
                window.open('?page=monthly_room_report&cmd=repair&rooms='+rooms_arr);
            else
                window.open('?page=monthly_room_report&cmd=un_repair&rooms='+rooms_arr);
	 }
     else if(act == 'cancel' || act == 'check_in' || act=='view' || act=='edit'|| act=='change_room' || act=='extra_service' || act=='assign' || act=='un_assign')
     {
		reservation_arr = new Array();
		for(var key in items_js)
        {
			for(var k in items_js[key]['days'])
            {
				 if(reser_act[items_js[key]['days'][k]['reservation_room_id']] != undefined)
                 {
					 reservation_arr = items_js[key]['days'][k];
				 }
			}
		}
        var div_id = 'reservation_'+reservation_arr['room_id']+'_'+reservation_arr['start_time']+'_' + reservation_arr['end_time'] + '_' + reservation_arr['reservation_room_id'];
		if(((act == 'checkin' && reservation_arr['start_time']==to_day)) && reservation_arr['reservation_status'] == 'BOOKED')
        {
	        //var text= '<div id="dialog" class="web-dialog"><div class="info"><span class="title_bound">[[.reservation_detail.]]</span><img width="15" height="15" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog(\'dialog\');" style="float:right;"/></div>';
//			text += '<table id="detail"><tr><td>[[.order_id.]] : </td><td>'+reservation_arr['reservation_room_id']+'</td></tr>';
//			text += '<tr><td>[[.customer_name.]]</td><td>'+reservation_arr['customer']+'</td></tr>';
//			text += '<tr><td>[[.arrival_time.]] : </td><td>'+reservation_arr['arrival_time']+'</td></tr>';
//			text += '<tr><td>[[.departure_time.]]</td><td>'+reservation_arr['departure_time']+'</td></tr>';
//			text += '<tr><td>[[.change_status_to.]]</td><td>'+act.toUpperCase()+'</td></tr>';
//			text += '<tr><td colspan=2><input name="chang_status" type="button" value="[[.update_status.]]" id="chang_status" onclick="change_status(\''+act.toUpperCase()+'\','+reservation_arr['reservation_room_id']+');"><input name="exit" type="button" value="[[.cancel.]]" id="exit" onclick="HideDialog(\'dialog\');jQuery(\'#mask\').hide();"></td></tr>';
//			text += '</table></div>';
//			jQuery('#reser_detail').html(text);
//			jQuery("#dialog").css('z-index',2500);	
//			jQuery("#dialog").fadeIn(300);
            
		 }
         else if(act=='view')
         {
				window.open('?page=reservation&room_invoice=1&hk_invoice=1&bar_invoice=1&other_invoice=1&phone_invoice=1&extra_service_invoice=1&included_deposit=1&included_related_total=1&cmd=invoice&id='+reservation_arr['reservation_room_id']);
				return false;
		 }
         else if(act=='edit')
         {
			 window.open('?page=reservation&cmd=edit&id='+reservation_arr['reservation_id']+'&r_r_id='+reservation_arr['reservation_room_id']+'&from_room_using_status=true&div_id='+div_id);
			 return false;
		 }
         else
         if (act == 'assign' || act == 'un_assign' || act == 'check_in')
         {
             if(act == 'check_in' && reservation_arr['start_time'] > to_day)
             {
                alert('can not checkin in the future');
                return false;
             }
             if(act == 'check_in' && reservation_arr['reservation_status'] == 'CHECKIN')
             {
                alert('[[.are_you_kidding_me.]]!');
                return false;
             }
             jQuery('#loading-layer').fadeIn(100);
             jQuery.ajax({
						url:"form.php?block_id="+block_id,
						type:"POST",
						data:{rr_id:reservation_arr['reservation_room_id'],room_id:reservation_arr['room_id'],cmd:'assign_room',act:act},
						success:function(html)
                        {                                
                            if(html == 'sucess')
                            {
                                //var div_id = 'reservation_'+reservation_arr['room_id']+'_'+reservation_arr['start_time']+'_' + reservation_arr['end_time'] + '_' + reservation_arr['reservation_room_id'];
                                if(act == 'assign')
                                {
                                    jQuery('#'+div_id).removeClass('reservation_LT');
                                    jQuery('#'+div_id+' label').removeClass('reservation_LT');
                                    jQuery('#'+div_id).addClass('reservation_TENTATIVE');
                                    jQuery('#'+div_id+' label').addClass('reservation_TENTATIVE');
                                    //reser_act[reservation_arr['reservation_room_id']] = 'BOOKED';
                                    //jQuery('#'+div_id).unbind('contextmenu');
                                    jQuery('#'+div_id+'_in').val('BOOKED');
                                    jQuery('#'+div_id).removeAttr('onmouseover');
                                    jQuery('#'+div_id).attr('onmouseover',
                                    "jQuery('#"+div_id+"').bind('contextmenu' , function(e)"+
                                    "{" +
                                    "        mouseX = e.pageX;" + 
                                    "        mouseY = e.pageY;" +
                            		"		reser_act={};" +
                                            "reser_act["+reservation_arr['reservation_room_id'] +"] = 'BOOKED';"+
                                            "check_invisible();" +
                            				"jQuery('#myMenu').css({'left' : mouseX , 'top' : mouseY}).show();" +
                            				"return false; " +
                        			    "});"
                                    );
                                }
                                else
                                if(act == 'un_assign')
                                {
                                    jQuery('#'+div_id).removeClass('reservation_TENTATIVE');
                                    jQuery('#'+div_id+' label').removeClass('reservation_TENTATIVE');
                                    jQuery('#'+div_id).addClass('reservation_LT');
                                    jQuery('#'+div_id+' label').addClass('reservation_LT');
                                    jQuery('#'+div_id+'_in').val('LT');
                                    //reser_act[reservation_arr['reservation_room_id']] = 'LT';
                                    //jQuery('#'+div_id).unbind('contextmenu');
                                    jQuery('#'+div_id).removeAttr('onmouseover');
                                    jQuery('#'+div_id).attr('onmouseover',
                                    "jQuery('#"+div_id+"').bind('contextmenu' , function(e)"+
                                    "{" +
                                    "        mouseX = e.pageX;" + 
                                    "        mouseY = e.pageY;" +
                            		"		reser_act={};" +
                                            "reser_act["+reservation_arr['reservation_room_id'] +"] = 'LT';"+
                                            "check_invisible();" +
                            				"jQuery('#myMenu').css({'left' : mouseX , 'top' : mouseY}).show();" +
                            				"return false; " +
                        			    "});"
                                    );
                                }
                                else
                                if(act == 'check_in')
                                {
                                    jQuery('#'+div_id).removeClass('reservation_TENTATIVE');
                                    jQuery('#'+div_id+' label').removeClass('reservation_TENTATIVE');
                                    jQuery('#'+div_id).addClass('reservation_CHECKIN');
                                    jQuery('#'+div_id+' label').addClass('reservation_CHECKIN');
                                    jQuery('#'+div_id+'_in').val('CHECKIN');
                                    //reser_act[reservation_arr['reservation_room_id']] = 'LT';
                                    //jQuery('#'+div_id).unbind('contextmenu');
                                    jQuery('#'+div_id).removeAttr('onmouseover');
                                    jQuery('#'+div_id).attr('onmouseover',
                                    "jQuery('#"+div_id+"').bind('contextmenu' , function(e)"+
                                    "{" +
                                    "        mouseX = e.pageX;" + 
                                    "        mouseY = e.pageY;" +
                            		"		reser_act={};" +
                                            "reser_act["+reservation_arr['reservation_room_id'] +"] = 'CHECKIN';"+
                                            "check_invisible();" +
                            				"jQuery('#myMenu').css({'left' : mouseX , 'top' : mouseY}).show();" +
                            				"return false; " +
                        			    "});"
                                    );
                                }
                            }
                            else 
                            {
                                alert(html);  
                            }
							jQuery('#loading-layer').fadeOut(0);
                            return false;
						}
			});
         }
         else if(act=='change_room')
         {
		 	window.open('?page=change_room&id='+reservation_arr['reservation_room_id']);
			return false;
	 	 }
         else if(act=='extra_service')
         {
		 	window.open('?page=extra_service_invoice&cmd=add&reservation_room_id='+reservation_arr['reservation_room_id']);
			return false;
	 	}
	 }
}
function checkin_room(div_id)
{
    //boc tach cac thong tin tu tham so div_id
	var info = div_id.split('_');
    var room_id = to_numeric(info[1]);
    var start_time = to_numeric(info[2]);
    var end_time = to_numeric(info[3]);
    var nights = (end_time - start_time)/86400 + 1;
    var to_end_day = to_day + end_time - start_time;
    var check_result = true;
    // mang luu cac dat phong khong gan phong
    var conflict_recode = '';
    for(var key in items_js)
    {
		for(var k in items_js[key]['days'])
        {
			if(items_js[key]['days'][k]['reservation_room_id'] != undefined)
            {
                //tim kiem cac dat phong co room_id va co time trong khoang to_day -> to_day + 86400*nights()
                if(items_js[key]['days'][k]['room_id'] == room_id && items_js[key]['days'][k]['start_time'] <= to_end_day && items_js[key]['days'][k]['start_time'] >= to_day)
                {
                    var string = items_js[key]['days'][k]['room_id']+'_'+items_js[key]['days'][k]['start_time']+'_'+items_js[key]['days'][k]['end_time']+'_'+items_js[key]['days'][k]['reservation_room_id'];
                    if(jQuery('#reservation_'+string).hasClass('reservation_LT') == true)
                    {
                        var random_room = get_random_room_id();
                        if(random_room == false)
                        {
                            check_result = false;
                            conflict_recode += items_js[key]['days'][k]['reservation_id'] + ',';
                        }
                    }
                    else
                    {
                        check_result = false;
                        conflict_recode += items_js[key]['days'][k]['reservation_id'] + ',';   
                    }
                }
			}
		}
	}
    if(check_result == true)
    {
        var to_offset = jQuery('#room_'+room_id+'_'+to_day).offset();
        var to_left = to_offset.left;
        jQuery('#'+div_id).css('left',to_left);
    }
    else
    {
        alert('[[.conflict_with_recode.]]'+': '+conflict_recode)
    }
}
//get random room_id of a level
function get_random_room_id(room_level_id, room_id, start_time, end_time)
{
    return false;
}