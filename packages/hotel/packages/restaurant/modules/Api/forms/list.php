<?php
class ListBarNoteForm extends Form
{
    function ListBarNoteForm()
    {
        Form::Form('ListBarNoteForm');
    }
    function draw()
    {
        $datas = $this->GetAllTable($_REQUEST['portal']);
        //System::debug($datas);
        //header('Content-type: application/json');
        echo json_encode($datas);
        $this->parse_layout('list',$datas);
    }
    
    function GetAllTable($portal)
    {
        $list_table = DB::fetch_all("select  id, name from bar_table where portal_id = '#".$portal."'");
        return $list_table;
    }
}

?>