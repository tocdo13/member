<?php 
class Customer extends Module
{
	public static $item = array();
	function Customer($row)
	{
		Module::Module($row);
        
	   /** check trung ma so thue **/
       if(Url::get('check_conflix_tax_code') and Url::get('cmd_check') and Url::get('tax_code')){
            $cond = '1=1';
            if(Url::get('cmd_check')=='edit'){
                $cond = 'customer.id!='.Url::get('id_check');
            }
            if(DB::exists('select id from customer where '.$cond.' and tax_code=\''.Url::get('tax_code').'\'')){
                echo 1; // false
            }else{
                echo 2;// true
            }
            exit();
       }
       /** end check trung mst */
		switch (Url::get('cmd')){
		    case 'download':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/download.php';
					$this->add_form(new DownloadCustomerForm());
				}else{
					Url::access_denied();
				}
				break;  
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){//false,ANY_CATEGORY
					require_once 'forms/edit.php';
					$this->add_form(new EditCustomerForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and Customer::$item = DB::select('CUSTOMER','ID = '.Url::iget('id'))){
					Customer::$item['start_date']=Date_Time::convert_orc_date_to_date(Customer::$item['start_date'],'/');
                    Customer::$item['creart_date']=Date_Time::convert_orc_date_to_date(Customer::$item['creart_date'],'/');
                    //System::debug(Customer::$item);
                    require_once 'forms/edit.php';
					$this->add_form(new EditCustomerForm());
				}else{
					Url::access_denied();
				}
				break;
            case 'import':
				if(User::can_add(false,ANY_CATEGORY))
                {
					require_once 'forms/import.php';
					$this->add_form(new ImportCustomerForm());
				}
                else
					Url::access_denied();
				break;
             case 'list_national':
                
                if(User::can_edit(false,ANY_CATEGORY)){
                    require_once 'forms/list_national.php';
                    $this->add_form(new listNationalForm());
                        break;
                }else{
                    Url::redirect_current();
                } 
                   
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT ID FROM CUSTOMER WHERE ID = '.Url::iget('id').'')){
					    $sql = "SELECT reservation.id,customer.name FROM reservation INNER JOIN customer ON reservation.customer_id = customer.id WHERE reservation.customer_id=".Url::iget('id');
                        $result = DB::fetch($sql);
					    if(empty($result))
                        {
                            DB::delete('CUSTOMER','ID = '.Url::iget('id'));
                            DB::delete('CUSTOMER_CONTACT','CUSTOMER_ID = '.Url::iget('id'));
                            DB::delete('CUSTOMER_DEBT_SETTLEMENT','CUSTOMER_ID = '.Url::iget('id'));
                            DB::delete('CUSTOMER_RATE_COMMISSION','CUSTOMER_ID = '.Url::iget('id'));
                            DB::delete('CUSTOMER_RATE_POLICY','CUSTOMER_ID = '.Url::iget('id'));
    						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
                        }
                        else
                        {
                            echo "<script>
                                    alert('Thao tác xóa không thành công. Nguồn khách ".$result['name']." đã được tạo booking');
                                    window.location='?page=customer';
                                  </script>";
                            return;
                        }
						Url::redirect_current(array('group_id'));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	                       
                        for($i=0;$i<sizeof($arr);$i++){
							$sql = "SELECT reservation.id,customer.name FROM reservation INNER JOIN customer ON reservation.customer_id = customer.id WHERE reservation.customer_id=".$arr[$i];
                            $result = DB::fetch($sql);
                            if(!empty($result))
                            {
                                echo "<script>
                                        alert('Thao tác xóa không thành công. Nguồn khách ".$result['name']." đã được tạo booking');
                                        window.location='?page=customer';
                                      </script>";
                                return;
                            }
						}
                        
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('CUSTOMER','ID = '.$arr[$i]);
                            DB::delete('CUSTOMER_CONTACT','CUSTOMER_ID = '.$arr[$i]);
                            DB::delete('CUSTOMER_DEBT_SETTLEMENT','CUSTOMER_ID = '.$arr[$i]);
                            DB::delete('CUSTOMER_RATE_COMMISSION','CUSTOMER_ID = '.$arr[$i]);
                            DB::delete('CUSTOMER_RATE_POLICY','CUSTOMER_ID = '.$arr[$i]);
						}
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current(array('group_id'));
					}else{
						Url::redirect_current(array('group_id'));
					}
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
                    
					require_once 'forms/list.php';
					$this->add_form(new ListCustomerForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>
