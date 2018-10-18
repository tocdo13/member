<?php

/**
 * @author DauNgo
 * @copyright 2018
 */

date_default_timezone_set('Asia/Saigon');
header("Content-type: application/json; charset=utf-8");
define('ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
require_once ROOT_PATH.'convert_vi_to_latin.php';
require_once ROOT_PATH.'packages/core/includes/system/config.php';
require_once ROOT_PATH.'packages/hotel/packages/restaurant/modules/TouchBarRestaurant/db.php';
set_time_limit(-1);

$data = array();
$act = Url::get('act');
$html = '';
$result = '';
$check = true;
if(Url::get('id') and $row = TouchBarRestaurantDB::get_bar_reservation(Url::get('id')) and ($row['print_kitchen_name'] or $row['print_bar_name']))
{
	if($act=='kitchen')
	{
		$products = TouchBarRestaurantDB::get_reservation_product(' AND product.type=\'PRODUCT\' AND ((bar_reservation_product.quantity-bar_reservation_product.printed)>0)');
	}
	else
	{
		$products = TouchBarRestaurantDB::get_reservation_product(' AND product.type<>\'PRODUCT\' AND product.type<>\'SERVICE\' AND ((bar_reservation_product.quantity-bar_reservation_product.printed)>0)');
	}
	if($act=='kitchen')
	{			
		$product_remain = TouchBarRestaurantDB::get_reservation_product(' AND product.type=\'PRODUCT\' AND (bar_reservation_product.remain-DECODE(bar_reservation_product.printed_cancel,null,0,bar_reservation_product.printed_cancel))>0');
	}
	else
	{
		$product_remain = TouchBarRestaurantDB::get_reservation_product(' AND product.type<>\'PRODUCT\' AND product.type<>\'SERVICE\' AND (bar_reservation_product.remain-DECODE(bar_reservation_product.printed_cancel,null,0,bar_reservation_product.printed_cancel))>0');				
	}
    
    $group_prduct=array();
    foreach($products as $key=>$value)
	{   
		 if($value['stt_order']==''){
		      $products[$key]['stt_order']='1'; 
		 }	 
	}
    foreach($product_remain as $key=>$value)
	{   
		 if($value['stt_order']==''){
		      $product_remain[$key]['stt_order']='1'; 
		 }	 
	}
    foreach($products as $key=>$value){
	 array_push($group_prduct,$products[$key]['stt_order']); 
	 $products[$value['stt_order'].'_'.$key]=$value;
     unset($products[$key]);
	}
    foreach($product_remain as $key=>$value){
	 array_push($group_prduct,$product_remain[$key]['stt_order']); 
	 $product_remain[$value['stt_order'].'_'.$key]=$value;
     unset($product_remain[$key]);
	}
    $group_prduct=array_unique($group_prduct);
    sort($group_prduct);
    ksort($products);
	$product_tmp = array();
	foreach($products as $key=>$value)
	{
		if(isset($product_tmp[$key])){
			$product_tmp[$key]['quantity'] += $value['quantity'];
			$product_tmp[$key]['quantity_discount'] += $value['quantity_discount'];
		}
		else
		{
			if(($value['quantity'] - $value['printed'])>0 ){
				$product_tmp[$key]['id'] = $key;			
				$product_tmp[$key]['product_id'] = $value['product_id'];
				$product_tmp[$key]['name'] = $value['name'];
				$product_tmp[$key]['quantity'] = $value['quantity']-$value['printed'];
				$product_tmp[$key]['quantity_discount'] = $value['quantity_discount'];
				$product_tmp[$key]['quantity_cancel'] = $value['quantity_cancel'];
				$product_tmp[$key]['unit_name'] = $value['unit_name'];
				$product_tmp[$key]['price'] = $value['price'];
				$product_tmp[$key]['discount_rate'] = $value['discount_rate'];
				$product_tmp[$key]['discount_category'] = $value['discount_category'];
				$product_tmp[$key]['printed'] = $value['quantity'] - $value['printed'];
				$product_tmp[$key]['note'] = $value['note'];
                $product_tmp[$key]['stt_order'] = $value['stt_order'];
                DB::update('bar_reservation_product', array('printed'=>$value['quantity'], 'note'=>''), 'id='.substr($key,2,strlen($key)-2));
			}
		}
	}
	foreach($product_remain as $k=>$v)
	{
		if($v['remain'] - $v['printed_cancel']>0)
		{
			$product_tmp[$k]['id'] = $k;
			$product_tmp[$k]['name'] = $v['name'];
            $product_tmp[$k]['stt_order'] = $v['stt_order'];
			$product_tmp[$k]['remain'] = $v['remain'] - $v['printed_cancel'];
            DB::update('bar_reservation_product', array('printed_cancel'=>$v['remain'],'printed'=>$v['printed']-$product_tmp[$k]['remain']), 'id='.substr($k,2,strlen($k)-2));
		}
	}
	$products = $product_tmp;
	$tables = TouchBarRestaurantDB::get_bar_table($row['id']);
	$table_name = '';
	$i=1;
	foreach($tables as $key=>$value)
	{
		if($i>1)
		{
			$table_name.= ','.$value['name'];
		}
		else
		{
			$table_name.= $value['name'];
		}
		$i++;
	}
	if($products and (($row['print_kitchen_name'] and $act=='kitchen') or ($row['print_bar_name'] and $act=='bar')))
	{
	    $data_array=array();
            $data_array['bar_id']=Url::get('bar_id');
            $data_array['table_id']=Url::get('table_id');
            $data_array['user_id']=User::id();
            $data_array['order_code']=$row['id'];
            $data_array['time']=time();
            $order_detail_id= DB::insert('order_detail',$data_array); 
        $data['group_product'] = $group_prduct;
        $data['products'] = $products;
        $data['code'] = $row['code'];
        //echo date('d/m/Y H:i', Date_Time::to_time(date('d/m/Y'))+12*3600);exit();
        $data['order_number']=DB::fetch('select max(order_number) as id from bar_reservation where bar_id='.Url::get('bar_id').' and time>='.(Date_Time::to_time(date('d/m/Y'))).'','id');
        if(!$data['order_number']){
            $data['order_number']=1;
        }else{
            $data['order_number']++;
        }
        DB::update('bar_reservation',array('order_number'=>$data['order_number']),'id='.$row['id'].'');
        $data['bar_name'] = $row['bar_name'];
        $data['bar_code'] = $row['bar_code'];
        $data['table_name'] = $table_name;
        $user_id = Url::get('user_id');
        $user_name = DB::fetch('SELECT full_name FROM party WHERE user_id=\''.$user_id.'\'', 'full_name');
        $data['user_id'] = isset($user_name)?$user_name:$user_id;
        $order = $data['order_number'];
        if($order=='')
        {
            $order=1;
        }
        $html .= '
                <head>
                    <meta http-equiv="Content-Type" content="text/css; charset=UTF-8">
                    <link rel="stylesheet" href="packages/core/skins/default/css/tcv.css?v=3.15" type="text/css">
                    <link rel="stylesheet" href="packages/core/skins/default/css/global.css?v=3.15" type="text/css">
                    <link rel="stylesheet" href="packages/core/skins/default/css/jquery/jquery.ribbon.css?v=3.15" type="text/css">
                    <link rel="stylesheet" href="packages/hotel/skins/default/css/style.css?v=3.15" type="text/css">
                </head>
        ';
        $html .= '<table id="Export" width="300px" style="position: relative; float: left;"><tr><td>';
            $html .= '<div id="printer" style="width:300px; padding:0px; text-align:left;">';
                $html .= '<div class="restaurant-invoice-bound" style="width: 100%;">';
                    $html .= '<table style="border-bottom: 1px solid #dddddd;" width="100%" border="0" cellspacing="0" cellpadding="0">';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 15px; font-weight: bold; text-align: center; line-height: 25px;">ORDER '.Convert::stripVN($data['bar_name']).'</td>';
                        $html .= '</tr>';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 15px; line-height: 25px;">Ten nhan vien: '.Convert::stripVN($data['user_id']).'</td>';
                        $html .= '</tr>';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 15px; line-height: 25px;">So ORDER: '.date('d').date('m').'-'.str_pad($order, 4, "0", STR_PAD_LEFT).'</td>';
                        $html .= '</tr>';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 15px; line-height: 25px;">So Ban: '.Convert::stripVN($table_name).'</td>';
                        $html .= '</tr>';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 15px; line-height: 25px;">So HD: '.Convert::stripVN($data['code']).'</td>';
                        $html .= '</tr>';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 15px; line-height: 25px;">Thoi gian: '.date("d-m-Y H:i", time()).'</td>';
                        $html .= '</tr>';
                    $html .= '</table>';
                    $html .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="selected-foot-and-drink-table">';
                        $html .= '<tr>';
                            $html .= '<td style="font-size: 18px; font-weight: bold; text-align: center; line-height: 25px;">SL</td>';
                            $html .= '<td style="font-size: 18px; font-weight: bold; text-align: center; line-height: 25px;">DVT</td>';
                            $html .= '<td style="font-size: 18px; font-weight: bold; text-align: center; line-height: 25px;">Ten Mon(Ghi chu)</td>';
                        $html .= '</tr>';
                    foreach($data['products'] as $key => $value)
                    {
                        if(isset($value['quantity']))
                        {
                            $product_name = ($value['note']!='')?$value['name'] .' (' .$value['note'] .')':$value['name'];
                            $html .= '<tr>';
                                $html .= '<td style="font-size: 18px; text-align: center; line-height: 25px;">'.$value['quantity'].'</td>';
                                $html .= '<td style="font-size: 18px; line-height: 25px;">'.Convert::stripVN($value['unit_name']).'</td>';
                                $html .= '<td style="font-size: 18px; line-height: 25px;">'.Convert::stripVN($product_name).'</td>';
                            $html .= '</tr>';
                        }
                        if(isset($value['remain']))
                        {
                            $html .= '<tr>';
                                $html .= '<td style="font-size: 18px; text-align: center; line-height: 25px;">'.$value['remain'].'</td>';
                                $html .= '<td style="font-size: 18px; line-height: 25px;"></td>';
                                $html .= '<td style="font-size: 18px; line-height: 25px;"> HUY '.Convert::stripVN($value['name']).'</td>';
                            $html .= '</tr>';  
                        }
                    }
                    $html .= '</table>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</td></tr></table>';
    }
}

echo $html;
exit();   
?>
<style>
*{
    font-family: sans-serif, Arial, Tahoma;
}
</style>