<?php
class ManageDepartmentDB
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
    
    
    
}

?>