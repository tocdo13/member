<?php
class AddPlandOfMonthForm extends Form
{
	function AddPlandOfMonthForm()
	{
		Form::Form('AddPlandOfMonthForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000'));
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
        //$this->add('total',new IntType(true,'please_add_product','1','100000000000'));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit()
	{
        if(Url::get('year'))
        {
            if(DB::fetch('select * from pland_of_month where year='.Url::get('year')))
            {
                $this->error('','đã tồn tại dữ liệu của năm '.Url::get('year'));
                return;   
            }           
        }
        $id = DB::insert('pland_of_month',array('year'=>Url::get('year')));
        if($_REQUEST['mi_plan']){
            foreach($_REQUEST['mi_plan'] as $key => $value){
                DB::insert('plan_of_month_detail',$value+array('pland_of_month_id'=>$id,'year'=>Url::get('year')));
            }
        }
        if(Url::get('save_stay'))
        {
            //echo 1;exit();
            Url::redirect_current(array('cmd'=>'edit','id'=>$id));
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
       
       $this->parse_layout('add',array() + $this->map);
	}
}
?>
