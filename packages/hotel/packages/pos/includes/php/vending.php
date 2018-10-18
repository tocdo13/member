<?php
function get_area_vending($cond='1=1',$privilege=true,$dept_id = false)
{
    if($dept_id)
    {
        $cond .= ' and department.id != '.$dept_id;
    }
    if($privilege)
	{
		$cond.= get_privilege_xxx();
	}
    $bars = DB::fetch_all('
    	SELECT
            department.id,
            department.code,
            department.name_'.Portal::language().' as name,
            portal_department.warehouse_id,
            department.id as bar_id_from
    	FROM
            department
            inner join portal_department on department.code = portal_department.department_code and portal_department.portal_id = \''.PORTAL_ID.'\'
    	WHERE
    		'.$cond.' AND department.parent_id = (select id from department where code = \'VENDING\')');
    return $bars;
    
}

function get_privilege_xxx()
{
	$areas = DB::fetch_all('SELECT 
                                    department.id,
                                    department.code,
                                    portal_department.privilege 
                                FROM 
                                    portal_department
                                    inner join department on department.code = portal_department.department_code and  portal_department.portal_id = \''.PORTAL_ID.'\' 
                                WHERE 
                                    department.parent_id = (Select id from department where code = \'VENDING\')'
                            );
	if(!User::is_admin())
    {
		$cond = ' and (';
		$i = 1;
		foreach($areas as $key=>$value)
        {
			if(User::can_edit(Portal::get_module_id($value['privilege']),ANY_CATEGORY))
            {
				if($i == 1)
                {
					$cond .= ' department.id = '.$key.'';
				}
                else
                {
					$cond .= ' OR department.id = '.$key.'';
				}
				$i++;
			}
		}
		$cond .= ')';
		if($i>1)
        {
			return $cond;
		}
        else
        {
			return false;
		}
		return $cond;
	}
    else
    {
		return false;	
	}
}
?>