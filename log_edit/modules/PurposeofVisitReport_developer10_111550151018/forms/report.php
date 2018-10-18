<?php
class PurposeofVisitReportForm extends Form
{
    function PurposeofVisitReportForm()
    {
        Form::Form('PurposeofVisitReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('skins/default/report.css');       
    }
    function cal_time($time)
    {
        $arr_time = explode(":",$time);
        return $arr_time[0]*3600 + $arr_time[1]*60;
    }
    function draw()
    {
        //System::debug($_REQUEST);
        //if(!isset($_REQUEST['from_date']))
            $_REQUEST['from_date'] = Url::sget('from_date')?Url::sget('from_date'):('01/'.date('m/Y'));
        if(!isset($_REQUEST['to_date']))
            $_REQUEST['to_date'] = date('d/m/Y'); 
        
        $cond = '(reservation_room.status = \'CHECKIN\' or  reservation_room.status = \'CHECKOUT\' )';
        if(Url::get('gender_id') == 1 ){
            $cond .= ' AND traveller.gender= 0';
        }
        if(Url::get('gender_id') == 2 ){
            $cond .= ' AND traveller.gender= 1';
        }
        if(Url::get('gender_id') == 3 ){
            $cond .= ' AND traveller.gender= 2';
        }
        if(Url::get('nationality_name')){
            $cond .= ' AND country.code_1 =\''.Url::get('nationality_id').'\'';
        }
        if(Url::get('from_date')){
            $cond .= ' AND reservation_traveller.departure_date >= \''.Date_Time::to_orc_date(Url::get('from_date')).'\'';
        }
        if(Url::get('to_date')){
            $cond .= ' AND reservation_traveller.arrival_date <= \''.Date_Time::to_orc_date(Url::get('to_date')).'\'';
        }
        if(Url::get('reservation_types_id') && Url::get('reservation_types_id') != ''){
            $cond .= ' AND reservation_room.reservation_type_id = \''.Url::get('reservation_types_id').'\'';
        }
        $this->map = array();
        $sql = '
            SELECT 
                 reservation_traveller.id
                ,traveller.id as traveller_id
                ,traveller.first_name || \' \' ||traveller.last_name as full_name
                ,country.name_1
                ,TO_CHAR(reservation_traveller.departure_date,\'DD/MM/YYYY\') as departure_date
                ,TO_CHAR(reservation_traveller.arrival_date,\'DD/MM/YYYY\') as arrival_date
                ,TO_CHAR(traveller.birth_date,\'DD/MM/YYYY\') as birth_date
                ,entry_purposes.name as target_of_entry
                ,traveller.gender
                ,reservation_type.name
                ,folio.total
                ,payment.amount
                ,traveller.address
                ,reservation_traveller.id as reservation_traveller_id
                ,reservation_traveller.entry_target
            FROM
                reservation_traveller
                inner join traveller on reservation_traveller.traveller_id=traveller.id
                left join entry_purposes on reservation_traveller.entry_target=entry_purposes.code
                inner join reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
                inner join reservation_type on reservation_room.reservation_type_id = reservation_type.id
                left join country on country.id=traveller.nationality_id
                left join folio on folio.reservation_traveller_id=reservation_traveller.id
                left join payment on payment.folio_id=folio.id
            WHERE'.$cond.'
            Order by reservation_traveller.departure_date DESC
        ';
        $list_traveller = DB::fetch_all($sql);
        $to_date = getdate();
        foreach($list_traveller as $key => $value){
            if($value['birth_date'] != ''){
                $year = explode('/',$value['birth_date']);
                $list_traveller[$key]['age'] = $to_date['year'] - $year[2];
            }
            else{
                $list_traveller[$key]['age'] = '';
            }
            if($value['entry_target'] == ''){
                $list_traveller[$key]['target_of_entry'] = 'Du lịch';
            }
            if($value['gender'] == 1){
                 $list_traveller[$key]['gender'] = 'Nam';
            }elseif($value['gender'] == 0){
                $list_traveller[$key]['gender'] = 'Nữ';
            }elseif($value['gender'] == 2){
                $list_traveller[$key]['gender'] = 'Nam/Nữ';
            }
        }
        $this->map['items'] = $list_traveller;
        $this->map['gender_id_list'] = array(
                                        '' => Portal::language('all'),
                                        '1' => Portal::language('female'),
                                        '2' => Portal::language('male'),
                                        '3' => Portal::language('Nam/Nữ')
                                        );
        $reservation_types = DB::fetch_all('select id,name from reservation_type order by position');
        $this->map['reservation_types_id_list'] = array(''=>Portal::language('all'))+ String::get_list($reservation_types);
        $this->parse_layout('report',$this->map);
    }
    
}
?>
