<?php 
class WarehouseInvoice extends Module
{
	public static $item = array();
	function WarehouseInvoice($row)
	{
		Module::Module($row);
        require_once 'db.php';
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
		if(!Url::get('type'))
        {
			Url::redirect_current(array('type'=>'IMPORT'));
		}
             
		switch (Url::get('cmd'))
        {
			case 'view':
				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and WarehouseInvoice::$item = DB::select('wh_invoice','id ='.Url::iget('id')))
                {
					require_once 'forms/view.php';
					$this->add_form(new ViewWarehouseInvoiceForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
			
            case 'add':
				if(User::can_add(false,ANY_CATEGORY))
                {
                    if(Url::get('choose_warehouse')==1)
                    {
                        if(User::can_view(false,ANY_CATEGORY))
                        {
            				require_once 'forms/choose_warehouse.php';
            				$this->add_form(new ChooseWarehouseForm());
            			}
                        break;
                    }
					require_once 'forms/edit.php';
					$this->add_form(new EditWarehouseInvoiceForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
            case 'add2':
				if(User::can_add(false,ANY_CATEGORY))
                {
                    if(Url::get('choose_warehouse')==1)
                    {
                        if(User::can_view(false,ANY_CATEGORY))
                        {
            				require_once 'forms/choose_warehouse.php';
            				$this->add_form(new ChooseWarehouseForm());
            			}
                        break;
                    }
					require_once 'forms/edit.php';
					$this->add_form(new EditWarehouseInvoiceForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
			
            case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and WarehouseInvoice::$item = DB::select('wh_invoice','id ='.Url::iget('id')))
                {
                    if(Url::get('choose_warehouse')==1)
                    {
                        if(User::can_view(false,ANY_CATEGORY))
                        {
            				require_once 'forms/choose_warehouse.php';
            				$this->add_form(new ChooseWarehouseForm());
            			}
                       
                    }
					require_once 'forms/edit.php';
					$this->add_form(new EditWarehouseInvoiceForm());
				}
                else
                {
					Url::access_denied();
				}
				break;
                
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY))
                {
					if(Url::get('id') and DB::exists('SELECT id, warehouse_id FROM wh_invoice WHERE id = '.Url::iget('id').''))
                    {
                        if (Url::get('type') == 'EXPORT')
                        {
                            $this->check_tmp_wh(Url::get('id'));
                        }
                        if (Url::get('type') == 'IMPORT')
                        {
                            $this->check_tmp_wh(Url::get('id'));
                        }
						DB::delete('wh_invoice_detail', 'invoice_id= '.Url::iget('id'));
						DB::delete('wh_invoice','id= '.Url::iget('id'));
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page').'&type='.Url::get('type')));
					}
					if(Url::get('item_check_box'))
                    {
                        $arr = Url::get('item_check_box');
						for($i=0;$i < sizeof($arr); $i++)
                        {
                            if (Url::get('type') == 'EXPORT')
                            {
                                $this->check_tmp_wh($arr[$i]);   	
                            }
                            if (Url::get('type') == 'IMPORT')
                            {
                                $this->check_tmp_wh($arr[$i]);   	
                            }
							DB::delete('wh_invoice','id = '.$arr[$i]);
							DB::delete('wh_invoice_detail','invoice_id= '.$arr[$i]);
						}
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page').'&type='.Url::get('type')));
					}
                    else
                    {
						Url::redirect_current();
					}
				}
                else
                {
					Url::access_denied();
				}
				break;
			
            default:
				if(User::can_view(false,ANY_CATEGORY))
                {
					require_once 'forms/list.php';
					$this->add_form(new ListWarehouseInvoiceForm());
				}
                else
                {
					Url::access_denied();
				}
				break;

		}
	}
    function check_tmp_wh($id)
    {
        /**
         * doan nay check xem co can thiet phai xoa ban ghi tuong ung voi product_id va warehouse_id trong bang
         * wh_tmp hay khong 
        **/
        $warehouse_id = DB::fetch('select * from wh_invoice where id = '. $id, 'warehouse_id');
        $remain_product = get_remain_products($warehouse_id);
        
        $wh_detail = DB::fetch_all('select * from wh_invoice_detail where invoice_id = '. $id);
        if (Url::get('type') == 'EXPORT')
        {
            foreach ($wh_detail as $key => $value)
            {
                if ($remain_product[$value['product_id']]['remain_number'] < 0 and ($remain_product[$value['product_id']]['remain_number'] + $value['num']) >= 0)
                {
                    $cond_tmp = 'warehouse_id = '. $warehouse_id . ' and product_id = \''. $value['product_id']. '\'';
                    // update cac gia xuat ra cho cac PX am
                    $price = isset($remain_product[$value['product_id']]['price'])? $remain_product[$value['product_id']]['price']:$remain_product[$value['product_id']]['start_term_price'];
                    DB::update('wh_invoice_detail', array('tmp' => '', 'price' => $price), $cond_tmp .' and tmp = 1');
                    // xoa ban ghi trong wh_tmp vi product_id trong warehouse_id da het am
                    DB::delete('wh_tmp', $cond_tmp);   
                }   
            }   
        }
        if (Url::get('type') == 'IMPORT')
        {
            foreach ($wh_detail as $key => $value)
            {
                $cond_tmp = 'warehouse_id = '. $warehouse_id . ' and product_id = \''. $value['product_id']. '\'';
                $temp_item = DB::fetch('select * from wh_tmp where '.$cond_tmp);
                if (!empty($temp_item))
                {
                    DB::update('wh_tmp', array('quantity' => ($temp_item['quantity'] - $value['num']), 'total' => $temp_item['total'] - $value['payment_price']), 'id = '.$temp_item['id']);
                }
            }   
        }
    }	
}
?>