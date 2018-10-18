<?php 
class ProductMaterial extends Module
{
	function ProductMaterial($row)
	{
		Module::Module($row);
        //System::debug(DB::fetch_all('select * from product_material'));
        switch(Url::get('cmd'))
        {
            case 'add':
            case 'edit':
				if(User::can_edit(false,ANY_CATEGORY))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditProductMaterialForm());
				}
                else
					Url::access_denied();
				break;
            case 'delete':
            case 'remove_all':
				if(User::can_delete(false,ANY_CATEGORY))
                {
					$this->delete_all();
				}
                else
					Url::access_denied();
				break;
                
            case 'import':
				if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/import.php';
					$this->add_form(new ImportProductMaterialForm());
				}
                else
					Url::access_denied();
				break;
            case 'export':
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListProductMaterialForm());
				}
                else
					Url::access_denied();
				break;
            case 'export_excel':
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListProductMaterialForm());
				}
                else
					Url::access_denied();
				break;
            default:
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListProductMaterialForm());
				}
                else
					Url::access_denied();
				break;
        }
	}
    
    function delete_all()
    {
        //xoa cua 1 san pham
        if(Url::get('product_id') and ($product = DB::fetch('Select * from product where id = \''.Url::get('product_id').'\'')))
        {
            DB::query('Delete from product_material where product_id = \''.$product['id'].'\' and portal_id = \''.PORTAL_ID.'\'');
            //xoa trong form edit
            if(Url::get('cmd')=='remove_all')
            {
                Url::redirect_current(array('cmd'=>'edit','product_id'=>$product['id'],'product_name'=>$product['name_'.Portal::language()],'portal_id'=>PORTAL_ID));
            }
                
            else//xoa trong form list
                Url::redirect_current(); 
        }
        else //xoa tat ca
        {
            $product_id_list = explode(",",Url::sget('product_id'));
            foreach($product_id_list as $key=>$value)
            {
                DB::query('Delete from product_material where product_id = \''.$value.'\' and portal_id = \''.PORTAL_ID.'\'');
            }
            Url::redirect_current();
        }
    }
    //lay cac nguyen vat lieu
    //edit 19/12/2012 theo yeu cau DeNhat : co them ca good
	function get_material()
	{
		DB::query('select 
                        product.id,
                        product.name_'.Portal::language().' as name,
                        unit.name_'.Portal::language().' as unit 
                    from 
                        product 
                        inner join unit on product.unit_id = unit.id
                        left join product_category on product.category_id = product_category.id
                    where 
                        product.type=\'MATERIAL\'
                        OR product.type=\'GOODS\'
                    ');
		$items = DB::fetch_all();
        return $items; 
	}
}
?>