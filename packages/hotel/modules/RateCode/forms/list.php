<?php
class ListRateCodeForm extends Form
{
	function ListRateCodeForm()
	{
		Form::Form('ListRateCodeForm');
		
	}
	function draw()
	{
		$this->map = array();
        $this->load_data();
        //System::debug($this->map['items']);
		$this->parse_layout('list',$this->map);
	}
    
    function load_data()
    {
        $sql ="SELECT rate_code.id || '_' || rate_customer_group.id as id,
                    rate_code.code,
                    rate_code.name,
                    rate_code.start_date,
                    rate_code.end_date,
                    rate_code.date_level,
                    rate_code.frequence,
                    rate_code.weekly,
                    rate_customer_group.customer_group_id
                    
                FROM rate_code
                LEFT JOIN rate_customer_group ON rate_customer_group.rate_code_id=rate_code.id
                ORDER BY rate_code.code
                ";
        $items = DB::fetch_all($sql);
        $i = 1;
        $rate_code_id = false;
        $rate_code_old = false;
        $result = array();
        
        foreach($items as $key=>$value)
        {
            $arr_ids = explode("_",$value['id']);
            if($rate_code_id!=$arr_ids[0])
            {
                $rate_code_old = $rate_code_id;
                $rate_code_id = $arr_ids[0];
                $result[$rate_code_id]['id'] = $rate_code_id;
                $result[$rate_code_id]['index'] =$i++; 
                $result[$rate_code_id]['code'] = $value['code'];
                $result[$rate_code_id]['name'] = $value['name'];
                $result[$rate_code_id]['frequence'] = $value['frequence'];
                $result[$rate_code_id]['date_level'] = $value['date_level'];
                $result[$rate_code_id]['start_date'] = Date_Time::convert_orc_date_to_date($value['start_date'],"/");
                $result[$rate_code_id]['end_date'] = Date_Time::convert_orc_date_to_date($value['end_date'],"/");
                
                if($value['weekly']!='')
                {
                   $arr_weeks = explode(",",$value['weekly']);
                   foreach($arr_weeks as $k=>$r)
                   {
                        if($r!=8)
                            $arr_weeks[$k] = 'T'.$r;
                        else
                            $arr_weeks[$k] ='CN';
                   }
                   $weekly = implode($arr_weeks,',');
                   $result[$rate_code_id]['weekly'] = '('.$weekly.')';
                }
                if($value['customer_group_id']!='')
                {
                    $result[$rate_code_id]['customer_groups'] =$value['customer_group_id'].', ';
                }
                if($rate_code_old!=false)
                {
                    if(isset($result[$rate_code_old]['customer_groups']))
                        $result[$rate_code_old]['customer_groups'] = substr($result[$rate_code_old]['customer_groups'],0,strlen($result[$rate_code_old]['customer_groups'])-2);
                    
                }
            }
            else
            {
                if($value['customer_group_id']!='')
                {
                    $result[$rate_code_id]['customer_groups'] .=$value['customer_group_id'].', ';
                }
            }
        }
        //System::debug($result);
        if($rate_code_id!=false)
        {
            if(isset($result[$rate_code_id]['customer_groups']))
                $result[$rate_code_id]['customer_groups'] = substr($result[$rate_code_id]['customer_groups'],0,strlen($result[$rate_code_id]['customer_groups'])-2);
            
        }
        
        $this->map['items']  = $result;
    }
}
?>