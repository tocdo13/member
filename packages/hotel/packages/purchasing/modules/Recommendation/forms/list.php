<?php
class RecommendationForm extends Form
{
    function RecommendationForm()
    {
        Form::Form('RecommendationForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function on_submit()
    {
        //System::debug($_REQUEST);exit();
        //cap nhat lai xac nhan cua Truong BP
        if($_REQUEST['id_confirm'] != 0)
        {
            $id = $_REQUEST['id_confirm'];
            DB::update('pc_recommendation',array('confirm'=>'HeadOfDepartment', 'person_confirm'=>$_REQUEST['person_confirm'], 'time_confirm'=>time()),'id='.$id);            
        }
        if($_REQUEST['id_unconfirm'] != 0 && $_REQUEST['reason_cancellation'] !='')
        {
            $id = $_REQUEST['id_unconfirm'];
            if(!DB::exists('
                            SELECT 
                                pc_order_detail.* 
                            FROM 
                                pc_order_detail 
                                INNER JOIN pc_recommend_detail_order ON pc_recommend_detail_order.order_id = pc_order_detail.id
                                INNER JOIN pc_recommend_detail ON pc_recommend_detail_order.pc_recommend_detail_id = pc_recommend_detail.id
                                INNER JOIN pc_recommendation ON pc_recommend_detail.recommend_id = pc_recommendation.id
                            WHERE
                                pc_recommendation.id = \''.$id.'\'
            '))
            {
                DB::update('pc_recommendation', array('confirm'=>'','reason_cancellation'=>$_REQUEST['reason_cancellation'], 'person_unconfirm'=>$_REQUEST['person_confirm'], 'time_unconfirm'=>time()),'id='.$id);
            }else
            {
                $this->error('','Đã được tạo đơn hàng không thể hủy!');
                return false;
            }                
        }else
        {
            //$this->error('','Hủy xác nhận đề xuất không thành công!');
        }
        //Url::redirect_current();
    }
    function draw()
    {
        
        require_once 'packages/core/includes/utils/vn_code.php';   
        $this->map = array();
        $cond = " 1=1 ";
        $cond_2 = " 1=1 ";
        if(Url::get('date'))
        {
            $d = Date_Time::to_time(Url::get('date'));
            $date = Date_Time::convert_time_to_ora_date($d);
            $cond .=" AND recommend_date='".$date."'";
            $cond_2 .=" AND recommend_date='".$date."'";
        }
        if(Url::get('department_id'))
        {
            $cond .=" AND portal_department_id=".Url::get('department_id');
        }
        if(Url::get('status'))
        {
            if(Url::get('status') != 'ALL')
            {
                if(Url::get('status') == 'HeadOfDepartment')
                {
                    $cond .=" AND pc_recommendation.confirm='".Url::get('status')."'";                    
                }else
                {
                    $cond .=" AND pc_recommendation.confirm is null";
                }
            }
        }
        if(Url::get('person_recomment'))
        {
            if(Url::get('person_recomment') != 'ALL')
            {
                $cond .=" AND pc_recommendation.recommend_person='".Url::get('person_recomment')."'";
            }
        }
        if(Url::get('who_confirm'))
        {
            if(Url::get('who_confirm') != 'ALL')
            {
                $cond .=" AND pc_recommendation.person_confirm='".Url::get('who_confirm')."'";
            }
        }
        $item_per_page =100;
        DB::query("
    			SELECT 
    				count(pc_recommendation.id) as acount
                FROM pc_recommendation
                INNER JOIN portal_department ON portal_department.id=pc_recommendation.portal_department_id
                INNER JOIN department ON portal_department.department_code=department.code
                WHERE ".$cond."
		");
        $count = DB::fetch();
        require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('date','department','status','person_recomment','who_confirm'));
		$sql = "SELECT * FROM(
                    SELECT 
                        pc_recommendation.*,department.name_1 as department,'' as order_id
                        ,row_number() over (".(URL::get('order_by')?"order by ".URL::get('order_by').(URL::get('order_dir')?" ".URL::get('order_dir'):""):"order by recommend_time DESC").") as rownumber
                    FROM 
                        pc_recommendation
                        INNER JOIN portal_department ON portal_department.id=pc_recommendation.portal_department_id
                        INNER JOIN department ON portal_department.department_code=department.code
                    WHERE 
                        ".$cond."
                    ORDER BY recommend_time desc
                )
                WHERE
                    rownumber > ".(page_no()-1)*$item_per_page." and rownumber<=".(page_no()*$item_per_page)."
        ";
        
        //System::debug($sql);
        $items = DB::fetch_all($sql);
        
        $i = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $i++;
            $items[$key]['recommend_date']  = date('d/m/Y H:i',$value['recommend_time']);
            if($value['last_edit_user'] != '')
            {
                $items[$key]['last_use'] = date('d/m/Y H:i', $value['last_edit_time']) . ' ' . $value['last_edit_user'];
            }else
            {
                $items[$key]['last_use'] = '';                
            }
                           
        }
        $check_create_order = DB::fetch_all('
                                    SELECT
                                        pc_recommend_detail.id as id,
                                        pc_recommend_detail.recommend_id,
                                        pc_recommend_detail.order_id
                                    FROM
                                        pc_recommend_detail
                                        inner join pc_recommendation on pc_recommendation.id = pc_recommend_detail.recommend_id 
                                    WHERE
                                        '.$cond_2.'
                                                                    
        ');
        foreach($check_create_order as $key => $value)
        {
            if(isset($items[$value['recommend_id']]))
            {
                $items[$value['recommend_id']]['order_id'] .= $value['order_id'];
            }
        }
        //System::debug($items);
        $this->map['items'] = $items;
        //System::debug($items);
        
        //2. hien thi danh sach bo phan 
        $sql="Select 
                portal_department.id, 
                portal_department.department_code as code, 
                '--' || department.name_1 as name,
                department.parent_id,
                department.id as department_id
                
            from 
                portal_department
                inner join department on  department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
            where 
                department.parent_id=0
            order by 
                portal_department.id ";
        
        $departments = DB::fetch_all($sql);
        //System::debug($sql);
        $default = array('id'=>0,'code'=>'CHOOSE_DEPARTMENT','name'=>Portal::language('select_department'));
        $result = array();
        
        $parent_id = false;
        /** Daund viet lai phan chon bo phan de toi uu toc do load*/
        array_push($result,$default);
        $department_list = '';
        $child_department = DB::fetch_all('
                                SELECT 
                                    portal_department.id,
                                    portal_department.department_code as code,
                                    \'----\' || department.name_1 as name,
                                    department.parent_id,
                                    department.id as department_id,
                                    portal_department.warehouse_pc_id,
                                    warehouse.name as warehouse_name --trung:lay ten kho
                                FROM 
                                    portal_department
                                    inner join department on department.code  = portal_department.department_code
                                    left join warehouse on portal_department.warehouse_pc_id = warehouse.id
                                WHERE 
                                    portal_department.portal_id=\''.PORTAL_ID.'\'                        
        ');
        foreach($departments as $key => $value)
        {
            array_push($result,$departments[$key]);
            foreach($child_department as $k=>$v)
            {
                if($v['parent_id'] == $value['department_id'])
                {
                    array_push($result,$child_department[$k]);
                }
            }         
        }
        foreach($result as $id => $value)
        {
            $department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        /** Daund viet lai phan chon bo phan de toi uu toc do load*/
        /*foreach($departments as $key=>$row)
        {
             array_push($result,$departments[$key]);
             //2. tim nhung dong child $row['department_id']
             $sql = "select portal_department.id,
                        portal_department.department_code as code,
                        '----' || department.name_1 as name,
                        department.parent_id,
                        department.id as department_id
                        from portal_department
                        inner join department on department.code  = portal_department.department_code
                    where department.parent_id=".$row['department_id']." AND portal_department.portal_id='".PORTAL_ID."'";
             $items = DB::fetch_all($sql);
             foreach($items as $k=>$v)
             {
                array_push($result,$items[$k]);
             }
        }
        //System::debug($result);
        $department_list = '';
		foreach($result as $id => $value)
        {
            if(isset($_REQUEST['department_id']) && $_REQUEST['department_id']==$value['id'])
            {
                $department_list .= '<option value="'.$value['id'].'" selected="selected">'.$value['name'].'</option>';
            }
            else
            {
                $department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
		}*/
        /** 7211 */
			$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
            if(!$user_privigele or $user_privigele==3 or $user_privigele==4){
                
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
				WHERE
					party.type=\'USER\'
			');
            }else{
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
                    INNER JOIN account_privilege_group ON account_privilege_group.account_id=account.id
				WHERE
					party.type=\'USER\'
					AND account_privilege_group.group_privilege_id is not null and account_privilege_group.group_privilege_id !=3 and account_privilege_group.group_privilege_id !=4
			');
            }
        /** 7211 end*/
        $this->map['person_recomment_list'] = array('ALL'=>Portal::language('select'))+String::get_list($users);
        $this->map['who_confirm_list'] = array('ALL'=>Portal::language('select'))+String::get_list($users);
        $this->map['users'] =$users;
        $this->map['department_list'] = $department_list;
        $this->map['status_list'] = array(
                'ALL'=>Portal::language('all'),
                'HeadOfDepartment'=>'Đã xác nhận',
                'Confirm'=>Portal::language('Xác nhận')
        );
        $user_data = Session::get('user_data');
        $this->map['person_confirm'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
        $this->map['paging'] = $paging;
		$this->parse_layout('list',$this->map);
	}	
}
?>