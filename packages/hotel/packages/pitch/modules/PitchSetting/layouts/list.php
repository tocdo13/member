<style>
    #tabs {
      overflow: hidden;
      width: 100%;
      margin: 0;
      padding: 0;
      list-style: none;
    }
    
    #tabs li {
      float: left;
      margin: 0 .5em 0 0;
    }
    
    #tabs a {
      position: relative;
      background: #ddd;
      background-image: linear-gradient(to bottom, #fff, #ddd);  
      padding: .7em 3.5em;
      float: left;
      text-decoration: none;
      color: #444;
      text-shadow: 0 1px 0 rgba(255,255,255,.8);
      border-radius: 5px 0 0 0;
      box-shadow: 0 2px 2px rgba(0,0,0,.4);
    }
    
    #tabs a:hover,
    #tabs a:hover::after,
    #tabs a:focus,
    #tabs a:focus::after {
      background: #fff;
    }
    
    #tabs a:focus {
      outline: 0;
    }
    
    #tabs a::after {
      content:'';
      position:absolute;
      z-index: 1;
      top: 0;
      right: -.5em;  
      bottom: 0;
      width: 1em;
      background: #ddd;
      background-image: linear-gradient(to bottom, #fff, #ddd);  
      box-shadow: 2px 2px 2px rgba(0,0,0,.4);
      transform: skew(10deg);
      border-radius: 0 5px 0 0;  
    }
    #tabs #current a,
    #tabs #current a::after {
      background: #fff;
      z-index: 3;
    }
    #menu-bar-area {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 200px;
        background-color: #f1f1f1;
        right: 0px;
    }
    
    #menu-bar-area li a {
        display: block;
        color: #000;
        padding: 8px 16px;
        text-decoration: none;
    }
    
    #menu-bar-area li a.active {
        background-color: #4CAF50;
        color: white;
    }
    
    #menu-bar-area li a:hover:not(.active) {
        background-color: #555;
        color: white;
    }
    .table{
        width: 100px;
         border: 1px solid 	#2F4F4F;
         float: left;
         height: 100px;
         border-radius: 100%;
         margin-left: 8px;
         margin-top: 8px;
         position: relative;
    }
    .table:hover .table-book{
        display: block;
    }
    .table-book{
         width: 120px;
         border: 15px solid 	#2F4F4F;
         height: 120px;
         border-radius: 100%;
         position: absolute;
         z-index: 10;
         left: -10px;
         top: -10px;
         border-top-color: transparent;
         border-bottom-color: transparent;
         border-left-color: transparent;
         display: none;
    }
    .table-name{
        position: absolute;
        font-size: 13px;  /* position the top  edge of the element at the middle of the parent */
        left: 50%; /* position the left edge of the element at the middle of the parent */
        top: 80%; /* position the left edge of the element at the middle of the parent */
        transform: translate(-50%, -50%);
        color: #008B8B;
    }
    @font-face {
      font-family: "Roboto-Regular";
      src: url("resources/font/RobotoFont/Roboto-Regular.ttf");
    }
    .w3-modal-content{
        width: 400px;
        
    }
    #id01 .w3-input,#id01 label,#id01 .w3-select{
        margin-left: 20px;
        width: 350px;
    }
</style>
<form name="f" method="post">
<table >
    <tr>
        <td>
            <ul id="tabs">
                <li><a href="#" name="tab1"><i class="fa fa-futbol-o" aria-hidden="true" style="font-size: 15px;"></i> Sân bóng</a></li>
                <li><a href="#" name="tab2"><i class="fa fa-circle-o" aria-hidden="true" style="font-size: 15px;"></i> Sân tennis</a></li>
                <li><a href="#" name="tab3">Giá sân</a></li>
            </ul>
        </td>
        <td></td>
        <td></td>
        <td>[[.date.]] <input type="text" class="date" /></td>
        <td><input type="button" onclick="document.getElementById('id01').style.display='block'" class="w3-btn w3-white w3-border w3-border-green w3-round" value="Thêm sân"/></td>
    </tr>
</table>
<div id="content">
    <div id="tab1" style="width: 1000px;margin: 0px auto;">
        <table>
            <!--LIST:football_pitch-->
                <tr>
                    <!--LIST:football_pitch.pitch-->
                         <td valign="top">
                            <div class="pitch">
                                <p class="pitch-title">[[|football_pitch.pitch.name|]]</p>
                                <img src="packages/hotel/packages/pitch/modules/PitchSetting/arrow-down.png" class="pitch-price-button" aria-hidden="true" id="pitch-price-button-[[|football_pitch.pitch.id|]]" height="20px" title="[[.show_pitch_price.]]" onclick="show_price_table([[|football_pitch.pitch.id|]])"/></i>
                                <i class="pitch-calendar-button"><img src="packages/hotel/packages/pitch/modules/PitchSetting/calendar.png" height="50px" /></i>
                                <a href="?page=pitch_setting&cmd=add_match&pitch_id=[[|football_pitch.pitch.id|]]&date=<?php echo $_REQUEST['date'];?>" target="_blank" class="pitch-add-match-button"><i class="fa fa-plus" aria-hidden="true" style="font-size: 50px;color: green;"></i></a>
                            </div>
                            <table class="pitch-price-table" id="pitch-price-table-[[|football_pitch.pitch.id|]]">
                                <tr>
                                    <th style="width: 95px;">[[.price_from_time.]]</th>
                                    <th style="width: 95px;">[[.price_to_time.]]</th>
                                    <th style="width: 95px;" align="right">[[.pitch_price.]]</th>
                                    <th style="width: 95px;" align="right">[[.price_weekend.]]</th>
                                </tr>
                                <!--LIST:pitch_price-->
                                    <!--IF:cond([[=pitch_price.pitch_id=]]==[[=football_pitch.pitch.id=]])-->
                                     <tr>
                                        <td> 
                                            <input type="hidden" name="pitch_price_id[]" value="[[|pitch_price.id|]]" />
                                            <input type="hidden" name="pitch_id[]" value="[[|football_pitch.pitch.id|]]" /> 
                                            <input type="text" class="hour" placeholder="00:00" name="pitch_price_from_time[]" value="<?php echo date('H:i',Date_Time::to_time(date('d/m/Y'))+[[=pitch_price.from_time=]]); ?>" />
                                        </td>
                                        <td><input type="text" class="hour" placeholder="00:00" name="pitch_price_to_time[]" value="<?php echo date('H:i',Date_Time::to_time(date('d/m/Y'))+[[=pitch_price.to_time=]]); ?>" /></td>
                                        <td><input type="text" name="pitch_price_price[]" placeholder="0,000" value="<?php echo System::display_number([[=pitch_price.price=]]); ?>"  class="price" onkeypress="return event.charCode>=48 && event.charCode<=57;" /></td>
                                        <td><input type="text" name="pitch_price_weekend[]" placeholder="0,000"  value="<?php echo System::display_number([[=pitch_price.price_weekend=]]); ?>" class="price" onkeypress="return event.charCode>=48 && event.charCode<=57;" /></td>
                                     </tr>
                                    <!--/IF:cond-->
                                <!--/LIST:pitch_price-->
                                <tr style="border-bottom: none;">
                                    <td colspan="4" class="w3-center" style="padding-top: 20px;">
                                        <input onclick="add_price([[|football_pitch.pitch.id|]])" class="w3-btn w3-ripple w3-green" style="width: 100px;" value="[[.add.]]"/>
                                        <input type="submit"  class="w3-btn w3-ripple w3-green" style="width: 100px;" value="[[.save.]]"/>
                                    </td>
                                </tr>
                            </table>
                        </td>   
                    <!--/LIST:football_pitch.pitch-->
                </tr>
            <!--/LIST:football_pitch-->
        </table>
    </div>
    <div id="tab3">
        <h3>[[.fast_setting_pitch_price.]]</h3>
        <div id="fast_setting">
            <div style="width: 500px;border: 1px solid green;">
                <br />
                <table>
                    <tr>
                        <td>
                            <label>[[.pitch_area.]]</label>
                              <select class="w3-select w3-border" name="fast_pitch_area" id="fast_pitch_area">
                              <option value="" disabled="" selected="">Choose your [[.pitch_type.]]</option>
                              <!--LIST:pitch_area-->
                              <option value="[[|pitch_area.id|]]">[[|pitch_area.name|]]</option>
                              <!--/LIST:pitch_area-->  
                            </select>
                        </td>
                        <td>
                            <label>[[.pitch_type.]]</label>
                              <select class="w3-select w3-border" name="fast_pitch_type" id="fast_pitch_type">
                              <option value="" disabled="" selected="">Choose your [[.pitch_type.]]</option>
                              <!--LIST:pitch_type-->
                              <option value="[[|pitch_type.id|]]">[[|pitch_type.name|]]</option>
                              <!--/LIST:pitch_type--> 
                            </select>
                        </td>
                    </tr>
                </table>
                <hr style="border: 0.5px solid green;" />
                <table id="fast-table" width="100%">
                    <tr>
                        <th>[[.price_from_time.]]</th>
                        <th>[[.price_to_time.]]</th>
                        <th align="right">[[.pitch_price.]]</th>
                        <th align="right">[[.price_weekend.]]</th>
                    </tr>
                    <tr>
                        <td><input class="w3-input w3-border hour" name="fast_time_from[]" type="text" placeholder="00:00"/></td>
                        <td><input class="w3-input w3-border hour" name="fast_time_to[]" type="text" placeholder="00:00"/></td>
                        <td><input class="w3-input w3-border price" placeholder="0,000" name="fast_price[]"  onkeypress="return event.charCode>=48 && event.charCode<=57;" type="text"/></td>
                        <td><input class="w3-input w3-border price" placeholder="0,000" name="fast_price_weekend[]"  onkeypress="return event.charCode>=48 && event.charCode<=57;" type="text"/></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="w3-center" style="padding-top: 20px;">
                        <input onclick="add_fast_time()" class="w3-btn w3-ripple w3-green" style="width: 100px;" value="[[.add.]]"/>
                        <input type="button" name="save-fast-price" onclick="check_fast_form()" class="w3-btn w3-ripple w3-green" style="width: 100px;" value="[[.save.]]"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="id01" class="w3-modal">
 <div class="w3-modal-content w3-card-4 w3-animate-zoom">
  <header class="w3-container w3-green"> 
   <span onclick="document.getElementById('id01').style.display='none'" 
   class="w3-button w3-green w3-xlarge w3-display-topright">&times;</span>
   <h2>Thêm sân</h2>
  </header>
  <p>
  <label>[[.name.]]</label>
  <input class="w3-input w3-border" name="pitch-name-add" id="pitch-name-add" type="text"/></p>
  <p>
    <label>[[.pitch_type.]]</label>
      <select class="w3-select w3-border" name="pitch-type-add" id="pitch-type-add">
      <option value="" disabled="" selected="">Choose your [[.pitch_type.]]</option>
      <!--LIST:pitch_type-->
      <option value="[[|pitch_type.id|]]">[[|pitch_type.name|]]</option>
      <!--/LIST:pitch_type-->
    </select>
   </p>
    <p>
    <label>[[.pitch_area.]]</label>
      <select class="w3-select w3-border" name="pitch-area-add" id="pitch-area-add">
      <option value="" disabled="" selected="">Choose your [[.area.]]</option>
      <!--LIST:pitch_area-->
      <option value="[[|pitch_area.id|]]">[[|pitch_area.name|]]</option>
      <!--/LIST:pitch_area--> 
    </select>
   </p>
   <p>
  <label>[[.note.]]</label>
  <input class="w3-input w3-border" name="note" type="text"></p>
  <div class="w3-container w3-light-grey w3-padding">
    <input type="button" class="w3-btn w3-white w3-border w3-border-green w3-round" value="[[.save.]]" onclick="check_add_pitch()"/>
   <button class="w3-button w3-right w3-white w3-border" 
   onclick="document.getElementById('id01').style.display='none'">Close</button>
  </div>
 </div>
</div>
</div>
<input type="hidden" id="is-save-fast-price" name="is-save-fast-price" value="0" />
</form>
<script>
    jQuery('.hour').mask('99:99');
    jQuery('.date').datepicker();
    var tab=<?php echo '\''.Url::get('tab').'\''; ?>;
    if(tab==''){
        tab='tab1';
    }
    jQuery("#content").find("[id^='tab']").hide(); // Hide all content
        jQuery("#tabs li:first").attr("id","current"); // Activate the first tab
        jQuery('#content #'+tab+'').fadeIn();
     jQuery('#tabs a').click(function(e) {
        e.preventDefault();
        if (jQuery(this).closest("li").attr("id") == "current"){ //detection for current tab
         return;       
        }
        else{             
          jQuery("#content").find("[id^='tab']").hide(); // Hide all content
          jQuery("#tabs li").attr("id",""); //Reset id's
          jQuery(this).parent().attr("id","current"); // Activate this
          jQuery('#' + jQuery(this).attr('name')).fadeIn(); // Show content for the current tab
        }
    });
    jQuery('.price').each(function(){
        jQuery(this).on('input',function(){
            if(jQuery(this).val()!=''){
                jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
            } 
        });
    });
    //document.getElementsByClassName("tablink")[0].click();
    function check_add_pitch(){
        var check=0;
        if(jQuery('#pitch-name-add').val()==''){
            alert('[[.input_pitch_name.]]');check=1;
        }
        if(jQuery('#pitch-type-add').val()==''){
            alert('[[.input_pitch_type.]]');check=1;
        }
        if(jQuery('#pitch-area-add').val()==''){
            alert('[[.input_pitch_area.]]');check=1;
        }
        if(check==0){
            f.submit();
        }
    }
    function openCity(evt, cityName) {
      var i, x, tablinks;
      x = document.getElementsByClassName("city");
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
      }
      tablinks = document.getElementsByClassName("tablink");
      for (i = 0; i < x.length; i++) {
        tablinks[i].classList.remove("w3-light-grey");
      }
      document.getElementById(cityName).style.display = "block";
      evt.currentTarget.classList.add("w3-light-grey");
    }
    function add_fast_time(){
        var lang=jQuery('#fast-table tr').length-2;
        var tr='<tr>'+
                    '<td><input class="w3-input w3-border hour add" placeholder="00:00" name="fast_time_from[]" type="text"></td>'+
                    '<td><input class="w3-input w3-border hour add" placeholder="00:00" name="fast_time_to[]" type="text"></td>'+
                    '<td><input class="w3-input w3-border price" placeholder="0,000" onkeypress="return event.charCode>=48 && event.charCode<=57;" name="fast_price[]" type="text"></td>'+
                    '<td><input class="w3-input w3-border price" placeholder="0,000" onkeypress="return event.charCode>=48 && event.charCode<=57;" name="fast_price_weekend[]" type="text"></td>'+
                '</tr>';
        jQuery('#fast-table tr:last').before(tr);
        jQuery('.hour.add').mask('99:99');
        jQuery('.price').each(function(){
            jQuery(this).on('input',function(){
                if(jQuery(this).val()!=''){
                    console.log(jQuery(this).val());
                    jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
                } 
            });
        });       
    }
    function show_price_table(table_id){
        if(jQuery('#pitch-price-table-'+table_id).css('display')=='none'){
            jQuery('#pitch-price-table-'+table_id).css('display','block');
            jQuery('#pitch-price-button-'+table_id).attr('src','packages/hotel/packages/pitch/modules/PitchSetting/arrow-up.png');
        }else{
            jQuery('#pitch-price-table-'+table_id).css('display','none');
            jQuery('#pitch-price-button-'+table_id).attr('src','packages/hotel/packages/pitch/modules/PitchSetting/arrow-down.png');
        }
    }
    function add_price(id){
        var tr ='<tr>'+
                    '<td><input type="hidden" name="pitch_price_id[]" /> <input type="hidden" name="pitch_id[]" value='+id+' /> <input type="text" name="pitch_price_from_time[]" placeholder="00:00" class="hour" /></td>'+
                    '<td><input type="text" name="pitch_price_to_time[]" placeholder="00:00" class="hour" /></td>'+
                    '<td><input type="text" class="price" placeholder="0,000" name="pitch_price_price[]" onkeypress="return event.charCode>=48 && event.charCode<=57;" /></td>'+
                    '<td><input type="text" class="price" placeholder="0,000" name="pitch_price_weekend[]" onkeypress="return event.charCode>=48 && event.charCode<=57;" /></td>'+
                 '</tr>';
        jQuery('#pitch-price-table-'+id+' tr:last').before(tr);
        jQuery('.hour').mask('99:99');
        jQuery('.price').each(function(){
            jQuery(this).on('input',function(){
                jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
            });
        });         
    }
    function check_fast_form(){
        jQuery('#is-save-fast-price').val(1);
        var check=0;
        if(jQuery('#fast_pitch_area').val()==''){
            alert('[[.select_pitch_area.]]');
            check=1;
        }
        if(jQuery('#fast_pitch_type').val()==''){
            alert('[[.select_pitch_type.]]');
            check=1;
        }
        jQuery('#fast-table .hour').each(function(){
            if(jQuery(this).val()==''){
                alert('[[.input_time.]]');
                check=1;
                jQuery(this).focus();
                return false;
            }
        });
        if(check==0){
            f.submit();
        }
    }
</script>