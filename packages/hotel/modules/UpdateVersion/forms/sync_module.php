<?php
class UpdateVersionSyncModuleForm extends Form
{
	function UpdateVersionSyncModuleForm()
	{
		Form::Form('UpdateVersionSyncModuleForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
	}
	function on_submit()
	{
	   if(Url::get('act')=='GETDATA' and Url::get('sync_table') and Url::get('link_api'))
       {
            $request = new HttpRequest();
            $request->setUrl(Url::get('link_api'));
            $request->setMethod(HTTP_METH_GET);
            
            $request->setQueryData(array(
              'page' => 'api_sync',
              'endpoint' => 'GetData',
              'table' => Url::get('sync_table')
            ));
            
            $request->setHeaders(array(
              'Cache-Control' => 'no-cache'
            ));
            
            try {
                $response = $request->send();
                $data = json_decode($response->getBody(),true);
                //System::debug($data);
                foreach($data as $key=>$value){
                    foreach($value['data'] as $ID=>$FEILD){
                        
                        if(!DB::exists('select id from '.Url::get('sync_table').' where id=\''.$FEILD['id'].'\''))
                        {
                            /** convert data **/
                            unset($FEILD['rownumber']);
                            if(strtoupper(Url::get('sync_table'))=='BAR'){
                                 if($FEILD['code']!='')
                                    $this->update_module('RES',$FEILD['code'],$FEILD['name'],'restaurant','Restaurant list');
                                 DB::insert('BAR_AREA',array('BAR_ID'=>1,'NAME'=>'Restaurant'));
                            }
                            if(strtoupper(Url::get('sync_table'))=='BAR_RESERVATION'){
                                if($FEILD['pay_with_room']==1){
                                    $FEILD['amount_pay_with_room'] = $FEILD['total'];
                                    $FEILD['total_payment_room'] = $FEILD['total'];
                                }
                            }
                            if(strtoupper(Url::get('sync_table'))=='EXTRA_SERVICE'){
                                if( $FEILD['code']=='EXTRA_BED' or $FEILD['code']=='BABY_COT' or $FEILD['code']=='VFD' or $FEILD['code']=='EARLY_CHECKIN' or $FEILD['code']=='LATE_CHECKOUT' or $FEILD['code']=='LATE_CHECKIN')
                                    $FEILD['type'] = 'ROOM';
                                else
                                    $FEILD['type'] = 'SERVICE';
                            }
                            if(strtoupper(Url::get('sync_table'))=='EXTRA_SERVICE_INVOICE'){
                                $FEILD['type'] = $FEILD['payment_type'];
                            }
                            if(strtoupper(Url::get('sync_table'))=='EXTRA_SERVICE_INVOICE_DETAIL'){
                                $detail_table_id = DB::insert('EXTRA_SERVICE_INVOICE_TABLE',array('from_date'=>$FEILD['in_date'],'to_date'=>$FEILD['in_date'],'invoice_id'=>$FEILD['invoice_id']));
                                $FEILD['table_id'] = $detail_table_id;
	                            $exchange_rate = DB::fetch('select id,exchange from currency where id=\'VND\'','exchange');
                                $FEILD['usd_price'] = $FEILD['price']/$exchange_rate;
                            }
                            if(strtoupper(Url::get('sync_table'))=='MASSAGE_RESERVATION_ROOM'){
                                if($FEILD['hotel_reservation_room_id']!=''){
                                    $FEILD['amount_pay_with_room'] = $FEILD['total_amount'];
                                    $FEILD['pay_with_room'] = 1;
                                }
                            }
                            if(strtoupper(Url::get('sync_table'))=='RESERVATION'){
                                unset($FEILD['bcf_extra']);
                            }
                            if(strtoupper(Url::get('sync_table'))=='RESERVATION_ROOM'){
                                $exchange_rate = DB::fetch('select id,exchange from currency where id=\'VND\'','exchange');
                                $FEILD['usd_price'] = $FEILD['price']/$exchange_rate;
                                
                                unset($FEILD['is_guestfolio']);
                                
                                unset($FEILD['guestfolio_reservation_id']);
                            }
                            if(strtoupper(Url::get('sync_table'))=='WAITING_BOOK'){
                                $FEILD['portal_id'] = PORTAL_ID;
                            }
                            /** convert data **/
                            
                            $query='INSERT INTO';
                            $feild_value = '';
                            $feild_data = '';
                            foreach($FEILD as $keyFeild=>$valueFeild)
                    		{
                    		    $feild_value .= $feild_value==''?strtoupper($keyFeild):','.strtoupper($keyFeild);
                                $feild_data .= $feild_data==''?'\''.$valueFeild.'\'':','.'\''.$valueFeild.'\'';
                    		}
                            $query.=' '.strtoupper(Url::get('sync_table')).'('.$feild_value.') VALUES('.$feild_data.')';
                            //echo $query.'<br/>';
                            DB::query($query);
                        }
                    }
                }
            } 
            catch (HttpException $ex) 
            {
              echo $ex;
            }
	   }
	}
	function draw()
	{
	    $_REQUEST['link_api'] = 'http://newwaypms.ddns.net:8085/grand/';
        $this->map['list_table'] = array(
                                        'BAR'=>array('id'=>'BAR'),
                                        'BAR_CATEGORY'=>array('id'=>'BAR_CATEGORY'),
                                        'BAR_CHARGE'=>array('id'=>'BAR_CHARGE'),
                                        'BAR_NOTE'=>array('id'=>'BAR_NOTE'),
                                        'BAR_RESERVATION'=>array('id'=>'BAR_RESERVATION'),
                                        'BAR_RESERVATION_CANCEL'=>array('id'=>'BAR_RESERVATION_CANCEL'),
                                        'BAR_RESERVATION_PRODUCT'=>array('id'=>'BAR_RESERVATION_PRODUCT'),
                                        'BAR_RESERVATION_SPLIT'=>array('id'=>'BAR_RESERVATION_SPLIT'),
                                        'BAR_RESERVATION_TABLE'=>array('id'=>'BAR_RESERVATION_TABLE'),
                                        'BAR_SHIFT'=>array('id'=>'BAR_SHIFT'),
                                        'BAR_TABLE'=>array('id'=>'BAR_TABLE'),
                                        'BAR_TABLE_MERGE'=>array('id'=>'BAR_TABLE_MERGE'),
                                        
                                        'BOOKING_CONFIRM'=>array('id'=>'BOOKING_CONFIRM'),
                                        
                                        'BUILDING'=>array('id'=>'BUILDING'),
                                        
                                        'CUSTOMER'=>array('id'=>'CUSTOMER'),
                                        'CUSTOMER_CARE'=>array('id'=>'CUSTOMER_CARE'),
                                        'CUSTOMER_CONTACT'=>array('id'=>'CUSTOMER_CONTACT'),
                                        'CUSTOMER_DEBT_SETTLEMENT'=>array('id'=>'CUSTOMER_DEBT_SETTLEMENT'),
                                        'CUSTOMER_GROUP'=>array('id'=>'CUSTOMER_GROUP'),
                                        'CUSTOMER_RATE_COMMISSION'=>array('id'=>'CUSTOMER_RATE_COMMISSION'),
                                        'CUSTOMER_RATE_POLICY'=>array('id'=>'CUSTOMER_RATE_POLICY'),
                                        'DEBIT_RECEPTION'=>array('id'=>'DEBIT_RECEPTION'),
                                        
                                        'EMAIL_GROUP_EVENT'=>array('id'=>'EMAIL_GROUP_EVENT'),
                                        'EMAIL_GROUP_EVENT_CUSTOMER'=>array('id'=>'EMAIL_GROUP_EVENT_CUSTOMER'),
                                        'EMAIL_LIST'=>array('id'=>'EMAIL_LIST'),
                                        'EMAIL_SEND'=>array('id'=>'EMAIL_SEND'),
                                        
                                        'EXTRA_SERVICE'=>array('id'=>'EXTRA_SERVICE'),
                                        'EXTRA_SERVICE_INVOICE'=>array('id'=>'EXTRA_SERVICE_INVOICE'),
                                        'EXTRA_SERVICE_INVOICE_DETAIL'=>array('id'=>'EXTRA_SERVICE_INVOICE_DETAIL'),
                                        
                                        'FILE_CUSTOMER'=>array('id'=>'FILE_CUSTOMER'),
                                        
                                        'FOLIO'=>array('id'=>'FOLIO'),
                                        
                                        'GUEST_TYPE'=>array('id'=>'GUEST_TYPE'),
                                        
                                        'HISTORY_MEMBER'=>array('id'=>'HISTORY_MEMBER'),
                                        'HISTORY_MEMBER_DETAIL'=>array('id'=>'HISTORY_MEMBER_DETAIL'),
                                        
                                        'LOG'=>array('id'=>'LOG'),
                                        
                                        'MASSAGE_GUEST'=>array('id'=>'MASSAGE_GUEST'),
                                        'MASSAGE_PRODUCT'=>array('id'=>'MASSAGE_PRODUCT'),
                                        'MASSAGE_PRODUCT_CONSUMED'=>array('id'=>'MASSAGE_PRODUCT_CONSUMED'),
                                        'MASSAGE_RESERVATION'=>array('id'=>'MASSAGE_RESERVATION'),
                                        'MASSAGE_RESERVATION_ROOM'=>array('id'=>'MASSAGE_RESERVATION_ROOM'),
                                        'MASSAGE_ROOM'=>array('id'=>'MASSAGE_ROOM'),
                                        'MASSAGE_ROOM_STATUS'=>array('id'=>'MASSAGE_ROOM_STATUS'),
                                        'MASSAGE_STAFF'=>array('id'=>'MASSAGE_STAFF'),
                                        'MASSAGE_STAFF_ROOM'=>array('id'=>'MASSAGE_STAFF_ROOM'),
                                        
                                        
                                        'MINIBAR'=>array('id'=>'MINIBAR'),
                                        'MINIBAR_PRODUCT'=>array('id'=>'MINIBAR_PRODUCT'),
                                        
                                        
                                        'PAYMENT'=>array('id'=>'PAYMENT'),
                                        'PAY_BY_CURRENCY'=>array('id'=>'PAY_BY_CURRENCY'),
                                        
                                        'PRODUCT'=>array('id'=>'PRODUCT'),
                                        'PRODUCT_CATEGORY'=>array('id'=>'PRODUCT_CATEGORY'),
                                        'PRODUCT_CATEGORY_DISCOUNT'=>array('id'=>'PRODUCT_CATEGORY_DISCOUNT'),
                                        'PRODUCT_IMPORT'=>array('id'=>'PRODUCT_IMPORT'),
                                        'PRODUCT_LIMIT'=>array('id'=>'PRODUCT_LIMIT'),
                                        'PRODUCT_MATERIAL'=>array('id'=>'PRODUCT_MATERIAL'),
                                        'PRODUCT_PRICE_LIST'=>array('id'=>'PRODUCT_PRICE_LIST'),
                                        'PRODUCT_STATUS'=>array('id'=>'PRODUCT_STATUS'),
                                        
                                        'RESERVATION'=>array('id'=>'RESERVATION'),
                                        'RESERVATION_NOTE'=>array('id'=>'RESERVATION_NOTE'),
                                        'RESERVATION_ROOM'=>array('id'=>'RESERVATION_ROOM'),
                                        'RESERVATION_ROOM_SERVICE'=>array('id'=>'RESERVATION_ROOM_SERVICE'),
                                        'RESERVATION_TRAVELLER'=>array('id'=>'RESERVATION_TRAVELLER'),
                                        'RESERVATION_TYPE'=>array('id'=>'RESERVATION_TYPE'),
                                        
                                        'ROOM'=>array('id'=>'ROOM'),
                                        'ROOM_AMENITIES'=>array('id'=>'ROOM_AMENITIES'),
                                        'ROOM_CLEANUP'=>array('id'=>'ROOM_CLEANUP'),
                                        'ROOM_GOOD'=>array('id'=>'ROOM_GOOD'),
                                        'ROOM_LEVEL'=>array('id'=>'ROOM_LEVEL'),
                                        'ROOM_LEVEL_RATE'=>array('id'=>'ROOM_LEVEL_RATE'),
                                        'ROOM_PRODUCT'=>array('id'=>'ROOM_PRODUCT'),
                                        'ROOM_PRODUCT_DETAIL'=>array('id'=>'ROOM_PRODUCT_DETAIL'),
                                        'ROOM_STATUS'=>array('id'=>'ROOM_STATUS'),
                                        'ROOM_TYPE'=>array('id'=>'ROOM_TYPE'),
                                        
                                        'TRAVELLER'=>array('id'=>'TRAVELLER'),
                                        'TRAVELLER_COMMENT'=>array('id'=>'TRAVELLER_COMMENT'),
                                        'TRAVELLER_FOLIO'=>array('id'=>'TRAVELLER_FOLIO'),
                                        
                                        'WAITING_BOOK'=>array('id'=>'WAITING_BOOK'),
                                        'WAITING_INFORMATION'=>array('id'=>'WAITING_INFORMATION'),
                                        
                                        'WH_INVOICE'=>array('id'=>'WH_INVOICE'),
                                        'WH_INVOICE_DETAIL'=>array('id'=>'WH_INVOICE_DETAIL'),
                                        
                                        'WH_PRODUCT'=>array('id'=>'WH_PRODUCT'),
                                        'WH_PRODUCT_CATEGORY'=>array('id'=>'WH_PRODUCT_CATEGORY'),
                                        'WH_PRODUCT_PRICE'=>array('id'=>'WH_PRODUCT_PRICE'),
                                        'WH_RECEIVER'=>array('id'=>'WH_RECEIVER'),
                                        'WH_START_TERM_DEBIT'=>array('id'=>'WH_START_TERM_DEBIT'),
                                        'WH_START_TERM_REMAIN'=>array('id'=>'WH_START_TERM_REMAIN'),
                                        'WH_TEMP'=>array('id'=>'WH_TEMP'),
                                        'WH_TMP'=>array('id'=>'WH_TMP')
                                        
                                        );
        if(Url::get('act')=='GETDATA' and Url::get('sync_table') and Url::get('link_api')){
            $_SESSION['list_table_user'] += array(Url::get('sync_table')=>Url::get('sync_table'));
        }else{
            $_SESSION['list_table_user'] = array();
        }
        //System::debug($_SESSION['list_table_user']);
		$this->parse_layout('sync_module',$this->map);
	}
    function update_module($prefix, $code, $name, $package_name, $category_name=false)
    {
        //code luon phai viet hoa
		$code = trim(strtoupper($code));
		require_once 'packages/core/includes/utils/vn_code.php';	
		$module_name = $prefix.str_replace('-','',convert_utf8_to_url_rewrite($code));
		//Lay module_id de tao category
        //Neu da co module
        if($module = DB::fetch('select id,name from module where name = \''.$module_name.'\''))
        {
			$module_id = $module['id'];
		}
        else//Neu chua co thi tao moi module
        {
            //Lay package_id
            $package_id = DB::fetch('Select id from package where name = \''.$package_name.'\' ','id');
			//Tao module
            $module_id = DB::insert('module',array(
                                        			'name'=>$module_name,
                                        			'package_id'=>$package_id,
                                        			'path'=>'packages/hotel/packages/'.$package_name.'/modules/'.$module_name.'/',
                                        			'title_1'=>$module_name
                                                    )
                                    );//25: package warehousing
		}
        
        //Neu kiem tra theo name, thi khi edit name cua item se sinh ra category moi
		//if(!DB::exists('select id,name_1 from category where upper(name_1) = \''.strtoupper($name).'\''))
        //Chuyen thanh kiem tra theo module id
        //module_id dc lay tu module name, module name sinh ra tu code cua item + prefix
        //=> trong 1 table code la duy nhat => de module name la duy nhat => prefix khong duoc giong nhau giua cac bo phan
        //da ton tai categoy thi update lai ten
        if($row = DB::fetch('select id from category where module_id = '.$module_id.' and type = \'MODERATOR\' and portal_id = \''.PORTAL_ID.'\' '))
        {
            DB::update_id('category',array('name_1'=>$name,'name_2'=>$name),$row['id']);
		}
        else//Neu chua co category
        {
            //lay struct id cua cateory cha, cac category tao ra se la con cua no
            if(!$category_name)
                $parent_structure_id = ID_ROOT;
            else
            {
                require_once 'packages/core/includes/system/si_database.php';
                $parent_structure_id = structure_id('category',DB::fetch('Select id from category where name_1 = \''.$category_name.'\' or name_2 = \''.$category_name.'\'  ','id'));
            } 
			//Tao category
			DB::insert('category',array(
                        				'name_1'=>$name,
                        				'name_2'=>$name,
                        				'is_visible'=>1,
                        				'type'=>'MODERATOR',
                        				'structure_id'=>si_child('category',$parent_structure_id),
                        				'portal_id'=>PORTAL_ID,
                        				'status'=>'SHOW',
                        				'name_id'=>convert_utf8_to_url_rewrite($name),
                        				'check_privilege'=>1,
                        				'group_name_1'=>Portal::language('quyen'),//Language nao cung ra Quyen (do php design loi font khong viet dc chu Quyen =)) )
                        				'group_name_2'=>'Privilege',
                        				'module_id'=>$module_id
                        			)
                        );
        }
		return $module_name;
	}
}
?>
