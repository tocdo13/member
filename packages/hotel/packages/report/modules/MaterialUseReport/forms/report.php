<?php
class MaterialUseReportForm extends Form
{
    function MaterialUseReportForm()
    {
        Form::Form('MaterialUseReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        require_once 'packages/hotel/includes/php/product.php';
        require_once 'packages/hotel/packages/restaurant/includes/table.php';
        
        $this->map = array();
        
        $cond ='';
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):90;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
            
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       
        //luu nguyen giap search bars with portal_id
        
        if(Url::get('portal_id'))
       {
             $portal_id =  Url::get('portal_id');
             if(Url::get('portal_id')!='ALL')
             {
                 $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('portal_id')."'");
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
        }
         else
         {
            $portal_id =PORTAL_ID;  
            $bars = DB::select_all('bar',false); 
         }
        //Start Luu Nguyen Giap add portal
        
        
        $bars = DB::fetch_all('select id,name from bar');
		$bar_ids = '';
        $bar_name = '';
		foreach($bars as $k => $bar)
        {
			if(Url::get('bar_id_'.$k))
            {
				$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
                $_REQUEST['bar_id_'.$k] = $bar['id'];
			}
		};
        $_REQUEST['bar_name'] = $bar_name;
        
        if($bar_name!='')
        {
            $cond_bar = " AND bar_reservation.bar_id in (".$bar_ids.")";
            //$cond_bar ='';
        }
        else
        {
            $cond_bar='';
        }  
       $this -> map['bars'] = $bars;   
        
        $this->map['from_date'] = Url::sget('from_date')?Url::sget('from_date'):date('d/m/Y');
        $this->map['to_date'] = Url::sget('to_date')?Url::sget('to_date'):date('d/m/Y');
        
        $_REQUEST['from_date'] = $this->map['from_date'];    

        $_REQUEST['to_date'] = $this->map['to_date'];   
        if(Url::get('from_time'))
        {
            $from_time = $this->calc_time(Url::get('from_time'));
            $to_time = $this->calc_time(Url::get('to_time'))+59;
        }
        else
        {
            $this->map['from_time'] = '00:00';            
            $from_time = $this->calc_time($this->map['from_time']);
            $_REQUEST['from_time'] = $this->map['from_time'];
            
            $this->map['to_time'] = '23:59';            
            $to_time = $this->calc_time($this->map['to_time'])+59;
            $_REQUEST['to_time'] = $this->map['to_time'];
        }    
        $sql = '
                Select 
                    bar_reservation.id,
                    bar.department_id,
                    bar.warehouse_id
                From
                    bar_reservation 
                    inner join bar on bar.id = bar_reservation.bar_id
                where
                    bar_reservation.status = \'CHECKOUT\'
                    '.$cond.$cond_bar.'
                    and (bar_reservation.time_out) >= '.(Date_Time::to_time($this->map['from_date'])+$from_time).'
                    and (bar_reservation.time_out) < '.(Date_Time::to_time($this->map['to_date']) + $to_time).'
                ';
        //echo $sql;
        //System::debug($sql);
        //cac hoa don thoa man
        $invoice = DB::fetch_all($sql);
        //System::debug($invoice);
        //cac sp dc su dung trong cac hoa don
        $report = new Report;
		$report->items = array(); 
        $category_id_list = array(); 
        $this->map['category_id'] = Url::get('category_id')?Url::get('category_id'):'';
        $_REQUEST['category_id'] = $this->map['category_id'];               
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
                    //echo $v['product_id']."<br />";
                    $category = DB::fetch("SELECT product_category.id,product_category.name FROM product inner join product_category on product_category.id=product.category_id WHERE product.id='".$v['product_id']."'");
                    //System::debug($category);
                    if(Url::get('category_id')){
                        if($category['id']!=Url::get('category_id')){
                            unset($report->items[$v['product_id']]);
                        }
                    }
                    $check=true;
                    foreach($category_id_list as $cate=>$gory){
                        if($gory['id']==$category['id']){
                            $check=false;
                        }
                    }
                    if($check==true){
                        $category_id_list += array($category['id']=>$category);
                    }
                }  
            }
        }
        
        $this->map['category_id_list'] = array(''=>Portal::language('all')) + String::get_list($category_id_list);
        //System::debug($category_id_list);
        $i = 1;			
		foreach($report->items as $key=>$value)
        {
			$report->items[$key]['stt'] = $i++;                                                                                                     
		}
        
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
			    //System::debug($page); 
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
	   $list_category = array();
       foreach($items as $id=>$value){
            $category = DB::fetch("SELECT product_category.id,product_category.name FROM product inner join product_category on product_category.id=product.category_id WHERE product.id='".$value['product_id']."'");
            $items[$id]['category_id'] = $category['id'];
            $check = true;
            foreach($list_category as $cate=>$gory){
                if($gory['id']==$category['id']){
                    $check = false;
                }
            }
            if($check==true){
                $list_category += array($category['id']=>$category);
            }
       }
       //System::debug($items);
		$this->parse_layout('report',array(
                'category'=>$list_category,
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}

?>