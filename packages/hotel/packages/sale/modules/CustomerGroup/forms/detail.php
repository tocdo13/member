<?php
class CustomerGroupForm extends Form
{
	function CustomerGroupForm()
	{
		Form::Form('CustomerGroupForm');
		$this->add('id',new IDType(true,'object_not_exists','customer_group'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm'))
		{ 
		     $delete_result=0;
             if(!$delete_result=$this->delete($this,$_REQUEST['id'])){
                $delete_result=1;
             } 
			Url::redirect_current(array('name'=>isset($_GET['name'])?$_GET['name']:'','status'=>$delete_result ));
		}
	}
	function draw()
	{
		DB::query('
			select 
				customer_group.id
				,customer_group.structure_id
				,customer_group.name 
			from 
			 	customer_group
			where
				customer_group.id = \''.URL::sget('id').'\'');
		$row = DB::fetch();
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		$row = DB::select('customer_group',$id);
        if(DB::exists('select * from customer where group_id=\''.$id.'\'')){
            return false;
        }
        else
        {
           DB::delete('customer_group','id=\''.$id.'\'');
           return true; 
        }	
	}
}
?>