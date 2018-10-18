<?php
class PitchSettingForm extends Form
{
	function PitchSettingForm()
	{
		Form::Form('PitchSettingForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/pitch/modules/PitchSetting/style.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function on_submit(){
       if(Url::get('pitch_price_id') and $_REQUEST['is-save-fast-price']!=1){
            foreach(Url::get('pitch_price_id') as $key=>$value){
                $pitch_price_from_time=explode(':',$_REQUEST['pitch_price_from_time'][$key]);
                $pitch_price_to_time=explode(':',$_REQUEST['pitch_price_to_time'][$key]);
                $pitch_price['from_time']=$pitch_price_from_time[0]*3600+$pitch_price_from_time[1]*60;
                $pitch_price['to_time']=$pitch_price_to_time[0]*3600+$pitch_price_to_time[1]*60;
                $pitch_price['pitch_id']=$_REQUEST['pitch_id'][$key];
                $pitch_price['price']=System::calculate_number($_REQUEST['pitch_price_price'][$key]);
                $pitch_price['price_weekend']=System::calculate_number($_REQUEST['pitch_price_weekend'][$key]);
                if($value){
                    DB::update('pitch_price',$pitch_price,'id='.$value.'');
                }else{
                    DB::insert('pitch_price',$pitch_price);
                }
            }
       }
       
       if($_REQUEST['is-save-fast-price']==1){
            $pitch=DB::fetch_all('select * from pitch where pitch_area_id='.$_REQUEST['fast_pitch_area'].' and pitch_type_id='.$_REQUEST['fast_pitch_type'].'');
            foreach($pitch as $k=>$v){
                DB::delete('pitch_price','pitch_id='.$k.'');
            }
            foreach($_REQUEST['fast_time_from'] as $key=>$value){
                $pitch_price_from_time=explode(':',$_REQUEST['fast_time_from'][$key]);
                $pitch_price_to_time=explode(':',$_REQUEST['fast_time_to'][$key]);
                $pitch_price['from_time']=$pitch_price_from_time[0]*3600+$pitch_price_from_time[1]*60;
                $pitch_price['to_time']=$pitch_price_to_time[0]*3600+$pitch_price_to_time[1]*60;
                foreach($pitch as $k=>$v){
                    $pitch_price['pitch_id']=$k;
                    $pitch_price['price']=System::calculate_number($_REQUEST['fast_price'][$key]);
                    $pitch_price['price_weekend']=System::calculate_number($_REQUEST['fast_price_weekend'][$key]);
                    DB::insert('pitch_price',$pitch_price);
                }
            }
       }
       if(Url::get('pitch-name-add')){
         DB::insert('pitch',array('name'=>Url::get('pitch-name-add'),'pitch_type_id'=>Url::get('pitch-type-add'),'pitch_area_id'=>Url::get('pitch-area-add')));
       }
       //System::debug($_REQUEST);exit();
	}
	function draw()
	{   
	    $_REQUEST['date']=Url::get('date')?Url::get('date'):date('d/m/Y');
        $this->map=array();
        $this->map['pitch_type']=DB::fetch_all('select * from pitch_type');
	    $this->map['pitch_area']=DB::fetch_all('select * from pitch_area');
        $football_pitch=DB::fetch_all('select * from pitch where pitch_type_id=1');
        $pitch=array();
        $i=1;
        foreach($football_pitch as $key=>$value){
            $pitch[$i]['pitch'][$key]=$value;
            if(count($pitch[$i]['pitch'])==2)$i++;  
        }
         
        $this->map['football_pitch']=$pitch;
        $this->map['pitch_price']=DB::fetch_all('select * from pitch_price');
        $this->parse_layout('list',$this->map);
	}
    
}
?>