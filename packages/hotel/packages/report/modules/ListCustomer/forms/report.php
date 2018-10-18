<?php
class DetailDebitReportForm extends Form
{
	function DetailDebitReportForm()
	{
		Form::Form('DetailDebitReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
	    $this->map = array();
             if(!Url::get('date')){
                $_REQUEST['date'] =date('d/m/Y');
             }else{
                $this->map['date'] =Url::get('date');
             }
		if(URL::get('do_search'))
		{
            $dates = $this->map['date'];
            $date =Date_time::to_orc_date($dates);
            $sql = '
                SELECT  ROW_NUMBER() OVER(ORDER BY customer.name DESC) AS cid,
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
                 
                WHERE  customer.start_date <= \''.$date.'\'
            ';
          //system::debug($sql); 
          require_once 'packages/core/includes/utils/lib/report.php';
          $report = new Report;
          $report ->items = DB::fetch_all($sql);  
          $this->print_all_parse($report);   
		}
		else
		{
			if(!Url::get('portal_id')){
			     $_REQUEST['portal_id'] = PORTAL_ID;
            }
            if(!Url::get('date')){
                $_REQUEST['date']=date('d/m/Y');
            }
           
			$this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
			$this->parse_layout('search',$this->map);	
		}				
	}
    function print_all_parse(&$report){
	   $n = sizeof($report->items);
       if($n<=0){
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
                $this->print_page($num_page,$page,$total_page);
            }
       }
	}
    function print_page($num_page,$page,$total_page){
     
        $this->parse_layout('report',array(
                                    'items'=>$page,
                                    'num_page'=>$num_page,
                                    'total_page'=>$total_page
                                    ));
    }

	
}
?>