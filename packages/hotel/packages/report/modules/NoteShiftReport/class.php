<?php
class NoteShiftReport extends Module
{
    function NoteShiftReport($row)
    {
        Module::Module($row);
    	if(User::can_view(false,ANY_CATEGORY))
		{
		  require_once('forms/report.php');
          $this->add_form(new ReportNoteShiftReport);
		}
		else
		{
			URL::access_denied();
		}
    }
}
?>