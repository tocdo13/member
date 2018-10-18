<?php
class ListRestaurantProductForm extends Form
{
	function ListRestaurantProductForm()
	{
		Form::Form('ListRestaurantProductForm');
		$this->link_css('skins/default/restaurant.css');		
	}
	function on_submit()
	{
		Url::redirect();
	}
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$this->map =  array();
		$languages = DB::select_all('language');
		$action='';
		if(Url::check('action'))
		{
			$action=Url::get('action');
		}
		if(is_array(URL::get('selected_ids')))
		{
			$_REQUEST['selected_ids'] = implode(',',URL::get('selected_ids'));
		}
		$cond = URL::get('selected_ids')?(strpos(URL::get('selected_ids'),',')?' res_product.id in (\''.str_replace(',','\',\'',URL::get('selected_ids')).'")':' res_product.id=\''.URL::get('selected_ids').'\''):
				' 1>0 and res_product.portal_id=\''.PORTAL_ID.'\''
				.((URL::get('category_id')and URL::get('category_id')!=1)?'
					and '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'
				':'')  
				.(URL::get('type')?' and UPPER(res_product.type)=\''.strtoupper(addslashes(URL::get('type'))).'\'':'')  
				.(URL::get('code')?' and UPPER(res_product.code) LIKE \'%'.strtoupper(addslashes(URL::get('code'))).'%\'':'')
				.(URL::get('name')?' and UPPER(res_product.name_'.Portal::language().') LIKE \'%'.strtoupper(addslashes(URL::get('name'))).'%\'':'');
		$item_per_page = 200;
		//.((URL::get('bar_id') and Url::get('page')=='restaurant_product')?' and res_product.bar_id='.intval(Url::sget('bar_id')):'')
		/*
			<tr>
    	<td>
        <div class="bar-id">
            <label for="bar_id">[[.Bar_name.]]: </label>
            <?php if(User::can_admin(MODULE_RESTAURANTPRODUCT,ANY_CATEGORY)){?>
            <select name="bar_id" id="bar_id" onchange="window.location='<?php echo Url::build('restaurant_product',array('cmd'))?>'+'&bar_id='+this.value"></select>
            <?php }else{?>
            <span>[[|bar_name|]]</span>
            <?php }?>
        </div>        
        </td>
    </tr>
		*/
		DB::query('
			select 
				count(*) as acount
			from 
				res_product
				left outer join product_category on product_category.id = res_product.category_id
			where 
				'.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] = $count['acount'];
		$paging = paging($count['acount'],$item_per_page,5,false,'page_no',array('type'));
		DB::query('
		select * from(
				select 
					res_product.id
					,res_product.code
					,res_product.price
					,res_product.status
					,res_product.type 
					,res_product.name_'.Portal::language().' as name 
					,product_category.name as category_id 
					,unit.name_'.Portal::language().' as unit_id
					,row_number() over (order by upper(product_category.name),upper(res_product.code)) as rownumber
				from 
					res_product
					left outer join product_category on res_product.category_id = product_category.id
					left outer join unit on res_product.unit_id = unit.id
				where 
					'.$cond.'
					
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();

		$category_id_list = String::get_list(RestaurantProductDB::get_categories('1>0'));  
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			$items[$key]['price'] = System::display_number_report($value['price']);
			if($this->check_product_delete($key))
			{
				$items[$key]['can_delete'] = false;
			}
			else
			{
				$items[$key]['can_delete'] = true;
			}
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$rows_list = Hotel::get_bar();
		$list_bar[''] = '-------';
		$list_bar = $list_bar+String::get_list($rows_list,'name');
		if(Url::get('bar_id'))
		{
			$bar = DB::select('bar','id='.intval(Url::get('bar_id')));
		}
		
		$this->parse_layout('list',$just_edited_id+$this->map+
			array(
				'items'=>$items,
				'bar_id_list'=>$list_bar,
				'bar_name'=>isset($bar['name'])?$bar['name']:'',
				'paging'=>$paging,
				'category_id_list' => $category_id_list,
				'category_id' => URL::get('category_id',''),
				'action'=>$action
				
			)
		);
	}
	function check_product_delete($id)
	{
	}
}
?>