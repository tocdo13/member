<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/config.php';
?>
                
<html>
<body>
<form enctype="multipart/form-data" 
action="import.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<table width="600">
<tr>
<td>Names file:</td>
<td><input type="file" name="file" /></td>
<td><input type="submit" value="Upload" /></td>
</tr>
</table>
</form>
</body>
</html>