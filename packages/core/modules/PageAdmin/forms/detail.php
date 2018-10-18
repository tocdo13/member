<?php
class PageAdminForm extends Form
{
	function PageAdminForm()
	{
		Form::Form("PageAdminForm");
		$this->add('id',new IDType(false,'object_not_exists','PAGE'));
		$this->link_css(Portal::template('core').'/css/admin.css');
		$this->link_css(Portal::template('core').'/css/category.css');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			$this->delete($this,$_REQUEST['id']);
			Url::redirect_current(array('PORTAL_ID','NAME','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:''));
		}
	}
	function draw()
	{
		$languages = DB::select_all('LANGUAGE');
		DB::query('
			SELECT 
				PAGE.ID
				,PAGE.NAME ,PAGE.CACHABLE,PAGE.CACHE_PARAM,PAGE.PARAMS
				,PAGE.TITLE_'.Portal::language().' as TITLE 
				,PAGE.DESCRIPTION_'.Portal::language().' as DESCRIPTION
				,PACKAGE.NAME as package_id 
			FROM 
			 	PAGE,PACKAGE
			WHERE
				PAGE.ID = \''.URL::sget('id').'\'
				AND  PACKAGE.ID(+)=PAGE.package_id
				');
		if($row = DB::fetch())
		{
		}
		$languages = DB::select_all('language');
		DB::query('
			SELECT 
				MODULE.* 
			FROM 
				MODULE
				inner join  BLOCK ON BLOCK.MODULE_ID=MODULE.ID
			WHERE 
				1>0 and PAGE_ID = '.$row['id'].'
		');
		$row['module_related_fields'] = DB::fetch_all(); 
		$this->parse_layout('detail',$row+array('languages'=>$languages));
	}
	function delete(&$form,$id)
	{
		$row = DB::select('PAGE',$id);
		$blocks = DB::select_all('BLOCK','PAGE_ID='.$id);
		foreach ($blocks as $key=>$value)
		{
			DB::delete('BLOCK_SETTING', 'BLOCK_ID='.$value['id']); 
		}
		DB::delete('BLOCK', 'PAGE_ID='.$id); 
		DB::delete_id('PAGE', $id);		
	}
}
?>