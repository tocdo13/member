<?php
function check_run_install(){
	if(is_dir('install')){
		if(file_exists('cache/config/config.php') and file_exists('cache/config/db.php')){
			@rename('install','install_');			
		}else{
            if(file_exists('install/install.php'))
            {
                header('Location:install/install.php');
            }

		}
	}	
}
?>