<?php
class EditExtraServiceAdminForm extends Form
{
	function EditExtraServiceAdminForm()
	{
		Form::Form('EditExtraServiceAdminForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->add('name',new TextType(true,'miss_name',0,255));
		//$this->add('code',new TextType(true,'miss_code',0,50));
	}
	function on_submit()
    {
		if($this->check())
        {	
			$array = array(
				'name',
				'code',
				'price'=>Url::get('price')?str_replace(',','',Url::get('price')):0,
				'unit',
                                'status','type' 
			);
			$log_description = '';
			if(Url::get('cmd')=='edit')
            {
				$log_action = 'edit';// Edited in 06/01/2011
				$id = Url::iget('id');
				DB::update('extra_service',$array,'id='.Url::iget('id'));
				$log_description .= 'Edit extra service: '.Url::get('name').' | price: '.Url::get('price').' | unit: '.Url::get('unit').' | status: '.Url::get('status').'';
			}
            else
            {
				$log_action = 'add';// Edited in 06/01/2011
				$id = DB::insert('extra_service',$array);
				$log_description .= 'Add extra service: '.Url::get('name').' | price: '.Url::get('price').' | unit: '.Url::get('unit').' | status: '.Url::get('status').'';
			} 
			//$log_description .= '</ul>';
			$log_title = 'Extra service: #'.$id.'';
			System::log($log_action,$log_title,$log_description,$id);// Edited in 07/03/2011
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = ExtraServiceAdmin::$item;
        $service_use=DB::fetch_all('select service_id as id from extra_service_invoice_detail group by service_id ');
        //System::debug($item);
		if($item)
        {
            if(Url::get('cmd')=='edit')
            {
                if(isset($service_use[$item['id']]))
                {
                    $this->map['can_edit']=1; 
                }else
                {
                    $this->map['can_edit']=0;  
                }
            }
			foreach($item as $key=>$value)
            {
				if(!isset($_REQUEST[$key]))
                {
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
        //System::debug($this->map);
		$this->map['type_list'] = array(
			'SERVICE'=>Portal::language('extra_service'),	
			'ROOM'=>Portal::language('room_amount')
		);
        $this->map['status_list'] = array(
			'SHOW'=>'SHOW',	
			'HIDE'=>'HIDE'
		);
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_extra_service'):Portal::language('edit_extra_service');
		$this->parse_layout('edit',$this->map);
	}	
}
?>
