<?php 
class CreateSetMenu extends Module
{
	function CreateSetMenu($row)
	{
		Module::Module($row);
            switch (Url::get('cmd'))
            {
                case "edit":
                            if(isset($_GET['id']) && !empty($_GET['id'])){
                                $id = $_GET['id'];
                                $sql = "SELECT id FROM bar_reservation_set_product WHERE bar_set_menu_id=".$id;
                                $result = DB::fetch($sql);
                                if(empty($result) || User::is_admin()){
                                    require_once 'forms/edit.php';
                                    $this->add_form(new EditSetMenuForm()); 
                                }
                                else{
                                    Url::redirect_current();
                                }
                            }
                            else{
                                require_once 'forms/edit.php';
                                $this->add_form(new EditSetMenuForm()); 
                            }
                            break;
                default :
                            if(isset($_GET['delete_id'])){
                                $delete_id = $_GET['delete_id'];
                                
                                $sql = "SELECT bar_set_menu.id,
                                bar_set_menu.code,
                                bar_set_menu.department_code 
                                FROM bar_set_menu INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id WHERE product_price_list.id = ".$delete_id;
                                $rs = DB::fetch($sql);
                                
                                $sql = "SELECT * FROM bar_reservation_product WHERE product_id='".mb_strtoupper(trim($rs['code']),'utf-8')."'";
                                $result = DB::fetch($sql);
                                
                                $sql = "SELECT * FROM mice_restaurant_product WHERE product_id='".mb_strtoupper(trim($rs['code']),'utf-8')."'";
                                $result_mice = DB::fetch($sql);
                                
                                if(empty($result) && !empty($result_mice))
                                { 
                                    if(DB::exists_id('bar_set_menu',$delete_id)){
                                        $sql = "SELECT * FROM bar_set_menu WHERE id=".$delete_id;
                                        $result = DB::fetch($sql);
                                        DB::delete('product_price_list','product_price_list.department_code = \''.$result['department_code'].'\' AND product_id=\''.$result['code'].'\'');
                                        DB::delete('bar_set_menu_product',' bar_set_menu_id='.$delete_id);
                                        DB::delete_id('bar_set_menu',$delete_id);
                                        require_once("packages/hotel/modules/ProductPrice/db.php");
                                        ProductPriceDB::export_cache();
                                    }
                                }
                            }
                            require_once 'forms/list.php';
                            $this->add_form(new ListSetMenuForm());
                            break;              
            }    
        }	
}
?>