<?php 
    class EditWaitingBook extends Form
    {
        function EditWaitingBook()
        {
            Form::Form('EditWaitingBook');
            $this->add('number_room',new TextType(true,'invalid_first_name',0,255));
            $this->link_js('packages/core/includes/js/jquery/datepicker.js');  
            $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
            $this->link_js('packages/core/includes/js/multi_items.js');
            $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
            $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        }
        
        function draw()
        {
            $this -> map = array();
            $this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_waiting_list'):Portal::language('edit_waiting_list');           
            /** Oanhbtk sua lai room_level theo portal_id **/
            $cond = 'room_level.portal_id = \''.PORTAL_ID.'\'
			'.(Url::get('keyword')?' AND room_level.name LIKE "%'.Url::sget('keyword').'%"':'').'
			';
            $room_type_list =DB::fetch_all('select id, name FROM room_level WHERE '.$cond.' ');
            /** end Oanhbtk **/
            $str_room_type = '<option value="">--------</option>';
            foreach($room_type_list as $k => $v)
            {
                $str_room_type .= "<option value=".$v['id'].">".$v['name']."</option>";
            }
            $this -> map['room_type_option'] = $str_room_type;
            $this -> map['payment_method_list']=array(''=>'','By company'=>'By company','By the guest'=>'By the guest','Cash'=>'Cash','Credit card'=>'Credit card','Bank transfer'=>'Bank transfer','Travel agency'=>'Travel agency','Other'=>'Other');
            
            if(!isset($_REQUEST['booking']))
    		{
                if(Url::get('id'))
                {
                    $cond =' waiting_book_id='.Url::get('id');
                    $sql = 'select * from waiting_information WHERE '.$cond.' order by id';
        			$booking_content = DB::fetch_all($sql);
                    foreach($booking_content as $k => $v)
                    {
                        $booking_content[$k]['from_date'] = Date_Time::convert_orc_date_to_date($v['from_date'],'/');
                        $booking_content[$k]['to_date'] = Date_Time::convert_orc_date_to_date($v['to_date'],'/');
                    }
        			$_REQUEST['booking'] = $booking_content;
                }
    		}
                                               		
            if(Url::get('cmd')=='edit')
            {
                $id = Url::get('id');
                $sql = 'SELECT WAITING_BOOK.*,
                               customer.id as cid,
                               customer.name as customer_name 
                        FROM WAITING_BOOK 
                             LEFT JOIN customer ON customer.id =WAITING_BOOK.customer
                        WHERE WAITING_BOOK.ID = '.$id.'';
                $record = DB::fetch_all($sql);
                foreach($record as $id=>$key)
                {
                    $this->map['contact_name']=$key['contact_name'];
                    $this->map['cid']=$key['cid'];
                    $this->map['customer_name']=$key['customer_name'];
                    $this->map['arrival_date']=$key['arrival_date'];
                    $this->map['departure_date']=$key['departure_date'];
                    $this->map['confirm_date']=$key['confirm_date'];
                    $this->map['note']=$key['note'];
                    $this->map['telephone']=$key['telephone'];
                    $this->map['code_booking']=$key['code_booking'];
                    $this->map['deposit']=$key['deposit'];
                    $this->map['before_date']=$key['before_date'];
                    $this->map['content_booking']=$key['content_booking'];
                    $this->map['payment_method']=$key['payment_method'];
                }
                     
            }
            if(URl::get('group_deleted_ids'))
            {
                
				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('waitting_book',$delete_id);
				}
			}
            $this->parse_layout('edit',$this->map);
        }
        function on_submit()
        {
            $this->map=array();
            $this->map['contact_name'] = $_REQUEST['contact_name'];
            $this->map['customer'] = $_REQUEST['customer_id'];
            $this->map['arrival_date'] =Date_Time::to_orc_date($_REQUEST['arrival_date']);
            $this->map['departure_date'] =Date_Time::to_orc_date($_REQUEST['departure_date']);
            $this->map['note'] =$_REQUEST['note'];
            $this->map['confirm_date'] =Date_Time::to_orc_date($_REQUEST['confirm_date']); 
            $this->map['telephone'] = $_REQUEST['telephone'];
            $this->map['code_booking'] = $_REQUEST['code_booking']; 
            $this->map['deposit'] = $_REQUEST['deposit'];
            $this->map['before_date'] = Date_Time::to_orc_date($_REQUEST['before_date']);
            $this->map['payment_method'] = $_REQUEST['payment_method'];
            $this->map['portal_id'] = PORTAL_ID;
            if(Url::get('id'))
            {
                DB::update('waiting_book',$this->map,'ID='.$_REQUEST['id']);
                $wainting_book_id = Url::get('id');
            }                
            else
            {
                $this->map['creat_time']=time();
                $waiting_book_id =  DB::insert('waiting_book',$this->map);
            }
            if(isset($_REQUEST['booking']))
    		{	
    		    if(Url::get('id'))
                {
                    DB::delete('waiting_information','waiting_book_id='.Url::get('id'));
                    foreach($_REQUEST['booking'] as $key=>$record)
        			{
        				if($record['id'] and DB::exists_id('waiting_information',$record['id']))
        				{
        				    $record['from_date'] = Date_Time::to_orc_date($record['from_date']);
                            $record['to_date'] = Date_Time::to_orc_date($record['to_date']);
                            //$record['guest_name'] = $record['guest_name'];
        					$event_id  = $record['id'];
        					unset($record['id']);
                            $record['waiting_book_id'] = Url::get('id');
        					DB::update('waiting_information',$record,'id=\''.$event_id.'\'');
        				}
        				else
        				{
        					unset($record['id']);
                            $record['from_date'] = Date_Time::to_orc_date($record['from_date']);
                            $record['to_date'] = Date_Time::to_orc_date($record['to_date']);
                            //$record['guest_name'] = $record['guest_name'];
                            $record['waiting_book_id'] = Url::get('id');
                            
        					$id = DB::insert('waiting_information',$record);
        				}
        			}
                }
                else
                {
                    foreach($_REQUEST['booking'] as $key=>$record)
        			{
    					unset($record['id']);
                        $record['from_date'] = Date_Time::to_orc_date($record['from_date']);
                        $record['to_date'] = Date_Time::to_orc_date($record['to_date']);
                        //$record['guest_name'] = $record['guest_name'];
                        $record['waiting_book_id'] = $waiting_book_id;
    					$id = DB::insert('waiting_information',$record);	
        			}
                }
    			
    		}
            
            Url::redirect_current();    
    }
}
?>