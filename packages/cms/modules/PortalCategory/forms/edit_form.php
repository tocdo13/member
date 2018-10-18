<?php
/*class EditModuleForm
--get form object 
--update or add new form into database(doc_form)
developer:giap.luunguyen 14/8/2014
***************************************/

class EditModuleForm extends Form
{
    
    function EditModuleForm()
    {
        Form::Form('EditModuleForm');
    }
    
    function get_form()
    {
        $row = array('title'=>Url::get('title_form'),
        'name'=>Url::get('form_name'),
        'module_id'=>Url::get('module_id'),
        'note'=>Url::get('note_form'),
        'account_id'=>Url::get('staff_id_dev'),
        'status_1'=>Url::get('status_1')=='OK'?1:0,
        'status_2'=>Url::get('status_2')=='OK'?1:0);
        
        $module_id = Url::get('module_id');
        $module = DB::fetch('select id,name from doc_module where id='.$module_id);
        
        /*if (!file_exists("description_module/".$module['name'])) { 
            mkdir("description_module/".$module['name']);
        } */
        if (!file_exists("description_module/".$module['name']."/".ucfirst(Url::get('form_name')))) { 
            mkdir("description_module/".$module['name']."/".ucfirst(Url::get('form_name')));
        }
        $content = Url::get('description_form');
        $filename_des = "description_module/".$module['name']."/".Url::get('form_name')."/des_".date('d.m.Y').'.txt';
        $this->write_content_file($filename_des,$content);
        
        $content = Url::get('interpret_code');
        $filename_code = "description_module/".$module['name']."/".Url::get('form_name')."/code_".date('d.m.Y').'.txt';
        $this->write_content_file($filename_code,$content);
        
        $row = $row + array('path_description'=>$filename_des,'path_code'=>$filename_code);
        
        return $row;
    }
    function on_submit()
    {
         if(isset($_REQUEST['save']))
         {
            
             $row_new = $this->get_form();
             $module_id = Url::get('module_id');
             $module = DB::fetch("SELECT * FROM doc_module WHERE id=".$module_id);
             if(Url::get('cmd')=='edit_form')
             {
                 DB::update('doc_form',$row_new,' id='.Url::get('id')); 
             }
             else
             {
                 $new_form_id = DB::insert('doc_form',$row_new);
             }
             echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating form to server...</div>';
             echo '<script>
             window.setTimeout("location=\''.URL::build_current(array('cmd'=>'view_module','category_id'=>$module['category_id'])).'\'",2000);
             </script>';
             exit();
         }
    }
    function draw()
    {
        /*Start: get object form with id
        ***************************************/
        
        if(Url::get('cmd')=='edit_form')
        {
            $form_id = Url::get('id');
            $sql = "
                SELECT 
                     doc_form.id,
                     doc_form.module_id,
                     doc_form.title as title_form,
                     doc_form.path_description,
                     doc_form.path_code,
                     doc_form.account_id as staff_id_dev,
                     doc_form.status_1 as status_1_form,
                     doc_form.status_2 as status_2_form,
                     doc_form.note as note_form,
                     doc_form.name as form_name ,
                     doc_module.account_id as module_user_id,
                     doc_module.category_id
                FROM doc_form
                LEFT JOIN doc_module ON doc_module.id=doc_form.module_id
                WHERE doc_form.id=".$form_id;
                
            $object_form = @DB::fetch($sql) ;
            foreach($object_form as $key=>$value)
            {
                if(is_string($value) and !isset($_POST[$key]))
                {
                    $_REQUEST[$key] = $value;
                }
            }
            
            if(isset($_REQUEST['path_description']))
            {
               $_REQUEST['description_form'] = $this->read_content_file($_REQUEST['path_description']);
            }
            
            if(isset($_REQUEST['path_code']))
            {
               $_REQUEST['interpret_code'] = $this->read_content_file($_REQUEST['path_code']);
            }
        }
        //END get object form
		if(Url::get('cmd')=='add_form')
        {
			$module_id = Url::get('module_id');
			$module_staff =@DB::fetch("
			SELECT doc_module.id,
				doc_module.account_id as module_user_id,
                doc_module.category_id
			FROM doc_module
			WHERE doc_module.id=".$module_id);
			
			foreach($module_staff as $key=>$value)
            {
                if(is_string($value) and !isset($_POST[$key]))
                {
                    $_REQUEST[$key] = $value;
                }
            }
            
		}
        /*Start: get list Trang thai 1, trang thai 2, category, nguoi thuc hien 
        ***********************************************************************/ 
        $arr_status_1 = array(array('id'=>'NOTOK','name'=>'Not OK'),
        array('id'=>'OK','name'=>'OK'));
        $this->map['status_1_list'] =  array('ALL'=>Portal::language('All'))+ String::get_list($arr_status_1);
        $this->map['status_2_list'] =  array('ALL'=>Portal::language('All'))+ String::get_list($arr_status_1);
        
        $sql="SELECT account.id,party.name_1 as name
                FROM account
                INNER JOIN  party on party.user_id=account.id
                WHERE  description_1='Development'";
        
        $staff = DB::fetch_all($sql);
        $this->map['staff_id_dev_list'] = array('ALL'=>Portal::language('All'))+ String::get_list($staff);
        
        //END get list   
        $this->parse_layout('edit_form', $this->map);  
    } 
    
    function read_content_file($filename)
    {
        $content ='';
        if(file_exists($filename))
        {
            $file = fopen($filename,'r');
            while(!feof($file))
            {
                $content.= fgets($file);
            }
            fclose($file);
        }
        return $content;
    }
    function write_content_file($filename,$content)
    {
        $file =fopen($filename,'w');
        fwrite($file,$content);
        fclose($file);
    }
    
}
?>
