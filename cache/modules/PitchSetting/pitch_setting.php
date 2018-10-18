
<div id="pitch-setting-content">
    <div id="pitch-menu-left">
        <ul>
            <li style="font-size: 18px;"><i class="fa fa-futbol-o" aria-hidden="true" style="font-size: 25px;"></i>&nbsp;&nbsp;Pitch Management</li>
            <li onclick="getFcType()" class="pitch-setting-menu-active">FC type</li>
            <li onclick="getFC()">FC</li>
            <li onclick="getPitch()">Pitch</li>
            <li onclick="getPitchType<()">Pitch Type</li>
            <li onclick="getPitchPrice()">Pitch Price</li>
            <li onclick="getTimeRanges()">Time Ranges</li>
        </ul>
    </div>
    <div id="pitch-setting-main">
    
    </div>
</div>
<script>
 jQuery(document).ready(function(){
    jQuery('#pitch-menu-left li').not(':first').click(function(){
        jQuery('#pitch-menu-left li').css('background-color', 'white');
        jQuery(this).css('background-color', '#1BA1E2');
    });
 });
</script>