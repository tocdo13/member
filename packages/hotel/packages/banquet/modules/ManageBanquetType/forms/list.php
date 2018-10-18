<?php
class ManageBanquetTypeForm extends Form
{
	function ManageBanquetTypeForm()
	{
		Form::Form('ManageBanquetTypeForm');
		$this->add('bar.name',new TextType(true,'name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	
	}
	function on_submit()
	{
		if($this->check()){		
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete('party_type','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['party_type']))
			{	
				foreach($_REQUEST['party_type'] as $key=>$record)
				{
					if($record['name'] == '')
					{
						echo "<script> alert 'Tên không được để trống ' </script>"; 
						
					}
					else if($record['id'] and DB::exists_id('party_type',$record['id']))//neu bang do ton tai thi chi cap nhat thoi.
					{
						DB::update('party_type', $values=array('name'=>$record['name'],'note'=>$record['note']),'id='.$record['code']);
					}
					else
					{ 
						unset($record['no']);
						unset($record['id']);
						$values=array('name'=>$record['name'],'note'=>$record['note']);
						DB::insert('party_type',$values);
					}
				}
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		if(!isset($_REQUEST['party_type'])){
			$cond = ' 1>0 ';
			$sql='SELECT id, id as code,name,note FROM party_type ORDER BY id';
			$party_type = DB::fetch_all($sql);
			$i=1;
			foreach($party_type as $key => $value){
				$party_type[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['party_type'] = $party_type;
		}
		$this->parse_layout('list');
	}
	
}