<?php
class RevenueSummaryReportForm extends Form{
	function RevenueSummaryReportForm(){
		Form::Form('RevenueSummaryReportForm');
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
            
            $result['this_month_ytd']['total_room'] = 0;
            $result['this_month_ytd']['repair_room'] = 0;
            $result['this_month_ytd']['room_available_for_sale'] = 0;
            $result['this_month_ytd']['total_room_occ'] = 0;
            $result['this_month_ytd']['foc_room'] = 0;
            $result['this_month_ytd']['hu_room'] = 0;
            $result['this_month_ytd']['adult'] = 0;
            $result['this_month_ytd']['room_soild'] = 0;
            $result['this_month_ytd']['room_revenue'] = 0;
            $result['this_month_ytd']['transport'] = 0;
            $result['this_month_ytd']['service_other'] = 0;
            $result['this_month_ytd']['bar_revenue'] = 0;
            $result['this_month_ytd']['total_telephone'] = 0;
            $result['this_month_ytd']['minibar'] = 0;
            $result['this_month_ytd']['laundry'] = 0; 
            $result['this_month_ytd']['spa'] = 0;
            $result['this_month_ytd']['shop'] = 0;
            $result['this_month_ytd']['hotel_revenue_total'] = 0;
            
            $result['last_year_current']['total_room'] = 0;
            $result['last_year_current']['repair_room'] = 0;
            $result['last_year_current']['room_available_for_sale'] = 0;
            $result['last_year_current']['total_room_occ'] = 0;
            $result['last_year_current']['foc_room'] = 0;
            $result['last_year_current']['hu_room'] = 0;
            $result['last_year_current']['adult'] = 0;
            $result['last_year_current']['room_soild'] = 0;
            $result['last_year_current']['room_revenue'] = 0;
            $result['last_year_current']['transport'] = 0;
            $result['last_year_current']['service_other'] = 0;
            $result['last_year_current']['bar_revenue'] = 0;
            $result['last_year_current']['total_telephone'] = 0;
            $result['last_year_current']['minibar'] = 0;
            $result['last_year_current']['laundry'] = 0; 
            $result['last_year_current']['spa'] = 0;
            $result['last_year_current']['shop'] = 0;
            $result['last_year_current']['hotel_revenue_total'] = 0;
            
            $result['last_year_ytd']['total_room'] = 0;
            $result['last_year_ytd']['repair_room'] = 0;
            $result['last_year_ytd']['room_available_for_sale'] = 0;
            $result['last_year_ytd']['total_room_occ'] = 0;
            $result['last_year_ytd']['foc_room'] = 0;
            $result['last_year_ytd']['hu_room'] = 0;
            $result['last_year_ytd']['adult'] = 0;
            $result['last_year_ytd']['room_soild'] = 0;
            $result['last_year_ytd']['room_revenue'] = 0;
            $result['last_year_ytd']['transport'] = 0;
            $result['last_year_ytd']['service_other'] = 0;
            $result['last_year_ytd']['bar_revenue'] = 0;
            $result['last_year_ytd']['total_telephone'] = 0;
            $result['last_year_ytd']['minibar'] = 0;
            $result['last_year_ytd']['laundry'] = 0; 
            $result['last_year_ytd']['spa'] = 0;
            $result['last_year_ytd']['shop'] = 0;
            $result['last_year_ytd']['hotel_revenue_total'] = 0;
            
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
            
            $result['budget_ytd']['units_built'] = 0; 
            $result['budget_ytd']['room_repair'] = 0; 
            $result['budget_ytd']['rooms_available_for_sale'] = 0; 
            $result['budget_ytd']['rooms_sold'] = 0; 
            $result['budget_ytd']['complimentary_rooms'] = 0;  
            $result['budget_ytd']['total_rooms_occupied'] = 0;  
            $result['budget_ytd']['house_use_rooms'] = 0;  
            $result['budget_ytd']['no_of_guests'] = 0;  
            $result['budget_ytd']['room_revenue'] = 0;  
            $result['budget_ytd']['bar_revenue'] = 0;  
            $result['budget_ytd']['telephone_revenue'] = 0; 
            $result['budget_ytd']['laundry_revenue'] = 0;  
            $result['budget_ytd']['minibar_revenue'] = 0; 
            $result['budget_ytd']['transport_revenue'] = 0; 
            $result['budget_ytd']['spa_revenue'] = 0; 
            $result['budget_ytd']['others_revenue'] = 0;  
            $result['budget_ytd']['vending_revenue'] = 0;  
            $result['budget_ytd']['hotel_revenue_total'] = 0;
            foreach($hotel as $v=>$k)
            {
                $request = new HttpRequest();
                $request->setUrl($k['hotel_link']);
                $request->setMethod(HTTP_METH_GET);
                $request->setQueryData(array(
                    'page' => 'revenue_summary_report_api',
                    'secretkey' => '9a8fa234b2520e9bb4f59d8178545a62',
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
                
                $result['this_month_ytd']['total_room'] += $items['this_month_ytd']['total_room'];
                $result['this_month_ytd']['repair_room'] += $items['this_month_ytd']['repair_room'];
                $result['this_month_ytd']['room_available_for_sale'] += $items['this_month_ytd']['room_available_for_sale'];
                $result['this_month_ytd']['total_room_occ'] += $items['this_month_ytd']['total_room_occ'];
                $result['this_month_ytd']['foc_room'] += $items['this_month_ytd']['foc_room'];
                $result['this_month_ytd']['hu_room'] += $items['this_month_ytd']['hu_room'];
                $result['this_month_ytd']['adult'] += $items['this_month_ytd']['adult'];
                $result['this_month_ytd']['room_soild'] += $items['this_month_ytd']['room_soild'];
                $result['this_month_ytd']['room_revenue'] += $items['this_month_ytd']['room_revenue'];
                $result['this_month_ytd']['transport'] += $items['this_month_ytd']['transport'];
                $result['this_month_ytd']['service_other'] += $items['this_month_ytd']['service_other'];
                $result['this_month_ytd']['bar_revenue'] += $items['this_month_ytd']['bar_revenue'];
                $result['this_month_ytd']['total_telephone'] += $items['this_month_ytd']['total_telephone'];
                $result['this_month_ytd']['minibar'] += $items['this_month_ytd']['minibar'];
                $result['this_month_ytd']['laundry'] += $items['this_month_ytd']['laundry']; 
                $result['this_month_ytd']['spa'] += $items['this_month_ytd']['spa'];
                $result['this_month_ytd']['shop'] += $items['this_month_ytd']['shop'];
                $result['this_month_ytd']['hotel_revenue_total'] += $items['this_month_ytd']['hotel_revenue_total'];
                
                $result['last_year_current']['total_room'] += $items['last_year_current']['total_room'];
                $result['last_year_current']['repair_room'] += $items['last_year_current']['repair_room'];
                $result['last_year_current']['room_available_for_sale'] += $items['last_year_current']['room_available_for_sale'];
                $result['last_year_current']['total_room_occ'] += $items['last_year_current']['total_room_occ'];
                $result['last_year_current']['foc_room'] += $items['last_year_current']['foc_room'];
                $result['last_year_current']['hu_room'] += $items['last_year_current']['hu_room'];
                $result['last_year_current']['adult'] += $items['last_year_current']['adult'];
                $result['last_year_current']['room_soild'] += $items['last_year_current']['room_soild'];
                $result['last_year_current']['room_revenue'] += $items['last_year_current']['room_revenue'];
                $result['last_year_current']['transport'] += $items['last_year_current']['transport'];
                $result['last_year_current']['service_other'] += $items['last_year_current']['service_other'];
                $result['last_year_current']['bar_revenue'] += $items['last_year_current']['bar_revenue'];
                $result['last_year_current']['total_telephone'] += $items['last_year_current']['total_telephone'];
                $result['last_year_current']['minibar'] += $items['last_year_current']['minibar'];
                $result['last_year_current']['laundry'] += $items['last_year_current']['laundry']; 
                $result['last_year_current']['spa'] += $items['last_year_current']['spa'];
                $result['last_year_current']['shop'] += $items['last_year_current']['shop'];
                $result['last_year_current']['hotel_revenue_total'] += $items['last_year_current']['hotel_revenue_total'];
                
                $result['last_year_ytd']['total_room'] += $items['last_year_ytd']['total_room'];
                $result['last_year_ytd']['repair_room'] += $items['last_year_ytd']['repair_room'];
                $result['last_year_ytd']['room_available_for_sale'] += $items['last_year_ytd']['room_available_for_sale'];
                $result['last_year_ytd']['total_room_occ'] += $items['last_year_ytd']['total_room_occ'];
                $result['last_year_ytd']['foc_room'] += $items['last_year_ytd']['foc_room'];
                $result['last_year_ytd']['hu_room'] += $items['last_year_ytd']['hu_room'];
                $result['last_year_ytd']['adult'] += $items['last_year_ytd']['adult'];
                $result['last_year_ytd']['room_soild'] += $items['last_year_ytd']['room_soild'];
                $result['last_year_ytd']['room_revenue'] += $items['last_year_ytd']['room_revenue'];
                $result['last_year_ytd']['transport'] += $items['last_year_ytd']['transport'];
                $result['last_year_ytd']['service_other'] += $items['last_year_ytd']['service_other'];
                $result['last_year_ytd']['bar_revenue'] += $items['last_year_ytd']['bar_revenue'];
                $result['last_year_ytd']['total_telephone'] += $items['last_year_ytd']['total_telephone'];
                $result['last_year_ytd']['minibar'] += $items['last_year_ytd']['minibar'];
                $result['last_year_ytd']['laundry'] += $items['last_year_ytd']['laundry']; 
                $result['last_year_ytd']['spa'] += $items['last_year_ytd']['spa'];
                $result['last_year_ytd']['shop'] += $items['last_year_ytd']['shop'];
                $result['last_year_ytd']['hotel_revenue_total'] += $items['last_year_ytd']['hotel_revenue_total'];
                
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
                
                $result['budget_ytd']['units_built'] += isset($items['budget_ytd']['units_built'])?$items['budget_ytd']['units_built']:0; 
                $result['budget_ytd']['room_repair'] += isset($items['budget_ytd']['room_repair'])?$items['budget_ytd']['room_repair']:0; 
                $result['budget_ytd']['rooms_available_for_sale'] += isset($items['budget_ytd']['rooms_available_for_sale'])?$items['budget_ytd']['rooms_available_for_sale']:0; 
                $result['budget_ytd']['rooms_sold'] += isset($items['budget_ytd']['rooms_sold'])?$items['budget_ytd']['rooms_sold']:0; 
                $result['budget_ytd']['complimentary_rooms'] += isset($items['budget_ytd']['complimentary_rooms'])?$items['budget_ytd']['complimentary_rooms']:0;  
                $result['budget_ytd']['total_rooms_occupied'] += isset($items['budget_ytd']['total_rooms_occupied'])?$items['budget_ytd']['total_rooms_occupied']:0;  
                $result['budget_ytd']['house_use_rooms'] += isset($items['budget_ytd']['house_use_rooms'])?$items['budget_ytd']['house_use_rooms']:0;  
                $result['budget_ytd']['no_of_guests'] += isset($items['budget_ytd']['no_of_guests'])?$items['budget_ytd']['no_of_guests']:0;  
                $result['budget_ytd']['room_revenue'] += isset($items['budget_ytd']['room_revenue'])?$items['budget_ytd']['room_revenue']:0;  
                $result['budget_ytd']['bar_revenue'] += isset($items['budget_ytd']['bar_revenue'])?$items['budget_ytd']['bar_revenue']:0;  
                $result['budget_ytd']['telephone_revenue'] += isset($items['budget_ytd']['telephone_revenue'])?$items['budget_ytd']['telephone_revenue']:0; 
                $result['budget_ytd']['laundry_revenue'] += isset($items['budget_ytd']['laundry_revenue'])?$items['budget_ytd']['laundry_revenue']:0;  
                $result['budget_ytd']['minibar_revenue'] += isset($items['budget_ytd']['minibar_revenue'])?$items['budget_ytd']['minibar_revenue']:0; 
                $result['budget_ytd']['transport_revenue'] += isset($items['budget_ytd']['transport_revenue'])?$items['budget_ytd']['transport_revenue']:0; 
                $result['budget_ytd']['spa_revenue'] += isset($items['budget_ytd']['spa_revenue'])?$items['budget_ytd']['spa_revenue']:0; 
                $result['budget_ytd']['others_revenue'] += isset($items['budget_ytd']['others_revenue'])?$items['budget_ytd']['others_revenue']:0;  
                $result['budget_ytd']['vending_revenue'] += isset($items['budget_ytd']['vending_revenue'])?$items['budget_ytd']['vending_revenue']:0;  
                $result['budget_ytd']['hotel_revenue_total'] += isset($items['budget_ytd']['hotel_revenue_total'])?$items['budget_ytd']['hotel_revenue_total']:0; 
                
            }
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
            
            $result['this_month_ytd']['occupancy_rate'] = round($result['this_month_ytd']['total_room_occ']/$result['this_month_ytd']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['this_month_ytd']['average_room_rate'] = ($result['this_month_ytd']['room_soild']!=0)?round($result['this_month_ytd']['room_revenue']/$result['this_month_ytd']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['this_month_ytd']['rev_par'] = round($result['this_month_ytd']['room_revenue']/$result['this_month_ytd']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['this_month_ytd']['spend_per_guest'] = ($result['this_month_ytd']['adult']!=0)?round($result['this_month_ytd']['hotel_revenue_total']/$result['this_month_ytd']['adult']):$result['this_month_ytd']['hotel_revenue_total'];
            $result['this_month_ytd']['room_revenue_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['room_revenue']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['bar_revenue_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['bar_revenue']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['total_telephone_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['total_telephone']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['laundry_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['laundry']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['minibar_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['minibar']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['transport_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['transport']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['spa_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['spa']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['service_other_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['service_other']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            $result['this_month_ytd']['shop_percent'] = ($result['this_month_ytd']['hotel_revenue_total']!=0)?round($result['this_month_ytd']['shop']/$result['this_month_ytd']['hotel_revenue_total']*100,2):0;
            
            $result['last_year_current']['occupancy_rate'] = round($result['last_year_current']['total_room_occ']/$result['last_year_current']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['last_year_current']['average_room_rate'] = ($result['last_year_current']['room_soild']!=0)?round($result['last_year_current']['room_revenue']/$result['last_year_current']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['last_year_current']['rev_par'] = round($result['last_year_current']['room_revenue']/$result['last_year_current']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['last_year_current']['spend_per_guest'] = ($result['last_year_current']['adult']!=0)?round($result['last_year_current']['hotel_revenue_total']/$result['last_year_current']['adult']):$result['last_year_current']['hotel_revenue_total'];
            $result['last_year_current']['room_revenue_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['room_revenue']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['bar_revenue_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['bar_revenue']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['total_telephone_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['total_telephone']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['laundry_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['laundry']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['minibar_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['minibar']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['transport_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['transport']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['spa_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['spa']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['service_other_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['service_other']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            $result['last_year_current']['shop_percent'] = ($result['last_year_current']['hotel_revenue_total']!=0)?round($result['last_year_current']['shop']/$result['last_year_current']['hotel_revenue_total']*100,2):0;
            
            $result['last_year_ytd']['occupancy_rate'] = round($result['last_year_ytd']['total_room_occ']/$result['last_year_ytd']['room_available_for_sale']*100,2); /** Cong suat phong **/
            $result['last_year_ytd']['average_room_rate'] = ($result['last_year_ytd']['room_soild']!=0)?round($result['last_year_ytd']['room_revenue']/$result['last_year_ytd']['room_soild']):0; /** Gia phong binh quan tren so phong ban dc **/
            $result['last_year_ytd']['rev_par'] = round($result['last_year_ytd']['room_revenue']/$result['last_year_ytd']['room_available_for_sale']); /** Gia phong binh quan tren so phong co san **/
            $result['last_year_ytd']['spend_per_guest'] = ($result['last_year_ytd']['adult']!=0)?round($result['last_year_ytd']['hotel_revenue_total']/$result['last_year_ytd']['adult']):$result['last_year_ytd']['hotel_revenue_total'];
            $result['last_year_ytd']['room_revenue_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['room_revenue']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['bar_revenue_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['bar_revenue']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['total_telephone_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['total_telephone']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['laundry_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['laundry']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['minibar_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['minibar']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['transport_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['transport']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['spa_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['spa']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['service_other_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['service_other']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            $result['last_year_ytd']['shop_percent'] = ($result['last_year_ytd']['hotel_revenue_total']!=0)?round($result['last_year_ytd']['shop']/$result['last_year_ytd']['hotel_revenue_total']*100,2):0;
            
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
            
            $result['budget_ytd']['occupancy_rate'] = ($result['budget_current']['rooms_available_for_sale']!=0)?round($result['budget_current']['total_rooms_occupied']/$result['budget_current']['rooms_available_for_sale']*100):0;
            $result['budget_ytd']['average_room_rate'] = ($result['budget_current']['rooms_sold']!=0)?round($result['budget_current']['room_revenue']/$result['budget_current']['rooms_sold']):0;
            $result['budget_ytd']['rev_par'] = ($result['budget_current']['rooms_available_for_sale']!=0)?round($result['budget_current']['room_revenue']/$result['budget_current']['rooms_available_for_sale']):0;
            $result['budget_ytd']['spend_per_guest'] = ($result['budget_current']['no_of_guests']!=0)?round($result['budget_current']['hotel_revenue_total']/$result['budget_current']['no_of_guests']):0;
            $result['budget_ytd']['room_revenue_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['room_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['bar_revenue_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['bar_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['total_telephone_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['telephone_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['laundry_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['laundry_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['minibar_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['minibar_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['transport_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['transport_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['spa_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['spa_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['service_other_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['others_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
            $result['budget_ytd']['shop_percent'] = ($result['budget_current']['hotel_revenue_total']!=0)?round($result['budget_current']['vending_revenue']/$result['budget_current']['hotel_revenue_total']*100,2):0;
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
                'page' => 'revenue_summary_report_api',
                'secretkey' => '9a8fa234b2520e9bb4f59d8178545a62',
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
        $month_current = $result['month_current'];
        $this_month_ytd=$result['this_month_ytd']; 
        $last_year_current=$result['last_year_current'];
        $last_year_ytd=$result['last_year_ytd'];
        $budget_current=$result['budget_current'];
        $budget_ytd=$result['budget_ytd'];
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
        $month_ytd_pie = array(0=>array(
                                        'name'=>Portal::language('room_revenue')
                                        ,'y'=>isset($this_month_ytd['room_revenue'])?round($this_month_ytd['room_revenue']):0
                                  ),
                                  1=>array(
                                        'name'=>Portal::language('bar_revenue')
                                        ,'y'=>isset($this_month_ytd['bar_revenue'])?round($this_month_ytd['bar_revenue']):0
                                  ),
                                  2=>array(
                                        'name'=>Portal::language('telephone_revenue')
                                        ,'y'=>isset($this_month_ytd['total_telephone'])?round($this_month_ytd['total_telephone']):0
                                  ),
                                  3=>array(
                                        'name'=>Portal::language('laundry_revenue')
                                        ,'y'=>isset($this_month_ytd['laundry'])?round($this_month_ytd['laundry']):0
                                  ),
                                  4=>array(
                                        'name'=>Portal::language('minibar_revenue')
                                        ,'y'=>isset($this_month_ytd['minibar'])?round($this_month_ytd['minibar']):0
                                  ),
                                  5=>array(
                                        'name'=>Portal::language('transport_revenue')
                                        ,'y'=>isset($this_month_ytd['transport'])?round($this_month_ytd['transport']):0
                                  ),
                                  6=>array(
                                        'name'=>Portal::language('spa_revenue')
                                        ,'y'=>isset($this_month_ytd['spa'])?round($this_month_ytd['spa']):0
                                  ),
                                  7=>array(
                                        'name'=>Portal::language('others_revenue')
                                        ,'y'=>isset($this_month_ytd['service_other'])?round($this_month_ytd['service_other']):0
                                  ),
                                  8=>array(
                                        'name'=>Portal::language('vending_revenue')
                                        ,'y'=>isset($this_month_ytd['shop'])?round($this_month_ytd['shop']):0
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
                                                'month_current'=>$month_current,
                                                'last_year_current'=>$last_year_current,
                                                'this_month_ytd'=>$this_month_ytd,
                                                'last_year_ytd'=>$last_year_ytd,
                                                'budget_current'=>$budget_current,
                                                'budget_ytd'=>$budget_ytd,
                                                'month_current_pie_js'=>json_encode($month_current_pie),
                                                'month_ytd_pie_js'=>json_encode($month_ytd_pie)
                ));	
    }
 
}
?>
