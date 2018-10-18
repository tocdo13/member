<?php 
/*
	WRITTEN BY :THEDEATH
	DATE : 20/10/2009
	EDIT : 17/7/2010
	EDITED BY KHOAND
	DATE : 01/03/2011 
*/
class Moderator extends Module
{
	function Moderator($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::can_view(false,ANY_CATEGORY))
		{	
			switch(Url::get('cmd'))
			{
				case 'cache':
					$this->update_cache();
					Url::redirect_current();
				case 'update':
					$this->update();
					break;
				case 'add':
				case 'edit':
					require_once 'forms/select_portal.php';
					$this->add_form(new GrantModeratorForm());					
					break;
				case 'extra':
					$this->extra();
					break;	
				case 'grant':
					if(Url::get('account_id') and DB::exists('SELECT id FROM account_privilege WHERE account_id=\''.Url::sget('account_id').'\''))
                    {
						Url::redirect_current(array('cmd'=>'edit','account_id'=>Url::get('account_id')));
					}
                    else
                    {
						Url::redirect_current(array('cmd'=>'add','account_id'=>Url::get('account_id')));						
					}
					break;		
				default:
					require_once 'forms/list.php';
					$this->add_form(new ListModeratorForm());
					break;												
			}			
		}
		else
		{
			URL::access_denied();
		}
	}	
	function extra()
	{
		if(Url::get('account_id'))
		{
			require_once 'forms/extra.php';
			$this->add_form(new ExtraForm());
		}
		else
		{
			echo '<script>alert(\''.Portal::language('you_must_select_account').'\');window.close();</script>';
		}
	}
	function update_cache()
	{
		DB::update('account',array('cache_privilege'=>'','cache_setting'=>''),'type=\'USER\'');
	}
	function update()
	{
		$valueGrant=Url::get('valueGrant');
		if($valueGrant=='1')
		{
			$valueGrant=0;
		}
		elseif($valueGrant=='0')
		{
			$valueGrant=1;
		}	
		$typeGrant=Url::get('typeGrant')?Url::get('typeGrant'):'edit';
		$id=Url::get('idGrant');
		if(isset($valueGrant) and isset($typeGrant))
		{
				DB::update_id('account_privilege',array($typeGrant=>$valueGrant),$id);		
		}
	}
}
?>