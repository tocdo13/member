<?php
class EditPlandOfMonthForm extends Form
{
	function EditPlandOfMonthForm()
	{
		Form::Form('EditPlandOfMonthForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000')); 
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
	  //System::debug($_REQUEST);
      if(URL::get('delete'))
        {
			DB::delete_id('pland_of_month',Url::get('id'));
            DB::delete('plan_of_month_detail','pland_of_month_id='.Url::get('id'));
            URL::redirect_current();
		}
       if($_REQUEST['mi_plan']){
            foreach($_REQUEST['mi_plan'] as $key => $value){
                DB::update_id('plan_of_month_detail',$value,$value['id']);
            }
       }
       //exit();
       if(Url::get('save_stay'))
       {
            //echo 1;exit();
            Url::redirect_current(array('cmd'=>'edit','id'=>Url::get('id')));
       }
       else
       {
    		echo '<script>if(window.opener){window.opener.history.go(0);}else{window.location="'.Url::build_current(array('just_edited_id'=>$id)).'"} window.close();</script> ';
    		Url::redirect_current();
      }
	}
	function draw()
	{
		$this->map = array();
		$pland = DB::fetch('select * from pland_of_month where id ='.Url::get('id'));
        $this->map+= $pland;
        $sql = '
            SELECT plan_of_month_detail.*,plan_of_month_detail.month as id,plan_of_month_detail.id as detail_id FROM plan_of_month_detail WHERE  pland_of_month_id = \''.Url::get('id').'\'';
        $month = DB::fetch_all($sql);
        $_REQUEST['month'] = $month;
		$this->parse_layout('edit',$this->map);
	}
}
?>
