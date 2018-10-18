<?php 
if(User::is_admin() and (Url::get('DEBUG')==1 or Url::get('debug')==1))
{	
	echo [[=information_query_in_page=]].'<br>';
	echo '<b>Page '.Url::get('page').' have : <span style="color:#ff0000">'.[[=total_query=]].' </span>query</b>';
}
?>
<div class="div-footer" <?php if(Url::get('print')){echo  ' style="display:none"';}?>>
    <div id="[[|button_name|]]" style="display:none;">
	<!--IF:is_admin(User::can_admin())-->
        <hr>
        <center>Time:[[|number_format|]] | Query: [[|number_query|]]
        | <a href="[[|link_structure_page|]]">B&#7889; c&#7909;c trang</a> | <a href="[[|link_edit_page|]]">S&#7917;a trang</a>
			| <a href="[[|delete_cache|]]">Xo&#225; cache</a>
		<!--LIST:languages-->
		<a href="<?php echo Url::build('change_language',array('language_id'=>[[=languages.id=]],'href'=>'?'.$_SERVER['QUERY_STRING']));?>">
			<!--IF:check([[=languages.id=]]==Portal::language())-->
				<b>[[|languages.name|]]</b>
			<!--ELSE-->
				[[|languages.name|]]
			<!--/IF:check-->
		</a>
		<!--/LIST:languages-->
		</center>
	<!--/IF:is_admin-->
	 </div>
</div>