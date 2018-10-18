<?php
class MaterialUseReportForm extends Form
{
    function MaterialUseReportForm()
    {
        Form::Form('MaterialUseReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');  
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        require_once 'packages/hotel/includes/php/product.php';
        require_once 'packages/hotel/packages/karaoke/includes/table.php';
        
        $this->map = array();
        
        $cond ='';
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
            
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        $this->map['karaoke_id_list'] = array(''=>Portal::language('all')) + String::get_list(DB::fetch_all('Select * from karaoke where portal_id = \''.Url::get('portal_id').'\' '.Table::get_privilege_karaoke().' '));
        
        if(Url::get('portal_id'))
            $portal_id = Url::get('portal_id');
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND karaoke_reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
        if(Url::get('karaoke_id'))
            $cond.=' AND karaoke_reservation.karaoke_id = '.Url::iget('karaoke_id'); 
        
        $this->map['from_date'] = Url::sget('from_date')?Url::sget('from_date'):date('d/m/Y');
        $this->map['to_date'] = Url::sget('to_date')?Url::sget('to_date'):date('d/m/Y');
        
        $_REQUEST['from_date'] = $this->map['from_date'];    

        $_REQUEST['to_date'] = $this->map['to_date'];   
            
        $sql = '
                Select 
                    karaoke_reservation.id,
                    karaoke.department_id,
                    karaoke.warehouse_id
                From
                    karaoke_reservation 
                    inner join karaoke on karaoke.id = karaoke_reservation.karaoke_id
                where
                    karaoke_reservation.status = \'CHECKOUT\'
                    '.$cond.'
                    and (karaoke_reservation.time_out) >= '.Date_Time::to_time($this->map['from_date']).'
                    and (karaoke_reservation.time_out) < '.(Date_Time::to_time($this->map['to_date']) + 86400).'
                ';
        //echo $sql;
        //System::debug($sql);
        //cac hoa don thoa man
        $invoice = DB::fetch_all($sql);
        //System::debug($invoice);
        //cac sp dc su dung trong cac hoa don
        $report = new Report;
		$report->items = array();                                
        foreach($invoice as $key=>$value)
        {
            //sp dc su dung trong tung hoa don
            $product = DeliveryOrders::get_list_product($key,$value['department_id'],$value['warehouse_id']);
            //System::debug($product);
            foreach($product as $k=>$v)
            {
                if(isset($report->items[$v['product_id']]))
                    $report->items[$v['product_id']]['quantity'] +=  $v['quantity'];
                else
                {
                    $report->items[$v['product_id']] =  $v;
                }   
            }
        }
        $i = 1;			
		foreach($report->items as $key=>$value)
        {
			$report->items[$key]['stt'] = $i++;                                                                                                     
		}
		
        //System::debug($report->items);
        $this->print_all_pages($report);
    }    
	function print_all_pages(&$report)
	{
		$count = 0;
        $total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        
        //Xoa bo cac phan tu trong mang ma co key (o day la page < start page)
        for($i = 1; $i< $this->map['start_page']; $i++)
            unset($pages[$i]);
                
        //muon xem bao nhieu trang ?
        
        //lay ra trang bat dau dc in (neu muon xem tu trang 5 th� se tra ve 5)
        $arr = array_keys($pages);
        if(!empty($arr))
        {
            $begin = $arr['0'];
            //trang cuoi cung dc in
            $end = $begin + $this->map['no_of_page'] - 1;
            //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
            for($i = $total_page; $i> $end; $i--)
                unset($pages[$i]);      
        }

        
		if(sizeof($pages)>0)
		{
            //tong page that su
            $this->map['real_total_page']=count($pages);
			
            //page that su
            $this->map['real_page_no'] = 1;
            foreach($pages as $page_no=>$page)
			{
				$this->print_page($page,$page_no,$total_page);
                $this->map['real_page_no']++;
			}
		}
		else
		{
            $this->map['real_total_page']=0;
            $this->map['real_page_no'] = 0;
			$this->parse_layout('report',
			get_time_parameters()+
				array(
					'page_no'=>0,
					'total_page'=>0
				)+$this->map
			);
		}
	}
	function print_page($items,$page_no,$total_page)
	{
		$this->parse_layout('report',array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
    
}

?>