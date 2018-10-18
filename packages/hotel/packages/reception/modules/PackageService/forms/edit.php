<?php
class EditExtraServiceInvoiceForm extends Form
{
	function EditExtraServiceInvoiceForm()
	{
		Form::Form('EditExtraServiceInvoiceForm');		
		$this->link_css(Portal::template('hotel').'/css/style.css');
        $this->add('name',new TextType(true,'miss_name',0,255));
		$this->add('code',new TextType(true,'miss_code',0,50));
	}
	function on_submit(){
        if($this->check())
        {	
			$array = array(
				'name',
				'code',
				'price'=>Url::get('price')?str_replace(',','',Url::get('price')):0,
				'unit',
                'department_id',
                'used' 
			);
			if(Url::get('cmd')=='edit')
            {
				$id = Url::iget('id');
				DB::update('package_service',$array,'id='.Url::iget('id'));
            }
            else
            {
                $sql = DB::exists('SELECT * FROM package_service WHERE code = \''.Url::get('code').'\'');
                if($sql > 1)
                {
                    Url::redirect_current();                    
                }else
                {
                    $log_action = 'add';
				    $id = DB::insert('package_service',$array);                    
                }
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
	  
        $this->map=array();
        $this->map['title']='Edit Service';
        $department = DB::fetch_all('select id, name_1 from department where parent_id =0');
        foreach($department as $key=>$val){
            $this->map['department_id_list'][$key]=$val['name_1'];
        }
        if ($_REQUEST['cmd'] == 'edit' and Url::get('id')) {
             $sql = '
                    select 
                        package_service.*,department.name_1 as department_name 
                    from 
                        package_service inner join department on department.id = package_service.department_id
                    where package_service.id = '.Url::get('id').'   
                    ';
              $sql_2 = '
                        select 
                            package_service.*,department.name_1 as department_name 
                        from 
                            package_service inner join department on department.id = package_service.department_id
                        where package_service.id != '.Url::get('id').'   
                        ';   
            $item = DB::fetch($sql);
            if ($item) {
                foreach ($item as $key => $value) {
                    if (!isset($_REQUEST[$key])) {
                        $_REQUEST[strtoupper($key)] = $value;
                       
                    }
                }
            }
            
            $this->map['used']=$item['used'];
        }
        if($_REQUEST['cmd'] == 'add'){
            $sql_2 = '
                        select 
                            package_service.*,department.name_1 as department_name 
                        from 
                            package_service inner join department on department.id = package_service.department_id
                         
                        '; 
            }
        $service_exist = DB::fetch_all($sql_2);
        $_REQUEST['service_exist']= $service_exist;
		$this->parse_layout('edit', $this->map);
	}	
	
}
?>