<?php
 
class SignInApi extends Module
{
	function SignInApi($row)
	{
		Module::Module($row);
        
        if(Url::get('cmd')=='IOS')
        {
            require_once 'packages/core/includes/system/restful_api.php';
            require_once 'packages/hotel/packages/app/modules/SignInApi/api.php';
        }else
        {
            if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $response = array("error" => false);
          		$is_login = false;
                if(Url::get('user_id') && Url::get('password'))
                {
                    $user = Url::sget('user_id');
            		$sql = '
            			select 
            				account.id,
            				account.type,
            				account.home_page,
            				account.restricted_access_ip,
            				account.language_id,
            				party.email,party.name_'.Portal::language().' as full_name 
            			from 
            				account 
            				inner join party on party.user_id=account.id 
            			where 
            				account.id=\''.$user.'\' and party.type=\'USER\' 
            				and password=\''.User::encode_password(Url::get('password')).'\' 
            				and account.is_active=1
            				and (account.is_block IS NULL OR account.is_block = 0)
            		';
            		if(!$row=DB::fetch($sql))
                    {
            			$response["error"] = true;
                        $response["error_msg"] = "Invalid username or password!";
            		}else
                    {
            			if(!User::is_admin() and $row['restricted_access_ip'])
                        {
            				if(preg_match('"'.str_replace('.','-',$row['restricted_access_ip']).'"', str_replace('.','-',$_SERVER['REMOTE_ADDR'])))
                            {
            					$is_login = true;
            				}else
                            {
            					$response["error"] = true;
                                $response["error_msg"] = "Access denies from this ip!";
            				}
            			}else
                        {
            				$is_login = true;	
            			}
            		}		
            		if($is_login)
                    {
            			{
            				Session::$name = md5($_SERVER['REMOTE_ADDR'].'&'.Session::getIP());
            				if(DB::exists('SELECT id FROM session_user WHERE user_id = \''.$user.'\''))
                            {
            					//DB::delete('session_user','user_id=\''.$user.'\'');
            				}
            				$vars = 'array("user_id"=>"'.$user.'")';
            				$id = DB::insert('SESSION_USER',array(
            					'session_id'=>Session::$name,
            					'user_id'=>$user,
            					'ip'=>$_SERVER['REMOTE_ADDR'],
            					'time'=>time(),
            					'last_active_time'=>time(),
            					'vars'=> $vars,
            					'language_id'=>$row['language_id']?$row['language_id']:1
            				));
            				//Session::set('user_id',$row['id']);
            				//Session::set('user_data',$row);
            				//setcookie('user_id',$row['id']);
            				if(sizeof(Portal::get_portal_list())>1)
                            {
            					Url::redirect('select_portal');
            				}else
                            {
            					$portal = array_shift(Portal::get_portal_list());
            					$portal_id = $portal['id'];
            					DB::update('SESSION_USER',array('portal_id'=>$portal_id),'id='.$id.'');
                                
                                $response["error"] = false;
                                $response["uid"] = uniqid('NewwayPMS_');
                                $response["user"]["full_name"] = $row["full_name"];
                                $response["user"]["user_id"] = $row["id"];
                                $response["user"]["created_at"] = time();
                                $response["user"]["updated_at"] = time();
            				}
            			}
            		}
                }
                echo json_encode($response);exit();
            }
        }
	}
}
?>