<?php
/*class ViewModuleForm
 
***************************************/

class ViewModuleForm extends Form
{
    
    function ViewModuleForm()
    {
        Form::Form('ViewModuleForm');
     }
    function draw()
    {
        /*Start: get object module with id
        ***************************************/
        $module_id = Url::get('module_id');
        $sql = "SELECT module.id,
                module.name as module_name,
                module.id as module_id,
                doc_module.id as doc_module_id,
                doc_module.title as title_module,
                doc_module.account_id as user_id,
                doc_module.status_1  as status_1_module,
                doc_module.status_2 as status_2_module,
                category.name_1 as category_name,
                category.url,
                doc_module.path as path_module, 
                doc_module.note as note_module,
                party.name_1 as account
            FROM module
            LEFT JOIN doc_module ON doc_module.module_id=module.id
            LEFT JOIN category ON category.module_id=module.id
            LEFT JOIN account ON doc_module.account_id=account.id
            LEFT JOIN party ON account.id=party.user_id
            WHERE module.id=".$module_id;
            
        $object_module = DB::fetch($sql) ;
    
        if(empty($object_module)==false)
        {
            foreach($object_module as $key=>$value)
            {
                if(is_string($value) and !isset($_POST[$key]))
                {
                    $_REQUEST[$key] = $value;
                }
            }
        }
        //END get object module 
        
        /*Start: read file name txt path object module
        *****************************************************/
        if(isset($_REQUEST['path_module']))
        {
           $_REQUEST['description_module'] = $this->read_content_file($_REQUEST['path_module']);
        }
        //END
        
        /*Start: get list form of module
        ********************************************/
        $sql ="
        SELECT doc_form.id,
            doc_form.title,
            doc_form.path_description,
            doc_form.path_code,
            doc_form.account_id as user_id,
            doc_form.status_1,
            doc_form.status_2,
            doc_form.note,
            doc_form.name,
            party.name_1 as account
        FROM doc_form
        INNER JOIN doc_module ON doc_module.id=doc_form.module_id
        INNER JOIN module ON module.id=doc_module.module_id
        INNER JOIN account ON account.id=doc_form.account_id
        INNER JOIN party ON party.user_id=account.id
        WHERE module.id=".$module_id;
        
        $items = DB::fetch_all($sql);
        
        $status_1_form = true;
        $status_2_form = true;
        foreach($items as $key=>$value)
        {
            $items[$key]['description_form'] = $this->read_content_file($value['path_description']);
            $items[$key]['interpert_code']  = $this->read_content_file($value['path_code']) ;
            
           if($value['status_1']==0)
           {
               $status_1_form = false;
           }
            
           if($value['status_2']==0)
           {
                $status_2_form = false;
           }
        }
        $this->map['items'] = $items;
        
        //END get list form
        $_REQUEST['status_1_form']  = $status_1_form;
        $_REQUEST['status_2_form'] =  $status_2_form;
        $this->parse_layout('view_module',$this->map);  
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
}
?>