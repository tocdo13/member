<?php
class CancelationListHungForm extends Form
{  
   
	function CancelationListHungForm()
	{
		Form::Form('CancelationListHungForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function on_submit()
    {
       
    }
    
    function draw()
    {   
	   require_once 'packages/core/includes/utils/time_select.php';
	   require_once 'packages/core/includes/utils/lib/report.php';
	   $report = new Report;
	   $now = getdate();
	   if(!Url::get('batdau_apdung'))
	        $_REQUEST['batdau_apdung'] = $now['mday'].'/'.$now['mon'].'/'.$now['year'];
	   if(!Url::get('guest_left'))
	        $_REQUEST['guest_left'] = $now['mday'].'/'.$now['mon'].'/'.$now['year'];
	   $this->no_of_page=50;
	   $this->start_page=1;
	   $this->num_adult=0;
	   $this->num_childrent=0;
	   //$this->varriable_count=0;
	   //-----------------------------------------------------------------------------------------------
	   if(isset($_REQUEST['btn_search']))
	   { 
	      if(URL::get('start_page'))
		  {
		    $this->start_page = URL::get('start_page');// trang bat dau duoc truyen vao
		  }
		  else
		  { 
		    $this->start_page=1; // mac dinh bat dau xem tu trang 1
		  }
		  //---------------------------------------------------------------------------------------------
	      if(URL::get('no_of_page'))
		  {
			$this->no_of_page = URL::get('no_of_page');// tong so trang can xem
		  }
		  //so dong tren trang mac dinh 20
		  $this->line_per_page = URL::get('line_per_page',20); 
		  $to_day = date("d-m-Y");// ngay thang nam hien tai
		  $cond='';
		  //----------------------------------------------------------------------------------------------
		  if(isset($_POST['batdau_apdung']) and $_POST['batdau_apdung'] != '')
		  {  
		     $this->date_current = $_POST['batdau_apdung'];
			// lay thoi gian tu 0h ngay bat tim kiem 
			 $time_guest_out = Date_Time::to_time($_POST['batdau_apdung']);
	         $cond = ' AND reservation_room.time_in >= \''.$time_guest_out.'\'';
		  }
		  //----------------------------------------------------------------------------------------------
		  if(isset($_POST['guest_left']) and $_POST['guest_left'] != '')
		  {  
		     $this->date_current_end = $_POST['guest_left'];
			// lay thoi gian tu 24h ngay ket thuc tim kiem 
			 $time_guest_out_end = Date_Time::to_time($_POST['guest_left']);
	         $cond .= ' AND reservation_room.time_out <= \''.($time_guest_out_end + 24*3600).'\'';
		  }
		  //---------------------------------------------------------------------------------------------
		  if(isset($_POST['guest_name']) and $_POST['guest_name'] != '')
		  {  
	         $cond .= ' AND UPPER(CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name))  LIKE UPPER(\'%'.addslashes($_POST['guest_name']).'%\')';
		  }
		  //---------------------------------------------------------------------------------------------
		  if(isset($_POST['guest_id_res']) and $_POST['guest_id_res'] != '')
		  {  
	         $cond .= ' AND reservation_room.id = \''.addslashes($_POST['guest_id_res']).'\'';
		  }
	   }
	   else
	   { 
	      
	      $this->start_page=1;
		  $this->line_per_page = URL::get('line_per_page',20); 
		  $to_day = date("d-m-Y");// ngay thang nam hien tai
		  $this->date_current = $to_day;
		  $this->date_current_end = $to_day;
		  $start_page=1;
		  $total_export = 50;
		  $time_guest_out_default = Date_Time::to_time($to_day);
		  $cond = ' AND reservation_room.time_in = \''.$time_guest_out_default.'\'';
		  
	   }
		  // cau truy van sql  Date_Time::to_time().  $start_date=$this->to_sql_date($_POST['batdau_apdung']);guest_name
		  //---------------------------------------------------------------------------------------------
		  $sql= 'select  
	                 reservation_room.id
					,reservation.id as id_res
					,room.name as room_name
					,room_type.name as room_type_name
					,reservation_room.price
					,traveller.first_name 
					,traveller.last_name 
					,reservation_room.customer_name
					,country.name_'.Portal::language().' as name_country
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.reservation_id
					,reservation_room.num_child
					,reservation_room.num_people
					,reservation_room.status
					,traveller.id as traveller_level_id
					,tour.name as tourname
					,customer.name as  customername
					,reservation_room.note
				from
					reservation_room
					inner join reservation on reservation_room.reservation_id = reservation.id
					left outer join tour on reservation.tour_id = tour.id
					inner join room on reservation_room.room_id = room.id 
					inner join room_type on room.room_type_id = room_type.id
					left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
					left outer join traveller on reservation_traveller.traveller_id = traveller.id
					left outer join country on traveller.nationality_id = country.id
					left outer join customer on reservation.customer_id = customer.id
				where 
					1 >0  AND reservation_room.status = \'CANCEL\' '.$cond.'     order by  reservation_room.time_in  DESC';
			// thuc hien sql gan cho items	
		   //--------------------------------------------------------------------------------------------- 		
		   $report->items = DB::fetch_all($sql);
		  // SYSTEM::debug($sql);
		  //exit();
		   if($report->items)
		   { 
		      $empti=1;
		   }
		   else
		   { 
		      $empti=0;
		   }
		   //---------------------------------------------------------------------------------------------
		   $i = 1;
		   foreach($report->items as $key=>$item)
			{
				//$report->items[$key]['traveller_level_id'] = $item['traveller_level_id'];
				$report->items[$key]['stt'] = $i++;
			}
			if($empti==1)// neu co data moi thuc hien in ra bao cao
			{
			   $this->print_all_pages($report);
			}	
			else
			{ 
			    
				$this->print_all_pages($report);
				echo ' Khong co du lieu phu hop. Xin lua chon lai ban oi';
			}				
		  //SYSTEM::debug($sql);
		 // exit();
		  
		  
	   //}
	  // else
	   //{
	       //$this->parse_layout('search');
		  // $this->print_all_pages($report);
	   //}
	}
	//--------------------------------------------
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		$status="0";
		$summary = array(
			'room_count'=>0,
			'guest_count'=>0,
			'total_price'=>0
		);
		$room_name = false;
		$reservation_id = false;
		//---------------------------------------------------------------------------------------------
		if(empty($report->items))
		{ 
		   $this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
					'to_day'=>$this->date_current,
					'today'=>Url::get('today',0),
					'day_end'=>$this->date_current_end,
					'start_page'=>1,
				    'day_end'=>$this->date_current_end
					
					
				)
			);
		}
		else
		{
		foreach($report->items as $key=>$item)
		{
			if($room_name<>$item['room_name'])
			{
				$room_name=$item['room_name'];
				$summary['room_count']++;
			}
			$summary['guest_count']++;
			//$summary['guest_count']=10;
			$summary['total_price']+=0;//str_replace(',','',$item['price']);
			
			$pages[$total_page][$key] = $item;
			++$count;
			if($count >= $this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
		}
		//---------------------------------------------------------------------------------------------
		$total_export=(($this->no_of_page+$this->start_page - 1) >= $total_page)?($total_page):($this->no_of_page+$this->start_page-1);
		if(sizeof($pages)>0)
		{  
		    $dem=1;
			foreach($pages as $page_no=>$page)
			{  $dem++;// cho nhung trang duoc chi dinh xem moi duoc in ra
				if(($dem>$this->start_page)and($dem <= $this->no_of_page+$this->start_page))
				{
				   $this->print_page($page, $report, $page_no,$total_page,$summary,$this->start_page,$total_export);
				}
				else
				{ 
				}
			}
			$this->varriable_count = ($total_export-$this->start_page)*$this->line_per_page;
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
					'to_day'=>$this->date_current,
					'today'=>Url::get('today',0),
					'day_end'=>$this->date_current_end
					
					
				)
			);
			//$this->parse_layout('search');
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			));
		}
		}
	}
	//-------------ham in trang---------------------
	function print_page($items, &$report, $page_no,$total_page,$summary,$start_page,$total_export)
	{   //---------------------------------------------------------------------------------------------
	    //----------------dem so nguoi lon va tre em---------------------------------------------------
		 foreach($items as $ke=>$va)
		 { 
		   $this->num_adult += $va['num_people'];
		   $this->num_childrent += $va['num_child'];
		 }
		//---------------------------------------------------------------------------------------------
	    //$to_day=31;
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'to_day'=>$this->date_current,
				'today'=>Url::get('today',0),
				'start_page'=>$start_page,
				'day_end'=>$this->date_current_end
			)
		);
		//$this->parse_layout('search');		
		$this->parse_layout('report',
			$summary+
			array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_record'=>($total_export-$this->start_page)*$this->line_per_page,
				'total_page'=>$total_export,
				'total_adult'=>$this->num_adult,
				'total_childrent'=>$this->num_childrent
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
			'total_export'=>$total_export
		));
	}
	//----------------------------------------------------------------
}
	