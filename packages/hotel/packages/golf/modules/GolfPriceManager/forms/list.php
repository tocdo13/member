<?php
class GolfPriceManagerForm extends Form
{
	function GolfPriceManagerForm()
    {
		Form::Form('GolfPriceManagerForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
    
    function on_submit(){
        if(Url::get('act')=='SAVE' and isset($_REQUEST['golf_price'])){
            foreach($_REQUEST['golf_price'] as $key=>$value){
                if(DB::exists('select id from golf_price where id='.$key)){
                    $price = System::calculate_number($value['price']);
                    if($price>0){
                        DB::update('golf_price',array('price'=>$price),'id='.$key);
                    }else{
                        DB::delete('golf_price','id='.$key);
                    }
                }
            }
        }
        Url::redirect('golf_price_manager',array('in_date'=>Url::get('in_date')));
    }
	function draw()
    {
        $this->map = array();
        $this->map['in_date'] = $_REQUEST['in_date'] = isset($_REQUEST['in_date'])?$_REQUEST['in_date']:date('d/m/Y');
        $this->map['in_time'] = Date_Time::to_time($this->map['in_date']);
        $this->map['golf_hole'] = DB::fetch_all("SELECT * 
                                    FROM golf_hole
                                    WHERE portal_id='".PORTAL_ID."'
                                    ORDER BY name
                                    ");
        $this->map['group_traveller'] = DB::fetch_all("SELECT * 
                                        FROM group_traveller
                                        ORDER BY name
                                        ");
        $timeline = array();
        $in_time = Date_Time::to_time($this->map['in_date']);
        $this->map['count_time'] = 0;
        for($i=0;$i<86400;$i+=3600){
            $this->map['count_time']++;
            $timeline[$i]['id'] = $i;
            $timeline[$i]['in_time'] = $i+$in_time;
            $timeline[$i]['in_house'] = date('H:i',$timeline[$i]['in_time']);
        }
        $this->map['timeline'] = $timeline;
        
        $this->map['items'] = DB::fetch_all('
                                    SELECT
                                        golf_price.*,
                                        TO_CHAR(golf_price.in_date,\'DD/MM/YYYY\') as in_date
                                    FROM
                                        golf_price
                                        inner join golf_hole on golf_hole.id=golf_price.golf_hole_id
                                        inner join group_traveller on group_traveller.id=golf_price.group_traveller_id
                                    WHERE
                                        golf_price.in_date=\''.Date_Time::to_orc_date($this->map['in_date']).'\'
                                        and golf_price.portal_id=\''.PORTAL_ID.'\'
                                    ORDER BY
                                        golf_price.start_time DESC
                                    ');
                                    
        $this->parse_layout('list',$this->map);
    }
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>        