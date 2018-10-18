<?php
class EditPurchasesDetailForm extends Form
{
	function EditPurchasesDetailForm()
	{
		Form::Form('EditPurchasesDetailForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
    {
        
        if(Url::get('save'))
        {
            $purchasing = array(
                                'adjustment'=>User::id(),
                                'adjustment_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                                'last_edit_user'=>User::id(),
                                'last_edit_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                                'status'=>'ADJUSTED'
                                );
            DB::update('purchases_proposed',$purchasing,'id='.Url::get('id'));
            $detail = DB::fetch_all("SELECT purchases_detail.id FROM purchases_detail WHERE purchases_detail.purchases_id=".Url::get('id')."");
            foreach($detail as $id=>$content)
            {
                $check=false;
                foreach($_REQUEST['mi_product'] as $key=>$value)
                {
                    if($value['id']==$content['id'])
                    {
                        $check=true;
                    }
                }
                if($check==false)
                {
                    DB::delete('purchases_detail','id='.$content['id']);
                    if(DB::exists("SELECT id from purchases_invoice_detail where purchases_detail_id=".$content['id']))
                    {
                        $invoice = DB::fetch("SELECT purchases_invoice_detail.purchases_invoice_id as id FROM purchases_invoice_detail Where purchases_invoice_detail.purchases_detail_id=".$content['id']);
                        DB::delete('purchases_invoice_detail','purchases_detail_id='.$content['id']);
                        if(DB::exists("SELECT id from purchases_invoice_detail where purchases_invoice_id=".$invoice['id']))
                        {
                            
                        }
                        else
                        {
                            $group = DB::fetch("SELECT purchases_invoice.group_id as id FROM purchases_invoice WHERE id=".$invoice['id']);
                            DB::delete('purchases_invoice','id='.$invoice['id']);
                            if(DB::exists("SELECT purchases_invoice.group_id as id FROM purchases_invoice WHERE group_id=".$group['id']))
                            {
                                
                            }
                            else
                            {
                                DB::delete('purchases_group_invoice','id='.$group['id']);
                            }
                        }
                    }
                        
                }
            }
            foreach($_REQUEST['mi_product'] as $key=>$value)
            {
                /** **/
                if($value['unit_id']=='')
                {
                    $unit = array('name_1'=>$value['unit_name'],'name_2'=>$value['unit_name'],'value'=>1);
                    $value['unit_id'] = DB::insert('unit',$unit);
                }
                $value['product_code'] = strtoupper($value['product_code']);
				if(!isset($value['check_product_code']))
                {
                    DB::insert('product',array('name_1'=>$value['product_name'],'name_2'=>$value['product_name'],'id'=>$value['product_code'],'category_id'=>$value['product_category_id'],'unit_id'=>$value['unit_id'],'type'=>'GOODS','status'=>'avaiable'));
                }
                /** **/
                $array = array(
                                'purchases_id'=>Url::get('id'),
                                'product_code'=>$value['product_code'],
                                'quantity'=>$value['quantity'],
                                'note'=>$value['note']
                                );
                if($value['id']=='')
                {
                    DB::insert('purchases_detail',$array);
                }
                else
                {
                    DB::update('purchases_detail',$array,'id='.$value['id']);
                }
            }
        }
        Url::redirect_current(array('cmd'=>'list'));
	}
	function draw()
	{
	    $this->map = DB::fetch('SELECT * from purchases_proposed where id='.Url::get('id'));
        $this->map['create_date'] = date('d/m/Y',$this->map['create_time']);
        $this->map['creater'] = DB::fetch('SELECT party.name_'.Portal::language().' as name from purchases_proposed inner join party on party.user_id=purchases_proposed.creater where purchases_proposed.creater=\''.$this->map['creater'].'\'','name');
        $this->map['confirm_user'] = DB::fetch('select party.name_'.Portal::language().' as name, party.description_1 as department from party where user_id=\''.User::id().'\'','name');
        if($this->map['status']=='ADJUSTED')
        {
            $this->map['status'] = Portal::language('adjusted');
        }
        if($this->map['status']=='CONFIRM')
        {
            $this->map['status'] = Portal::language('confirm_1');
        }
        $all_products = DB::fetch_all('select  
                                            product.id,
                                            product.name_'.Portal::language().' as name,
                                            unit.name_'.Portal::language().' as unit_name,
                                            product_category.name as category_name,
                                            product_category.id as product_category_id,
                                            unit.id as unit_id,
                                            1 as check_product_code
                                        from 
                                            product
                                            inner join unit on unit.id=product.unit_id
                                            inner join product_category on product_category.id=product.category_id
                                        where 
                                            type=\'PRODUCT\' OR type=\'SERVICE\' OR type=\'DRINK\' OR type=\'GOODS\'
                                        ORDER BY id');
        $this->map['all_product_list'] = json_encode($all_products);
        $this->map['all_product'] = '';
        foreach($all_products as $id=>$content)
        {
            $this->map['all_product'] .= '<option value="'.$content['id'].'---'.$content['name'].'">';
        }
        
        $all_unit = DB::fetch_all("SELECT id,name_".Portal::language()." as name FROM unit ORDER BY id");
        $this->map['all_unit_list'] = json_encode($all_unit);
        $this->map['all_unit'] = '';
        foreach($all_unit as $id_1=>$content_1)
        {
            $this->map['all_unit'] .= '<option value="'.$content_1['name'].'">';
        }
        $categorys = $this->get_category();
        $this->map['categorys_id'] = $categorys;
        
        $sql = '
			SELECT
                purchases_detail.*,
                product.id as product_code,
                product.name_'.Portal::language().' as product_name,
                unit.name_'.Portal::language().' as unit_name,
                product_category.name as category_name,
                product_category.id as product_category_id,
                unit.id as unit_id,
                purchases_invoice_detail.purchases_group_invoice_id
            FROM
                purchases_detail
                left join purchases_invoice_detail on purchases_invoice_detail.purchases_detail_id=purchases_detail.id
                inner join product on product.id=purchases_detail.product_code
                inner join unit on unit.id=product.unit_id
                inner join product_category on product_category.id=product.category_id
            WHERE
                purchases_detail.purchases_id='.Url::get('id').'
            ORDER BY
                purchases_detail.id
		';
		$mi_product = DB::fetch_all($sql);
		$_REQUEST['mi_product'] = $mi_product;
        
        $_REQUEST += $this->map;
        
        $this->parse_layout('edit',$this->map);
	}
    
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function get_category()
    {
        $category_id_list = String::get_list(DB::fetch_all('Select * from product_category order by structure_id'));	 
		$category = '';
		foreach($category_id_list as $id => $value){
			$category .= '<option value="'.$id.'">'.$value.'</option>';	
		}
		return $category;
	}
}
?>
