<?php
class FooterForm extends Form
{
	function FooterForm()
	{
		Form::Form('FooterForm');
		$this->link_css('packages/utils/skins/default/css/footer_toolbar.css');
	}
	function draw()
	{
		$content = Portal::get_setting('estore_footer_'.Portal::language(),false);
		$button_name='hidden_'.Module::block_id();
		$button='<a href="'.URL::build('account_setting',array('category_id'=>44,'portal'=>substr(PORTAL_ID,1),'account_id'=>PORTAL_ID)).'#enchor_estore_footer_content_1"><img src="'.Portal::template('portal').'/images/buttons/select.jpg" alt="Thay &#273;&#7893;i n&#7897;i dung"></a>';
		if($content)
		{
			$languages = DB::select_all('language');
		}
		$number_format = number_format(Portal::$page_gen_time->get_timer(),4);
		$link_admin_html = URL::build('admin_html',array('href'=>'?'.$_SERVER['QUERY_STRING'],'block_id'=>Module::block_id()));
		
		$information_query_in_page='';		
		$total_query=0;
		if(DEBUG==1)
		{			
			$str='';
			if(isset($GLOBALS['information_query']) and count($GLOBALS['information_query'])>0)
			{
				foreach($GLOBALS['information_query'] as $key=>$value)
				{
					$str.='<span style="font-weight:bold">Module '.$value['name'].' have got '.$value['number_queries'].' query (in time '.$value['timer'].'s).</span><br><span style="text-decoration:underline">Query excuted :</span><br>';
					$query='';
					if(is_array($value['query']) and count($value['query'])>0)
					{
						foreach($value['query'] as $key_query=>$value_query)
						{
							$query.='<span style="color:#ff0000;padding-left:50px">'.$value_query.'</span><br>';
						}	
					}
					$total_query+=$value['number_queries'];
					$str.=$query;
				}		
			}
			$information_query_in_page=$str;
		}	
		$this->map['current_portal'] = DB::fetch('select account.id,party.name_1 from account inner join party on party.user_id = account.id where account.id=\''.PORTAL_ID.'\'','name_1');
		//$this->parse_layout('toolbar',$this->map);
		$this->parse_layout('footer', $this->map + array(
			'content'=>$content,
			'button_name'=>$button_name,
			'button'=>$button,
			'information_query_in_page'=>$information_query_in_page,
			'total_query'=>$total_query,
			'number_format'=>$number_format,
			'number_query'=>DB::num_queries(),
			'link_structure_page'=>Url::build('edit_page',array('id'=>Portal::$page['id'])),
			'link_edit_page'=>Url::build('page',array('id'=>Portal::$page['id'],'cmd'=>'edit')),
			'delete_cache'=>Url::build('page',array('id'=>Portal::$page['id'],'cmd'=>'refresh','href'=>'?'.$_SERVER['QUERY_STRING'])),
			'link_admin_html'=>$link_admin_html
		));
	}
}
?>