<?php 
class ValidateData extends Module
{
	function ValidateData($row)
	{
	    if(Url::get('action')=='delete_directory'){
            if(Url::get('link_uri')){
               if(is_dir(Url::get('link_uri'))){
                    $this->deleteDirectory(Url::get('link_uri'));
                   echo 'Đã xóa Folder : '.Url::get('link_uri');
               }elseif(is_file(Url::get('link_uri'))){
                   unlink(Url::get('link_uri'));
                   echo 'Đã xóa File : '.Url::get('link_uri');
               }else{
                   echo '';
               }
            }else{
               echo '';
            }
            exit();
	    }
        if(Url::get('action')=='delete_database'){
            if(DB::exists('select id from '.Url::get('table_name').' where id='.Url::get('field_id'))){
               DB::delete(Url::get('table_name'),'id='.Url::get('field_id'));
               echo 'Success!';
            }else{
               echo '';
            }
            exit();
	    }
        
		Module::Module($row);
		if(User::is_admin() OR User::is_deploy())
		{
			require_once 'forms/edit.php';
			$this->add_form(new EditValidateDataForm());
		}
		else
		{
			URL::access_denied();
		}
	}
    function deleteDirectory($dirPath) {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != "." && $object !="..") {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }
}
?>