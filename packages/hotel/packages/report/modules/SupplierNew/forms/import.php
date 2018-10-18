<?php
    class ImportSupplierFormNew extends Form
    {
        function ImportSupplierFormNew()
        {
            Form::Form('ImportSupplierFormNew');
        }
        //function on_submit()
//        {
//            
//        }
        function draw()
        {
            $this->map= array();
            if(Url::get('do_action'))
            {
                $file = $this->save_file();  
                //System::debug($file);
                $file_type = explode(".",$file);
            }
            $this->parse_layout('import',$this->map);
        }
        function save_file()
        {
		$dir = 'excel';
        $file='path';
        if(isset($_FILES[$file]) and $_FILES[$file]['name'])
            {
           	    if(file_exists('resources/'.$dir.'/'.$_FILES[$file]['name']))
    			{
    				$new_name = 'resources/'.$dir.'/'.time().'_'.$_FILES[$file]['name'];
    			}
    			else
    			{
    				$new_name = 'resources/'.$dir.'/'.$_FILES[$file]['name'];
    			}
                System::debug($new_name);exit();
    			$_REQUEST[$file] = $new_name;
                 move_uploaded_file($_FILES[$file]['tmp_name'],$_REQUEST[$file]);  
            }
        }
        
        
    }
    
    
    

?>