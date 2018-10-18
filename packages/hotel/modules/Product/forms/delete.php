<?php
class DeleteProductForm extends Form
{
	function DeleteProductForm()
	{
		Form::Form("DeleteProductForm");
		$this->add('id',new IDType(true,'object_not_exists','product'));
	}
	function on_submit()
	{
		if($this->check() and $product = DB::select('product','id=\''.$_REQUEST['id'].'\''))
		{
			$bar_reservation_product = DB::select('bar_reservation_product','product_id=\''.$_REQUEST['id'].'\'');
            $housekeeping_invoice_detail = DB::select('housekeeping_invoice_detail','product_id=\''.$_REQUEST['id'].'\'');
            $wh_invoice_detail = DB::select('wh_invoice_detail','product_id=\''.$_REQUEST['id'].'\'');
            $wh_start_term_remain = DB::select('wh_start_term_remain','product_id=\''.$_REQUEST['id'].'\'');
            $massage_product_consumed = DB::select('massage_product_consumed','product_id=\''.$_REQUEST['id'].'\'');
            $product_material = DB::select('product_material','product_id=\''.$_REQUEST['id'].'\'');
            //echo $wh_invoice_detail;exit();
            if($bar_reservation_product || $housekeeping_invoice_detail || $wh_invoice_detail || $massage_product_consumed || $product_material || $wh_start_term_remain)
            {
                // 1;exit();
                $this->error('id','da co du lieu khong the xoa');
                return false;
            }
            else
            {
                //echo 2;exit();
                if(DB::select('store_product','product_id=\''.$_REQUEST['id'].'\''))
    			{
    				$this->error('id','product_in_use');
    			}
    			else
    			{
    				$this->delete($_REQUEST['id']);
    				Url::redirect_current(array('type'));
    			}
            }
            Url::redirect_current(array('type','category_id'));
		}
	}
	function draw()
	{
		$languages = DB::select_all('language');
		DB::query('
			select 
				product.id
				,product.type 
				,product.name_'.Portal::language().' as name 
				,product_category.name as category_id 
				,unit.name_'.Portal::language().' as unit_id 
			from 
			 	product
				left outer join product_category on product_category.id=product.category_id 
				left outer join unit on unit.id=product.unit_id 
			where
				product.id = \''.URL::get('id').'\'');
		if($row = DB::fetch())
		{
			$defintition = array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL');
			if(isset($defintition[$row['type']]))
			{
				$row['type'] = $defintition[$row['type']];
			}
			else
			{
				$row['type'] = '';
			} 
		}
		$this->parse_layout('delete',$row);
	}
	function delete($id)
	{
		DB::delete('product', 'id=\''.$id.'\'');
		DB::delete('product_price_list', 'product_id=\''.$id.'\'');
	}
	function update($id)
	{
		DB::update_id('product',array('status'=>'NO_USE'),$id);
	}
}
?>