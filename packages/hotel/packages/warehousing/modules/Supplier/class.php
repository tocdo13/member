<?php 
class Supplier extends Module
{
	public static $item = array();
	function Supplier($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(1==1)
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditSupplierForm());
				}
                else
					Url::access_denied();
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and Supplier::$item = DB::select('supplier','id = '.Url::iget('id')))
                {
					require_once 'forms/edit.php';
					$this->add_form(new EditSupplierForm());
				}
                else
					Url::access_denied();
				break;
			case 'delete':
				if(1==1)
                {
                    $log_action = 'delete';
					if(Url::get('id') and DB::exists('SELECT id FROM supplier WHERE id = '.Url::iget('id').''))
                    {
                        $supplier = DB::fetch('SELECT * FROM supplier WHERE id = '.Url::iget('id').'');
                        $log_title = 'Delete Supplier: #'.Url::get('id').'';
                        $description.= '<strong>Supplier:</strong><br>';
                        $description.= '[Supplier id: '.Url::iget('id').', Supplier name: '.$supplier['name'].', Address: '.$supplier['address'].']<br>';
                        $description.= '<strong>Contact Details:</strong><br>';
                        $description.= '[Contact name: '.$supplier['contact_person_name'].', Contact phone: '.$supplier['contact_person_phone'].', Hotline: '.$supplier['contact_person_mobile'].', Email: '.$supplier['contact_person_email'].']<br>';
						DB::delete('Supplier','ID = '.Url::iget('id'));
                        System::log($log_action,$log_title,$description,Url::get('id'));
						Url::redirect_current();
					}
					if(Url::get('item_check_box'))
                    {
						$arr = Url::get('item_check_box');
                        $parameter='';
                        $log_title = 'Delete Supplier:';
						for($i=0;$i<sizeof($arr);$i++)
                        {
                            
                            $supplier = DB::fetch('SELECT * FROM supplier WHERE id = '.$arr[$i].'');
                            $description.= '<strong>Supplier:</strong><br>';
                            $description.= '[Supplier id: '.$arr[$i].', Supplier name: '.$supplier['name'].', Address: '.$supplier['address'].']<br>';
                            $description.= '<strong>Contact Details:</strong><br>';
                            $description.= '[Contact name: '.$supplier['contact_person_name'].', Contact phone: '.$supplier['contact_person_phone'].', Hotline: '.$supplier['contact_person_mobile'].', Email: '.$supplier['contact_person_email'].']<br>';
                        	
                            if($i< (sizeof($arr)-1))
                            {
                                $log_title .= ' #'.$arr[$i].',';
                                $parameter.=$arr[$i].', ';
                            }
                            if($i== ((sizeof($arr)-1)))
                            {
                                $log_title .= ' #'.$arr[$i].'';
                                $parameter.=$arr[$i].'';
                            }
                            if($i< (sizeof($arr) - 1))
                            {
                                $description.= '<hr>';
                            }
                            DB::delete('Supplier','id = '.$arr[$i]);
						}
                        System::log($log_action,$log_title,$description,$parameter);
						Url::redirect_current();
					}
                    else
						Url::redirect_current();
				}
                else
					Url::access_denied();
				break;
			
            default:
				if(1==1)
                {
					require_once 'forms/list.php';
					$this->add_form(new ListSupplierForm());
				}
                else
					Url::access_denied();
				break;
		}
	}	
}
?>