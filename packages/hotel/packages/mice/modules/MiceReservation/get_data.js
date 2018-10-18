function Autocomplete()
{
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item){
            document.getElementById('customer_id').value = item.data[0];
            //jQuery('.acResults').remove();
        }
    }) ;
}
function get_traveler()
{
	jQuery("#traveller_name").autocomplete({
		url:'get_traveller.php',
		onItemSelect: function(item) {
			var newStr = item.value;
			var arr = newStr.split(' - ');
			jQuery('#traveller_name').val(arr[0]+' '+arr[1]);
			jQuery('#traveller_id').val(arr[2]);
            //jQuery('.acResults').remove();
		}
	});
}
/** EXTRA JS **/
function selecttypeserviceextra(index)
{
    type_service_invoice = jQuery("#extra_type_"+index).val();
    extra_service_id = jQuery("#extra_service_id_"+index).val();
    option_service = '<option value=""> Chon DV</option>';
    for(var service_id in all_extra_service)
    {
        if(all_extra_service[service_id]['type']==type_service_invoice)
        {
            option_service += '<option value="'+all_extra_service[service_id]['id']+'">'+all_extra_service[service_id]['name']+'</option>';
        }
    }
    jQuery("#extra_service_id_"+index).html(option_service);
    
    if(extra_service_id=='' || all_extra_service[extra_service_id]['type']==type_service_invoice)
    {
        GetTotalAmount();
    }
    else
    {
        jQuery("#extra_price_"+index).val(0);
        jQuery("#extra_service_id_"+index).val('');
        jQuery("#extra_service_name_"+index).val('');
        GetTotalAmount();
    }
    
}
function selectserviceextra(index)
{
    extra_service_id = jQuery("#extra_service_id_"+index).val();
    if(extra_service_id!='' && all_extra_service[extra_service_id])
    {
        jQuery("#extra_service_name_"+index).val(all_extra_service[extra_service_id]['name']);
        jQuery("#extra_price_"+index).val(number_format(all_extra_service[extra_service_id]['price']));    
    }
    else
    {
        jQuery("#extra_price_"+index).val(0);
        jQuery("#extra_service_name_"+index).val('');
    }
    jQuery("#extra_type_"+index).val(all_extra_service[extra_service_id]['type']);
    GetTotalAmount();
}
/** END EXTRA JS **/
/** SPA JS **/
function productAutoComplete(index,indexchild)
{
   jQuery("#spa_"+index+"_child_service_product_id_"+indexchild).autocomplete({
	        url: 'get_product.php?massage=1&type=1&mice=1&spa_room_id='+jQuery("#spa_"+index+"_child_service_spa_room_id_"+indexchild).val()+'&staff_ids='+jQuery("#spa_"+index+"_child_service_staff_ids_"+indexchild).val(),
            onItemSelect: function(item)
            {
                jQuery("#spa_"+index+"_child_service_product_id_"+indexchild).val(item['data'][1]);
                getProductFromCode(index,indexchild);
                jQuery(".acResults").css('display','none');
	       }
    });
}
function p_productAutoComplete(index,indexchild)
{
   jQuery("#spa_"+index+"_child_product_product_id_"+indexchild).autocomplete({
	        url: 'get_product.php?massage=1&type=2&mice=1',
            onItemSelect: function(item){
                jQuery("#spa_"+index+"_child_product_product_id_"+indexchild).val(item['data'][1]);
                p_getProductFromCode(index,indexchild);
                jQuery(".acResults").css('display','none');
	       }
    });
}
function getProductFromCode(index,indexchild)
{
    var product_id = jQuery("#spa_"+index+"_child_service_product_id_"+indexchild).val();
    $check=false;
    for(var i in all_products)
    {
        if(all_products[i]['product_id']==product_id)
        {
            $check=true;
            jQuery("#spa_"+index+"_child_service_product_name_"+indexchild).val(all_products[i]['name']);
            jQuery("#spa_"+index+"_child_service_price_id_"+indexchild).val(all_products[i]['price_id']);
            jQuery("#spa_"+index+"_child_service_price_"+indexchild).val(number_format(all_products[i]['price']));
            break;
        }
    }
    if($check==false)
    {
        jQuery("#spa_"+index+"_child_service_product_id_"+indexchild).val('')
        jQuery("#spa_"+index+"_child_service_product_name_"+indexchild).val('');
        jQuery("#spa_"+index+"_child_service_price_id_"+indexchild).val('');
        jQuery("#spa_"+index+"_child_service_price_"+indexchild).val('');
    }
    GetTotalAmount();
}
function p_getProductFromCode(index,indexchild)
{
	var product_id = jQuery("#spa_"+index+"_child_product_product_id_"+indexchild).val();
    $check=false;
    for(var i in all_products)
    {
        if(all_products[i]['product_id']==product_id)
        {
            $check=true;
            jQuery("#spa_"+index+"_child_product_product_name_"+indexchild).val(all_products[i]['name']);
            jQuery("#spa_"+index+"_child_product_price_id_"+indexchild).val(all_products[i]['price_id']);
            jQuery("#spa_"+index+"_child_product_price_"+indexchild).val(number_format(all_products[i]['price']));
            break;
        }
    }
    if($check==false)
    {
        jQuery("#spa_"+index+"_child_product_product_id_"+indexchild).val('')
        jQuery("#spa_"+index+"_child_product_product_name_"+indexchild).val('');
        jQuery("#spa_"+index+"_child_product_price_id_"+indexchild).val('');
        jQuery("#spa_"+index+"_child_product_price_"+indexchild).val('');
    }
    GetTotalAmount();
}
function Check_Spa_Room_Availblity(index,indexchild) 
{
    var string = '?page=spa_order&cmd=select_spa_room&service_id='+jQuery("#spa_"+index+"_child_service_product_id_"+indexchild).val()+'&status=BOOKED';
	string += '&index='+index+'&indexchild='+indexchild;
    if(jQuery("#spa_"+index+"_child_service_time_in_"+indexchild).val()!='')
    {
		string += '&time_in_hour='+jQuery("#spa_"+index+"_child_service_time_in_"+indexchild).val();
	}
    else
    {
        alert('Ban chua nhap gio bat dau!')
        return false;
    }
    
    if(jQuery("#spa_"+index+"_child_service_time_out_"+indexchild).val()!='')
    {
		string += '&time_out_hour='+jQuery("#spa_"+index+"_child_service_time_out_"+indexchild).val();
	}
    else
    {
        alert('Ban chua nhap gio ket thuc!')
        return false;
    }
    
    if(jQuery("#spa_"+index+"_child_service_in_date_"+indexchild).val()!='')
    {
		string += '&in_date='+jQuery("#spa_"+index+"_child_service_in_date_"+indexchild).val();
	}
    else
    {
        alert('Ban chua chon ngay');
        return false;
    }
	window.open(string,'select_spa_room');
}
function Check_Staff_Availblity(index,indexchild)
{
    var string = 'get_spa_staff.php?service_id='+jQuery("#spa_"+index+"_child_service_product_id_"+indexchild).val()+'&status=BOOKED&spa_order_id=';
	string += '&index='+index+'&indexchild='+indexchild;
    if(jQuery("#spa_"+index+"_child_service_time_in_"+indexchild).val()!='')
    {
		string += '&time_in_hour='+jQuery("#spa_"+index+"_child_service_time_in_"+indexchild).val();
	}
    else
    {
        alert('Ban chua nhap gio bat dau!')
        return false;
    }
    
    if(jQuery("#spa_"+index+"_child_service_time_out_"+indexchild).val()!='')
    {
		string += '&time_out_hour='+jQuery("#spa_"+index+"_child_service_time_out_"+indexchild).val();
	}
    else
    {
        alert('Ban chua nhap gio ket thuc!')
        return false;
    }
    
    if(jQuery("#spa_"+index+"_child_service_in_date_"+indexchild).val()!='')
    {
		string += '&in_date='+jQuery("#spa_"+index+"_child_service_in_date_"+indexchild).val();
	}
    else
    {
        alert('Ban chua chon ngay');
        return false;
    }
    // chon nhan vien
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else
    {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var text_reponse = xmlhttp.responseText;
            var objs = jQuery.parseJSON(text_reponse);
            //console.log(objs);
            $header = '<label>Danh sach nhan vien</label>';
            $content = '';
            if(objs=='')
            {
                alert('khong co nhan vien phu hop!');
                return false;
            }
            else
            {
                $content += '<table style="margin: 0px auto;" border="1" bordercolor="#EEEEEE" cellpadding="10">';
                    $content += '<tr style="height: 35px; background: #EEEEEE;">';
                        $content += '<th><label></label></th>';
                        $content += '<th>Ten nhan vien</th>';
                        $content += '<th>Ca lam viec</th>';
                        $content += '<th>Thoi gian bat dau ca</th>';
                        $content += '<th>Thoi gian ket thuc ca</th>';
                    $content += '</tr>';
                    for(var i in objs)
                    {
                        $content += '<tr>';
                            $content += '<td><input type="checkbox" class="selectstaffspa" id="selectstaffspa_'+objs[i]['staff_id']+'" value="'+objs[i]['staff_id']+'" onclick="selectstaffspa('+index+','+indexchild+');" /></td>';
                            $content += '<td>'+objs[i]['staff_name']+'</td>';
                            $content += '<td style="text-align: center;">'+objs[i]['work_shift_name']+'</td>';
                            $content += '<td style="text-align: center;">'+objs[i]['start_hour']+'</td>';
                            $content += '<td style="text-align: center;">'+objs[i]['end_hour']+'</td>';
                        $content += '</tr>';
                    }
                $content += '</table>';
            }
            $footer = '';
            OpenLightBox($header,$content,$footer);
            staff_ids = jQuery("#spa_"+index+"_child_service_staff_ids_"+indexchild).val().split(',');
            for(var j in staff_ids)
            {
                if(jQuery("#selectstaffspa_"+staff_ids[j]).val()!=undefined)
                    document.getElementById("selectstaffspa_"+staff_ids[j]).checked=true;
            }
            return true;
        }
    }
    xmlhttp.open("GET",string,true);
    xmlhttp.send();
}
function selectstaffspa(index,indexchild)
{
    var staff_ids = '';
    jQuery(".selectstaffspa").each(function(){
        var id = this.id;
        if(document.getElementById(id).checked==true)
        {
            if(staff_ids=='')
                staff_ids = this.value;
            else
                staff_ids += ','+this.value;
        }
    });
    jQuery("#spa_"+index+"_child_service_staff_ids_"+indexchild).val(staff_ids);
    return false;
}
/** END SPA JS **/

/** PARTY JS **/
function selectpartyrooms(index,indexchild)
{
    if(jQuery("#party_"+index+"_child_room_party_room_id_"+indexchild).val()!='')
    {
        partyroomid = jQuery("#party_"+index+"_child_room_party_room_id_"+indexchild).val();
        jQuery("#party_"+index+"_child_room_address_"+indexchild).val(banquet_rooms[partyroomid]['address']);
        if(jQuery("#party_"+index+"_child_room_time_type_"+indexchild).val()=='DAY')
            jQuery("#party_"+index+"_child_room_price_"+indexchild).val(number_format(banquet_rooms[partyroomid]['price']));
        else
            jQuery("#party_"+index+"_child_room_price_"+indexchild).val(number_format(banquet_rooms[partyroomid]['price_half_day']));
    }
    else
    {
        jQuery("#party_"+index+"_child_room_address_"+indexchild).val('');
        jQuery("#party_"+index+"_child_room_price_"+indexchild).val('');
    }
    GetTotalAmount();
}
function partyproductAutoComplete(index,indexchild)
{
    product_type = jQuery("#party_"+index+"_child_product_type_"+indexchild).val();
    if(product_type==1)
        product_type = 'DRINK';
    else if(product_type==2)
        product_type = 'PRODUCT';
    else if(product_type==3)
        product_type = 'SERVICE';
    else if(product_type==4)
        product_type = 'VEGETARIAN';
    
    jQuery("#party_"+index+"_child_product_product_id_"+indexchild).autocomplete({
                url: 'get_product.php?banquet=1&mice=1&product_type='+product_type,
				selectFirst:false,
			 	onItemSelect: function(item) {
			 	   jQuery("#party_"+index+"_child_product_product_id_"+indexchild).val(item['data'][1]);
			 	   partygetProductFromCode(index,indexchild);
                   jQuery(".acResults").css('display','none');
        		}
        });
}
function partygetProductFromCode(index,indexchild)
{
    var product_id = jQuery("#party_"+index+"_child_product_product_id_"+indexchild).val();
    product_type = jQuery("#party_"+index+"_child_product_type_"+indexchild).val();
    if(product_type==1)
        product_type = 'DRINK';
    else if(product_type==2)
        product_type = 'PRODUCT';
    else if(product_type==3)
        product_type = 'SERVICE';
    else if(product_type==4)
        product_type = 'VEGETARIAN';
    $check = false;
    for(var i in all_products)
    {
        if(all_products[i]['product_id']==product_id)
        {
            $check=true;
            jQuery("#party_"+index+"_child_product_product_name_"+indexchild).val(all_products[i]['name']);
            jQuery("#party_"+index+"_child_product_unit_name_"+indexchild).val(all_products[i]['unit_name']);
            jQuery("#party_"+index+"_child_product_unit_id_"+indexchild).val(all_products[i]['unit_id']);
            jQuery("#party_"+index+"_child_product_price_"+indexchild).val(number_format(all_products[i]['price']));
            break;
        }
    }
    if($check==false)
    {
        jQuery("#party_"+index+"_child_product_product_id_"+indexchild).val('')
        jQuery("#party_"+index+"_child_product_product_name_"+indexchild).val('');
        jQuery("#party_"+index+"_child_product_unit_name_"+indexchild).val('');
        jQuery("#party_"+index+"_child_product_unit_id_"+indexchild).val('');
        jQuery("#party_"+index+"_child_product_price_"+indexchild).val('');
    }
    GetTotalAmount();
}
function Select_Promotions(index)
{
    if(all_party_promotions=='')
    {
        alert('khong co khuyen mai');
        return false;
    }
    else
    {
        $check = false;
        $header = '<label>Danh sach khuyen mai tiec</label>';
        $content = '';
        $content += '<table style="margin: 0px auto;" border="1" bordercolor="#EEEEEE" cellpadding="10">';
            $content += '<tr style="height: 35px; background: #EEEEEE;">';
                $content += '<th><label></label></th>';
                $content += '<th>Danh muc khuyen mai</th>';
                $content += '<th>Ghi chu</th>';
            $content += '</tr>';
            for(var i in all_party_promotions)
            {
                if(all_party_promotions[i]['party_type_id']==jQuery("#party_party_type_"+index).val())
                {
                    $check = true;
                    $content += '<tr>';
                        $content += '<td><input type="checkbox" class="selectpartyPromotions" id="selectpartyPromotions_'+all_party_promotions[i]['id']+'" value="'+all_party_promotions[i]['id']+'" onclick="selectpartypromotions('+index+');" /></td>';
                        $content += '<td>'+all_party_promotions[i]['name']+'</td>';
                        $content += '<td style="text-align: center;">'+all_party_promotions[i]['note']+'</td>';
                    $content += '</tr>';
                }
            }
            
        $content += '</table>';
        $footer = '';
        if($check==false)
        {
            alert('khong co khuyen mai');
            return false;
        }
        OpenLightBox($header,$content,$footer);
        party_promotions = jQuery("#party_promotions_"+index).val().split(' ');
        for(var j in party_promotions)
        {
            if(jQuery("#selectpartyPromotions_"+party_promotions[j]).val()!=undefined)
                document.getElementById("selectpartyPromotions_"+party_promotions[j]).checked=true;
        }
        return true;
    }
}
function selectpartypromotions(index)
{
    var party_promotions = '';
    jQuery(".selectpartyPromotions").each(function(){
        var id = this.id;
        if(document.getElementById(id).checked==true)
        {
            if(party_promotions=='')
                party_promotions = this.value;
            else
                party_promotions += ' '+this.value;
        }
    });
    jQuery("#party_promotions_"+index).val(party_promotions);
    return false;
}
/** END PARTY JS **/
/** BOOKIG JS **/
function selectroomlevel(index)
{
    room_level_id = jQuery("#booking_room_level_id_"+index).val();
    exchange_rate = jQuery("#booking_exchange_rate_"+index).val();
    if(room_level_id!='' && room_level[room_level_id])
    {
        jQuery("#booking_adult_"+index).val(room_level[room_level_id]['num_people']);
        jQuery("#booking_price_"+index).val(number_format(room_level[room_level_id]['price']));
        jQuery("#booking_usd_price_"+index).val(number_format(room_level[room_level_id]['price']*(1/exchange_rate)));
    }
    else
    {
        jQuery("#booking_adult_"+index).val('');
        jQuery("#booking_price_"+index).val(0);
        jQuery("#booking_usd_price_"+index).val(0);
    }
    GetTotalAmount();
}
function checkdatetimebooking(index)
{
    $timein = jQuery("#booking_time_in_"+index).val();
    $timeout = jQuery("#booking_time_out_"+index).val();
    $arrivaltime = jQuery("#booking_from_date_"+index).val();
    $departuretime = jQuery("#booking_to_date_"+index).val();
    messengercontent = '';
    if(jQuery("#booking_time_in_"+index).val()=='')
    {
        if(messengercontent=='')
            messengercontent='<span>Ban chua nhap GIO DEN</span>';
        else
            messengercontent+='<br/><span>Ban chua nhap GIO DEN</span>';
        $timein = TIME_IN;
    }
    if(jQuery("#booking_time_out_"+index).val()=='')
    {
        if(messengercontent=='')
            messengercontent='<span>Ban chua nhap GIO DI</span>';
        else
            messengercontent+='<br/><span>Ban chua nhap GIO DI</span>';
        $timeout = TIME_OUT;
    }
    if(jQuery("#booking_from_date_"+index).val()=='')
    {
        if(messengercontent=='')
            messengercontent='<span>Ban chua nhap NGAY DEN</span>';
        else
            messengercontent+='<br/><span>Ban chua nhap NGAY DEN</span>';
        $arrivaltime = ARRIVAL_TIME;
    }
    if(jQuery("#booking_to_date_"+index).val()=='')
    {
        if(messengercontent=='')
            messengercontent='<span>Ban chua nhap NGAY DI</span>';
        else
            messengercontent+='<br/><span>Ban chua nhap NGAY DI</span>';
        $departuretime = DEPARTURE_TIME;
    }
    
    if(messengercontent!='')
    {
        alert(messengercontent);
        jQuery("#booking_time_in_"+index).val($timein);
        jQuery("#booking_time_out_"+index).val($timeout);
        jQuery("#booking_from_date_"+index).val($arrivaltime);
        jQuery("#booking_to_date_"+index).val($departuretime);
        return false;
    }
    else
    {
        $countdate = count_date($arrivaltime,$departuretime);
        if($countdate<0)
        {
            alert('<h3>BOOKING</h3><br/><span><i class="fa fa-calendar-times-o fa-fw"></i> Ngay den phai nho hon hoac bang ngay di</span>');
            jQuery("#booking_to_date_"+index).val($arrivaltime);
            return false;
        }
        else if($countdate==0)
        {
            $timein_arr = $timein.split(':');
            $timeout_arr = $timeout.split(':');
            if( ($timein_arr[0]*3600+$timein_arr[1]*60)>=($timeout_arr[0]*3600+$timeout_arr[1]*60) )
            {
                alert('<h3>BOOKING</h3><br/><span><i class="fa fa-calendar-times-o fa-fw"></i> Gio den phai nho hon Gio di</span>');
                jQuery("#booking_time_in_"+index).val(TIME_IN);
                jQuery("#booking_time_out_"+index).val(TIME_OUT);
            }
        }
    }
}
/** END BOOKING JS **/

/** BAR **/
function barproductAutoComplete(index,indexchild)
{
    var $bar_id = jQuery("#bar_bar_id_"+index).val();
    if($bar_id!='')
    {
        $department = list_bars[$bar_id]['department_id'];
        jQuery("#bar_"+index+"_child_product_id_"+indexchild).autocomplete({
                    url: 'get_product.php?bar=1&mice=1&warehouse_code='+$department,
    				selectFirst:false,
    			 	onItemSelect: function(item) {
    			 	   jQuery("#bar_"+index+"_child_product_id_"+indexchild).val(item['data'][1]);
    			 	   bargetProductFromCode(index,indexchild);
                       jQuery(".acResults").css('display','none');
            		}
            });
     }
     else
     {
        alert('<h3>RESTAURANT</h3><br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Ban chua chon nha hang</span>');
     }
}
function bargetProductFromCode(index,indexchild)
{
    var product_id = jQuery("#bar_"+index+"_child_product_id_"+indexchild).val();
    $check = false;
    for(var i in all_products)
    {
        if(all_products[i]['product_id']==product_id)
        {
            var $department = all_products[i]['department_code'];
            var $bar_id = jQuery("#bar_bar_id_"+index).val();
            var $department_bar = list_bars[$bar_id]['department_id'];
            if($department_bar==$department)
            {
                $check=true;
                jQuery("#bar_"+index+"_child_product_name_"+indexchild).val(all_products[i]['name']);
                jQuery("#bar_"+index+"_child_price_id_"+indexchild).val(all_products[i]['price_id']);
                jQuery("#bar_"+index+"_child_unit_name_"+indexchild).val(all_products[i]['unit_name']);
                jQuery("#bar_"+index+"_child_unit_id_"+indexchild).val(all_products[i]['unit_id']);
                jQuery("#bar_"+index+"_child_price_"+indexchild).val(number_format(all_products[i]['price']));
                break;
            }
        }
    }
    if($check==false)
    {
        jQuery("#bar_"+index+"_child_product_id_"+indexchild).val('');
        jQuery("#bar_"+index+"_child_price_id_"+indexchild).val('');
        jQuery("#bar_"+index+"_child_product_name_"+indexchild).val('');
        jQuery("#bar_"+index+"_child_unit_name_"+indexchild).val('');
        jQuery("#bar_"+index+"_child_unit_id_"+indexchild).val('');
        jQuery("#bar_"+index+"_child_price_"+indexchild).val('');
    }
    GetTotalAmount();
}
function parselayouttable(index)
{
    $table_option = '<option value="">Chon ban</option>';
    if(jQuery("#bar_bar_id_"+index).val()=='')
    {
        document.getElementById("bar_table_id_"+index).innerHTML = $table_option;
        document.getElementById("bar_full_charge_"+index).checked=false;
        document.getElementById("bar_full_rate_"+index).checked=false;
    }
    else
    {
        $bar_id = jQuery("#bar_bar_id_"+index).val();
        $table_id_old = jQuery("#bar_table_id_"+index).val();
        if(jQuery("#bar_id_"+index).val()==''){
            if(list_bars[$bar_id]['full_charge']==1)
                document.getElementById("bar_full_charge_"+index).checked=true;
            else
                document.getElementById("bar_full_charge_"+index).checked=false;
            if(list_bars[$bar_id]['full_rate']==1)
                document.getElementById("bar_full_rate_"+index).checked=true;
            else
                document.getElementById("bar_full_rate_"+index).checked=false;
        }
        for(var i in list_bar_tables)
        {
            if(list_bar_tables[i]['bar_id']==$bar_id)
            {
                $table_option += '<option value="'+list_bar_tables[i]['id']+'">'+list_bar_tables[i]['name']+'</option>';
            }
        }
        document.getElementById("bar_table_id_"+index).innerHTML = $table_option;
        jQuery("#bar_table_id_"+index).val($table_id_old);
    }
    parselayoutproduct(index);
}
function parselayoutproduct(index)
{
    if(jQuery("#bar_table_id_"+index).val()=='')
    {
        for(var i=100;i<=Bar_InputCount_Product;i++)
        {
            if(jQuery("#bar_"+index+"_child_id_"+i).val()!=undefined)
            {
                if(jQuery("#bar_"+index+"_child_product_id_"+i).val()!='')
                    jQuery("#TemplateResProduct_"+index+"_"+i).remove();
            }
        }
    }
    else
    {
        $table_id = jQuery("#bar_table_id_"+index).val();
        $bar_id = list_bar_tables[$table_id]['bar_id'];
        jQuery("#bar_bar_id_"+index).val($bar_id);
        if(jQuery("#bar_id_"+index).val()==''){
            if(list_bars[$bar_id]['full_charge']==1)
                document.getElementById("bar_full_charge_"+index).checked=true;
            else
                document.getElementById("bar_full_charge_"+index).checked=false;
            if(list_bars[$bar_id]['full_rate']==1)
                document.getElementById("bar_full_rate_"+index).checked=true;
            else
                document.getElementById("bar_full_rate_"+index).checked=false;
        }
        $department = list_bars[$bar_id]['department_id'];
        for(var i=100;i<=Bar_InputCount_Product;i++)
        {
            if(jQuery("#bar_"+index+"_child_id_"+i).val()!=undefined)
            {
                $product_id = jQuery("#bar_"+index+"_child_price_id_"+i).val();
                if($product_id!='')
                {
                    if(all_products[$product_id])
                        $product_department = all_products[$product_id]['department_code'];
                    else
                        $product_department = '';
                    if($product_department!='RES' && $product_department!=$department)
                    {
                        jQuery("#TemplateResProduct_"+index+"_"+i).remove();
                    }
                }
            }
        }
    }
    GetTotalAmount();
}
/** END BAR **/

/** VENDING **/
function vendingproductAutoComplete(index,indexchild)
{
    var $department_id = jQuery("#vending_department_id_"+index).val();
    if($department_id!='')
    {
        $department = list_area_vending[$department_id]['code'];
        jQuery("#vending_"+index+"_child_product_id_"+indexchild).autocomplete({
                    url: 'get_product.php?vending=1&mice=1&warehouse_code='+$department,
    				selectFirst:false,
    			 	onItemSelect: function(item) 
                     {
                       jQuery("#vending_"+index+"_child_product_id_"+indexchild).val(item['data'][1]);
    			 	   vendinggetProductFromCode(index,indexchild);
                       jQuery(".acResults").css('display','none');
            		}
            });
     }
     else
     {
        alert('<h3>VENDING</h3><br/><span><i class="fa fa-calendar-times-o fa-fw"></i>Ban chua chon khu ban hang</span>');
     }
}
function vendinggetProductFromCode(index,indexchild)
{
    var product_id = jQuery("#vending_"+index+"_child_product_id_"+indexchild).val();
    $check = false;
    for(var i in all_products)
    {
        if(all_products[i]['product_id']==product_id)
        {
            var $department = all_products[i]['department_code'];
            var $department_id = jQuery("#vending_department_id_"+index).val();
            var $department_vending = list_area_vending[$department_id]['code'];
            if($department_vending==$department)
            {
                $check=true;
                jQuery("#vending_"+index+"_child_product_name_"+indexchild).val(all_products[i]['name']);
                jQuery("#vending_"+index+"_child_price_id_"+indexchild).val(all_products[i]['price_id']);
                jQuery("#vending_"+index+"_child_unit_name_"+indexchild).val(all_products[i]['unit_name']);
                jQuery("#vending_"+index+"_child_unit_id_"+indexchild).val(all_products[i]['unit_id']);
                jQuery("#vending_"+index+"_child_price_"+indexchild).val(number_format(all_products[i]['price']));
                break;
            }
        }
    }
    if($check==false)
    {
        jQuery("#vending_"+index+"_child_product_id_"+indexchild).val('');
        jQuery("#vending_"+index+"_child_price_id_"+indexchild).val('');
        jQuery("#vending_"+index+"_child_product_name_"+indexchild).val('');
        jQuery("#vending_"+index+"_child_unit_name_"+indexchild).val('');
        jQuery("#vending_"+index+"_child_unit_id_"+indexchild).val('');
        jQuery("#vending_"+index+"_child_price_"+indexchild).val('');
    }
    GetTotalAmount();
}
function parselayoutproductvending(index)
{
    if(jQuery("#vending_department_id_"+index).val()=='')
    {
        jQuery("#vending_department_code_"+index).val('');
        for(var i=100;i<=Vending_InputCount_Product;i++)
        {
            if(jQuery("#vending_"+index+"_child_id_"+i).val()!=undefined)
            {
                if(jQuery("#vending_"+index+"_child_product_id_"+i).val()!='')
                    jQuery("#TemplateVendingProduct_"+index+"_"+i).remove();
            }
        }
    }
    else
    {
        var $department_id = jQuery("#vending_department_id_"+index).val();
        jQuery("#vending_department_code_"+index).val(list_area_vending[$department_id]['code']);
        $department = list_area_vending[$department_id]['code'];
        for(var i=100;i<=Vending_InputCount_Product;i++)
        {
            if(jQuery("#vending_"+index+"_child_id_"+i).val()!=undefined)
            {
                $product_id = jQuery("#vending_"+index+"_child_price_id_"+i).val();
                if($product_id!='')
                {
                    if(all_products[$product_id])
                        $product_department = all_products[$product_id]['department_code'];
                    else
                        $product_department = '';
                    
                    if($product_department!='VENDING' && $product_department!=$department)
                    {
                        jQuery("#TemplateVendingProduct_"+index+"_"+i).remove();
                    }
                }
            }
        }
    }
    GetTotalAmount();
}
/** END VENDING **/

/** TICKET JS **/
function selectticketarea(index)
{
    $ticket_area_id = jQuery("#ticket_ticket_area_id_"+index).val();
    $ticket_id = jQuery("#ticket_ticket_id_"+index).val();
    $ticket_option = new Array();
    if($ticket_area_id=='')
    {
        for(var i in all_ticket)
        {
            $ticket_option[all_ticket[i]['ticket_id']] = new Array();
            $ticket_option[all_ticket[i]['ticket_id']]['id'] = all_ticket[i]['ticket_id'];
            $ticket_option[all_ticket[i]['ticket_id']]['name'] = all_ticket[i]['name'];
        }
        parselayoutticket(index,$ticket_option);
        jQuery("#ticket_ticket_id_"+index).val('');
        jQuery("#ticket_price_"+index).val('');
        jQuery("#ticket_discount_quantity_"+index).val('');
        jQuery("#ticket_discount_rate_"+index).val('');
    }
    else
    {
        $check = 0;
        for(var i in all_ticket)
        {
            if(all_ticket[i]['ticket_area_id'] == $ticket_area_id)
            {
                $ticket_option[all_ticket[i]['ticket_id']] = new Array();
                $ticket_option[all_ticket[i]['ticket_id']]['id'] = all_ticket[i]['ticket_id'];
                $ticket_option[all_ticket[i]['ticket_id']]['name'] = all_ticket[i]['name'];
                if(all_ticket[i]['ticket_id']==$ticket_id)
                {
                    $check = 1;
                }
            }
        }
        console.log($ticket_area_id);
        console.log($ticket_option);
        parselayoutticket(index,$ticket_option);
        if($check == 0)
        {
            jQuery("#ticket_ticket_id_"+index).val('');
            jQuery("#ticket_price_"+index).val('');
            jQuery("#ticket_discount_quantity_"+index).val('');
            jQuery("#ticket_discount_rate_"+index).val('');
        }
        else
        {
            jQuery("#ticket_ticket_id_"+index).val($ticket_id);
        }
    }
    GetTotalAmount();
}

function parselayoutticket(index,$ticket_option)
{
    $content = '<option value="">chon loai ve </option>';
    for(var i in $ticket_option)
    {
        $content += '<option value="'+$ticket_option[i]['id']+'">'+$ticket_option[i]['name']+'</option>';
    }
    document.getElementById("ticket_ticket_id_"+index).innerHTML = $content;
}

function selectticket(index)
{
    $ticket_area_id = jQuery("#ticket_ticket_area_id_"+index).val();
    $ticket_id = jQuery("#ticket_ticket_id_"+index).val();
    if($ticket_id=='')
    {
        jQuery("#ticket_ticket_id_"+index).val('');
        jQuery("#ticket_price_"+index).val('');
        jQuery("#ticket_discount_quantity_"+index).val('');
        jQuery("#ticket_discount_rate_"+index).val('');
    }
    else
    {
        $check=0;
        $ticket_area_id_s='';
        for(var i in all_ticket)
        {
            if(all_ticket[i]['ticket_id']==$ticket_id)
            {
                $ticket_area_id_s = all_ticket[i]['ticket_area_id'];
                jQuery("#ticket_price_"+index).val(number_format(all_ticket[i]['price']));
                jQuery("#ticket_note_"+index).val(all_ticket[i]['desc']);
                if(all_ticket[i]['ticket_area_id']==$ticket_area_id)
                {
                    $check=1;
                }
            }
        }
        
        if($check==0 || jQuery("#ticket_ticket_area_id_"+index).val()=='')
        {
            jQuery("#ticket_ticket_area_id_"+index).val($ticket_area_id_s);
        }
    }
    GetTotalAmount();
}
/** END TICKET **/

