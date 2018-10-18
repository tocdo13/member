<?php
class ActiveDepartmentDB
{
    function select_all_department()
    {
        return DB::fetch_all('  Select
                                    department.id, 
                                    department.code,
                                    department.name_1,
                                    department.name_2,
                                    department.portal_id,
                                    party.name_1 as portal_name
                                from 
                                    department inner join party on department.portal_id = party.user_id 
                                where 
                                    party.type = \'PORTAL\' 
                                order by 
                                    department.portal_id ');
    }
    
    function get_warehouse($portal_id)
    {
    	return DB::fetch_all('
    			select 
    				warehouse.*
    			from 
    			 	warehouse
    			where
                    portal_id = \''.$portal_id.'\'
                    and structure_id !='.ID_ROOT.'
    			order by 
    				warehouse.structure_id
    		',false);
    }
    
    
    
}

?>