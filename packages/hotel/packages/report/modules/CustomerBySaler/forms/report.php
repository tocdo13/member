<?php
class CustomerSaler extends Form{
    
    function CustomerSaler(){
        Form::Form('CustomerSaler');
    }
    function Draw(){
        $this->map=array();
        $_REQUEST['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):20;
        if($_REQUEST['line_per_page']<1) $_REQUEST['line_per_page'] = 20;
        $_REQUEST['total_page'] = Url::get('total_page')?Url::get('total_page'):50;
        if($_REQUEST['total_page']<1) $_REQUEST['total_page'] = 50;
        $_REQUEST['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
        if($_REQUEST['start_page']<1) $_REQUEST['start_page'] = 1;
        /*
        $users = DB::fetch_all('
                                    select 
										account_privilege_group.account_id as id,
                                        account_privilege_group.account_id as name
									from 
                                        account_privilege_group 
									WHERE 
                                        account_privilege_group.group_privilege_id=\'10\' 
                                    ORDER BY account_privilege_group.id DESC
                                    ');	
         /*
        $users = DB::fetch_all('SELECT
					party.user_id as id,party.user_id as name
				FROM
					party
				WHERE
					
					party.description_1 = \'Kinh doanh\'
				ORDER BY
					party.user_id
                                    '); 
                                   */    
        $users = DB::fetch_all('SELECT
					party.user_id as id,party.full_name as name
				FROM
					party
					INNER JOIN account ON party.user_id = account.id
                    INNER JOIN portal_department ON portal_department.id= account.portal_department_id
				WHERE
					(account.id<>\'admin\' AND account.id<>\'khoand\' AND account.id<>\'tester\' AND account.id<>\'developer\'  AND account.id<>\'trienkhai\')
					AND party.type=\'USER\'
                    -- AND party.description_1=\'Kinh doanh\'
					AND account.is_active = 1
                    AND portal_department.department_code = \'SALES\'
				ORDER BY
					party.user_id');
        
        if(Url::get('do_search'))
        {
            $cond = " 1>0 ";
            $user_id= Url::get('user_id')?Url::get('user_id'):"";
            if($user_id != ""){
                $cond .= ' AND customer.sale_code = \''.$user_id. '\'
                ';
            }
            require_once 'packages/core/includes/utils/lib/report.php';
            $report = new Report;
            
            $sql = "
                SELECT  ROW_NUMBER() OVER(ORDER BY customer.name ASC) AS cid,
                        customer.*, 
                        zone.name_1,
                        sectors.name AS sname,
                        customer_group.name AS groupname,
                        customer_contact.contact_regency AS cregency
                FROM   customer
                        LEFT JOIN zone ON zone.structure_id = customer.city
                        LEFT JOIN sectors ON sectors.id = customer.sectors_id
                        LEFT JOIN customer_group ON customer_group.id = customer.group_id
                        LEFT JOIN customer_contact ON  customer_contact.customer_id =customer.contact_person_name
                Where  $cond  
                ";
                //System::debug($sql);
            $report ->items = DB::fetch_all($sql);
            
            $this->phan_trang($report,$users);
           
           
        }else{
                if(!Url::get('portal_id')){
    			$_REQUEST['portal_id'] = PORTAL_ID;
             }
                 
                 
			 $this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
            
             		
			
            $this->map['user_id_list']=array(''=>Portal::language('all_user'))+String::get_list($users);

            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
          
			$this->parse_layout('search',$this->map);
        } 
    }
    
    function phan_trang(&$report,$users){
	   $n = sizeof($report->items);
       if($n<=0){
           	
			//$this->map['customer_id_list'] = array(''=>Portal::language('All')) + String::get_list(DB::select_all('customer','GROUP_ID is not null','name'));
			$this->map['user_id_list']=array(''=>Portal::language('all_user'))+String::get_list($users);
            $this->map['line_per_page'] = $_REQUEST['line_per_page']?$_REQUEST['line_per_page']:20;
            $this->map['total_page'] = $_REQUEST['total_page']?$_REQUEST['total_page']:50;
            $this->map['start_page'] = $_REQUEST['start_page']?$_REQUEST['start_page']:1;
            $this->map['portal_id_list'] = array('all'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$_REQUEST['no_record'] = 1;
            $this->parse_layout('search',$this->map);
            echo "Không có dữ liệu";
            return;
       }
       $pages = array();
       $count = 0;
       $i=1;
       if($n<=$_REQUEST['line_per_page']){
            $this->parse_layout('header',array());
            $this->parse_layout('report',array('items'=>$report->items,'num_page'=>'1','total_page'=>'1'));
       }else{
            foreach($report->items as $key=>$value){
                $count += 1;
                if($count > $_REQUEST['line_per_page']){
                    $count = 1;
                    $i +=1;   
                }
                $pages[$i][$key]=$value;
            }
            $total_page = sizeof($pages);
            $this->parse_layout('header',array());
            foreach($pages as $num_page=>$page){
                if(($num_page>=$_REQUEST['start_page']) AND ($num_page<=$_REQUEST['total_page']))
                $this->in_trang($num_page,$page,$total_page);
            }
       }
	}
    function in_trang($num_page,$page,$total_page){
     
        $this->parse_layout('report',array(
                                    'items'=>$page,
                                    'num_page'=>$num_page,
                                    'total_page'=>$total_page
                                    ));
    }
}

?>