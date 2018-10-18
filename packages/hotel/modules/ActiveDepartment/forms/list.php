<?php
class ListActiveDepartmentForm extends Form
{
    function ListActiveDepartmentForm()
    {
        Form::Form('ListActiveDepartmentForm');
    }
    
    function draw()
    {
        $this->map['portal'] = Portal::get_portal_list();
        
        //System::debug($this->map['portals']);
        foreach($this->map['portal'] as $key=>$value)
        {
            $this->map['portal'][$key]['department'] = DB::fetch_all('Select 
                                                        department.code as id,
                                                        department.name_'.Portal::language().' as name 
                                                    from 
                                                        department 
                                                        inner join portal_department on department.code = portal_department.department_code 
                                                    where
                                                        portal_department.portal_id = \''.$value['id'].'\'
                                                        and department.parent_id = 0
                                                ');
        }
        //System::debug($this->map['portals']);
        $this->parse_layout('list', $this->map);
    }
}

?>