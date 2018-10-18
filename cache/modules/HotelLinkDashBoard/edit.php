<style>
body{
    -webkit-user-select: none; /* Safari 3.1+ */
    -moz-user-select: none; /* Firefox 2+ */
    -ms-user-select: none; /* IE 10+ */
    user-select: none; /* Standard syntax */
}
body {-webkit-text-stroke: 0px !important; -webkit-font-smoothing: antialiased! important;}
.simple-layout-center{
    margin-top: -36px;
}
nav a{
    text-decoration: none;
}
nav a:hover{
    text-decoration: none;
}
#loader{
    padding: 100px;
} 
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid grey;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
#table-room td{
    border-bottom: 1px solid #CCCCCC;
}
#table-room select{
    border: none !important;
    background: white !important;
    outline: none ;
}
#table-room th{
    border: none;
    padding: 5px;
    text-align: left;
    border-bottom: 2px solid #6678B1;
    color: #1F4BA5;
    font-weight: bold;
}
#table-rateplan{
    width: 50% !important;
}
#table-availability td,th{
    text-align: center;
    padding: 5px;
    border: 1px solid #778899;
    min-width: 35px !important;
}
#table-availability td{
    cursor: pointer;
}
#table-availability{
    overflow:scroll;
}
#table-rates td,th{
    text-align: center;
    padding: 5px;
    border: 1px solid #778899;
    min-width: 35px !important;
}
#table-stop-sell td,th{
    text-align: center;
    padding: 5px;
    border: 1px solid #778899;
    min-width: 35px !important;
}
#table-stop-sell td{
    cursor: pointer;
}
#table-extra-adult td,th{
    text-align: center;
    padding: 5px;
    border: 1px solid #778899;
    min-width: 35px !important;
}
#table-extra-adult td{
    cursor: pointer;
}
#table-rates td{
    cursor: pointer;
}
#change_availability_input{
    height: 27px !important;
}
#change_availability{
    height: 27px;
    width: 50px;
}
option:first-child{
        color: red;
}
.weekend{
    background-color: #dbfdb2;
}
.weekend-th{
    background-color: #76be43;
}
.box-select{
    background-color: #7CB7FF !important;
}
.empty-data{
    background-color: #FFCDCD;
}
.month_option{
    height: 27px;
}
input[type=radio]{
    height: 20px;
}
#layout-content-main> *{
    font-family: "Roboto-Regular" !important;
    font-size: 13px !important;
}
*{
    font-family: "Roboto-Regular" !important;
    font-size: 12.5px !important;
}
.room_close{
    background-image: url('packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/close.png');
    background-repeat: no-repeat;
    background-size: 10px;
}
@font-face {
  font-family: "Roboto-Regular";
  src: url("resources/font/RobotoFont/Roboto-Regular.ttf");
}
</style>
<div id="layout-content-main">
    <div style="margin-top: 50px;" class="w3-bar w3-white w3-large" style="z-index:4">
      <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i> &nbsp;Menu</button>
      <span class="w3-bar-item w3-right"></span>
    </div>
    <!-- Sidebar/menu -->
    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:200px;" id="mySidebar"><br/>
      <div class="w3-container w3-row">
        <div class="w3-col s4">
          <img src="packages/core/skins/default/css/jquery/images/newway_logo.png" class="w3-margin-right" style="width:150px"/>
        </div>
      </div>
      <hr/>
      <div class="w3-container">
        <h5 style="color: #167fc1; font-weight: bold;"> Dashboard</h5>
      </div>
      <div class="w3-bar-block">
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-power-off fa-fw"></i>&nbsp;Close Menu</a>
        <a id="400" href="#" class="w3-bar-item w3-button w3-padding" onclick="getLayout(<?php echo '\''.date('d/m/Y').'\''; ?>);selectedBarItem(400);" style="color: red;"><i class="fa fa-history" style="color: #3CC1FF;"></i>&nbsp;Booking Management</a>
        <a id="100" href="#" class="w3-bar-item w3-button w3-padding" onclick="getBookingEngineForm();selectedBarItem(100);"><i class="fa fa-cog" style="color: #3CC1FF;"></i>&nbsp;Booking Engine</a>
        <a id="500" href="#" class="w3-bar-item w3-button w3-padding" onclick="getRatePlansLayoutContent();selectedBarItem(500);"><i class="fa fa-cog" style="color: #3CC1FF;"></i>&nbsp;Rate Plans</a>
        <a id="600" href="#" class="w3-bar-item w3-button w3-padding" onclick="getRoomLayoutContent();selectedBarItem(600);"><i class="fa fa-recycle" style="color: #3CC1FF;"></i>&nbsp;Room Mapping</a>
        <a id="700" href="#" class="w3-bar-item w3-button w3-padding" onclick="getCustomerLayoutContent();selectedBarItem(700);"><i class="fa fa-recycle" style="color: #3CC1FF;"></i>&nbsp;Customer Mapping</a>
      </div>
    </nav>
    
    
    <!-- Overlay effect when opening sidebar on small screens -->
    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
    <input type="text" id="id1" />
    <!-- !PAGE CONTENT! -->
    <form name="Form" method="post"> 
        <div class="w3-main" style="margin-left:200px;font-family: San Francisco;font-size: 20px!important;">
        
          <!-- Header -->
          <header class="w3-container">
          </header>
          <div id="content" class="w3-container">
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script src="packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/layouts/get_content.js" type="text/javascript"></script>
<script>
var isDown = false; 
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
getLayout(<?php echo '\''.date('d/m/Y').'\''; ?>); 
</script>
