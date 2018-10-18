<?php
class DeclareCounterForm extends Form
{    
	function DeclareCounterForm()
	{
		Form::Form('DeclareCounterForm');	
	}
	function on_submit()
	{
        if(Url::get('Save') == 'Save')
        {
            foreach($_REQUEST['vending'] as $key => $value)
            {
                $recode = array(
                            'department_id' => $value['department_id'],
                            'print_kitchen_name' => $value['print_kitchen_name'],
                            'print_bar_name' => $value['print_bar_name']
                );
                if($value['id'] == '')
                {
                    DB::insert('vending_counter', $recode);
                }else
                {
                    DB::update('vending_counter', $recode, 'id='. $value['id']);
                } 
            }            
        }		
	}
	function draw()
    {
		$this->map = array();
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $AreaCounter = get_area_vending();
        $conter = DB::fetch_all('
                            SELECT 
                                vending_counter.department_id as id, 
                                vending_counter.id as vending_counter_id,
                                vending_counter.print_kitchen_name,
                                vending_counter.print_bar_name
                            FROM 
                                vending_counter 
                            ORDER BY 
                                id ASC
        ');
        
        foreach($AreaCounter as $key => $value)
        {
            if(isset($conter[$value['id']]))
            {
                $AreaCounter[$key]['vending_counter_id'] = $conter[$value['id']]['vending_counter_id'];
                $AreaCounter[$key]['print_kitchen_name'] = $conter[$value['id']]['print_kitchen_name'];
                $AreaCounter[$key]['print_bar_name'] = $conter[$value['id']]['print_bar_name'];                
            }else
            {
                $AreaCounter[$key]['vending_counter_id'] = '';
                $AreaCounter[$key]['print_kitchen_name'] = '';
                $AreaCounter[$key]['print_bar_name'] = '';
            }            
        }
        //System::debug($AreaCounter);
        $this->map['items'] = $AreaCounter;
        
        $this->parse_layout('declare_counter', $this->map);
	}
}
?>