<?php 
class Notice extends Module
{
	function Notice($row)
	{
		Module::Module($row);
		switch(Url::get('cmd')){
			case 'delete':
				echo '
					<p><h3>Item has been deleted forever...!</h3></p>
					<script>
						setTimeout("window.location = \''.Url::get('href').'\'",2000);
					</script>';
				break;
			case 'not_exists':
				echo '
					<p><h3>Does not exists...!</h3></p>
					<script>
						setTimeout("window.location = \''.Url::get('href').'\'",2000);
					</script>';
				break;
			case 'night_audit':
				echo '
					<p><h3>You can not perform night audit now...!</h3></p>
					<script>
						setTimeout("window.location = \''.Url::get('href').'\'",2000);
					</script>';
				break;	
			default:
				echo '
					<p><h3>You have no right to access...!</h3></p>
					<script>
						setTimeout("window.location = \''.Url::get('href').'\'",2000);
					</script>';
				break;
		}
	}
}
?>