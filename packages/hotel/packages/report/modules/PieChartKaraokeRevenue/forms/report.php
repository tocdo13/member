<?php
class PieChartRoomRevenueReportForm extends Form{
	function PieChartRoomRevenueReportForm(){
		Form::Form('PieChartRoomRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
    
    function get_category_by_level($categories,$level,$accept='')
	{
        $result_struct = array();
        foreach($categories as $key => $value)
        {
            $struct_level = $value['structure_id'];
            while(IDStructure::level($struct_level)>$level)
            {
                $struct_level = IDStructure::parent($struct_level);
            }
            if(!isset($result_struct[$struct_level]) and $struct_level!= $accept)
            {
                $result_struct[$struct_level] = array('struct'=>$struct_level);
            }
        }
        
        $cond_struct = '';
        foreach($result_struct as $key=>$value)
            $cond_struct.=",".$value['struct'];
        $cond_struct = $cond_struct?" product_category.structure_id in (".substr($cond_struct,1,strlen($cond_struct)-1).") ":" 1=1 ";
        
        $categories = DB::fetch_all("SELECT
        					product_category.id
        					,product_category.name
        					,product_category.structure_id
                            ,0 as amount_total
        				FROM
        					product_category
        				WHERE ".$cond_struct."
        				ORDER BY product_category.position");
        return $categories;
    }
    
	function get_data(){
		$_REQUEST['from_date'] = Url::get("from_date",date('01/m/Y'));
        $_REQUEST['to_date'] = Url::get("to_date",date('d/m/Y'));
              
        $from_time = Date_Time::to_time($_REQUEST['from_date']);     
        $to_time = Date_Time::to_time($_REQUEST['to_date']); 
                
        /***get categories***/
        //get cond department
        $cond_portal_d = Url::get('portal_id')?" and portal_id = '".Url::get('portal_id')."'":"";
        $dp_code = DB::fetch_all("select department_id as id from karaoke where 1=1 ".$cond_portal_d);
        $str_dp_codes='';
        foreach($dp_code as $key=>$value)
        {
            $str_dp_codes.=",'".$value['id']."'";
        }
                
        $cond_dep_cate = $str_dp_codes?" and product_price_list.department_code in (".substr($str_dp_codes,1,strlen($str_dp_codes)-1).")":"";
        $cond_portal_cate = Url::get('portal_id')?" and product_price_list.portal_id = '".Url::get('portal_id')."'":"";
        //get cond structure_id
        require_once 'packages/core/includes/system/id_structure.php';
        $ROOT_ID = "1000000000000000000";
        
        $cond_struct_cate = "(".IDStructure::child_cond($ROOT_ID,1,"product_category.").")";
                
        //categories level = DA
        $categories = DB::fetch_all("SELECT
        					product_category.id
        					,product_category.name
        					,product_category.structure_id
        				FROM
        					product_category
        					INNER JOIN product ON product_category.id = product.category_id
        					INNER JOIN product_price_list ON product_price_list.product_id = product.id
        					INNER JOIN unit ON unit.id = product.unit_id
        				WHERE ".$cond_struct_cate.$cond_dep_cate.$cond_portal_cate."
        				ORDER BY product_category.position");
        //categories child of DA
        $categories = $this->get_category_by_level($categories,1);
        
        /***get items***/
        $cond_portal_brp = Url::get('portal_id')?" and karaoke_reser.portal_id = '".Url::get('portal_id')."'":"";
                
        $karaoke_rp = DB::fetch_all("select karaoke_reservation_product.*,
                                            karaoke_reser.tax_rate,
                                            karaoke_reser.karaoke_fee_rate,
                                            product_category.structure_id,
                                            karaoke_reser.num_guest,
                                            karaoke_reser.id as karaoke_reservation_id,
                                            karaoke_reser.code,
                                            karaoke_reser.karaoke_id,
                                            traveller.first_name || traveller.last_name as agent_name,
                                            karaoke_reser.time_out as day,
                                            case
                                                when karaoke_reser.full_rate = 1
                                                then karaoke_reservation_product.quantity*karaoke_reservation_product.price*(1-karaoke_reser.discount_percent/100)
                                                else(
                                                    case 
                                                        when karaoke_reser.full_charge = 1
                                                        then karaoke_reservation_product.quantity*karaoke_reservation_product.price*(1+karaoke_reser.tax_rate/100)*(1-karaoke_reser.discount_percent/100)
                                                        else  (karaoke_reservation_product.quantity*karaoke_reservation_product.price*(1+karaoke_reser.karaoke_fee_rate/100))*(1+karaoke_reser.tax_rate/100)*(1-karaoke_reser.discount_percent/100)
                                                    end
                                                )
                                            end as total_amount
                                        from karaoke_reservation_product
                                            inner join (
                                                select sum(num_people) as num_guest,
                                                    karaoke_reservation.id,
                                                    karaoke_reservation.code,
                                                    karaoke_reservation.karaoke_id,
                                                    karaoke_reservation.agent_name,
                                                    karaoke_reservation.time_out,
                                                    karaoke_reservation.tax_rate,
                                                    karaoke_reservation.discount_percent,
                                                    karaoke_reservation.karaoke_fee_rate,
                                                    karaoke_reservation.full_rate,
                                                    karaoke_reservation.full_charge,
                                                    karaoke_reservation.reservation_traveller_id,
                                                    karaoke_reservation.portal_id
                                                from karaoke_reservation
                                                  inner join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
                                                where karaoke_reservation.status != 'CANCEL' 
                                                group by (karaoke_reservation.id,karaoke_reservation.code,karaoke_reservation.karaoke_id,karaoke_reservation.agent_name,karaoke_reservation.time_out,karaoke_reservation.tax_rate,karaoke_reservation.karaoke_fee_rate,karaoke_reservation.portal_id,karaoke_reservation.status,karaoke_reservation.discount_percent,karaoke_reservation.full_rate,karaoke_reservation.reservation_traveller_id,karaoke_reservation.full_charge)
                                              ) karaoke_reser 
                                                    on (karaoke_reser.id = karaoke_reservation_product.karaoke_reservation_id
                                                    and karaoke_reser.time_out >= ".$from_time." 
                                                    and karaoke_reser.time_out <= ".$to_time.$cond_portal_brp.")
                                            left outer join reservation_traveller on reservation_traveller.id = karaoke_reser.reservation_traveller_id
                                            left outer join traveller on traveller.id = reservation_traveller.traveller_id
                                            inner join product on product.id = karaoke_reservation_product.product_id
                                            inner join product_category on product_category.id = product.category_id
                                        where karaoke_reser.time_out >= ".$from_time." 
                                            and karaoke_reser.time_out <= ".$to_time.$cond_portal_brp.
                                        " order by karaoke_reser.time_out desc");
                
        foreach($karaoke_rp as $key => $value)
        {
            foreach($categories as $k=>$v)
            {
                if(IDStructure::is_child($value['structure_id'],$v['structure_id']) or $value['structure_id'] == $v['structure_id'])
                {
                    $categories[$k]['amount_total'] += $value['total_amount'];
                }
            }
        }
        
        return $categories;      
	}
	function draw(){
		 $amount_karaoke = $this->get_data();
         //System::debug($amount_karaoke);
         //total
         $total = 0;
         foreach($amount_karaoke as $key => $value)
         {
            $total += $value['amount_total'];
         }
         
         $stt = 1;
         foreach($amount_karaoke as $key => $value)
         {
            $amount_karaoke[$key]['stt'] = $stt++;
            $amount_karaoke[$key]['percent'] = $value['amount_total']/$total*100;
         }
         //System::debug($amount_karaoke);
         $js_amount_room = String::array2js($amount_karaoke);
		 $this->parse_layout('report',array('items'=>$js_amount_room,'datas'=>$amount_karaoke,'total'=>$total));
	}
}
?>