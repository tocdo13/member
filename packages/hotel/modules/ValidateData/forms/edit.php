<?php
class EditValidateDataForm extends Form
{
	function EditValidateDataForm()
	{
		Form::Form('EditValidateDataForm');
	}
	function draw()
	{	            
	   $this->map = array();
       /**
        * @Portal: Scan portal in folder cache/portal/ + resources/interfaces/images/ + cache/data/
       */
       $portal_list = Portal::get_portal_list();
       $this->map['portal_list'] = $portal_list;
       $this->map['portal_folder_error'] = array();
       // cache/portal/
           $cache_portal = scandir('cache/portal');
           for($i=0;$i<sizeof($cache_portal);$i++){
                if($i>1 and !isset($portal_list['#'.$cache_portal[$i]])){
                    $this->map['portal_folder_error']['#'.$cache_portal[$i]]['id'] = '#'.$cache_portal[$i];
                    $this->map['portal_folder_error']['#'.$cache_portal[$i]]['cache']['#'.$cache_portal[$i]]['link'] = 'cache/portal/'.$cache_portal[$i];
                }
           }
       // resources/interfaces/images/
           $resources_portal =  scandir('resources/interfaces/images');
           for($i=0;$i<sizeof($resources_portal);$i++){
                if($i>1 and !isset($portal_list['#'.$resources_portal[$i]])){
                    $this->map['portal_folder_error']['#'.$resources_portal[$i]]['id'] = '#'.$resources_portal[$i];
                    $this->map['portal_folder_error']['#'.$resources_portal[$i]]['resources']['#'.$resources_portal[$i]]['link'] = 'resources/interfaces/images/'.$resources_portal[$i];
                }
           } 
       // cache/data/
           $cache_portal =  scandir('cache/data');
           for($i=0;$i<sizeof($cache_portal);$i++){
                if($i>1 and !isset($portal_list['#'.$cache_portal[$i]])){
                    $this->map['portal_folder_error']['#'.$cache_portal[$i]]['id'] = '#'.$cache_portal[$i];
                    $this->map['portal_folder_error']['#'.$cache_portal[$i]]['data']['#'.$cache_portal[$i]]['link'] = 'cache/data/'.$cache_portal[$i];
                }
           }
       
        
        /**
         * @Account: Scan Account in Portal 
        */
        $account_list = DB::fetch_all('SELECT 
                        					ACCOUNT.ID,
                                            party.full_name,
                                            ACCOUNT.is_active
                        				FROM
                        					ACCOUNT
                        					INNER JOIN party ON party.user_id = account.id
                        					LEFT OUTER JOIN zone ON zone.id = party.zone_id
                        					LEFT OUTER JOIN account_related ON account_related.child_id = account.id
                        					LEFT OUTER JOIN session_user ON session_user.user_id = account.id
                        				WHERE ACCOUNT.TYPE=\'USER\' AND PARTY.TYPE=\'USER\'');
        $this->map['account_list'] = $account_list;
        $this->map['account_file_error'] = array();
        // cache/portal/
           $cache_portal = scandir('cache/portal');
           for($i=0;$i<sizeof($cache_portal);$i++){
                if($i>1 and !isset($portal_list['#'.$cache_portal[$i]])){
                    $cache_account = scandir('cache/portal/'.$cache_portal[$i].'/user');
                    for($j=0;$j<sizeof($cache_account);$j++){
                        if($j>1){
                            $this->map['account_file_error']['#'.$cache_portal[$i]]['id'] = '#'.$cache_portal[$i];
                            $this->map['account_file_error']['#'.$cache_portal[$i]]['user'][str_replace('.php','',$cache_account[$j])]['link'] = 'cache/portal/'.$cache_portal[$i].'/user/'.$cache_account[$j];
                            $this->map['account_file_error']['#'.$cache_portal[$i]]['user'][str_replace('.php','',$cache_account[$j])]['id'] = str_replace('.php','',$cache_account[$j]);
                        }
                    }
                }elseif($i>1){
                    $cache_account = scandir('cache/portal/'.$cache_portal[$i].'/user');
                    for($j=0;$j<sizeof($cache_account);$j++){
                        if($j>1){
                            if(!isset($account_list[str_replace('.php','',$cache_account[$j])])){
                                $this->map['account_file_error']['#'.$cache_portal[$i]]['id'] = '#'.$cache_portal[$i];
                                $this->map['account_file_error']['#'.$cache_portal[$i]]['user'][str_replace('.php','',$cache_account[$j])]['link'] = 'cache/portal/'.$cache_portal[$i].'/user/'.$cache_account[$j];
                                $this->map['account_file_error']['#'.$cache_portal[$i]]['user'][str_replace('.php','',$cache_account[$j])]['id'] = str_replace('.php','',$cache_account[$j]);
                            }
                        }
                    }
                }
           }
        
        /**
         * @GetAllProduct: Select All Product
        */
        $product = DB::fetch_all('select * from product order by id');
        
        /**
         * @GetAllRoom: Select All Room
        */
        $room = DB::fetch_all('select * from room order by name');
        
        /**
         * @Amenities: Scan product amenities used in Portal 
        */
        $this->map['amenities_error'] = array();
        $amenities_list = DB::fetch_all('select * from amenities_used_detail order by room_id,product_id');
        foreach($amenities_list as $key=>$value){
            if(!isset($product[$value['product_id']]) or !isset($room[$value['room_id']]) or !isset($portal_list[$value['portal_id']])){
                $this->map['amenities_error'][$key] = $value;
            }
        }
        
        /**
         * @areaGroupRoom: Get All Group Room 
        */
        $this->map['area_group_error'] = array();
        $area_group_list = DB::fetch_all('select * from area_group order by code');
        $group_room_real = DB::fetch_all('select area_id as id from room where area_id is not null');
        foreach($area_group_list as $key=>$value){
            if(!isset($group_room_real[$value['code']]) or $value['code']==''){
                $this->map['area_group_error'][$key] = $value;
            }
        }
        
        
        
       //System::debug($this->map['area_group_error']);
       
       $this->parse_layout('edit',$this->map);
	}
}
?>