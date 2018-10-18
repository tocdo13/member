<?php 
class WSDailyStaff extends Module
{
	function WSDailyStaff($row)
	{
		Module::Module($row);
        require_once 'packages/hotel/includes/php/hotel.php';
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch(Url::get('cmd'))
            {
                case 'add':
                {
                    require_once 'forms/edit.php';
                    $this->add_form(new EditDailyStaffForm());
                    break;
                }
               
                case 'edit':
                {
                    require_once 'forms/edit.php';
                    $this->add_form(new EditDailyStaffForm());
                    break;
                }
                case 'delete':
                {
                    $this->delete_staff_date(Url::get('id'));
                    require_once 'forms/list.php';
                    $this->add_form(new WSDailyStaffForm());
                    break;
                }
                case 'delete_group':
                {
                    $this->delete_group();
                    require_once 'forms/list.php';
                    $this->add_form(new WSDailyStaffForm());
                    break;
                }
                case 'report':
                {
                    require_once 'forms/report.php';
                    $this->add_form(new WorkSheetDailyReportForm());
                    break;
                }
                case 'view_all':
                {
                    require_once 'forms/view.php';
                    $this->add_form(new WorkSheetDailyViewForm());
                    break;
                }
                default:
                {
                    require_once 'forms/list.php';
                    $this->add_form(new WSDailyStaffForm());
                    break;
                }
            }
        }
        else
        {
            URL::access_denied();
        }
        
        
	}
    function delete_group()
    {
        $ids = explode(",",Url::get('selected_chb'));
        for($i =0;$i<count($ids)-1;$i++)
        {
            $this->delete_staff_date($ids[$i]);
        }
    }
    function delete_staff_date($id)
    {
        DB::delete('ws_daily_staff','id='.$id);
        DB::delete('ws_daily_room','ws_daily_staff_id='.$id);
    }
}
?>
