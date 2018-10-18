<?php
class AddHousekeepingEquipmentForm extends Form
{
	function AddHousekeepingEquipmentForm()
	{   
		Form::Form('AddHousekeepingEquipmentForm');        
		$this->add('housekeeping_equipment_detail.quantity',new FloatType(true,'invalid_quantity','0.00000000001','100000000000')); 
		$this->add('housekeeping_equipment_detail.product_id',new IDType(true,'invalid_product_id','product_price_list','product_id'));
        //$this->add('housekeeping_equipment_detail.name',new TextType(true,'invalid_housekeeping_equipment_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
            $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		//$this->link_js('packages/core/includes/js/suggest.js');
		//$this->link_js('packages/core/includes/js/calendar.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        
       
        $this->link_js('packages/core/includes/js/bootstrap.min.js');
        $this->link_js('packages/core/includes/js/bootstrap-clockpicker.min.js');
        
        $this->link_css('packages/core/skins/default/css/bootstrap-clockpicker.min.css');
        $this->link_css('packages/core/skins/default/css/github.min.css');
     
       $this->link_js('packages/core/includes/js/highlight.min.js');

          $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
         $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit(){
        if($this->check()){ 
            //ki?m tra d? li?u nh?p vào t? form
         $arr_end_time=array();$repeat_on='';$end_time=''; 

          if(Url::get('end_time')){
		     foreach(Url::get('end_time') as $key=>$val){	      
                if($val!=''){
                    $end_time=$val;break;
                }
                
		     }
              if(Url::get('ends')){
                if(Url::get('ends')==3){
                     $end_time=Date_Time::to_time($end_time);
                     $end_time=strtotime('+ 23 hours 59 minutes 59 seconds ',$end_time);
                }else if(Url::get('ends')==2){
                    $end_time=$end_time;
                }          
             }
		 }
       //echo $end_time;die;
		 if(Url::get('check_list')){
		     $repeat_on = implode(',',Url::get('check_list'));
            
		 }
        
        $from_date=explode('/',$_REQUEST['from_date']);
        $to_date=explode('/',$_REQUEST['to_date']);
       
        $time_start= explode(':', ($_REQUEST['hour_from']?$_REQUEST['hour_from']:'00:00'));
        $time_end= explode(':', ($_REQUEST['hour_to']?$_REQUEST['hour_to']:'00:00'));
        $status= Url::get('status')?Url::get('status'):0;
        $start=mktime($time_start[0],$time_start[1],date('s'),$from_date[1],$from_date[0],$from_date[2]);
        $end=mktime($time_end[0],$time_end[1],date('s'),$to_date[1],$to_date[0],$to_date[2]);  
		
  
        $repeat_type_end = Url::get('ends')?Url::get('ends'):0;
        
        $notes = array('title','status'=>$status,'description','repeat_time','repeat','repeat_month','full_day',
        'end_time'=>$end,'start_time'=>$start,'repeat_week'=>$repeat_on,'repeat_end_time'=>$end_time,
        'time_remin','type_time_remin','repeat_type_end'=>$repeat_type_end,'repeat_type','account_id'=>User::id()
        );
    		if(Url::get('cmd')=='edit'){ 
    			DB::update('note',$notes,'id='.Url::get('id'));
               
    		}
            else
            {
    			DB::insert('note',$notes);
    		}
			Url::redirect_current();
		}
    }
	function draw()
	{
	  $item= DB::select('note','ID='.Url::iget('id'));
        if($item){
	       foreach($item as $key=>$value){
    		if(!isset($_REQUEST[$key])){
    			$_REQUEST[strtoupper($key)] = $value;
    		}
			}
		}
       // System::debug($_REQUEST);die;
        $repeat_type_list=array( '1'=>'Hàng ngày',
                        '2'=>'Hàng tuần',
                        '3'=>'Hàng tháng',
                        '4'=>'Hàng năm',
                        );        
        $repeat_time_list=array();
        for($i=1;$i<=30;$i++){
            $repeat_time_list[$i]=$i;
        }
       $type_time_remin_list= array(
                            '1'=>'Phút',
                            '2'=>'Giờ',
                            '3'=>'Ngày',
                            '4'=>'Tuần'
                            );
         
         if($_REQUEST['cmd']=='edit'){
           // System::debug($_REQUEST);die;
         $_REQUEST['hour_from']=date('H:i',Url::iget('start_time'));
         $_REQUEST['hour_to'] = date('H:i',Url::iget('end_time'));
         $_REQUEST['from_date'] = date('d/m/Y',Url::iget('start_time'));
         $_REQUEST['to_date'] = date('d/m/Y',Url::iget('end_time'));
         $repeat_month = Url::iget('repeat_month');
         $doned=Url::get('status');
         $repeat= Url::get('repeat');
         $repeat_on = Url::get('repeat_week'); 
         $ends = Url::iget('repeat_type_end');
         $repeat_full = Url::iget('full_day');
         $end_time = Url::iget('repeat_end_time');
         if($end_time && $ends==3){
            $end_time =date('d/m/Y',$end_time);
         }
         $this->parse_layout('add',
            array(
            'doned'=>$doned,
            'repeat'=>$repeat,
            'repeat_full'=>$repeat_full,
            'repeat_on'=>$repeat_on,
            'ends'=>$ends,
            'end_time'=>$end_time,
            'repeat_month'=>$repeat_month,
            'repeat_type_list'=>$repeat_type_list,
            'repeat_time_list'=>$repeat_time_list,
            'type_time_remin_list'=>$type_time_remin_list
            ));
      }else{
     	$this->parse_layout('add',
         array(  'repeat_type_list'=>$repeat_type_list,
            'repeat_time_list'=>$repeat_time_list,
            'type_time_remin_list'=>$type_time_remin_list
            )
         
         );
         }
	
        
	}
    

}
?>