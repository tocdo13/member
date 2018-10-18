<?php
function begin_vml()
{
?><head>
<xml:namespace ns="urn:schemas-microsoft-com:vml" prefix="v"/>
<style type="text/css">
v\:* { behavior: url(#default#VML);}
</style>

</head>
<?php echo '<?xml:namespace prefix = v />';
}
function end_vml()
{
echo '</body>';
}
function draw_column_chart($values,$title,$x_label,$y_label,$currency='USD',$x=100,$y=50,$width=650,$height = 400,$bar_width=15,$bar_space = 3)
{
$max_value = 0;
foreach($values as $i=>$value)
{
	if($max_value<$value['amount'])
	{
		$max_value=$value['amount'];
	}
}

echo '<v:line from="'.($x).'px,'.($y+$height+1).'px" to="'.($width+$x).'px,'.($y+$height+1).'px" style="POSITION:absolute;"><v:stroke startarrow="true" endarrow="block"/></v:line>';
echo '<v:line from="'.($x).'px,'.($y+$height+1).'px" to="'.($x).'px,'.($y).'px" style="POSITION:absolute;" fillcolor="green"><v:stroke startarrow="true" endarrow="block"/></v:line>';
$amount_scale = 10000000;
while($amount_scale>0 and $max_value/$amount_scale<50)
{
	$amount_scale /= 10;
}
if($amount_scale>0 and $scale = $max_value/($height-50))
{
	echo '<v:rect style="LEFT: '.($x-50).'px; WIDTH:200px;POSITION:absolute; TOP: '.($y-24).'px; " strokecolor="white"><v:textbox style="mso-fit-text-to-shape:TRUE">&#272;&#417;n v&#7883; '.System::display_number($amount_scale).' '.$currency.'</v:textbox></v:rect>';
	echo '<v:rect style="LEFT: '.($x-70).'px; WIDTH:200px;POSITION:absolute; TOP: '.($y).'px; " strokecolor="white"><v:textbox style="mso-fit-text-to-shape:TRUE">'.$y_label.'</v:textbox></v:rect>';
	echo '<v:rect style="LEFT: '.($width+$x).'px; POSITION:absolute; TOP: '.($y+$height+5).'px; "><v:textbox style="mso-fit-text-to-shape:TRUE">'.$x_label.'</v:textbox></v:rect>';
	echo '<v:rect style="LEFT: '.($x-25).'px; POSITION:absolute; TOP: '.($y+$height+5).'px; "><v:textbox style="mso-fit-text-to-shape:TRUE">0</v:textbox></v:rect>';
	echo '<v:rect style="LEFT: '.($x+100).'px; WIDTH:'.($width-100).'px;POSITION:absolute; TOP: '.($y-30).'px; " strokecolor="white"><v:textbox style="mso-fit-text-to-shape:TRUE"><font size=4>'.$title.'</font></v:textbox></v:rect>';
	for($mult = 5;$mult*20<$max_value/$amount_scale;$mult+=5);
	for($i=$mult;$i<$max_value/$amount_scale+$mult;$i+=$mult)
	{
		$yi = round(($i*$amount_scale)/$scale);
		echo '<v:line from="'.($x-3).'px,'.($y+$height+1-$yi).'px" to="'.($x+3).'px,'.($y+$height+1-$yi).'px" style="POSITION:absolute;"></v:line>';
		echo '<v:rect style="LEFT: '.($x-50).'px; POSITION:absolute; TOP: '.($y+$height+1-$yi-10).'px; "><v:textbox style="mso-fit-text-to-shape:TRUE">'.$i.'</v:textbox></v:rect>';
	}
	foreach($values as $i=>$value)
	{
		$bar_height = round($value['amount']/$scale);
		echo '<v:rect style="LEFT: '.($x+$bar_width+$i*($bar_width+$bar_space)).'px; WIDTH: '.$bar_width.'px; POSITION:absolute; TOP: '.($y+$height-$bar_height).'px; HEIGHT: '.$bar_height.'px" fillcolor="green"><v:fill type=gradient color="black" color2="red"/></v:rect>';
		echo '<v:rect style="LEFT: '.($x+$bar_width+$i*($bar_width+$bar_space)-12).'px; POSITION:absolute; TOP: '.($y+$height-$bar_height-25).'px; "><v:textbox style="mso-fit-text-to-shape:TRUE">'.round($value['amount']/$amount_scale).'</v:textbox></v:rect>';
		echo '<v:rect style="LEFT: '.($x+$bar_width+$i*($bar_width+$bar_space)-5).'px; POSITION:absolute; TOP: '.($y+$height+5).'px; "><v:textbox style="mso-fit-text-to-shape:TRUE">'.$i.'</v:textbox></v:rect>';
	}
}
}
function draw_pie_chart($values,$title,$cx,$cy,$r,$title_x,$title_y,$lx,$ly)
{
	echo '<div style="'.$title_x.'px;top:'.$title_y.'px;font-family:sans-serif;font-size:24px">'.$title.'</div>';
    $total = 0;
    foreach($values as $value)
	{
		$total += $value['amount'];
	}
	if($total==0)
	{
		echo  '<div style="'.$title_x.'px;top:'.$title_y.'px;font-family:sans-serif;font-size:24px;color:#ff0000">'.Portal::language('nodata').'</div>';
		return;
	}
    $angles = array();
    foreach($values as $id=>$value)
	{
		$angles[$id] = $value['amount']/$total*360;
	}	
    $startangle = 90;
	$i=0;
    foreach($values as $id=>$value)
	{
        $sa = round($startangle * 65535);
        $a = -round($angles[$id] * 65536);
		echo '<v:shape path = "M '.$cx.' '.$cy.' AE '.$cx.' '.$cy.' '.$r.' '.$r.' '.$sa.' '.$a.' X E" fillcolor="'.$value['color'].'" strokeweight="2px" style="position:absolute;width:'.($r*2).'px;height:'.($r*2).'px;"></v:shape>';
        $startangle -= $angles[$id];
		echo '<v:rect style="left:'.$lx.'px;top:'.($ly+$i*30).'px;width:20px;height:20px;position:absolute" fillcolor="'.$value['color'].'" stroke="black" strokeweight="2"></v:rect>';
        echo '<div style="position:absolute;left:'.($lx + 30).'px;top:'.($ly + 30*$i + 5).'px;font-family:sans-serif;font-size:16px">'.$value['label'].'</div>';
		$i++;
    }

}
?>