<?php
class ListKaraokeCatalogueForm extends Form
{
	function ListKaraokeCatalogueForm()
	{
		Form::Form('ListKaraokeCatalogueForm');
		$this->link_css('skins/default/karaoke.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function draw()
	{
		$this->map = array();
		$this->map['from_arrival_time'] = Url::get('from_arrival_time')?Url::get('from_arrival_time'):date('d/m/Y');		
		$this->map['to_arrival_time'] = Url::get('to_arrival_time')?Url::get('to_arrival_time'):date('d/m/Y');
		$_REQUEST['from_arrival_time'] = $this->map['from_arrival_time'];
		$_REQUEST['to_arrival_time'] = $this->map['to_arrival_time'];
		require_once 'packages/hotel/includes/php/hotel.php';
		$languages = DB::select_all('language');
		$action='';
		if(Url::check('action'))
		{
			$action=Url::get('action');
		}
        
        if(Url::get('start_time'))
        {
            $shift_from = $this->calc_time(Url::get('start_time'));
            $this->map['start_shift_time'] = Url::get('start_time');
        }
        else
        {
            $shift_from = $this->calc_time('00:00');
            $this->map['start_shift_time'] = '00:00';
        }
        $_REQUEST['start_time'] = $this->map['start_shift_time'];
        if(Url::get('end_time'))
        {
            $shift_to = $this->calc_time(Url::get('end_time'));
            $this->map['end_shift_time'] = Url::get('end_time'); 
        }
        else
        {
            $shift_to = $this->calc_time('23:59');
            $this->map['end_shift_time'] = '23:59'; 
        }
        $_REQUEST['end_time'] = $this->map['end_shift_time'];
        
		$cond = ' 
            karaoke_reservation.portal_id=\''.PORTAL_ID.'\' 
            and 
			(    karaoke_reservation.arrival_time>='.( Date_Time::to_time($this->map['from_arrival_time']) + $shift_from  ).'
                 and karaoke_reservation.arrival_time<'.( Date_Time::to_time($this->map['to_arrival_time'])+ $shift_to  ).'
            )'
			.((URL::get('category_id')and URL::get('category_id')!=1)?' AND product.category_id = '.URL::get('category_id'):'')  
			.(URL::get('code')?' AND UPPER(product.id) LIKE \''.strtoupper(addslashes(URL::get('code'))).'%\'':'')
			.(URL::get('name')?' AND UPPER(product.name_'.Portal::language().') LIKE \'%'.strtoupper(addslashes(URL::get('name'))).'%\'':'')
		;//product.portal_id=\''.PORTAL_ID.'\'
        //.((URL::get('category_id')and URL::get('category_id')!=1)?' AND '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'':'')
		//System::debug($cond);
        $item_per_page = 2000;
		$sql = '
		SELECT * 
			FROM(
				SELECT
					product.id ||\'-\'|| karaoke_reservation_product.name as id
					,SUM(karaoke_reservation_product.quantity - karaoke_reservation_product.remain - karaoke_reservation_product.quantity_cancel) AS quantity
				FROM 
					karaoke_reservation_product
					INNER JOIN product ON product.id = karaoke_reservation_product.product_id
                    LEFT JOIN product_category ON product_category.id = product.category_id
					INNER JOIN karaoke_reservation ON karaoke_reservation.id = karaoke_reservation_product.karaoke_reservation_id
					INNER JOIN unit on product.unit_id = unit.id
				WHERE
					'.$cond.'
				GROUP BY
					product.id,
                    karaoke_reservation_product.name
				ORDER BY
					'.((Url::get('order_by')=='quantity')?' SUM(karaoke_reservation_product.quantity) DESC':' product.id').'
			)
			WHERE
				1=1
		';
		$items = DB::fetch_all($sql);
		$this->map['total'] = sizeof($items);
		$category_id_list = String::get_list(KaraokeCatalogueDB::get_categories('1>0'));  
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			$product = DB::fetch('
				SELECT
					product.id ||\'-\'|| karaoke_reservation_product.name as id
					,product_price_list.price
                    ,karaoke_reservation_product.name as name 
					,unit.name_'.Portal::language().' AS unit_name
				FROM 
					karaoke_reservation_product
					INNER JOIN product ON product.id = karaoke_reservation_product.product_id
					inner join product_price_list ON product_price_list.product_id = product.id
					INNER JOIN karaoke_reservation ON karaoke_reservation.id = karaoke_reservation_product.karaoke_reservation_id
					LEFT OUTER JOIN product_category on product.category_id = product_category.id
					LEFT OUTER JOIN unit on product.unit_id = unit.id
				WHERE
					product.id ||\'-\'|| karaoke_reservation_product.name = \''.$value['id'].'\'
			');
			$items[$key]['name'] = $product['name'];
			$items[$key]['unit_name'] = $product['unit_name'];
			$items[$key]['price'] = System::display_number($product['price']);
			$items[$key]['quantity'] = System::display_number($value['quantity']);
            if( $pos = strpos( $value['id'],'-' ) )
                $items[$key]['id'] = substr($value['id'],0,$pos);
		}
		$this->map['order_by_list'] =  array(
			'code'=>Portal::language('code'),
			'quantity'=>Portal::language('quantity')
		);
		$this->parse_layout('list',$this->map+
			array(
				'items'=>$items,
				'category_id_list' => $category_id_list,
				'category_id' => URL::get('category_id',''),
				'action'=>$action
				
			)
		);
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>