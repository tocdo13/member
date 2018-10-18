<?php
/*class EditModule
--get module object 
--update or add new module into database(doc_module)
developer:giap.luunguyen 10/8/2014
***************************************/

class EditModule extends Form
{
    function EditModule()
    {
        Form::Form('EditModule');
    }
    function get_module()
    {
        $name = trim(Url::get('name'));
        $row = array('title'=>Url::get('title_module'),
        'name'=>$name,
        'note'=>Url::get('note_module'),
        'category_id'=>Url::get('category'),
        'account_id'=>Url::get('staff_id_dev'),
        'status_1'=>Url::get('status_1')=='OK'?1:0,
        'status_2'=>Url::get('status_2')=='OK'?1:0);
        
        $content = Url::get('description_module');
        if(!file_exists("description_module/".$name)) { 
            mkdir("description_module/".$name);
        } 
        $filename = "description_module/".$name."/description_".date('d.m.Y').'.txt';
        $this->write_content_file($filename,$content);
        $row = $row + array('path'=>$filename);
        
        return $row;
    }
    function on_submit()
    {
        /*Start: get object module info 
        ***********************************************/
        $row_module_new = $this->get_module();
        //END get object 
        if(isset($_REQUEST['save']))
        {
            if(Url::get('category')==1)
            {
                $_REQUEST['error'] ='Bạn phải chọn category!';
            }
            else
            {
                if(Url::get('cmd')=='edit_module')
                {
                    $name = $row_module_new['name'];
                    unset($row_module_new['name']);
                    if(DB::exists("SELECT * FROM doc_module WHERE name='$name'"))
                    {
                        DB::update('doc_module',$row_module_new,"name='$name'");
                    }
                    else
                        $new_id_module = DB::insert('doc_module',$row_module_new);
                   
                   
                }  
                
                echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating module to server...</div>';
                echo '<script>
                window.setTimeout("location=\''.URL::build_current(array('cmd'=>'view_module','category_id'=>Url::get('category_id'))).'\'",2000);
                </script>';
                exit();
            }
        }
    }
    function draw()
    {
        /*Start: get object module with id
        ***************************************/
        if(Url::get('cmd')=='edit_module')
        {
            $category_id = Url::get('category_id');
            $sql = "SELECT 
                    module.id,
                    module.name,
                    doc_module.name as module_name,
                    doc_module.title as title_module,
                    doc_module.account_id as user_id,
                    doc_module.status_1 as status_1_module,
                    doc_module.status_2 as status_2_module,
                    category.name_1 as category_name,
                    category.id as category_id,
                    doc_module.path as path_module, 
                    doc_module.note as note_module 
                FROM module
                INNER JOIN category ON category.module_id=module.id 
                LEFT JOIN doc_module ON doc_module.category_id=category.id
                WHERE category.id=".$category_id;
                
            $object_module = @DB::fetch($sql) ;
            foreach($object_module as $key=>$value)
            {
                if(is_string($value) and !isset($_POST[$key]))
                {
                    $_REQUEST[$key] = $value;
                }
            }
            
            if(isset($_REQUEST['path_module']))
            {
               $_REQUEST['description_module'] = $this->read_content_file($_REQUEST['path_module']);
            }
        }
        //END get object module
        
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
        
        $sql ='Select id,name_1 as name,structure_id from category ORDER BY structure_id';
        $category = DB::fetch_all($sql);
        $this->map['category_list'] = String::get_list($category);
        //END get list
        
        $this->parse_layout('edit_module',$this->map);  
    } 
    /*Start: read file return content string
    ************************************************/
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
    //END read file
    
    /*Start: write file with parameter: open file_name, write content into file_name
    **********************************************************************************/
    function write_content_file($filename,$content)
    {
        $file = fopen($filename,'w');
        fwrite($file,$content);
        fclose($file);
    }
    //END write file 
    
}
?>
