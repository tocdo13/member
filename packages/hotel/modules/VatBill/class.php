<?php
class VatBill extends Module
{
    function VatBill($row) {
        Module::Module($row);
        require 'db.php';
        /** ajax **/
        if(Url::get('actioncancel')=='CANCELVAT') {
            if(DB::exists('select id from vat_bill where id='.Url::get('vat_bill_id'))) {
                DB::update('vat_bill',array(
                                            'status'=>'CANCEL',
                                            'note_cancel'=>Url::get('note_cancel'),
                                            'time_cancel'=>time(),
                                            'user_cancel'=>User::id()
                                            ),'id='.Url::get('vat_bill_id'));
                echo 'success';
            } else {
                echo 'error';
            }
            exit();
        }
        /** end ajax **/
        switch(Url::get('cmd')) {
            case 'add':
                if(User::can_add(false,ANY_CATEGORY)) {
					require_once 'forms/edit.php';
					$this->add_form(new VatBillEditForm());
				}
                else
					Url::access_denied();
				break;
            case 'edit':
                if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and DB::fetch('select id from vat_bill where id='.Url::get('id'))) {
					require_once 'forms/edit.php';
					$this->add_form(new VatBillEditForm());
				}
                else {
                    require_once 'forms/list.php';
					$this->add_form(new VatBillListForm());
                }
				break;
            case 'view':
                if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and DB::fetch('select id from vat_bill where id='.Url::get('id'))) {
					require_once 'forms/view.php';
					$this->add_form(new VatBillViewForm());
				}
                else {
                    require_once 'forms/list.php';
					$this->add_form(new VatBillListForm());
                }
				break;
            default:
            	if(User::can_view(false,ANY_CATEGORY)) {
					require_once 'forms/list.php';
					$this->add_form(new VatBillListForm());
				}
                else
					Url::access_denied();
				break;
        }
    }
    
}
?>