<?php
class EditSupplierForm extends Form
{
	function EditSupplierForm()
	{
		Form::Form('EditSupplierForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->add('code',new UniqueType(true,'code_missed_or_code_duplicated','supplier','code'));
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check())
        {
			$description = '';
			$array = array(
				'code'=>strtoupper(Url::get('code')),
				'name'=>trim(Url::get('name')),
				'fax',
				'tax_code',
				'phone',
				'mobile',
				'address',
				'email',
				'contact_person_name',
                'contact_person_phone',
                'contact_person_mobile',
                'contact_person_email',
			);
			
            if(Url::get('cmd')=='edit')
            {
				$log_title = 'Edit Supplier: #'.Url::iget('id').'';
				$description.= '<strong>Supplier:</strong><br>';
                $description.= '[Supplier id: '.Url::iget('id').', Supplier name: '.Url::get('name').', Address: '.Url::get('address').']<br>';
                $description.= '<strong>Contact Details:</strong><br>';
                $description.= '[Contact name: '.Url::get('contact_person_name').', Contact phone: '.Url::get('contact_person_phone').', Hotline: '.Url::get('contact_person_mobile').', Email: '.Url::get('contact_person_email').']<br>';
                $supplier_id = Url::iget('id');
				DB::update('Supplier',$array,'id='.Url::iget('id'));
			}
            else
            {
				$log_action = 'add';
                if(DB::exists('Select * from supplier where code = \''.$array['code'].'\''))
                {
                    $this->error('code','code_missed_or_code_duplicated');
                    return false;
                }	
				$supplier_id = DB::insert('Supplier',$array);
                $log_title = 'Add Supplier: #'.$supplier_id.'';
                $description.= '<strong>Supplier:</strong><br>';
                $description.= '[Supplier id: '.$supplier_id.', Supplier name: '.Url::get('name').', Address: '.Url::get('address').']<br>';
                $description.= '<strong>Contact Details:</strong><br>';
                $description.= '[ Contact name: '.Url::get('contact_person_name').', Contact phone: '.Url::get('contact_person_phone').', Hotline: '.Url::get('contact_person_mobile').', Email: '.Url::get('contact_person_email').']<br>';
			}
			System::log($log_action,$log_title,$description,$supplier_id);
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = Supplier::$item;
		if($item)
        {
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key]))
                {
					$_REQUEST[$key] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_supplier'):Portal::language('edit_supplier');
		$this->parse_layout('edit',$this->map);
	}	
}
?>