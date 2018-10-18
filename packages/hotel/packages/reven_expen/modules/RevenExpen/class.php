<?php 
class RevenExpen extends module{
    function RevenExpen($row){
        Module::Module($row);
        //System::debug($_REQUEST);exit();
        if(User::can_view(false,ANY_CATEGORY)){
            if(
				(((URL::check(array('cmd'=>'delete')) and User::can_delete(false,ANY_CATEGORY))
				or (URL::check(array('cmd'=>'edit'))and User::can_edit(false,ANY_CATEGORY)))
                and URL::get('ids'))
				or (URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
			)
			{
			     switch(URL::get('cmd'))
			     {
    				case 'delete':
                        RevenExpen::delete($_REQUEST['ids']);break;
    				case 'edit':
    					require_once 'forms/add.php';
    					$this->add_form(new RevenExpenAddForm());break;					
    				case 'add':
    					require_once 'forms/add.php';
    					$this->add_form(new RevenExpenAddForm());break;
    				default: 
    					require_once 'forms/list.php';
    					$this->add_form(new RevenExpenListForm());break;
				}
            }
            else
            {
                require_once 'forms/list.php';
				$this->add_form(new RevenExpenListForm());
            }
        }else{
            Url::access_denied();
        }
    }
    
    function delete($ids){
        if($ids)
        {
            $arrid = explode(",",$ids);
            //System::debug($arrid);exit();
            foreach($arrid as $k=>$v)
            {
                if(DB::exists('select id from REVEN_EXPEN where id=\''.$v.'\''))
                    DB::delete("REVEN_EXPEN",'id=\''.$v.'\'');
            }
        }
        echo "<script>history.go(-1)</script>";
    }
}
?>