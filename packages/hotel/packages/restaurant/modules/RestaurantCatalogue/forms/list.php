<?php
class ListRestaurantCatalogueForm extends Form
{
	function ListRestaurantCatalogueForm()
	{
		Form::Form('ListRestaurantCatalogueForm');
		$this->link_css('skins/default/restaurant.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        
	}
	function draw()
	{
		$this->map = array();
        require_once 'packages/core/includes/utils/vn_code.php';
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
            $shift_to = $this->calc_time(Url::get('end_time'))+59;
            $this->map['end_shift_time'] = Url::get('end_time'); 
        }
        else
        {
            $shift_to = $this->calc_time('23:59')+59;
            $this->map['end_shift_time'] = '23:59'; 
        }
        $_REQUEST['end_time'] = $this->map['end_shift_time'];
        //Luu nguyen giap add search for cac nha hang
        //lay ra id cua tat ca nhung nha hang can lay ra
        
        $this->map['total']='';
        $cond ='';
        if(isset($_REQUEST['on_search']))
        {
            //$cond_bar ='';
            if(URL::get('barids'))
            {
               $barids = URL::get('barids');
               $barids =trim($barids,",");
               $_REQUEST['barids'] = $barids;
               
               if($barids!='')
               {
                    //tach chuoi barids
                    $str_bar = explode(',',$barids);
                    //System::debug($str_bar);
                    if(count($str_bar)>0)
                    {
                       // $cond_bar  .='(';
                        $cond .='(';
                    }
                        
                    if(count($str_bar)==1)
                    {
                        $cond .=' bar_reservation.bar_id='.$str_bar[0].')';
                       //$cond_bar .=' bar.id='.$str_bar[0].')';
                    }
                    else
                    {
                        for($i=0;$i<count($str_bar);$i++)
                        {
                            if($i==count($str_bar)-1)
                            {
                                if($str_bar[$i]!='')
                                {
                                    //$cond_bar .=' bar.id='.$str_bar[$i].')';
                                    $cond .=' bar_reservation.bar_id='.$str_bar[$i].')';
                                }
                                    
                            }
                            else
                            {
                                if($str_bar[$i]!='')
                                {
                                    $cond .=' bar_reservation.bar_id='.$str_bar[$i].' or';
                                    //$cond_bar .=' bar.id='.$str_bar[$i].' or';
                                }
                                    
                            }
                        }
                    }  
               } 
            }
            else
            {
                $_REQUEST['barids'] =''; 
            }
            if($cond==''){
                $cond=' 1=1 ';
            }
             //echo '--'.$cond.'--';   
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
               if($portal_id!="ALL")
                {
                    $cond .=" AND  bar_reservation.portal_id ='".$portal_id."' ";
                }
            }
            
            
            //End Luu Nguyen Giap add portal
            $cond .= ' 
                AND (    bar_reservation.arrival_time>='.( Date_Time::to_time($this->map['from_arrival_time']) + $shift_from  ).'
                     and bar_reservation.arrival_time<'.( Date_Time::to_time($this->map['to_arrival_time'])+ $shift_to  ).'
                )'
                .((URL::get('category_id')and URL::get('category_id')!=1)?' AND product.category_id = '.URL::get('category_id'):'')  
                .(URL::get('code')?' AND UPPER(product.id) LIKE \''.strtoupper(addslashes(URL::get('code'))).'%\'':'')
                .(URL::get('name')?' AND (LOWER(FN_CONVERT_TO_VN(product.name_'.Portal::language().')) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('name'),'utf-8')).'%\')':'');
                //UPPER(product.name_'.Portal::language().') LIKE \'%'.$product_name.'%\''
            //product.portal_id=\''.PORTAL_ID.'\'
            //.((URL::get('category_id')and URL::get('category_id')!=1)?' AND '.IDStructure::child_cond(DB::structure_id('product_category', URL::get('category_id',1)),false,'product_category.').'':'')
            }
            else
            {
                if($cond=='')
                $cond =' 1=1  
                AND (    bar_reservation.arrival_time>='.( Date_Time::to_time($this->map['from_arrival_time']) + $shift_from  ).'
                     and bar_reservation.arrival_time<'.( Date_Time::to_time($this->map['to_arrival_time'])+ $shift_to  ).'
                )';
                if(Url::get('portal_id'))
                {
                   $portal_id =  Url::get('portal_id');
                   if($portal_id!="ALL")
                    {
                        $cond .=" AND  bar_reservation.portal_id ='".$portal_id."' ";
                    }
                }
            }
            $item_per_page = 2000;
            $sql = '
                    SELECT
                        product.id ||\'-\'|| bar_reservation_product.name as id
                        ,product.id as id_product
                        ,SUM(bar_reservation_product.quantity) AS quantity
                        ,sum(bar_reservation_product.quantity_discount) as quantity_discount
                    FROM 
                        bar_reservation_product
                        INNER JOIN product ON product.id = bar_reservation_product.product_id
                        LEFT JOIN product_category ON product_category.id = product.category_id
                        INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
                        INNER JOIN unit on product.unit_id = unit.id
                    WHERE
                        '.$cond.' AND bar_reservation.status=\'CHECKOUT\'
                    GROUP BY
                        
                        product.id,
                        bar_reservation_product.name
                    ORDER BY
                        '.((Url::get('order_by')=='quantity')?' quantity desc':' product.id').'

            ';// - bar_reservation_product.remain - bar_reservation_product.quantity_cancel
            $items = DB::fetch_all($sql);
            $this->map['total'] = sizeof($items);
            
            $i=1;
            foreach ($items as $key=>$value)
            {
                $items[$key]['i']=$i++;
                $product = DB::fetch('
                    SELECT
                        product.id ||\'-\'|| bar_reservation_product.name as id
                        ,product_price_list.price
                        ,CASE
                                WHEN
                                (bar_reservation_product.product_id=\'FOUTSIDE\' OR bar_reservation_product.product_id=\'DOUTSIDE\' OR bar_reservation_product.product_id=\'SOUTSIDE\')
                                THEN
                                bar_reservation_product.name
                                ELSE
                                product.name_'.Portal::language().'
                                END name 
                        ,unit.name_'.Portal::language().' AS unit_name
                    FROM 
                        bar_reservation_product
                        INNER JOIN product ON product.id = bar_reservation_product.product_id
                        inner join product_price_list ON product_price_list.product_id = product.id
                        INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
                        LEFT OUTER JOIN product_category on product.category_id = product_category.id
                        LEFT OUTER JOIN unit on product.unit_id = unit.id
                    WHERE
                         product.id ||\'-\'|| bar_reservation_product.name = \''.$value['id'].'\'
                ');
                $items[$key]['name'] = $product['name'];
                $items[$key]['unit_name'] = $product['unit_name'];
                $items[$key]['price'] = System::display_number($product['price']);
                $items[$key]['quantity'] = System::display_number($value['quantity']);
                $items[$key]['quantity_discount'] = $value['quantity_discount'];
                $items[$key]['id'] = $value['id'];
            }
            $this->map['order_by_list'] =  array(
                'code'=>Portal::language('code'),
                'quantity'=>Portal::language('quantity')
            );
            $this->map['items'] = $items;
        
        
        
        $category_id_list = String::get_list(RestaurantCatalogueDB::get_categories('1>0'));  
        
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        if(Url::get('portal_id'))
         {
             if(Url::get('portal_id')!='ALL')
             {
                 $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('portal_id')."'");
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
         }
         else
         {
            $bars = DB::select_all('bar',false); 
         }
        //End   :Luu Nguyen GIap add portal
        
		$this->parse_layout('list',$this->map+
			array(
				'category_id_list' => $category_id_list,
				'category_id' => URL::get('category_id',''),
				'action'=>$action,
                'bars' =>$bars
				
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
