<?php
/******************************
WRITTEN BY TCV PORTAL
EDIT BY NGOCNV, KHOAND
******************************/
class ChangeLanguage extends Module
{
    function ChangeLanguage($row)
    {
        Module::Module($row);
        if(Url::get('language_id'))
        {
            if($item = DB::fetch('SELECT id FROM language WHERE id = '.Url::iget('language_id')))
            {
                DB::update('session_user',array('language_id'=>Url::iget('language_id')),'user_id = \''.Session::get('user_id').'\'');
                if(URL::get('ldd'))
                {   //Luu Nguyen Giap add choose language edit all form
                    $param_build_change_lang = array();
                    
                    //lay ra duong link va cac tham so tren duong link do
                    $path = $_SERVER['REQUEST_URI'];
                    
                   // echo $path;
                    $pos = strpos($path,'cmd');

                    if($pos!=false)
                    {
                       $str = substr($path,$pos);
                       //tach chuoi theo & lay ra cac tham so va cac gia tri
                       $str_cmd = explode("&",$str);
                       for($i=0;$i<count($str_cmd);$i++)
                       {
                           //tach theo dau =
                           $s =explode("=",$str_cmd[$i]);
                           $param_build_change_lang = $param_build_change_lang + array($s[0]=>$s[1]);
                       }
                    }
                    URL::redirect(urldecode(Url::get('ldd')),$param_build_change_lang);
                    //End Luu Nguyen GIap
                }
                else
                {
                    URL::redirect('room_map');
                } 
            }
        }

    }
}
?>
