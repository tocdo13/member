<?php
$doc = new DOMDocument();
@$doc->loadHTMLFile('http://anhdep.top1.vn/anh-girl-xinh-chau/44973-em-xinh-xinh.html');
$doc->saveHTML();
$items = $doc->getElementsByTagName('img');
foreach($items as $item){
	list($width, $height) = getimagesize($item->getAttribute('src'));
	if($width>500){
		echo '<img src="'.$item->getAttribute('src').'">';
	}
}
?>
<div style="position:absolute;left:400px;top:0p;"><h2 style="color:#FF0000">
Th?i h?n s? d?ng ph?n m?m c?a quý khách còn <?php $start_date = strtotime(date('m/d/Y',1282639084)); echo date('d',$start_date)+5 - date('d');?> ngày</h2></div>