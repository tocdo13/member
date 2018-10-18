<?php
class EditCustomerForm extends Form
{
	function EditCustomerForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->add('group_id',new TextType(true,'miss_group_id',0,255));
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
       require_once 'packages/core/includes/utils/vn_code.php';
       $_REQUEST['msgbox'] = "";
       $file_array = array();
       if(Url::get('delete_file')){
        $list_delete = explode(',',Url::get('delete_file'));
        unset($list_delete[0]);
        foreach($list_delete as $id_file=>$value_file){
            DB::delete("FILE_CUSTOMER","id=".$value_file);
        }
       }
		if($this->check()){
		  if($_FILES['file']['name'] != NULL){ // Đã chọn file
                // Tiến hành code upload file
                        // file hợp lệ, tiến hành upload
                        $path = ROOT_PATH."packages/hotel/packages/sale/modules/Customer/file/"; // file sẽ lưu vào thư mục data
                        $tmp_name = $_FILES['file']['tmp_name'];
                        
                        $name = "FILE_".Url::get('group_id')."_".str_replace(" ","_",convert_utf8_to_latin($_FILES['file']['name']));
                        $type = $_FILES['file']['type'];
                        $size = $_FILES['file']['size'];
                        $check_file = move_uploaded_file($tmp_name,$path.$name);
                        if($check_file){
                            $_REQUEST['msgbox'] .= " upload FILE thành công + ";
                            echo '<script>';
                            echo 'alert("upload FILE thành công !");';
                            echo '</script>';
                        }else{
                            $_REQUEST['msgbox'] .= " Không up load được file + ";
                            echo '<script>';
                            echo 'alert("Không thể up load được file!");';
                            echo '</script>';
                        }
            }
			$description = '';
			$array = array(
                'start_date'=>Date_Time::to_orc_date(Url::get('start_date')),
				'code'=>Url::get('code'),
                'def_name'=> mb_strtoupper(trim(Url::get('def_name')),'utf-8'),
				'name'=> mb_strtoupper(trim(Url::get('name')),'utf-8'),
				'fax'=> Url::get('fax'),
				'tax_code'=> Url::get('tax_code'),
				'mobile'=> Url::get('mobile'),
				'address'=> Url::get('address'),
				'email'=> Url::get('email'),
				'group_id'=>Url::get('group_id'),
                'country'=>Url::get('country'),
                'city'=>Url::get('city'),
                'district'=>Url::get('district'),
                'sectors_id'=>Url::get('sectors_id'),
                'bank_code'=>Url::get('bank_code'),
                'bank_id'=>Url::get('bank_id'),
                'status'=>Url::get('status'),
                'creart_date'=>Date_Time::to_orc_date(Url::get('creart_date')),
                'note'=>Url::get('note'),
                'sale_code'=>Url::get('sale_code'),
                'user_id'=>Url::get('user_id'),
                'portal_id'=>PORTAL_ID
			);
            //System::debug($array); exit();
            //echo 111;
			if(Url::get('cmd')=='edit'){
				$log_action = 'edit';
				$description.= 'Edit customer';
				$customer_id = Url::iget('id');
				DB::update('CUSTOMER',$array,'ID='.Url::iget('id'));
                $_REQUEST['msgbox'] .= " Cập nhập thành công ";
                if($_FILES['file']['name'] != NULL){
                    $file_array['CUSTOMER_CODE'] = Url::get('code');
                    $file_array['FILE_NAME'] = "FILE_".Url::get('group_id')."_".$_FILES['file']['name'];
                    $file_array['FILE_TYPE'] = $_FILES['file']['type'];
                    $file_array['FILE_SIZE'] = $_FILES['file']['size'];
                    
                    $file_array['FILE_LINK'] = "packages/hotel/packages/sale/modules/Customer/file/"."FILE_".Url::get('group_id')."_".str_replace(" ","_",convert_utf8_to_latin($_FILES['file']['name']));
                    if($check_file)
                    DB::insert('FILE_CUSTOMER',$file_array);
                }
			}else{
				$description.= 'Add customer';
				$log_action = 'add';
                    $max = DB::fetch_all('select max(customer.id) as id from customer');
                    foreach($max as $id_max=>$code_max){
                        $code_max['id'] += 1;
                        if($code_max['id']>0 AND $code_max['id']<10){
                            $max_array['code'] = "000".$code_max['id'];
                        }
                        elseif($code_max['id']>9 AND $code_max['id']<100){
                            $max_array['code'] = "00".$code_max['id'];
                        }
                        elseif($code_max['id']>99 AND $code_max['id']<1000){
                            $max_array['code'] = "0".$code_max['id'];
                        }
                        else{
                            $max_array['code'] = $code_max['id'];
                        }
                    }
                    $array['code'] = $max_array['code'];
                    $this->add('code',new UniqueType(true,'code_missed_or_code_duplicated','customer','code'));
                    //echo $array['code'];
                    //exit();
				$customer_id = DB::insert('CUSTOMER',$array);
                $_REQUEST['msgbox'] .= " Thêm mới thành công ";
                if($_FILES['file']['name'] != NULL){
                    $file_array['CUSTOMER_CODE'] = $max_array['code'];
                    $file_array['FILE_NAME'] = strtoupper(Url::get('name'))."_".Url::get('group_id')."_".$_FILES['file']['name'];
                    $file_array['FILE_TYPE'] = $_FILES['file']['type'];
                    $file_array['FILE_SIZE'] = $_FILES['file']['size'];
                    $file_array['FILE_LINK'] = "packages/hotel/packages/sale/modules/Customer/file/"."FILE_".Url::get('group_id')."_".str_replace(" ","_",convert_utf8_to_latin($_FILES['file']['name']));
                    if($check_file)
                    DB::insert('FILE_CUSTOMER',$file_array);
                }
			}
			if(URl::get('group_deleted_ids')){
				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
				$description .= '<hr>';
				foreach($group_deleted_ids as $delete_id)
				{
					$description .= 'Delete contact id: '.$delete_id.'<br>';
					DB::delete_id('customer_contact',$delete_id);
				}
			}
			if(isset($_REQUEST['mi_contact_group']))
			{	
				$description .= '<hr>';
				foreach($_REQUEST['mi_contact_group'] as $key=>$record)
				{
					$empty = true;
					foreach($record as $record_value)
					{
						if($record_value)
						{
							$empty = false;
						}
					}
					if(!$empty)
					{
						$record['contact_name'] = $record['contact_name'];
                        $record['contact_brithday'] = Date_Time::to_orc_date($record['contact_brithday']);
                        $record['contact_regency'] = $record['contact_regency'];
						$record['contact_phone'] = $record['contact_phone'];
						$record['contact_mobile'] = $record['contact_mobile'];
						$record['contact_email'] = $record['contact_email'];
                        $record['contact_given'] = $record['contact_given'];
                        $record['contact_status'] = $record['contact_status'];
						$record['customer_id'] = $customer_id;
						$record_id = false;
						if($record['id']){
							$record_id = $record['id'];	
						}
						if($record['id'])
						{
							$id = $record['id'];
							unset($record['id']);
							$description .= 'Edit [Contact name: '.$record['contact_name'].', Mobile: '.$record['contact_phone'].', Mobile: '.$record['contact_mobile'].', Email: '.$record['contact_email'].']<br>';
							DB::update('customer_contact',$record,'id=\''.$id.'\'');
						}
						else
						{
							if(isset($record['id'])){
								unset($record['id']);
							}
							$description .= 'Edit [Contact name: '.$record['contact_name'].', Mobile: '.$record['contact_phone'].', Mobile: '.$record['contact_mobile'].', Email: '.$record['contact_email'].']<br>';
							DB::insert('customer_contact',$record);
						}
					}
				}
			}			
			$log_title = 'Customer: #'.$id.'';
			System::log($log_action,$log_title,$description,$id);// Edited in 07/03/2011
			if(Url::get('action')=='select_customer'){
                Url::redirect_url('?page=customer&action=select_customer');
            }else{
                Url::redirect_current();
            }
		}
	}
	function draw()
	{
	  // echo Url::build_all();
		$this->map = array();
		$item = Customer::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
			if(!isset($_REQUEST['mi_contact_group']))
			{
				$sql = '
					SELECT
						customer_contact.*
					FROM
						customer_contact
						inner join customer on customer.id = customer_contact.customer_id
					WHERE 1>0
						AND customer_contact.customer_id=\''.$item['id'].'\' AND customer_contact.contact_status != \'Ngừng hoạt động\'
					ORDER BY
						customer_contact.id
				';
				$mi_contact_group = DB::fetch_all($sql);
                //System::debug($mi_contact_group);
                foreach($mi_contact_group as $key_id=>$value_id){
                    if(!empty($value_id['contact_brithday'])){
                        $mi_contact_group[$key_id]['contact_brithday'] = Date_Time::convert_orc_date_to_date($value_id['contact_brithday'],"/");
                    }
                }
				$_REQUEST['mi_contact_group'] = $mi_contact_group;
			} 
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_customer'):Portal::language('edit_customer');
        // lấy list loại cho vào thẻ select
		$groups = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER_GROUP WHERE '.IDStructure::child_cond(ID_ROOT,1).'');
		$this->map['group_id_list'] = array(''=>'---') + String::get_list($groups);
        //lấy list ngành cho vào thẻ select
        $groups_sectors = DB::fetch_all('SELECT sectors.id as ID,sectors.NAME FROM sectors ');
        $this->map['sectors_id_list'] = array(''=>'---') + String::get_list($groups_sectors);
        //lấy list ngân hàng cho vào thẻ select
        $groups_bank = DB::fetch_all('SELECT bank.id as ID, bank.NAME FROM bank ');
        $this->map['bank_id_list'] = array(''=>'---') + String::get_list($groups_bank);
        //fix cứng list tình trạng
        $this->map['status_list'] = array(''=>'---',
                                        'dang_giao_dich'=>'đang giao dịch',
                                        'tiem_nang'=>'tiềm năng',
                                        'ngung_giao_dich'=>'ngừng giao dịch'
                                        );
        //lấy list mã ssale
        $sale = DB::fetch_all('SELECT
					party.user_id as id,party.full_name as name
				FROM
					party
					INNER JOIN account ON party.user_id = account.id
                    INNER JOIN portal_department ON portal_department.id= account.portal_department_id
				WHERE
					(account.id<>\'admin\' AND account.id<>\'khoand\' AND account.id<>\'tester\' AND account.id<>\'developer\'  AND account.id<>\'trienkhai\')
					AND party.type=\'USER\'
                    -- AND party.description_1=\'Kinh doanh\'
					AND account.is_active = 1
                    AND portal_department.department_code = \'SALES\'
				ORDER BY
					party.user_id
                            ');
       // System::debug($sale);
        $this->map['sale_code_list'] = array(''=>'---') + String::get_list($sale);
        //LẤY MÃ COUNTRY+city+district
		
        $country = DB::fetch_all('Select zone.structure_id as id, zone.name_1 as name from zone ORDER BY zone.name_1 DESC');
        $this->map['country_list']=array(""=>"--select-country--");
        $this->map['district_list']=array(""=>"--select-district--","0"=>"Chưa chọn thành phố");
        $this->map['city_list']=array(""=>"----select-city----","0"=>"chưa chọn nước");
        foreach($country as $id=>$content){
            if(substr($content['id'],3,2)=="00"){
                $id_test = $content['id'];
                $content_test = $content['name'];
                $this->map['country_list'] += array("$id_test"=>"$content_test");
            }
            if((substr($content['id'],3,2)!="00")and(substr($content['id'],5,2)=="00")){
                $id_test = $content['id'];
                $content_test = $content['name'];
                $this->map['city_list'] += array("$id_test"=>"$content_test");
            }
            if((substr($content['id'],5,2)!="00")and(substr($content['id'],7,2)=="00")){
                $id_test = $content['id'];
                $content_test = $content['name'];
                $this->map['district_list'] += array("$id_test"=>"$content_test");
            }
            
        }
		
        //System::debug($this->map['country_list']);
        //System::debug($this->map['city_list']);
        //System::debug($this->map['district_list']);
        
		$list_customer = DB::fetch_all('SELECT * FROM customer');
        foreach($list_customer as $key_id=>$value_id){
            $list_customer[$key_id]['creart_date'] = Date_Time::convert_orc_date_to_date($value_id['creart_date'],"/");
        }
        $this->map['customers'] = $list_customer;
        $list_file_customer = array();
        if(Url::get('cmd')=='edit'){
            $file_code = Url::get('id');
            if(is_numeric($file_code)){
                if($file_code<10){
                    $file_code = '000'.$file_code;
                }
                elseif($file_code>=10 ANd $file_code<100){
                    $file_code = '00'.$file_code;
                }
                elseif($file_code>=100 ANd $file_code<1000){
                    $file_code = '0'.$file_code;
                }else{
                    $file_code = $file_code;
                }
            }
            $list_file_customer = DB::fetch_all("SELECT * FROM FILE_CUSTOMER WHERE CUSTOMER_CODE = '$file_code'");
            //echo "SELECT * FROM FILE_CUSTOMER WHERE CUSTOMER_CODE = '$file_code'";
        }
        //System::debug($max);
        //System::debug($list_file_customer);
        
        /** giap.ln lay ra max code customer & banks  cho tich hop TCV vs CNS**/
        if(Url::get('code'))
        {
            $this->map['max_code'] = Url::get('code');
             $this->map['max_id']  = Url::get('id');
        }
        else
        {
            $max_code = DB::fetch('select max(customer.id) as max from customer');
            if(empty($max_code)==false)
                $max_code = $max_code['max'] + 1;
            else
                $max_code = 1;
            $this->map['max_id'] = $max_code;
            $max_code = sprintf("%'.04d",$max_code);
            $this->map['max_code'] = $max_code;
        }
        
        $this->map['banks'] = $groups_bank;
        /** end giap.ln **/
		$this->parse_layout('edit',array('list_file'=>$list_file_customer)+$this->map);
	}	
}
function convert_month_to_month_orcl($month){
    if($month=="01") $month="JAN";
    elseif($month=="02") $month="FEB";
    elseif($month=="03") $month="MAR";
    elseif($month=="04") $month="APR";
    elseif($month=="05") $month="MAY";
    elseif($month=="06") $month="JUN";
    elseif($month=="07") $month="JULY";
    elseif($month=="08") $month="AUG";
    elseif($month=="09") $month="SEP";
    elseif($month=="10") $month="OCT";
    elseif($month=="11") $month="NOV";
    elseif($month=="12") $month="DEC";
    else $month="JUN";
    return($month);
}
function convert_date($date){
    $test = explode('/',$date);
    $test[1]= convert_month_to_month_orcl($test[1]);
    $date = $test[0]."-".$test[1]."-".$test[3];
    return($date);
}
?>
