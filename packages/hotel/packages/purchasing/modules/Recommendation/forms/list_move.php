<?php
class ListMoveForm extends Form
{
    function ListMoveForm()
    {
        Form::Form('ListMoveForm');
        
    }
    function draw()
    {  
       $sql = "SELECT pc_recommendation.*,department.name_1 as department
                FROM pc_recommendation
                INNER JOIN portal_department ON portal_department.id=pc_recommendation.portal_department_id
                INNER JOIN department ON portal_department.department_code=department.code
                WHERE pc_recommendation.status='MOVE'
                ORDER BY pc_recommendation.recommend_time desc";
        $items = DB::fetch_all($sql);
        
        $i = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $i++;
            $items[$key]['recommend_date']  = date('d/m/Y H:i',$value['recommend_time']);        
        }
        $this->map['items'] = $items;
        
        $this->parse_layout('list_move',$this->map);
        
    }   
}
?>