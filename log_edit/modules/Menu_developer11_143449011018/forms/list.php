<?php
class MenuForm extends Form
{
	function MenuForm()
	{
		Form::Form('MenuForm');
		$this->link_css('packages/core/skins/default/css/jquery/jquery.ribbon.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.officebar.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.cookie.js');
		$this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.css');
	}
	function draw()
	{
		require 'cache/hotel/category.php';
		require 'packages/core/includes/utils/category.php';
        //System::Debug($categories);
		$categories = check_categories($categories);
        //echo count($categories);
        //System::Debug($categories);
		$categories = String::array2tree($categories,'child');
        //if(User::id() == 'ldd' or User::id() == 'developer02')
//		System::Debug($categories);
		$count =0;
		$arr = array();
		$key1=0;$key2=0;
		$id1=0;$id2=0;$id3=0;
		foreach($categories as $k => $cate)
        {
			$group = '';
			foreach($cate['child'] as $t => $cate_child)
            {
				if($group == $cate_child['group_name'])
                {
						$j++;//echo 'thuy'.$j.'<br>';	
				}
                else
                {
					if($key1!=0 && $key2!=0)
                    {
						$categories[$key1]['child'][$key2]['counnt'] = $j;	
					}
					$j=1;
					$group = $cate_child['group_name'];	
				}
				//$categories[$k]['child'][$t]['counnt'] = sizeof($cate['child']);	
				$group_child = '';
				$kt = 0;
                foreach($cate_child['child'] as $a => $child)
                { 
					if($group_child == $child['group_name'])
                    {
                        if(isset($kt))
                            $kt++;	//echo 'le'.$k.'<br>';
                        else
                            $kt =1;	
					}
                    else
                    {
						if($id1!=0 && $id2!=0 && $id3!=0)
                        {
							//$categories[$id1]['child'][$id2]['child'][$id3]['counnt'] = $kt;
						}
						$kt=1;
						$group_child = $child['group_name'];	
					}
					//$categories[$k]['child'][$t]['child'][$a]['counnt'] = sizeof($cate_child['child']);		
					$id1= $k;
					$id2= $t;
					$id2= $a;
				}
				$key1= $k;
				$key2= $t;
			}
		}
        //if(User::id() == 'ldd' or User::id() == 'developer02')
		//System::Debug($categories);
		/*if(count($categories)>=2)
		{
			$layout = 'list';
		}else
		{
			$layout = 'vertical';
			$temp = current($categories);
			$categories = $temp['child'];
			//System::debug($categories);
		}*/
		$layout = 'list';
		$this->map['current_portal'] = DB::fetch('select account.id,party.name_1 from account inner join party on party.user_id = account.id where account.id=\''.PORTAL_ID.'\'','name_1');
        //if(User::is_admin())
        //{ 
            //echo count($categories['child']);
            //System::debug($categories);
        //}
		$this->map['categories'] = $categories;
		$user_data = Session::get('user_data');
		$this->map['user_name'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
		 //dinh van binh show list work in today
        $count_note=array();
        $start_time_today= mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end_time_today =  mktime(23,59,59,date('m'),date('d'),date('Y'));
        $time_now =  mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
        $this->map['list_work']= DB::fetch_all('select note.id as id,note.status,note.start_time,note.end_time,note.title,note.account_id from account inner join note on note.account_id=account.id where note.start_time >= '.$start_time_today.' and note.end_time <= '.$end_time_today.' and note.account_id=\''.User::id().'\'  order by start_time desc ');
 
        $count_note = DB::fetch('select count(note.id) as count from note join account on note.account_id=account.id where note.start_time >= '.$start_time_today.' and note.start_time > '.$time_now.' and note.end_time <= '.$end_time_today.' and note.account_id=\''.User::id().'\' and note.status=0 ','count');
        $count2 = DB::fetch('select count(note.id) as count from note join account on note.account_id=account.id where note.start_time >= '.$start_time_today.' and start_time < '.$time_now.' and note.end_time <= '.$end_time_today.' and note.account_id=\''.User::id().'\' and note.status=0 ','count');
        $this->map['count_all_note']=count($this->map['list_work']);
        $this ->map['count_note'] = $count_note;
        $this ->map['count2'] =$count2;
        $this ->map['time_now']=$time_now;
        foreach($this->map['list_work'] as $k => $v)
        {
            $this->map['list_work'][$k]['start_time'] = date('H-i',$v['start_time']);
            $this->map['list_work'][$k]['end_time'] = date('H-i',$v['end_time']);
            $this->map['list_work'][$k]['mkt_start']= $v['start_time'];
        }
        $day_start=mktime(00,00,00,date('m'),date('d'),date('Y'));
        $day_end=mktime(23,59,59,date('m'),date('d'),date('Y'));
        $popub=array();$pop=array();
        $popub= DB::fetch_all('select id,title,start_time,end_time,time_remin,type_time_remin from note where
        start_time>'.time().' and time_remin is not NULL and type_time_remin is not NULL and account_id= \''.User::id().'\'');
        $i=0;
        foreach($popub as $k=>$v)
        {
            $i++;
            if($v['type_time_remin']==1){
                $n= $v['time_remin']*60*1000;
               
            }else if($v['type_time_remin']==2){
                $n=$v['time_remin']*60*60*1000;
               
            }else if($v['type_time_remin']==3){
                $n=$v['time_remin']*60*60*24*1000;
                
            }else{
                $n=$v['time_remin']*60*60*24*7*1000;
            
            }
            if((($v['start_time']*1000)-$n) >=($day_start*1000) && (($v['start_time']*1000)-$n) <=($day_end*1000))
            {
                $n=($v['start_time']*1000)-$n-(time()*1000);
               $_REQUEST['show'][$i]['job']=$v['title'];
               $_REQUEST['show'][$i]['time']=$n;
               $_REQUEST['show'][$i]['start_time']=date('H-i:d-m-Y',$v['start_time']);
            }
        }
        /** manh them de lay mo ta cua module **/
        
        $url = explode("/",$_SERVER["REQUEST_URI"]);
        $pages = explode("=",$url[2]);
        $page = $pages[1];
        $page = explode("&",$page);
        $page = $page[0];
        $sql ="SELECT  
                    module.id,
                    module.name
                FROM module 
                INNER JOIN block on block.module_id=module.id
                INNER JOIN page on page.id=block.page_id
                WHERE  page.name='$page'";
        $blocks  = DB::fetch_all($sql);
        
        foreach($blocks as $row)
        {
            if($row['name']!='Menu' && $row['name']!='Footer')
            {
                $this->map['module_id'] = $row['id'];
                
                break;
            }
        }
        
        $sql = "SELECT party.id, party.user_id, UPPER(party.full_name) as full_name 
        FROM party INNER JOIN account ON party.user_id = account.id 
        WHERE party.type!='PORTAL' AND account.is_active=1 AND party.description_1!='1001' AND  party.description_1!='1002' AND party.description_1!='1000' ORDER BY FN_CONVERT_TO_VN(UPPER(party.full_name))";
        $user = DB::fetch_all($sql);
        $current_user = User::id();
        $check_use_chat = 0;
        foreach($user as $k=>$v)
        {
            if($v['user_id']==$current_user)
            {
                $check_use_chat = 1;
                unset($user[$k]);
                break;
            }
        }
        $this->map['user_list'] = $user;
        $this->map['check_use_chat'] = $check_use_chat;
        //end list word
        //
        $this->parse_layout($layout,$this->map+$pop);		
	}
}
?>