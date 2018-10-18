<?php
class ImportMemberDiscountForm extends Form
{
	function ImportMemberDiscountForm()
	{
		Form::Form('ImportMemberDiscountForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
    
    function on_submit()
    {  
        require_once 'packages/core/includes/utils/vn_code.php';
        //import to db
        if(Url::get('save'))
        {
            if(isset($_SESSION['content']) AND !empty($_SESSION['content']))
            {
                $array_log = array();
                $member_discount = array();
                foreach($_SESSION['content'] as $key=>$value)
                {
                    $member_discount['code'] = 'GGTV-'.$this->get_code();
                    $member_discount['title'] = isset($_SESSION['content'][$key]['title'])?$_SESSION['content'][$key]['title']:'';
                    $member_discount['description'] = isset($_SESSION['content'][$key]['direction'])?$_SESSION['content'][$key]['direction']:'';
                    $member_discount['operator'] = isset($_SESSION['content'][$key]['math'])?$_SESSION['content'][$key]['math']:'=';
                    $member_discount['num_people'] = isset($_SESSION['content'][$key]['number_people'])?$_SESSION['content'][$key]['number_people']:'';
                    $member_discount['start_date'] = isset($_SESSION['content'][$key]['start_date'])?Date_Time::to_orc_date($_SESSION['content'][$key]['start_date']):'';
                    $member_discount['end_date'] = isset($_SESSION['content'][$key]['end_date'])?Date_Time::to_orc_date($_SESSION['content'][$key]['end_date']):'';
                    $member_discount['percent'] = isset($_SESSION['content'][$key]['percent'])?$_SESSION['content'][$key]['percent']:0;
                    if($_SESSION['content'][$key]['pin_service']!='')
                    {
                        
                    }
                    if($_SESSION['content'][$key]['card_type'] != '')
                    {
                        if(strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['card_type'],'utf-8'))==strtoupper(convert_utf8_to_latin(Portal::language('parent_card'),'utf-8')))
                        {
                            $member_discount['is_parent'] = 'PARENT';
                        }
                        if(strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['card_type'],'utf-8'))==strtoupper(convert_utf8_to_latin(Portal::language('son_card'),'utf-8')))
                        {
                            $member_discount['is_parent'] = 'SON';
                        }
                        if(strtoupper(convert_utf8_to_latin($_SESSION['content'][$key]['card_type'],'utf-8'))==strtoupper(convert_utf8_to_latin(Portal::language('all'),'utf-8')))
                        {
                            $member_discount['is_parent'] = '';
                        }
                    }
                    else
                    {
                        $member_discount['is_parent'] = '';
                    }
                    $member_discount['creater'] = User::id();
                    $member_discount['create_time'] = time();
                    $id = DB::insert('member_discount',$member_discount);
                    
                    $array_log[$id] = $member_discount;
                }
                $description = '<b>Insert member discount in excel</b><br/>';
                foreach($array_log as $key_1=>$value_1)
                {
                    $description.= '<p>Make member discount id: #'.$key_1.'</p>';
                    foreach($value_1 as $k=>$v)
                    {
                        $description.= $k.' :'.$v;
                    }
                    $description.= '<br/>';
                }
                System::log('ADD','Insert member discount in excel',$description,$id);
                unset($_SESSION['content']);
                Url::redirect_current(array('cmd','action'=>'success'));      
            }
            //Neu ko the upload loi file
            else
                $this->error('upload_file_missing',Portal::language('re_upload'));
        }
    }
	function draw()
    {
        $this->map = array();
        //echo $portal_id;
        //upload file anh preview
        if(Url::get('do_upload'))
		{
            $file = $this->save_file('path_file');	
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //System::debug($sheetData);
            $this->result = $this->parse_sheet($sheetData);
            @unlink($file);
            //Luu vao session
            $_SESSION['header'] = $this->result['header'];
            $_SESSION['content'] = $this->result['content'];
            $_SESSION['preview'] = $this->result['preview'];
            $_SESSION['error'] = $this->result['error'];
            $this->parse_layout('import', $this->map+array('preview'=>$_SESSION['preview'],'error'=>$_SESSION['error']));
		}
        else
        {
           $this->parse_layout('import',$this->map);  
        }
    }
    function save_file($file)
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		$dir = 'excel';
		update_upload_file('path',$dir);
		return Url::get('path');
	}
    
    function parse_sheet($sheet)
	{
        require_once 'packages/core/includes/utils/vn_code.php';
        $header = array();
        $content = array();
        $preview = array();
        $error = array();
        $i = 1;
        foreach($sheet as $rows=>$col)
        {
			if($rows == 1)
            {
                $header[$i] = array(
                                    'pin_service'=>isset($col['A'])?$col['A']:'',
                                    'card_type'=>isset($col['B'])?$col['B']:'',
                                    'title'=>isset($col['C'])?$col['C']:'',
                                    'start_date'=>isset($col['D'])?$col['D']:'',
                                    'end_date'=>isset($col['E'])?$col['E']:'',
                                    'math'=>isset($col['F'])?$col['F']:'',
                                    'number_people'=>isset($col['G'])?$col['G']:'',
                                    'direction'=>isset($col['H'])?$col['H']:'',
                                    'percent'=>isset($col['I'])?$col['I']:'',
                                    );
            }
            else
            {
                if($col['A']=='' AND $col['B']=='' AND $col['C']=='' AND $col['D']=='' AND $col['E']=='' AND $col['F']=='')
                {
                
                }
                else
                {
                    $row_content = array(
                                    'pin_service'=> $this->set_array($col,'A'),
                                    'card_type'=> $this->set_array($col,'B'),
                                    'title'=> $this->set_array($col,'C'),
                                    'start_date'=> $this->set_array($col,'D'),
                                    'end_date'=> $this->set_array($col,'E'),
                                    'math'=> $this->set_array($col,'F'),
                                    'number_people'=> $this->set_array($col,'G'),
                                    'direction'=> $this->set_array($col,'H'),
                                    'percent'=> $this->set_array($col,'I'),
                                    );
                    //System::debug($row_content);                             
                    $check_error = true;
                    $error[$i] = $row_content;
                    $error[$i]['note'] = '';
                    // kiem tra tinh hop le cua cac truong trong $row_content - loi thi dua vao Array error - khong thi dua vao Array content
                    if($row_content['pin_service']!='')
                    {
                        
                           
                    }
                    if($row_content['card_type']!='')
                    {
                        if(strtoupper(convert_utf8_to_latin($row_content['card_type'],'utf-8'))!=strtoupper(convert_utf8_to_latin(Portal::language('parent_card'),'utf-8')) 
                        and strtoupper(convert_utf8_to_latin($row_content['card_type'],'utf-8'))!=strtoupper(convert_utf8_to_latin(Portal::language('son_card'),'utf-8'))
                        and strtoupper(convert_utf8_to_latin($row_content['card_type'],'utf-8'))!=strtoupper(convert_utf8_to_latin(Portal::language('all'),'utf-8'))
                        )
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">- Không tồn tại loại thẻ</span><br />';
                        }
                    }
                    if($row_content['number_people']!='')
                    {
                        if(is_numeric($row_content['number_people'])!=1)
                        {
                            $check_error = false;
                            $error[$i]['note'] .= '<span style="color: red;">-'.Portal::language('number_people').' Không phải định dạng số </span><br />';
                        }
                    }
                    if($check_error==true)
                    {
                        unset($error[$i]);
                        $content[$i]=$row_content;
                        if($i<=10)
    				       $preview[$i] = $content[$i];
                    }
    				$i++;
                }
            }
                
        }
        //System::debug($preview);
        $result = array('header'=>$header, 'content'=>$content,'preview'=>$preview,'error'=>$error);
        return $result;
	}
    
    
    function set_array($col,$index,$default='')
    {
        if(isset($col[$index]))
            return trim($col[$index]);
        else
            return $default;
    }
    function get_code()
    {
        $max_code = DB::fetch("SELECT max(id) as max FROM member_discount","max");
        if(!isset($max_code) OR $max_code=='' OR $max_code==0 OR !$max_code)
        {
            $max_code = 1;
        }
        else
        {
            $max_code ++;
        }
        return $max_code;
    }
}
?>