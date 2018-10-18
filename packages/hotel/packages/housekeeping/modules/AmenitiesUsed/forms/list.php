<?php
class ListAmenitiesUsedForm extends Form
{
    function ListAmenitiesUsedForm()
    {
        Form::Form('ListAmenitiesUsedForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
        if(!empty($selected_ids))
        {
            require_once 'packages/hotel/includes/php/product.php';
			foreach($selected_ids as $id)
			{
                DB::delete_id( 'amenities_used', $id );
                DB::delete( 'amenities_used_detail', ' amenities_used_id = '.$id );
                DeliveryOrders::delete_delivery_order($id,'AMENITIES');
			}  
        } 
    }
    function draw()
    {
        
        
        $this->map=array();
        
        //Start : Luu Nguyen Giap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        
        if(!Url::get('from_date'))
        {
            $_REQUEST['from_date'] = Date('1/m/Y');
            $_REQUEST['to_date'] = Date('d/m/Y');   
        }
        $cond ='1=1 ';
        $cond .= 'and amenities_used.create_date >=\''.Date_Time::to_orc_date($_REQUEST['from_date']).'\'
                  and amenities_used.create_date <=\''.Date_Time::to_orc_date($_REQUEST['to_date']).'\'     
                ';
		
        
        //$cond = ' amenities_used.time <= '.$time_to.' and amenities_used.time >= '.$time_from.' ';
        //Start Luu Nguyen Giap add portal
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id =PORTAL_ID;
        }
        if($portal_id!="ALL")
        {
            $cond .=" AND amenities_used.portal_id ='".$portal_id."' ";
        }
        else
        {
            $cond .=" AND 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
        
        $item_per_page = 50;
		$sql = 'SELECT count(*) AS acount FROM amenities_used where '.$cond.' ';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array());

        $sql = '
			SELECT * FROM
			(
				SELECT
					amenities_used.*,
					ROW_NUMBER() OVER (ORDER BY amenities_used.id) as rownumber
				FROM
					amenities_used
                where 
                    '.$cond.'
				ORDER BY
					amenities_used.id
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
                 order by id desc
		';
		$items = DB::fetch_all($sql);
        
        $i = ((page_no()-1)*$item_per_page)+1;
        foreach($items as $k => $v)
        {
            $items[$k]['stt'] = $i++;
            $items[$k]['time'] = date('d/m/Y',$items[$k]['time'] );
            $items[$k]['last_modify_time'] = $items[$k]['last_modify_time']? date('d/m/Y',$items[$k]['last_modify_time'] ) :'';
        }
        //System::debug($items);
        $this->map['items'] = $items;
        $this->map['title'] = Portal::language('list_amenities_used');
        $this->parse_layout('list',$this->map);
    }
    
    
    
}

?>