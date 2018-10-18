<?php
class DuplicatePageForm extends Form
{
	function DuplicatePageForm()
	{
		Form::Form('duplicatePage');
		$this->add('id',new IDType(true,false,'PAGE'));
		$this->add('name',new NameType(true,'missing_name'));
		$languages = DB::select_all('LANGUAGE');
		foreach($languages as $language)
		{
			$this->add('title_'.$language['id'],new TextType(false, 'miss_title',0,2000));
			$this->add('description_'.$language['id'],new TextType(false, 'miss_description',0,10000));
		}
		$this->add('params',new TextType(false,'invalid_params',0,255));
		$this->link_css(Portal::template('core').'/css/admin.css');
		$this->link_css(Portal::template('core').'/css/tabs/tabpane.css');
	}
	/// thuc hien cac hanh dong submit form
	function on_submit()
	{
		if($this->check())
		{
			if(DB::select('PAGE','NAME=\''.DB::escape($_REQUEST['name']).'\' and PARAMS=\''.DB::escape($_REQUEST['name']).'\''))
			{
				$this->error('name','trang &#273;&#227; t&#7891;n t&#7841;i. Ch&#7885;n t&#234;n kh&#225;c!');
			}
			else
			{
				if($page = DB::select('PAGE',URL::get('id')))
				{
					require_once 'packages/core/includes/portal/clone.php';
					$languages = DB::select_all('LANGUAGE');
					$extra = array();
					foreach($languages as $language)
					{
						$page['title_'.$language['id']] = $_REQUEST['title_'.$language['id']];
						$page['description_'.$language['id']] = $_REQUEST['description_'.$language['id']];
					}
					if(CloneLib::clone_page($page, URL::get('name'), URL::get('params')))
					{
						Url::redirect_current();
					}
				}
				$this->error('name','Kh&#244;ng th&#7875; t&#7841;o trang &#273;&#432;&#7907;c');
			}
		}
	}		
	// hien thi form sua doi thong tin cua Page
	function draw()
	{	
		$page = DB::select('PAGE',URL::get('id'));
		$languages = DB::select_all('LANGUAGE');
		foreach($languages as $key=>$language)
		{
			$languages[$key] = $language+array('title'=>$page['title_'.$key],'description'=>$page['description_'.$key]);
		}
		$this->parse_layout('duplicate',$page+array(
			'languages'=>$languages
		));
	}
}//end class
?>