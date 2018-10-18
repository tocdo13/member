<?php
class EditModuleCodeAdminForm extends Form
{
	function EditModuleCodeAdminForm()
	{
		Form::Form('EditModuleCodeAdminForm');
		$this->add('id',new IDType(true,'object_not_exists','module'));
		//$this->add('code',new TextType(true,'invalid_name',0,2550000)); 
		//$this->add('layout',new TextType(true,'invalid_name',0,2550000)); 
		$this->link_css(Portal::template('core').'/css/tabs/largetabpane.css');
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{
			$id = $_REQUEST['id'];
			$row = DB::select('module',$id);
            if(is_dir($row['path']))
			{
			 
			    if(!is_dir('log_edit/modules'))
        		{
        		    if(!is_dir('log_edit'))
                    {
                        mkdir('log_edit');   
                    }
                    if(!is_dir('log_edit/modules'))
        		    {
                        mkdir('log_edit/modules');
      		        }
        		}
				$this->smartCopy($row['path'],'log_edit/modules/'.$row['name'].'_'.Session::get('user_id').'_'.date('Hisdmy'));
			}
			if(URL::get('files'))
			{
				foreach($_REQUEST['files'] as $name=>$content)
				{
					$f = fopen($row['path'].$name,'w+');
					fwrite($f,$content);
					fclose($f);
				}
			}			
			require_once 'packages/core/includes/portal/update_page.php';
			$pages = DB::fetch_all('select page_id as id from block where module_id=\''.$id.'\'');
			foreach($pages as $page_id=>$page)
			{
				update_page($page_id);
			}
			if(URL::get('href'))
			{
				URL::redirect_url('?'.URL::get('href'));
			}
			else
			{
				Url::redirect_current(array('cmd','id','package_id'=>$row['package_id'],'just_edited_id'=>$id));
	  		}
		}
	}	
	function draw()
	{	
		if($row=DB::select('module',URL::sget('id')))
		{
			$files = array(
				'class'=>array('name'=>'class','files'=>array()),
				'forms'=>array('name'=>'forms','files'=>array()),
				'layouts'=>array('name'=>'layouts','files'=>array())
			);
			$i = 0;
			
			$dir = opendir($row['path']);
			while($file = readdir($dir))
			{
				if(strpos($file,'.php'))
				{
					$i++;
					$files['class']['files'][$file] = array('id'=>$i, 'name'=>str_replace('.php','',$file),'path'=>$file,'content'=>$this->get_file_contents($row['path'].$file));
				}
			}
			if($dir = @opendir($row['path'].'layouts'))
			{
				while($file = readdir($dir))
				{
					if(strpos($file,'.php'))
					{
						$i++;
						$files['layouts']['files'][$file] = array('id'=>$i, 'name'=>'l/'.str_replace('.php','',$file),'path'=>'layouts/'.$file,'content'=>$this->get_file_contents($row['path'].'layouts/'.$file));
					}
				}
			}
			if($dir = @opendir($row['path'].'forms'))
			{
				while($file = readdir($dir))
				{
					if(strpos($file,'.php'))
					{
						$i++;
						$files['forms']['files'][$file] = array('id'=>$i, 'name'=>'f/'.str_replace('.php','',$file),'path'=>'forms/'.$file,'content'=>$this->get_file_contents($row['path'].'forms/'.$file));
					}
				}
			}
			$this->parse_layout('edit_code',$row+array('dirs'=>$files));
		}
	}
	function get_file_contents($file_name)
	{
		return str_replace(array('<','>'),array('&lt;','&gt;'),str_replace('&','&amp;',file_get_contents($file_name)));
	}
    function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755)) 
	{ 
		$result=false; 
		
		 if (is_file($source)) { 
            if ($dest[strlen($dest)-1]=='/') { 
                if (!file_exists($dest)) { 
                   // cmfcDirectory::makeAll($dest,$options['folderPermission'],true); 
                } 
                $__dest=$dest."/".basename($source); 
            } else { 
                $__dest=$dest; 
            } 
            $result=copy($source, $__dest); 
            chmod($__dest,$options['filePermission']); 
            
        } elseif(is_dir($source)) { 
			if ($dest[strlen($dest)-1]=='/') { 
				if ($source[strlen($source)-1]=='/') { 
					//Copy only contents 
				} else { 
					//Change parent itself and its contents 
					$dest=$dest.basename($source); 
					@mkdir($dest); 
					chmod($dest,$options['filePermission']); 
				} 
			} else { 
				if ($source[strlen($source)-1]=='/') { 
					//Copy parent directory with new name and all its content 
					@mkdir($dest,$options['folderPermission']); 
					chmod($dest,$options['filePermission']); 
				} else { 
					//Copy parent directory with new name and all its content 
					@mkdir($dest,$options['folderPermission']); 
					chmod($dest,$options['filePermission']); 
				} 
			} 
		
			$dirHandle=opendir($source); 
			while($file=readdir($dirHandle)) 
			{ 
				if($file!="." && $file!="..") 
				{ 
					 if(!is_dir($source."/".$file)) { 
						$__dest=$dest."/".$file; 
					} else { 
						$__dest=$dest."/".$file; 
					} 
					//echo "$source/$file ||| $__dest<br />"; 
					$result=$this->smartCopy($source."/".$file, $__dest, $options); 
				} 
			} 
			closedir($dirHandle); 
			
		} else { 
			$result=false; 
		} 
		return $result; 
	}
}
?>