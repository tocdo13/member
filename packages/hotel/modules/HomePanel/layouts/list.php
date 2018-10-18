<p id="hello"></p>
<div class="w3-text-blue" style="text-align: center; font-size: 30px; font-family: sans-serif; padding-top: 50px;">
<?php
$t=date("H");
if($t<"10"){
    echo '<span class="w3-text-orange" style="font-size: 26px;">Good Morning!</span><br/><br/>Welcome to NewwayPMS<br/><br/><br/><br/><span class="w3-text-indigo" style="font-size: 14px;">If you need assistance, please contact us <span class="w3-text-red" style="font-size: 18px;">1900636116</span><br/><br/>Website: <a class="w3-text-red" style="font-size: 14px;" href="http://quanlyresort.com" target="blank">www.quanlyresort.com</a></span>';
}elseif($t<14){
    echo '<span class="w3-text-orange" style="font-size: 26px;">Have a nice day!</span><br/><br/>Welcome to NewwayPMS<br/><br/><br/><br/><span class="w3-text-indigo" style="font-size: 14px;">If you need assistance, please contact us <span class="w3-text-red" style="font-size: 18px;">1900636116</span><br/><br/>Website: <a class="w3-text-red" style="font-size: 14px;" href="http://quanlyresort.com" target="blank">www.quanlyresort.com</a></span>';
}elseif($t<18){
    echo '<span class="w3-text-orange" style="font-size: 26px;">Good Afternoon!</span><br/><br/>Welcome to NewwayPMS<br/><br/><br/><br/><span class="w3-text-indigo" style="font-size: 14px;">If you need assistance, please contact us <span class="w3-text-red" style="font-size: 18px;">1900636116</span><br/><br/>Website: <a class="w3-text-red" style="font-size: 14px;" href="http://quanlyresort.com" target="blank">www.quanlyresort.com</a></span>';
}else{
    echo '<span class="w3-text-orange" style="font-size: 26px;">Good Evening!</span><br/><br/>Welcome to NewwayPMS<br/><br/><br/><br/><span class="w3-text-indigo" style="font-size: 14px;">If you need assistance, please contact us <span class="w3-text-red" style="font-size: 18px;">1900636116</span><br/><br/>Website: <a class="w3-text-red" style="font-size: 14px;" href="http://quanlyresort.com" target="blank">www.quanlyresort.com</a></span>';
}
  ?>
</div>