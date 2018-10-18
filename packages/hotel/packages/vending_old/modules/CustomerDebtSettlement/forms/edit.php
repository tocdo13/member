<?php
class EditSettlementForm extends Form
{
    function EditSettlementForm()
    {
        Form::Form('EditSettlementForm');
        //if(Url::get('confirm'))
        $this->add('total',new TextType(true,'total_is_required',1,255));
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    
    function on_submit()
    {
        if($this->check())
        {
            /*
            if(Url::get('confirm'))
            {
                if(!Url::get('confirm_user'))
                {
                    $this->error('confirm_user_is_required','confirm_user_is_required');
                    return false;
                }   
            }
            */
            $row = array(
                            'portal_id'=> PORTAL_ID,
                            'customer_id',
                            'total'=>System::calculate_number(Url::get('total')),
                            'currency_id',
                            'note',
                            'exchange_rate'=>DB::fetch('select id, exchange from currency where id = \''.Url::get('currency_id').'\'','exchange'),
                        );
                          
            if(Url::get('cmd')=='add')
            {
                $row['user_id'] = Session::get('user_id');
                $row['time'] = time();
                $row['create_date'] = Url::get('create_date')?Date_Time::to_orc_date(Url::get('create_date')):Date_Time::to_orc_date(date('d/m/Y'));
                DB::insert('customer_debt_settlement',$row);
            }
            else
            {
                $row['lastest_edited_user_id'] = Session::get('user_id');
                $row['lastest_edited_time'] = time();
                DB::update_id('customer_debt_settlement',$row,Url::get('id'));
            }
            Url::redirect_current(); 
        }
    }
    
    function draw()
    {
        $this->map = array();
        if(Url::get('cmd')=='edit')
        {
            $row = DB::fetch('Select * from customer_debt_settlement where id = '.Url::get('id'));
            foreach($row as $key=>$value)
                $_REQUEST[$key] = $value;
            $_REQUEST['total'] = System::display_number($row['total']);
            $_REQUEST['create_date'] = date('d/m/Y', $row['time']);
            $this->map['title'] = Portal::language('edit_settlement');
        }
        else
        {
            $this->map['title'] = Portal::language('add_settlement');
        }
        $this->map['currency_id_list'] = String::get_list(DB::fetch_all('Select * from currency where allow_payment = 1 order by id desc'));
        $this->map['customer_id_list'] = String::get_list(DB::select_all('vending_customer',''));
        /*
        $this->map['confirm_user_list'] = DB::fetch_all('select 
                    										account.id
                    										,party.full_name 
                    									from 
                                                            account 
                                                            INNER JOIN party on party.user_id = account.id 
                                                            AND party.type=\'USER\' 
                                                        WHERE 
                                                            account.type=\'USER\' 
                                                            AND party.description_1=\'Lễ tân\' 
                                                        ORDER BY account.id');
                                                        
        $this->map['confirm_user_list'] = array(''=>Portal::language('confirm_user'))+String::get_list($this->map['confirm_user_list']);
        */
        $this->parse_layout('edit',$this->map);
    }
}

?>