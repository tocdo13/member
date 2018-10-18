<?php 
// Process night audit
// Written by: khoand
// Date: 29/11/2010
class NightAudit extends Module
{
	function NightAudit($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			//if(USE_NIGHT_AUDIT==1)
		    {
			/*if(intval(date('H'))>=22 or (intval(date('H'))>=0 and intval(date('H'))<=6)){
				}else{
					echo '<div style="font-size:14;color:#F00;padding:20px;">'.Portal::language('can_not_do_night_audit_at_this_time').'<br>'.Portal::language('after').' 10:00PM<br><a href="'.Url::build('room_map').'">'.Portal::language('back_to_room_map').'</a></div>';
					exit();
				}*/
			}
			switch(Url::get('cmd')){
			 case 'cancel':
				if(!isset($_SESSION['night_audit_date'])){
					Url::redirect('night_audit');
				}
				require_once 'forms/cancel_reservation_room.php';
				$this->add_form(new CancelReservationRoomForm());
				break;
			case 'user_session_control':
				if(!isset($_SESSION['night_audit_date'])){
					Url::redirect('night_audit');
				}
				require_once 'forms/user_session_control.php';
				$this->add_form(new UserSessionControlForm());
				break;
			case 'without_guest_name':
				if(!isset($_SESSION['night_audit_date'])){
					Url::redirect('night_audit');
				}
				require_once 'forms/without_guest_name.php';
				$this->add_form(new WithoutGuestNameForm());
				break;
			case 'arrivals_not_yet_checked_in':
                
    				require_once 'forms/arrivals_not_yet_checked_in.php';
    				$this->add_form(new ArrivalsNotYetCheckedInForm());
				
                break;	
			case 'departures_not_checked_out':
				require_once 'forms/departures_not_checked_out.php';
				$this->add_form(new DeparturesNotCheckedOutForm());
				break;		
			case 'canceled_reservations':
				require_once 'forms/canceled_reservations.php';
				$this->add_form(new CanceledReservationsForm());
				break;
			case 'guest_mission_country_code':
				require_once 'forms/guest_mission_country_code.php';
				$this->add_form(new GuestMissionCountryCodeForm());
				break;
            case 'early_checkin':
				require_once 'forms/early_checkin.php';
				$this->add_form(new EarlyCheckinForm());
				break;
            case 'late_checkin':
				require_once 'forms/late_checkin.php';
				$this->add_form(new LateCheckinForm());
				break;
            case 'late_checkout':
				require_once 'forms/late_checkout.php';
				$this->add_form(new LateCheckoutForm());
				break;
			case 'check_revenue':
				if(!isset($_SESSION['night_audit_date'])){
					Url::redirect('night_audit');
				}
				require_once 'forms/check_revenue.php';
				$this->add_form(new CheckRevenueForm());
				break;
            case 'bar_not_checkout':
				require_once 'forms/bar_not_checkout.php';
				$this->add_form(new BarNotCheckoutForm());
				break;
            case 'bar_booked':
				require_once 'forms/bar_booked.php';
				$this->add_form(new BarBookedForm());
				break; 
            case 'bar_cancel':
				require_once 'forms/bar_cancel.php';
				$this->add_form(new BarCancelForm());
				break;
            case 'difference_price':
				require_once 'forms/difference_price.php';
				$this->add_form(new DifferencePriceForm());
				break;   
			case 'summary_report':
				require_once 'forms/summary_report.php';
				$this->add_form(new SummaryReportForm());
				break;
			default:
				require_once 'forms/choise_audit_method.php';
				$this->add_form(new ChoiseAuditMethodForm());
				break;
			}
		}
		else
		{
			URL::access_denied();
		}
	}
}
?>