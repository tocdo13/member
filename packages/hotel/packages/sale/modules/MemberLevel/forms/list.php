<?php
class MemberLevelForm extends Form
{
	function MemberLevelForm()
	{
		Form::Form('MemberLevelForm');
	}
	function on_submit()
	{
	   //if(Url::get("check_delete_member_level")=='on')
//       {
//	       if(Url::get("delete_select_list")!="")
//           {
//	           $list_delete = Url::get("delete_select_list");
//               $arr_list_delete = explode("_",$list_delete);
//               unset($arr_list_delete[0]);
//               foreach($arr_list_delete as $key=>$value)
//               {
//                    $id = $arr_list_delete[$key];
//                    DB::delete("member_level","id=".$id);
//                    DB::delete("member_level_discount","member_level_id=".$id);
//               }
//	       }
//	   }	
	}	
	function draw()
	{
	   $list_level_member = DB::fetch_all("SELECT * FROM member_level ORDER BY id");
       $this->map['list_level_member_js'] = String::array2js($list_level_member);
       $this->parse_layout('list',array('list_level_member'=>$list_level_member)+$this->map);
	}
}
?>
