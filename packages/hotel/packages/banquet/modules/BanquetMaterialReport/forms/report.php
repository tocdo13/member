<?php
class BanquetMaterialReportForm extends Form
{
    function BanquetMaterialReportForm()
    {
        Form::Form('BanquetMaterialReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');  
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        require_once 'packages/hotel/includes/php/product.php';      
        $this->map = array();
        
        $cond ='';
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
            
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        //$this->map['status_list'] = array(''=>Portal::language('all')) + String::get_list(DB::fetch_all('Select status from party_reservation where portal_id = \''.PORTAL_ID.'\' '));
//        System::debug($this->map['status_list']);
        $this->map['status_list'] = array(
            ''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT',
			'BOOKED'=>'BOOKED'
			);
        if(Url::get('status'))
		{
			$cond.=' and party_reservation.status=\''.URL::get('status').'\'';
		}
        if(Url::get('portal_id'))
            $portal_id = Url::get('portal_id');
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND party_reservation.portal_id = \''.$portal_id.'\' '; 
        }       
        $this->map['from_date'] = Url::sget('from_date')?Url::sget('from_date'):date('d/m/Y');
        $this->map['to_date'] = Url::sget('to_date')?Url::sget('to_date'):date('d/m/Y');
        
        $_REQUEST['from_date'] = $this->map['from_date'];    

        $_REQUEST['to_date'] = $this->map['to_date'];         
        $sql = '
                Select 
                    party_reservation_detail.id,
                    party_reservation_detail.product_id,
                    product.type,
                    product.name_'.Portal::language().' as product_name,
                    party_reservation_detail.quantity,
                    unit.name_'.Portal::language().' as unit_name
                From
                    party_reservation_detail 
                    inner join party_reservation on party_reservation.id = party_reservation_detail.party_reservation_id
                    inner join product on party_reservation_detail.product_id = product.id
                    inner join unit on unit.id = product.unit_id
                where
                    party_reservation_detail.product_id is not null
                    
                    '.$cond.'
                    and FROM_UNIXTIME(party_reservation.checkout_time) >=\''.Date_Time::to_orc_date($this->map['from_date']).'\'
                    and FROM_UNIXTIME(party_reservation.checkout_time) <=\''.Date_Time::to_orc_date($this->map['to_date']).'\'
                ';
        //cac hoa don thoa man
        //System::debug($sql);
        $invoice = DB::fetch_all($sql);
        
        //System::debug($invoice);
        //exit();
        //cac sp dc su dung trong cac hoa don
        $report = new Report;
		$report->items = array();                                
        foreach($invoice as $key=>$value)
        {
            
            if($value['type']=='PRODUCT'|| $value['type']=='DRINK')
            {
                //neu product dc lam tu cac material
                if(
                    $product = DB::fetch_all('Select 
                                                product_material.id, 
                                                product_material.material_id as product_id, 
                                                product_material.quantity, 
                                                product.type, 
                                                product.name_'.Portal::language().' as product_name,
                                                unit.name_'.Portal::language().' as unit_name	    
                                            from 
                                                product_material
                                                inner join product on  product_material.material_id = product.id
                                                inner join unit on product.unit_id = unit.id
                                            where 
                                                product_material.product_id = \''.$value['product_id'].'\' 
                                                and product_material.portal_id = \''.PORTAL_ID.'\'
                                            '))
                {
                    //System::debug($material);
                    foreach($product as $k=>$v)
                    {
                        if(isset($report->items[$v['product_id']]))
                        {
                            //material dc su dung = dinh. luong * so luong product trong phieu
                            	$report->items[$v['product_id']]['quantity'] +=  $v['quantity']*$value['quantity'];
                        }  
                        else
                        {
                            unset($v['id']);
                            //$v chi co dinh luong cua product, nen se phai * voi so luong duoc su dung
                            	$report->items[$v['product_id']] =  $v;
                            	$report->items[$v['product_id']]['quantity'] = $report->items[$v['product_id']]['quantity']*$value['quantity'];
                        }
                            
                    }
                }
                 else//neu khong se la goods
                {
                    if(isset(	$report->items[$value['product_id']]))
                        	$report->items[$value['product_id']]['quantity'] +=  $value['quantity'];
                    else
                    {
                        unset($value['id']);
                        	$report->items[$value['product_id']] =  $value;
                    }
                         
                }    
            }
           
        } 
        //sp dc su dung trong tung hoa don
        
        //System::debug($report->items);
            
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
        
        //lay ra trang bat dau dc in (neu muon xem tu trang 5 th? se tra ve 5)
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