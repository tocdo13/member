<?php
class UpdateVersionSyncSystemForm extends Form
{
	function UpdateVersionSyncSystemForm()
	{
		Form::Form('UpdateVersionSyncSystemForm');
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
                            if(strtoupper(Url::get('sync_table'))=='WAREHOUSE'){
                                 if($FEILD['code']!='' and $FEILD['module_name']!='')   
                                    $this->update_module('WH',strtoupper($FEILD['code']),$FEILD['name'],'warehousing','Warehouse list');
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
                                        'ACCOUNT'=>array('id'=>'ACCOUNT'),
                                        'ACCOUNT_PRIVILEGE'=>array('id'=>'ACCOUNT_PRIVILEGE'),
                                        'ACCOUNT_PRIVILEGE_GROUP'=>array('id'=>'ACCOUNT_PRIVILEGE_GROUP'),
                                        'ACCOUNT_RELATED'=>array('id'=>'ACCOUNT_RELATED'),
                                        'ACCOUNT_SETTING'=>array('id'=>'ACCOUNT_SETTING'),
                                        
                                        'AREA_GROUP'=>array('id'=>'AREA_GROUP'),
                                        
                                        'BANK'=>array('id'=>'BANK'),
                                        
                                        'COUNTRY'=>array('id'=>'COUNTRY'),
                                        'CREDIT_CARD'=>array('id'=>'CREDIT_CARD'),
                                        'CURRENCY'=>array('id'=>'CURRENCY'),
                                        
                                        'DEPARTMENT'=>array('id'=>'DEPARTMENT'),
                                        
                                        'LANGUAGE'=>array('id'=>'LANGUAGE'),
                                        
                                        'PARTY'=>array('id'=>'PARTY'),
                                        
                                        'PAYMENT_TYPE'=>array('id'=>'PAYMENT_TYPE'),
                                        
                                        'PORTAL_DEPARTMENT'=>array('id'=>'PORTAL_DEPARTMENT'),
                                        'PORTAL_PACKAGE'=>array('id'=>'PORTAL_PACKAGE'),
                                        'PORTAL_PRIVILEGE'=>array('id'=>'PORTAL_PRIVILEGE'),
                                        'PORTAL_TYPE'=>array('id'=>'PORTAL_TYPE'),
                                        
                                        'PRINTER'=>array('id'=>'PRINTER'),
                                        
                                        'PRIVILEGE'=>array('id'=>'PRIVILEGE'),
                                        'PRIVILEGE_GROUP'=>array('id'=>'PRIVILEGE_GROUP'),
                                        'PRIVILEGE_GROUP_DETAIL'=>array('id'=>'PRIVILEGE_GROUP_DETAIL'),
                                        'PRIVILEGE_MODERATOR'=>array('id'=>'PRIVILEGE_MODERATOR'),
                                        'PRIVILEGE_MODULE'=>array('id'=>'PRIVILEGE_MODULE'),
                                        
                                        'SECTORS'=>array('id'=>'SECTORS'),
                                        'SESSION_USER'=>array('id'=>'SESSION_USER'),
                                        
                                        'TYPE'=>array('id'=>'TYPE'),
                                        
                                        'WAREHOUSE'=>array('id'=>'WAREHOUSE'),
                                        
                                        'ZONE'=>array('id'=>'ZONE')
                                        );
        if(Url::get('act')=='GETDATA' and Url::get('sync_table') and Url::get('link_api')){
            $_SESSION['list_table_user'] += array(Url::get('sync_table')=>Url::get('sync_table'));
        }else{
            $_SESSION['list_table_user'] = array();
        }
        //System::debug($_SESSION['list_table_user']);
		$this->parse_layout('sync_system',$this->map);
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
