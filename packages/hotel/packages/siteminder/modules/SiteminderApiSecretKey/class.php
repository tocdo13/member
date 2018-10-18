<?php 
class SiteminderApiSecretKey extends Module
{
	function SiteminderApiSecretKey($row)
	{
	    if(User::is_deploy() OR User::is_admin())
        {
	       if(Url::get('status')=='LOADSECRETKEY')
           {
	           $data = DB::fetch_all('SELECT * from siteminder_apikey order by lifetimes DESC');
               foreach($data as $key=>$value){
                    $data[$key]['lifetimes'] = date('H:i d/m/Y',$value['lifetimes']);
               }
               echo json_encode($data);
               exit();
	       }
           elseif(Url::get('status')=='GETSECRETKEY' AND Url::get('secretname') AND Url::get('secretpass'))
           {
               $data = array('secretkey'=>'','create_time'=>''); 
               $lifetime = time(); 
	           $secretkey = md5(Url::get('secretname').''.Url::get('secretpass').$lifetime);
               $data = array('secretkey'=>$secretkey,'create_time'=>date('H:i d/m/Y',$lifetime),'lifetimes'=>$lifetime);
               echo json_encode($data);
               exit();
	       }
           elseif(Url::get('status')=='SAVESECRETKEY' AND Url::get('secretname') AND Url::get('secretpass') AND Url::get('secretkey') AND Url::get('lifetimes'))
           {
               $data = array('status'=>0,'messenge'=>''); 
               $lifetime = Url::get('lifetimes'); 
	           $secretkey = md5(Url::get('secretname').''.Url::get('secretpass').$lifetime);
               if($secretkey!=Url::get('secretkey')){
                    $data['messenge'] = 'OPP! You must not change SecretKey!';
                    echo json_encode($data);
                    exit();
               }
               if($_REQUEST['apikey_id']==''){
                    DB::insert('siteminder_apikey',array('secretname'=>Url::get('secretname'),'secretpass'=>Url::get('secretpass'),'secretkey'=>$secretkey,'lifetimes'=>Url::get('lifetimes')));
               }else{
                    DB::update('siteminder_apikey',array('secretname'=>Url::get('secretname'),'secretpass'=>Url::get('secretpass'),'secretkey'=>$secretkey,'lifetimes'=>Url::get('lifetimes')),'id='.Url::get('apikey_id'));
               }
               $data = array('status'=>1,'messenge'=>'Success!'); 
               echo json_encode($data);
               exit();
	       }
           elseif(Url::get('status')=='EDITSECRETKEY' AND Url::get('apikey_id'))
           {
               $data = DB::fetch('SELECT * from siteminder_apikey where id='.Url::get('apikey_id').'');
               $data['create_time'] = date('H:i d/m/Y',$data['lifetimes']);
               echo json_encode($data);
               exit();
	       }
           elseif(Url::get('status')=='DELETESECRETKEY' AND Url::get('apikey_id'))
           {
               DB::delete('siteminder_apikey','id='.Url::get('apikey_id'));
               echo json_encode(array());
               exit();
	       }
        }
		Module::Module($row);
        if(User::is_deploy() OR User::is_admin())
        {
            require_once 'forms/edit.php';
    		$this->add_form(new SiteminderApiSecretKeyForm());
        }else{
            Url::access_denied();
        }
	}
}
?>