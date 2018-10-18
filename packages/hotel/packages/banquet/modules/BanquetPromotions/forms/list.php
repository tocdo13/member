<?php
class BanquetPromotionsList extends Form
{
	function BanquetPromotionsList()
	{
		Form::Form('BanquetPromotionsList');
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
					DB::delete('party_promotions','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['party_promotions']))
			{	
				foreach($_REQUEST['party_promotions'] as $key=>$record)
				{
					if($record['name'] == '')
					{
						echo "<script> alert 'Tên không được để trống ' </script>"; 
						
					}
					else if($record['id'] and DB::exists_id('party_promotions',$record['id']))//neu bang do ton tai thi chi cap nhat thoi.
					{
						DB::update('party_promotions', $values=array('party_type_id'=>$record['party_type_id'],'name'=>$record['name'],'note'=>$record['note']),'id='.$record['code']);
					}
					else
					{ 
						unset($record['no']);
						unset($record['id']);
						$values=array('name'=>$record['name'],'note'=>$record['note'],'party_type_id'=>$record['party_type_id']);
						DB::insert('party_promotions',$values);
					}
				}
			}
			Url::redirect_current();
		}
	}
	function draw()
	{	        	   
	   $items_exist = DB::fetch_all('select party_promotions.*,party_promotions.id as code,0 as exist from party_promotions order by party_promotions.id');       
       $items_promotion = DB::fetch_all('select * from party_reservation');
       foreach($items_promotion as $key=>$value)
       {
            $promotion = explode(' ',$value['promotions']);            
            foreach($promotion as $k=>$v)
            {                
                if(isset($items_exist[$promotion[$k]]))
                {
                    $items_exist[$promotion[$k]]['exist'] = 1;
                }
            }
       }              
		if(!isset($_REQUEST['party_promotions']))
        {
			$cond = ' 1>0 ';
			$_REQUEST['party_promotions'] = $items_exist;
		}
        $party_type = DB::select_all('party_type');
		$party_type_options = '<option value="">'.Portal::language('choose_party_promotions').'</option>';
		foreach($party_type as $key=>$value)
		{
			$party_type_options.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        $this->map['items_exist'] = $items_exist;
        $this->map['promotion_type'] = $party_type_options;
		$this->parse_layout('list',$this->map);
	}
	
}
?>