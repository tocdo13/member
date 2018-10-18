<?php
class RevenueSummaryByDayReportForm extends Form{
	function RevenueSummaryByDayReportForm(){
		Form::Form('RevenueSummaryByDayReportForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
	}
    function draw()
    {
        $this->map = array();
        $from_date = (Url::get('from_day'))?Url::get('from_day'):date('d/m/Y');
        $hotel_ids = Url::get('hotel_id_');
        if($hotel_ids!='')
        {
            $hotel = DB::fetch_all("select * from hotel where id in (".$hotel_ids.")");
            //System::debug($hotel);
            $result = array();
            $result['to_day']['total_room'] = 0;
            $result['to_day']['repair_room'] = 0;
            $result['to_day']['room_available_for_sale'] = 0;
            $result['to_day']['total_room_occ'] = 0;
            $result['to_day']['foc_room'] = 0;
            $result['to_day']['hu_room'] = 0;
            $result['to_day']['adult'] = 0;
            $result['to_day']['room_soild'] = 0;
            $result['to_day']['room_revenue'] = 0;
            $result['to_day']['transport'] = 0;
            $result['to_day']['service_other'] = 0;
            $result['to_day']['bar_revenue'] = 0;
            $result['to_day']['total_telephone'] = 0;
            $result['to_day']['minibar'] = 0;
            $result['to_day']['laundry'] = 0; 
            $result['to_day']['spa'] = 0;
            $result['to_day']['shop'] = 0;
            $result['to_day']['hotel_revenue_total'] = 0;
            
            $result['last_month']['total_room'] = 0;
            $result['last_month']['repair_room'] = 0;
            $result['last_month']['room_available_for_sale'] = 0;
            $result['last_month']['total_room_occ'] = 0;
            $result['last_month']['foc_room'] = 0;
            $result['last_month']['hu_room'] = 0;
            $result['last_month']['adult'] = 0;
            $result['last_month']['room_soild'] = 0;
            $result['last_month']['room_revenue'] = 0;
            $result['last_month']['transport'] = 0;
            $result['last_month']['service_other'] = 0;
            $result['last_month']['bar_revenue'] = 0;
            $result['last_month']['total_telephone'] = 0;
            $result['last_month']['minibar'] = 0;
            $result['last_month']['laundry'] = 0; 
            $result['last_month']['spa'] = 0;
            $result['last_month']['shop'] = 0;
            $result['last_month']['hotel_revenue_total'] = 0;
            
            $result['last_year']['total_room'] = 0;
            $result['last_year']['repair_room'] = 0;
            $result['last_year']['room_available_for_sale'] = 0;
            $result['last_year']['total_room_occ'] = 0;
            $result['last_year']['foc_room'] = 0;
            $result['last_year']['hu_room'] = 0;
            $result['last_year']['adult'] = 0;
            $result['last_year']['room_soild'] = 0;
            $result['last_year']['room_revenue'] = 0;
            $result['last_year']['transport'] = 0;
            $result['last_year']['service_other'] = 0;
            $result['last_year']['bar_revenue'] = 0;
            $result['last_year']['total_telephone'] = 0;
            $result['last_year']['minibar'] = 0;
            $result['last_year']['laundry'] = 0; 
            $result['last_year']['spa'] = 0;
            $result['last_year']['shop'] = 0;
            $result['last_year']['hotel_revenue_total'] = 0;
            
            $result['month_current']['total_room'] = 0;
            $result['month_current']['repair_room'] = 0;
            $result['month_current']['room_available_for_sale'] = 0;
            $result['month_current']['total_room_occ'] = 0;
            $result['month_current']['foc_room'] = 0;
            $result['month_current']['hu_room'] = 0;
            $result['month_current']['adult'] = 0;
            $result['month_current']['room_soild'] = 0;
            $result['month_current']['room_revenue'] = 0;
            $result['month_current']['transport'] = 0;
            $result['month_current']['service_other'] = 0;
            $result['month_current']['bar_revenue'] = 0;
            $result['month_current']['total_telephone'] = 0;
            $result['month_current']['minibar'] = 0;
            $result['month_current']['laundry'] = 0; 
            $result['month_current']['spa'] = 0;
            $result['month_current']['shop'] = 0;
            $result['month_current']['hotel_revenue_total'] = 0;
            
            $result['budget_current']['units_built'] = 0; 
            $result['budget_current']['room_repair'] = 0; 
            $result['budget_current']['rooms_available_for_sale'] = 0; 
            $result['budget_current']['rooms_sold'] = 0; 
            $result['budget_current']['complimentary_rooms'] = 0;  
            $result['budget_current']['total_rooms_occupied'] = 0;  
            $result['budget_current']['house_use_rooms'] = 0;  
            $result['budget_current']['no_of_guests'] = 0;  
            $result['budget_current']['room_revenue'] = 0;  
            $result['budget_current']['bar_revenue'] = 0;  
            $result['budget_current']['telephone_revenue'] = 0; 
            $result['budget_current']['laundry_revenue'] = 0;  
            $result['budget_current']['minibar_revenue'] = 0; 
            $result['budget_current']['transport_revenue'] = 0; 
            $result['budget_current']['spa_revenue'] = 0; 
            $result['budget_current']['others_revenue'] = 0;  
            $result['budget_current']['vending_revenue'] = 0;  
            $result['budget_current']['hotel_revenue_total'] = 0; 
            
            foreach($hotel as $v=>$k)
            {
                $request = new HttpRequest();
                $request->setUrl($k['hotel_link']);
                $request->setMethod(HTTP_METH_GET);
                $request->setQueryData(array(
                    'page' => 'revenue_summary_by_day_report_api',
                    'secretkey' => '9a8fa234b2520e9bb4f59d8178545a63',
                    'endpoint' => 'draw',
                    'from_day'=>$from_date
                ));
                
                $request->setHeaders(array(
                    'Cache-Control' => 'no-cache'
                ));
                
                $items = "";
                try {
                  $response = $request->send();
                
                  $items = $response->getBody();
                }catch (HttpException $ex) 
                {
                  echo $ex;
                }
                
                $bom = pack('H*','EFBBBF');
                $items = preg_replace("/^$bom/", '', $items);
                $items = json_decode($items, true);
                //System::debug($items);
                $result['to_day']['total_room'] += $items['to_day']['total_room'];
                $result['to_day']['repair_room'] += $items['to_day']['repair_room'];
                $result['to_day']['room_available_for_sale'] += $items['to_day']['room_available_for_sale'];
                $result['to_day']['total_room_occ'] += $items['to_day']['total_room_occ'];
                $result['to_day']['foc_room'] += $items['to_day']['foc_room'];
                $result['to_day']['hu_room'] += $items['to_day']['hu_room'];
                $result['to_day']['adult'] += $items['to_day']['adult'];
                $result['to_day']['room_soild'] += $items['to_day']['room_soild'];
                $result['to_day']['room_revenue'] += $items['to_day']['room_revenue'];
                $result['to_day']['transport'] += $items['to_day']['transport'];
                $result['to_day']['service_other'] += $items['to_day']['service_other'];
                $result['to_day']['bar_revenue'] += $items['to_day']['bar_revenue'];
                $result['to_day']['total_telephone'] += $items['to_day']['total_telephone'];
                $result['to_day']['minibar'] += $items['to_day']['minibar'];
                $result['to_day']['laundry'] += $items['to_day']['laundry']; 
                $result['to_day']['spa'] += $items['to_day']['spa'];
                $result['to_day']['shop'] += $items['to_day']['shop'];
                $result['to_day']['hotel_revenue_total'] += $items['to_day']['hotel_revenue_total'];
                
                $result['last_month']['total_room'] += $items['last_month']['total_room'];
                $result['last_month']['repair_room'] += $items['last_month']['repair_room'];
                $result['last_month']['room_available_for_sale'] += $items['last_month']['room_available_for_sale'];
                $result['last_month']['total_room_occ'] += $items['last_month']['total_room_occ'];
                $result['last_month']['foc_room'] += $items['last_month']['foc_room'];
                $result['last_month']['hu_room'] += $items['last_month']['hu_room'];
                $result['last_month']['adult'] += $items['last_month']['adult'];
                $result['last_month']['room_soild'] += $items['last_month']['room_soild'];
                $result['last_month']['room_revenue'] += $items['last_month']['room_revenue'];
                $result['last_month']['transport'] += $items['last_month']['transport'];
                $result['last_month']['service_other'] += $items['last_month']['service_other'];
                $result['last_month']['bar_revenue'] += $items['last_month']['bar_revenue'];
                $result['last_month']['total_telephone'] += $items['last_month']['total_telephone'];
                $result['last_month']['minibar'] += $items['last_month']['minibar'];
                $result['last_month']['laundry'] += $items['last_month']['laundry']; 
                $result['last_month']['spa'] += $items['last_month']['spa'];
                $result['last_month']['shop'] += $items['last_month']['shop'];
                $result['last_month']['hotel_revenue_total'] += $items['last_month']['hotel_revenue_total'];
                
                $result['last_year']['total_room'] += $items['last_year']['total_room'];
                $result['last_year']['repair_room'] += $items['last_year']['repair_room'];
                $result['last_year']['room_available_for_sale'] += $items['last_year']['room_available_for_sale'];
                $result['last_year']['total_room_occ'] += $items['last_year']['total_room_occ'];
                $result['last_year']['foc_room'] += $items['last_year']['foc_room'];
                $result['last_year']['hu_room'] += $items['last_year']['hu_room'];
                $result['last_year']['adult'] += $items['last_year']['adult'];
                $result['last_year']['room_soild'] += $items['last_year']['room_soild'];
                $result['last_year']['room_revenue'] += $items['last_year']['room_revenue'];
                $result['last_year']['transport'] += $items['last_year']['transport'];
                $result['last_year']['service_other'] += $items['last_year']['service_other'];
                $result['last_year']['bar_revenue'] += $items['last_year']['bar_revenue'];
                $result['last_year']['total_telephone'] += $items['last_year']['total_telephone'];
                $result['last_year']['minibar'] += $items['last_year']['minibar'];
                $result['last_year']['laundry'] += $items['last_year']['laundry']; 
                $result['last_year']['spa'] += $items['last_year']['spa'];
                $result['last_year']['shop'] += $items['last_year']['shop'];
                $result['last_year']['hotel_revenue_total'] += $items['last_year']['hotel_revenue_total'];
                
                $result['month_current']['total_room'] += $items['month_current']['total_room'];
                $result['month_current']['repair_room'] += $items['month_current']['repair_room'];
                $result['month_current']['room_available_for_sale'] += $items['month_current']['room_available_for_sale'];
                $result['month_current']['total_room_occ'] += $items['month_current']['total_room_occ'];
                $result['month_current']['foc_room'] += $items['month_current']['foc_room'];
                $result['month_current']['hu_room'] += $items['month_current']['hu_room'];
                $result['month_current']['adult'] += $items['month_current']['adult'];
                $result['month_current']['room_soild'] += $items['month_current']['room_soild'];
                $result['month_current']['room_revenue'] += $items['month_current']['room_revenue'];
                $result['month_current']['transport'] += $items['month_current']['transport'];
                $result['month_current']['service_other'] += $items['month_current']['service_other'];
                $result['month_current']['bar_revenue'] += $items['month_current']['bar_revenue'];
                $result['month_current']['total_telephone'] += $items['month_current']['total_telephone'];
                $result['month_current']['minibar'] += $items['month_current']['minibar'];
                $result['month_current']['laundry'] += $items['month_current']['laundry']; 
                $result['month_current']['spa'] += $items['month_current']['spa'];
                $result['month_current']['shop'] += $items['month_current']['shop'];
                $result['month_current']['hotel_revenue_total'] += $items['month_current']['hotel_revenue_total'];
                
                
                
                $result['budget_current']['units_built'] += isset($items['budget_current']['units_built'])?$items['budget_current']['units_built']:0; 
                $result['budget_current']['room_repair'] += isset($items['budget_current']['room_repair'])?$items['budget_current']['room_repair']:0; 
                $result['budget_current']['rooms_available_for_sale'] += isset($items['budget_current']['rooms_available_for_sale'])?$items['budget_current']['rooms_available_for_sale']:0; 
                $result['budget_current']['rooms_sold'] += isset($items['budget_current']['rooms_sold'])?$items['budget_current']['rooms_sold']:0; 
                $result['budget_current']['complimentary_rooms'] += isset($items['budget_current']['complimentary_rooms'])?$items['budget_current']['complimentary_rooms']:0;  
                $result['budget_current']['total_rooms_occupied'] += isset($items['budget_current']['total_rooms_occupied'])?$items['budget_current']['total_rooms_occupied']:0;  
                $result['budget_current']['house_use_rooms'] += isset($items['budget_current']['house_use_rooms'])?$items['budget_current']['house_use_rooms']:0;  
                $result['budget_current']['no_of_guests'] += isset($items['budget_current']['no_of_guests'])?$items['budget_current']['no_of_guests']:0;  
                $result['budget_current']['room_revenue'] += isset($items['budget_current']['room_revenue'])?$items['budget_current']['room_revenue']:0;  
                $result['budget_current']['bar_revenue'] += isset($items['budget_current']['bar_revenue'])?$items['budget_current']['bar_revenue']:0;  
                $result['budget_current']['telephone_revenue'] += isset($items['budget_current']['telephone_revenue'])?$items['budget_current']['telephone_revenue']:0; 
                $result['budget_current']['laundry_revenue'] += isset($items['budget_current']['laundry_revenue'])?$items['budget_current']['laundry_revenue']:0;  
                $result['budget_current']['minibar_revenue'] += isset($items['budget_current']['minibar_revenue'])?$items['budget_current']['minibar_revenue']:0; 
                $result['budget_current']['transport_revenue'] += isset($items['budget_current']['transport_revenue'])?$items['budget_current']['transport_revenue']:0; 
                $result['budget_current']['spa_revenue'] += isset($items['budget_current']['spa_revenue'])?$items['budget_current']['spa_revenue']:0; 
                $result['budget_current']['others_revenue'] += isset($items['budget_current']['others_revenue'])?$items['budget_current']['others_revenue']:0;  
                $result['budget_current']['vending_revenue'] += isset($items['budget_current']['vending_revenue'])?$items['budget_current']['vending_revenue']:0;  
                $result['budget_current']['hotel_revenue_total'] += isset($items['budget_current']['hotel_revenue_total'])?$items['budget_current']['hotel_revenue_total']:0; 
                
            }
            
            $result['to_day']['occupancy_rate'] = round($result['to_day']['total_room_occ']/$result['to_day']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['to_day']['average_room_rate'] = ($result['to_day']['room_soild']!=0)?round($result['to_day']['room_revenue']/$result['to_day']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['to_day']['rev_par'] = round($result['to_day']['room_revenue']/$result['to_day']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['to_day']['spend_per_guest'] = ($result['to_day']['adult']!=0)?round($result['to_day']['hotel_revenue_total']/$result['to_day']['adult']):$result['to_day']['hotel_revenue_total'];
            $result['to_day']['room_revenue_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['room_revenue']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['bar_revenue_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['bar_revenue']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['total_telephone_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['total_telephone']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['laundry_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['laundry']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['minibar_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['minibar']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['transport_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['transport']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['spa_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['spa']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['service_other_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['service_other']/$result['to_day']['hotel_revenue_total']*100,2):0;
            $result['to_day']['shop_percent'] = ($result['to_day']['hotel_revenue_total']!=0)?round($result['to_day']['shop']/$result['to_day']['hotel_revenue_total']*100,2):0;
            
            $result['last_month']['occupancy_rate'] = round($result['last_month']['total_room_occ']/$result['last_month']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['last_month']['average_room_rate'] = ($result['last_month']['room_soild']!=0)?round($result['last_month']['room_revenue']/$result['last_month']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['last_month']['rev_par'] = round($result['last_month']['room_revenue']/$result['last_month']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['last_month']['spend_per_guest'] = ($result['last_month']['adult']!=0)?round($result['last_month']['hotel_revenue_total']/$result['last_month']['adult']):$result['last_month']['hotel_revenue_total'];
            $result['last_month']['room_revenue_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['room_revenue']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['bar_revenue_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['bar_revenue']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['total_telephone_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['total_telephone']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['laundry_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['laundry']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['minibar_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['minibar']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['transport_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['transport']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['spa_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['spa']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['service_other_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['service_other']/$result['last_month']['hotel_revenue_total']*100,2):0;
            $result['last_month']['shop_percent'] = ($result['last_month']['hotel_revenue_total']!=0)?round($result['last_month']['shop']/$result['last_month']['hotel_revenue_total']*100,2):0;
            
            $result['last_year']['occupancy_rate'] = round($result['last_year']['total_room_occ']/$result['last_year']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['last_year']['average_room_rate'] = ($result['last_year']['room_soild']!=0)?round($result['last_year']['room_revenue']/$result['last_year']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['last_year']['rev_par'] = round($result['last_year']['room_revenue']/$result['last_year']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['last_year']['spend_per_guest'] = ($result['last_year']['adult']!=0)?round($result['last_year']['hotel_revenue_total']/$result['last_year']['adult']):$result['last_year']['hotel_revenue_total'];
            $result['last_year']['room_revenue_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['room_revenue']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['bar_revenue_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['bar_revenue']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['total_telephone_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['total_telephone']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['laundry_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['laundry']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['minibar_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['minibar']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['transport_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['transport']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['spa_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['spa']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['service_other_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['service_other']/$result['last_year']['hotel_revenue_total']*100,2):0;
            $result['last_year']['shop_percent'] = ($result['last_year']['hotel_revenue_total']!=0)?round($result['last_year']['shop']/$result['last_year']['hotel_revenue_total']*100,2):0;
            
            $result['month_current']['occupancy_rate'] = round($result['month_current']['total_room_occ']/$result['month_current']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['month_current']['average_room_rate'] = ($result['month_current']['room_soild']!=0)?round($result['month_current']['room_revenue']/$result['month_current']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['month_current']['rev_par'] = round($result['month_current']['room_revenue']/$result['month_current']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['month_current']['spend_per_guest'] = ($result['month_current']['adult']!=0)?round($result['month_current']['hotel_revenue_total']/$result['month_current']['adult']):$result['month_current']['hotel_revenue_total'];
            $result['month_current']['room_revenue_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['room_revenue']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['bar_revenue_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['bar_revenue']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['total_telephone_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['total_telephone']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['laundry_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['laundry']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['minibar_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['minibar']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['transport_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['transport']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['spa_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['spa']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['service_other_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['service_other']/$result['month_current']['hotel_revenue_total']*100,2):0;
            $result['month_current']['shop_percent'] = ($result['month_current']['hotel_revenue_total']!=0)?round($result['month_current']['shop']/$result['month_current']['hotel_revenue_total']*100,2):0;
            
            $result['budget_current']['occupancy_rate'] = ($result['budget_current']['rooms_available_for_sale']!=0)?round($result['budget_current']['total_rooms_occupied']/$result['budget_current']['rooms_available_for_sale']*100):0;
            $result['budget_current']['average_room_rate'] = ($result['budget_current']['rooms_sold']!=0)?round($result['budget_current']['room_revenue']/$result['budget_current']['rooms_sold']):0;
            $result['budget_current']['rev_par'] = ($result['budget_current']['rooms_available_for_sale']!=0)?round($result['budget_current']['room_revenue']/$result['budget_current']['rooms_available_for_sale']):0;
            $result['budget_current']['spend_per_guest'] = ($result['budget_current']['no_of_guests']!=0)?round($result['budget_current']['hotel_revenue_total']/$result['budget_current']['no_of_guests']):0;
            $result['budget_current']['room_revenue_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['room_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['bar_revenue_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['bar_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['total_telephone_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['telephone_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['laundry_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['laundry_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['minibar_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['minibar_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['transport_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['transport_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['spa_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['spa_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['service_other_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['others_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_current']['shop_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['vending_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            
            //System::debug($result);
        }
        else
        {
            
            $hotel = DB::fetch_all("select * from hotel where is_active=1");
            $request = new HttpRequest();
            foreach($hotel as $k=>$v){
                $request->setUrl($v['hotel_link']);
            }
            $request->setMethod(HTTP_METH_GET);
            $request->setQueryData(array(
                'page' => 'revenue_summary_by_day_report_api',
                'secretkey' => '9a8fa234b2520e9bb4f59d8178545a63',
                'endpoint' => 'draw',
                'from_day'=>$from_date
            ));
            
            $request->setHeaders(array(
                'Cache-Control' => 'no-cache'
            ));
            
            $result = "";
            try {
              $response = $request->send();
            
              $result = $response->getBody();
            }catch (HttpException $ex) 
            {
              echo $ex;
            }
            
            $bom = pack('H*','EFBBBF');
            $result = preg_replace("/^$bom/", '', $result);
            $result = json_decode($result, true);
        }
        $result['difference']['units_built'] = $result['month_current']['total_room']-$result['budget_current']['units_built']; 
        $result['difference']['room_repair'] = $result['month_current']['repair_room']-$result['budget_current']['room_repair']; 
        $result['difference']['rooms_available_for_sale'] = $result['month_current']['room_available_for_sale']-$result['budget_current']['rooms_available_for_sale'];
        $result['difference']['rooms_sold'] = $result['month_current']['room_soild']-$result['budget_current']['rooms_sold']; 
        $result['difference']['complimentary_rooms'] = $result['month_current']['foc_room']-$result['budget_current']['complimentary_rooms'];  
        $result['difference']['total_rooms_occupied'] = $result['month_current']['total_room_occ']-$result['budget_current']['total_rooms_occupied'];  
        $result['difference']['house_use_rooms'] = $result['month_current']['hu_room']-$result['budget_current']['house_use_rooms'];  
        $result['difference']['no_of_guests'] = $result['month_current']['adult']-$result['budget_current']['no_of_guests'];  
        $result['difference']['room_revenue'] = $result['month_current']['room_revenue']-$result['budget_current']['room_revenue']; 
        $result['difference']['bar_revenue'] = $result['month_current']['bar_revenue']-$result['budget_current']['bar_revenue'];  
        $result['difference']['telephone_revenue'] = $result['month_current']['total_telephone']-$result['budget_current']['telephone_revenue']; 
        $result['difference']['laundry_revenue'] = $result['month_current']['laundry']-$result['budget_current']['laundry_revenue']; 
        $result['difference']['minibar_revenue'] = $result['month_current']['minibar']-$result['budget_current']['minibar_revenue']; 
        $result['difference']['transport_revenue'] = $result['month_current']['transport']-$result['budget_current']['transport_revenue']; 
        $result['difference']['spa_revenue'] = $result['month_current']['spa']-$result['budget_current']['spa_revenue']; 
        $result['difference']['others_revenue'] = $result['month_current']['service_other']-$result['budget_current']['others_revenue'];  
        $result['difference']['vending_revenue'] = $result['month_current']['shop']-$result['budget_current']['vending_revenue'];  
        $result['difference']['hotel_revenue_total'] = $result['month_current']['hotel_revenue_total']-$result['budget_current']['hotel_revenue_total'];
        $result['difference']['occupancy_rate'] = $result['month_current']['occupancy_rate']-$result['budget_current']['occupancy_rate'];
        $result['difference']['average_room_rate'] = $result['month_current']['average_room_rate']-$result['budget_current']['average_room_rate'];
        $result['difference']['rev_par'] = $result['month_current']['rev_par']-$result['budget_current']['rev_par'];
        $result['difference']['spend_per_guest'] = $result['month_current']['spend_per_guest']-$result['budget_current']['spend_per_guest'];
        $to_day = $result['to_day'];
        $last_month = $result['last_month'];
        $last_year = $result['last_year'];
        $month_current = $result['month_current'];
        $budget_current=$result['budget_current'];
        $difference = $result['difference'];
        $month_current_pie = array(0=>array(
                                        'name'=>Portal::language('room_revenue')
                                        ,'y'=>isset($month_current['room_revenue'])?round($month_current['room_revenue']):0
                                  ),
                                  1=>array(
                                        'name'=>Portal::language('bar_revenue')
                                        ,'y'=>isset($month_current['bar_revenue'])?round($month_current['bar_revenue']):0
                                  ),
                                  2=>array(
                                        'name'=>Portal::language('telephone_revenue')
                                        ,'y'=>isset($month_current['total_telephone'])?round($month_current['total_telephone']):0
                                  ),
                                  3=>array(
                                        'name'=>Portal::language('laundry_revenue')
                                        ,'y'=>isset($month_current['laundry'])?round($month_current['laundry']):0
                                  ),
                                  4=>array(
                                        'name'=>Portal::language('minibar_revenue')
                                        ,'y'=>isset($month_current['minibar'])?round($month_current['minibar']):0
                                  ),
                                  5=>array(
                                        'name'=>Portal::language('transport_revenue')
                                        ,'y'=>isset($month_current['transport'])?round($month_current['transport']):0
                                  ),
                                  6=>array(
                                        'name'=>Portal::language('spa_revenue')
                                        ,'y'=>isset($month_current['spa'])?round($month_current['spa']):0
                                  ),
                                  7=>array(
                                        'name'=>Portal::language('others_revenue')
                                        ,'y'=>isset($month_current['service_other'])?round($month_current['service_other']):0
                                  ),
                                  8=>array(
                                        'name'=>Portal::language('vending_revenue')
                                        ,'y'=>isset($month_current['shop'])?round($month_current['shop']):0
                                  )
                                  ); 
        $to_day_pie = array(0=>array(
                                        'name'=>Portal::language('room_revenue')
                                        ,'y'=>isset($to_day['room_revenue'])?round($to_day['room_revenue']):0
                                  ),
                                  1=>array(
                                        'name'=>Portal::language('bar_revenue')
                                        ,'y'=>isset($to_day['bar_revenue'])?round($to_day['bar_revenue']):0
                                  ),
                                  2=>array(
                                        'name'=>Portal::language('telephone_revenue')
                                        ,'y'=>isset($to_day['total_telephone'])?round($to_day['total_telephone']):0
                                  ),
                                  3=>array(
                                        'name'=>Portal::language('laundry_revenue')
                                        ,'y'=>isset($to_day['laundry'])?round($to_day['laundry']):0
                                  ),
                                  4=>array(
                                        'name'=>Portal::language('minibar_revenue')
                                        ,'y'=>isset($to_day['minibar'])?round($to_day['minibar']):0
                                  ),
                                  5=>array(
                                        'name'=>Portal::language('transport_revenue')
                                        ,'y'=>isset($to_day['transport'])?round($to_day['transport']):0
                                  ),
                                  6=>array(
                                        'name'=>Portal::language('spa_revenue')
                                        ,'y'=>isset($to_day['spa'])?round($to_day['spa']):0
                                  ),
                                  7=>array(
                                        'name'=>Portal::language('others_revenue')
                                        ,'y'=>isset($to_day['service_other'])?round($to_day['service_other']):0
                                  ),
                                  8=>array(
                                        'name'=>Portal::language('vending_revenue')
                                        ,'y'=>isset($to_day['shop'])?round($to_day['shop']):0
                                  )
                                  );
        
        /** Nguon khach **/
        $this->map['hotel'] = Url::get('hotel_ids','');                               
        $l_hotel = DB::fetch_all($sql='
                       select hotel.id,hotel.hotel_name as name from hotel order by hotel.hotel_name
        ');                    
        $this->map['hotel_js'] = String::array2js(explode(',',Url::get('hotel_id_')));            
        $hotel_list = '<div id="checkboxes_hotel" style="width: 150px;">';
        foreach($l_hotel as $key=>$value)
        {                
            $hotel_list .= '<label for="hotel_'.$value['id'].'">';    
            $hotel_list .= '<input name="hotel_'.$value['id'].'" type="checkbox" id="hotel_'.$value['id'].'" flag="'.$value['id'].'" class="hotel" onclick="get_ids(\'hotel\');"/>'.$value['name'].'</label>';                                    
        }   
        $hotel_list .= '</div>';            
        $this->map['list_hotel'] = $hotel_list; 
        /** Nguon khach **/ 
        $this->map['from_date'] = $from_date;
        //System::debug($hotel);
        $this->map['hotel']=$hotel;
        $this->parse_layout('report', $this->map+array(
                                                'to_day'=>$to_day,
                                                'last_month'=>$last_month,
                                                'last_year'=>$last_year,
                                                'month_current'=>$month_current,
                                                'budget_current'=>$budget_current,
                                                'difference'=>$difference,
                                                'month_current_pie_js'=>json_encode($month_current_pie),
                                                'to_day_pie_js'=>json_encode($to_day_pie)
                ));	
    }
 
}
?>
