<?php
class ListExtraServiceInvoiceForm extends Form
{
	function ListExtraServiceInvoiceForm()
	{
		Form::Form('ListExtraServiceInvoiceForm');
        
	}
    function on_submit()
    {
    }
	function draw()
	{  
       $cond = '1=1';
       if(Url::get('keyword')){
            $cond .=''.(Url::get('keyword')?' AND (lower(package_service.name) LIKE \'%'.mb_strtolower(Url::sget('keyword'),'utf-8').'%\' or lower(package_service.code) LIKE \'%'.mb_strtolower(Url::sget('keyword'),'utf-8').'%\')':'').'';
       }
       $sql = '
            select 
                package_service.*,department.name_1 as department_name,
                (CASE 
                    WHEN exists(SELECT * FROM package_sale_detail WHERE package_sale_detail.service_id=package_service.id)
                    THEN 1
                    WHEN package_service.code in (\'RES\',\'LAUNDRY\',\'MINIBAR\',\'ROOM\',\'GIATLA\')
                        THEN 1
                    ELSE 0
                    END ) as can_delete
            from 
                package_service 
                inner join department on department.id = package_service.department_id
            where 
                '.$cond.'    
            ';      
        $items=DB::fetch_all($sql);
        
        
		$this->parse_layout('list',array('items'=>$items,'title'=>Portal::language('list_package')));
        
	}	
}
?>