<?php
class EditExtraServiceInvoiceForm extends Form
{
	function EditExtraServiceInvoiceForm()
	{
		Form::Form();
		System::set_page_title(HOTEL_NAME);		
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.jec.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.alphanumeric.pack.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
    {
        if(Url::get('cmd')=='add')
        {
            //1. khai bao bien va gia tri 
            $total_amount = Url::get('total_amount');
            $total_amount = str_replace(",","",$total_amount);
            $total_amount = intval($total_amount);
            if(Url::get('start_date'))
            {
                $start_date = Date_Time::to_time(Url::get('start_date'));
                $start_date = Date_Time::convert_time_to_ora_date($start_date);
            } 
            else
                $start_date ='';
                
            if(Url::get('end_date'))
            {
                $end_date = Date_Time::to_time(Url::get('end_date'));
                $end_date = Date_Time::convert_time_to_ora_date($end_date);
            }
            else
                $end_date ='';
            $row_invoice = array('code'=>Url::get('code'),
                                    'name'=>Url::get('name'),
                                    'content'=>Url::get('content'),
                                    'total_amount'=>$total_amount,
                                    'creater'=>User::id(),
                                    'create_time'=>time(),
                                    'start_date'=>$start_date,
                                    'end_date'=>$end_date);
            //System::debug($row_invoice);
            $sql = DB::exists('SELECT code from package_sale where code =\''.Url::get('code').'\' ');
            if($sql > 1)
            {
                Url::redirect_current();
            }else
            {   
                //2. thuc hien chuc nang them moi 
                $package_sale_id = DB::insert('package_sale',$row_invoice);
                foreach($_REQUEST['mi_product_group'] as $row)
                {
                    if(trim($row['in_date'])=='')
                    {
                        $date = Date_Time::convert_time_to_ora_date(time());
                    }
                    else
                    {
                        $date = explode("/",$row['in_date']);
                        $d = mktime(0,0,0,$date[1],$date[0],$date[2]);
                        $date = Date_Time::convert_time_to_ora_date($d);
                    }
                    
                    $price = str_replace(",","",$row['price']);
                    $price = intval($price);
                    $row_invoice_detail = array('service_id'=>$row['service_id'],
                                                'quantity'=>$row['quantity'],
                                                'price'=>$price,
                                                'time'=>time(),
                                                //'used'=>(isset($row['used']) && $row['used']=='on')?1:0,
                                                'note'=>$row['note'],
                                                'in_date'=>$date,
                                                'package_sale_id'=>$package_sale_id);
                    DB::insert('package_sale_detail',$row_invoice_detail);
                }
            }
                
            //3. goi tro lai form list
            Url::redirect_current();
        }
        if(Url::get('cmd')=='edit')
        {
            //1. khai bao
            $total_amount = Url::get('total_amount');
            $total_amount = str_replace(",","",$total_amount);
            $total_amount = intval($total_amount);
            if(Url::get('start_date'))
            {
                $start_date = Date_Time::to_time(Url::get('start_date'));
                $start_date = Date_Time::convert_time_to_ora_date($start_date);
            } 
            else
                $start_date ='';
                
            if(Url::get('end_date'))
            {
                $end_date = Date_Time::to_time(Url::get('end_date'));
                $end_date = Date_Time::convert_time_to_ora_date($end_date);
            }
            else
                $end_date ='';
            $package_sale = DB::exists("SELECT * FROM package_sale WHERE id=".Url::get('id'));
            $row_invoice = array('code'=>Url::get('code'),
                                    'name'=>Url::get('name'),
                                    'content'=>Url::get('content'),
                                    'total_amount'=>$total_amount,
                                    'lastest_time'=>time(),
                                    'lastest_user'=>User::id(),
                                    'start_date'=>$start_date,
                                    'end_date'=>$end_date);
            DB::update('package_sale',$row_invoice,'id='.$package_sale['id']);
            //2, goi ham ghi lai 
            foreach($_REQUEST['mi_product_group'] as $row)
            {
                if(trim($row['in_date'])=='')
                {
                    $date = Date_Time::convert_time_to_ora_date(time());
                }
                else
                {
                    $date = explode("/",$row['in_date']);
                    $d = mktime(0,0,0,$date[1],$date[0],$date[2]);
                    $date = Date_Time::convert_time_to_ora_date($d);
                }
                
                $price = str_replace(",","",$row['price']);
                $price = intval($price);
                if($row['id']!='')
                {
                    //1.1.2. truong hop cap nhat thong tin chi tiet  package
                    $row_invoice_detail = array('service_id'=>$row['service_id'],
                                            'quantity'=>$row['quantity'],
                                            'price'=>$price,
                                            //'used'=>(isset($row['used']) && $row['used']=='on')?1:0,
                                            'note'=>$row['note'],
                                            //'in_date'=>$date,
                                            'package_sale_id'=>$package_sale['id']);
                    DB::update('package_sale_detail',$row_invoice_detail,'id='.$row['id']);
                    
                }
                else
                {
                    //1.1.3. truong hop them moi chi tiet package
                    $row_invoice_detail = array('service_id'=>$row['service_id'],
                                            'quantity'=>$row['quantity'],
                                            'price'=>$price,
                                            'note'=>$row['note'],
                                            //'in_date'=>$date,
                                            'package_sale_id'=>$package_sale['id']);
                    DB::insert('package_sale_detail',$row_invoice_detail);
                     
                }
            }
            //3. thuc hien quay ve trang chu
            if($_REQUEST['deleted_ids']!='')
            {
                $ids = explode(",",$_REQUEST['deleted_ids']);
                
                foreach($ids as $id)
                {
                    DB::delete('package_sale_detail','id='.$id);
                }
                Url::redirect_current(array('cmd'=>'edit','id'=>Url::get('id')));
            }
            else
                Url::redirect_current();
        }
        
    }
	function draw()
	{
		$this->map = array();

        if(!isset($_REQUEST['mi_product_group']))
		{
            if(Url::get('id'))
                $id= Url::get('id');
            else
                $id= 0;
			$sql = '
				SELECT
					package_sale_detail.*,
					(package_sale_detail.price*package_sale_detail.quantity) as payment_price,
					package_service.unit,
                    department.name_1 as department_name
				FROM
					package_sale_detail
					INNER JOIN package_service ON package_service.id = package_sale_detail.service_id
                    INNER JOIN department ON department.id=package_service.department_id
				WHERE
					package_sale_detail.package_sale_id=\''.$id.'\'
			';
			$mi_product_group = DB::fetch_all($sql);
			foreach($mi_product_group as $k=>$v){
				$mi_product_group[$k]['price'] = System::display_number_report($v['price']);
                unset($mi_product_group[$k]['in_date']);
			//	$mi_product_group[$k]['in_date'] = Date_Time::convert_orc_date_to_date($v['in_date'],'/');
				$mi_product_group[$k]['payment_price'] = System::display_number_report($v['payment_price']);
				$mi_product_group[$k]['quantity'] = System::display_number($v['quantity']);
			}
			$_REQUEST['mi_product_group'] = $mi_product_group;
            
            $package_sale = DB::fetch("SELECT * FROM package_sale WHERE id=".$id);
            $this->map['code'] = $package_sale['code'];
            $this->map['name'] = $package_sale['name'];
            $this->map['content'] = $package_sale['content'];
            if($id!=0)//truong hop edit 
            {
                if($package_sale['start_date']!='')
                    $this->map['start_date'] = Date_Time::convert_orc_date_to_date($package_sale['start_date'],"/");
                else
                    $this->map['start_date'] = '';
                    
                if($package_sale['end_date']!='')
                    $this->map['end_date'] = Date_Time::convert_orc_date_to_date($package_sale['end_date'],"/");
                else
                    $this->map['end_date'] = '';
            }
            else //truong hop add 
            {
                $this->map['start_date'] = date('d/m/Y');
                $this->map['end_date'] = date('d/m/Y',time() + 30*24*60*60);
            }     
		}
        
        $this->map['service_options']='<option value=""></option>';
        $services = DB::fetch_all("SELECT package_service.id,package_service.name,package_service.unit,package_service.price,department.name_1 as department_name FROM package_service INNER JOIN department ON department.id=package_service.department_id ORDER BY package_service.id");
		foreach($services as $key=>$value){
			$this->map['service_options'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
       // System::debug($services);
        //$services = DB::select_all('package_service');
       // System::debug($services);
		$this->map['services'] = String::array2js($services);
        $this->parse_layout('edit',$this->map);
	}	
	
}
?>