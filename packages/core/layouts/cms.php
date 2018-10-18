<script type="text/javascript" src="packages/core/includes/js/jquery/splitter.js"></script>
<style type="text/css" media="all">
#MySplitter {
	height: 478px;
	width:100%;
}
#LeftPane {
	overflow: auto;
	width: 20%;	
	min-width: 100px;
}
#RightPane {
	min-width: 100px;
	width:80%;
	overflow:auto;
}
#MySplitter .vsplitbar {
	width: 6px;
	background: #aca url(vgrabber.gif) no-repeat center;
}
#MySplitter .vsplitbar.active, #MySplitter .vsplitbar:hover {
	background: #e88 url(vgrabber.gif) no-repeat center;
	cursor:crosshair;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#MySplitter").splitter({
		type: 'v',
		initA: true,	// use width of A (#LeftPane) from styles
		accessKey: '|',
		maxA : 200,
		minA:100		
	});
	jQuery(window).bind("resize", function(){
		jQuery("#MySplitter").trigger("resize"); 
	}).trigger("resize");
});
</script>
<div class="cms-layout-bound">
	<div class="cms-banner">[[|banner|]]</div>
	<div id="MySplitter">
		<div id="LeftPane">[[|left|]]</div>	
		<div id="RightPane">[[|right|]]</div>
	</div>
	<div class="cms-footer">[[|footer|]]</div>
</div>
