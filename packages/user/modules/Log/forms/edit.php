<?php
class EditLogForm extends Form
{
	function EditLogForm()
	{
		Form::Form('EditLogForm');
		$this->add('id',new IDType(true,'object_not_exists','log'));	
		$this->add('type',new TextType(false,'invalid_type',0,255));
		$this->add('description',new TextType(false,'invalid_description',0,2000)); 

		$this->add('parameter',new TextType(false,'invalid_parameter',0,255)); 

		$this->add('note',new TextType(false,'invalid_note',0,2000)); 

		$this->add('title',new TextType(false,'invalid_title',0,255));
		$this->add('module_id',new IDType(true,'invalid_module_id','module')); 
	}
	function on_submit()
	{

		$row = DB::select('log',$_REQUEST['id']);
		
		if(URL::get('delete_table') and URL::get('delete_id'))
		{
			$item_row = DB::select(URL::get('delete_table'),URL::get('delete_id'));
			DB::delete_id(URL::get('delete_table'),URL::get('delete_id'));
		}
		else
		if($this->check())
		{		
				
			DB::update_id('log', 
				array(
					'user_id', 'module_id', 'type', 'description', 'parameter', 'note', 'title', 
				),
				$_REQUEST['id']
			);
			$title = ''
			.substr(URL::get('time'),0,32).'  |  ' .substr(URL::get('type'),0,32).'  |  '   .substr(URL::get('note'),0,32).'  |  ' .substr(URL::get('title'),0,32).'  |  ' 
			.URL::get('user_id').'  |  ' .URL::get('module_id').'  |  ' ;
			$description = ''
			.((URL::get('time')!=$row['time'])?'Change '.Portal::language('time').' from '.substr($row['time'],0,255).' to '.substr(URL::get('time'),0,255).'<br>  ':'') .((URL::get('type')!=$row['type'])?'Change '.Portal::language('type').' from '.substr($row['type'],0,255).' to '.substr(URL::get('type'),0,255).'<br>  ':'') .((URL::get('description')!=$row['description'])?'Change '.Portal::language('description').' from '.substr($row['description'],0,255).' to '.substr(URL::get('description'),0,255).'<br>  ':'') .((URL::get('parameter')!=$row['parameter'])?'Change '.Portal::language('parameter').' from '.substr($row['parameter'],0,255).' to '.substr(URL::get('parameter'),0,255).'<br>  ':'') .((URL::get('note')!=$row['note'])?'Change '.Portal::language('note').' from '.substr($row['note'],0,255).' to '.substr(URL::get('note'),0,255).'<br>  ':'') .((URL::get('title')!=$row['title'])?'Change '.Portal::language('title').' from '.substr($row['title'],0,255).' to '.substr(URL::get('title'),0,255).'<br>  ':'') 
			.((URL::get('user_id')!=$row['user_id'])?'Change '.Portal::language('user_id').' from <a href=\'?page=user&id='.$row['user_id'].'\'>#'.$row['user_id'].'</a> to <a href=\'?page=user&id='.URL::get('user_id').'\'>#'.URL::get('').'</a><br>  ':'') .((URL::get('module_id')!=$row['module_id'])?'Change '.Portal::language('module_id').' from <a href=\'?page=module&id='.$row['module_id'].'\'>#'.$row['module_id'].'</a> to <a href=\'?page=module&id='.URL::get('module_id').'\'>#'.URL::get('').'</a><br>  ':'') ;
			System::log('edit',$title,$description,$_REQUEST['id']);
			Url::redirect_current();
		}
	}	
	function draw()
	{	
		
		//require_once 'package_system/library/rich_editor.php';
		$sql ='
			select 
				*
			from 
			 	log
			where
				id = '.$_REQUEST['id'].'
		';
		$mode =false;
		if($row = DB::fetch($sql))
		{
			$mode = true;
		}
		$rows=DB::fetch_all('select id,id as name from account where type=\'USER\' and is_active=1');
		$this->parse_layout('edit',
			($mode)?$row+
			array(
				'user_id_list'=>String::get_list($rows), 
				'module_id_list'=>String::get_list(DB::select_all('module',false,'name')), 
			):
			array(
				'user_id_list'=>String::get_list($rows), 
				'module_id_list'=>String::get_list(DB::select_all('module',false,'name')), 
			)
		);
	}
}
?>