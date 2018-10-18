<?php
class DeleteManageDepartmentForm extends Form
{
    function DeleteManageDepartmentForm()
    {
        Form::Form('DeleteManageDepartmentForm');
        $this->add('id',new IDType(true,'object_not_exists','department'));
        $this->link_css(Portal::template('core').'/css/admin.css');
    }
    
    function on_submit()
    {
        //doan nay cha de lam j` ca, vi da viet ben class.php
        if($this->check())
        {
            if( Url::get('cmd')=='delete_id' and $id = Url::iget('id') and $items = DB::exists_id( 'department', $id ) )
            {
                DB::delete_id( 'department', $id );
                Url::redirect('manage_department',array());
            }
        }
    }
    
    function draw()
    {
        if(Url::get('cmd')=='delete')
        {
            $this->map['items'] = DB::fetch_all('Select * from department where id = '.Url::get('id'));
        }
        $this->parse_layout('delete',$this->map);
    }
}

?>