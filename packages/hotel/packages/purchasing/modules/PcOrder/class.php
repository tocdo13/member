<?php 
class PcOrder extends Module
{
    function PcOrder($row)
    {
        Module::Module($row);
        switch (Url::get('cmd'))
        {
			case 'add':
				if(User::can_view(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY))
                {
                    require_once 'forms/add.php';
                    $this->add_form(new AddPcOrderForm());
                }
                else
                    Url::access_denied();
                break;
            case 'edit':
				if(User::can_view(false,ANY_CATEGORY) AND Url::get('id')!='' AND DB::exists('select id from pc_order where id='.Url::get('id')))
                {
                    require_once 'forms/edit.php';
					$this->add_form(new EditPcOrderForm());
                }
                else
                    Url::access_denied();
                break;
            case 'list_order':
                if(User::can_add(Portal::get_module_id('PrivilegeDepartmentOrder'),ANY_CATEGORY))
                {
                    require_once 'forms/list_order.php';
                    $this->add_form(new ListOrderForm());
				}
                else
					Url::access_denied();
				break;
            case 'print_order':
                if(User::can_view(false,ANY_CATEGORY) AND Url::get('id')!='' AND DB::exists('select id from pc_order where id='.Url::get('id')))
                {
                    require_once 'forms/print_order.php';
                    $this->add_form(new PrintOderPcOrderForm());
                }
                else
                    Url::access_denied();
                break;
            case 'print_order_payment':
                if(User::can_view(false,ANY_CATEGORY) AND Url::get('id')!='' AND DB::exists('select id from pc_order where id='.Url::get('id')))
                {
                    require_once 'forms/print_order_payment.php';
                    $this->add_form(new PrintOderPaymentPcOrderForm());
                }
                else
                    Url::access_denied();
                break;
            case 'edit_cancel':
                if(User::can_edit(false,ANY_CATEGORY) AND Url::get('id')!='' AND DB::exists('select id from pc_order where id='.Url::get('id')))
                {
                    require_once 'forms/edit_cancel.php';
                    $this->add_form(new EditPcOrderForm());
                }
                else
                    Url::access_denied();
                break;
            default:
                if(User::can_view(false,ANY_CATEGORY))
                {
                    require_once 'forms/list.php';
                    $this->add_form(new ListPcOrderForm());
                }
                else
                    Url::access_denied();
                break;
        }
        $action = Url::get('action');
        /** Xu ly su kien xoa san pham*/
        if($action == 'delete')
        {
            if(User::can_delete(false,ANY_CATEGORY))
            {
				if(Url::get('p_id') and DB::exists('SELECT id FROM pc_order_detail WHERE id = '.Url::get('p_id').'') and DB::exists('SELECT id FROM pc_recommend_detail_order WHERE order_id = '.Url::get('p_id').''))
                {
                    $id = Url::get('p_id');
                    $total_amount = DB::fetch('SELECT total FROM pc_order WHERE id=\''.Url::get('pc_order_id').'\'');
                    $update_total = $total_amount['total'] - System::calculate_number(Url::get('total_del'));
                    $update = array('total'=>$update_total);
                    DB::update('pc_order', $update, 'id ='.Url::get('pc_order_id'));
                    DB::delete('pc_order_detail','id = '.$id);
                    DB::delete('pc_recommend_detail_order','order_id = '.$id);
                    Url::redirect_url('?page=pc_order&cmd=edit&id='.Url::get('pc_order_id'));
				}
			}
        }elseif($action == 'GetSupplier')
        {
            $supplier = DB::fetch("
                            SELECT 
                                * 
                            FROM 
                                supplier 
                            WHERE 
                                id = ".Url::get('pc_supplier_id')."
            ");
            echo json_encode($supplier);
            exit();
        }elseif($action == 'GetPriceSupplier')
        {
            $sup_price = DB::fetch_all("
                            SELECT 
                                CONCAT(concat(pc_sup_price.product_id,'_'),pc_sup_price.supplier_id) as id,
                                pc_sup_price.product_id,
                                pc_sup_price.supplier_id,
                                pc_sup_price.price,
                                pc_sup_price.tax, 
                                product.tax_percent
                            FROM
                                pc_sup_price
                                INNER JOIN product ON product.id=pc_sup_price.product_id
                            WHERE
                                pc_sup_price.supplier_id = ".Url::get('supplier_id')."
            ");
            echo json_encode($sup_price);
            exit();
        }
    }	   
}
?>