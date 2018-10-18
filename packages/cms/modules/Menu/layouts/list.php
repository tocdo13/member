<style>
/*.menu-top
{
   z-index: 1000000;
   position: fixed; 
}*/
#support-bound{
	position:fixed;
	bottom:35px;
	right:0px;
	width:250px;
	display:none;
	border:1px solid #00b9f2;
	background-color: white;
    z-index: 99;
    border-radius: 10px;
    box-shadow: 0px 0px 5px #171717;
    background: white;
}

#chat
{
    width: 100%;
    height: 300px;
    background: white;
    border-radius: 10px;
}
.head_chat
{
    padding-left: 20px;
    height: 25px;
    line-height: 25px;
    border-radius: 10px 10px 0px 0px;
    background: #4267B2;
    color: white;
}


.user_tag
{
    width:100%; 
    height:30px; 
    line-height:30px;
}

.user_tag:hover
{
    background: khaki;
    cursor: pointer;
}


.user_tag span.child_name
{
    font-size: 9px;
    width:80%; 
    height:30px; 
    line-height:30px;
    float:left;
}

.send_msg
{
    padding : 5px 10px;  
}

.send_msg textarea
{
  width: 210px;    
  height: 50px;
  padding: 5px;
  resize: none;
  float: left;
  border-radius: 3px;
  font-weight: normal;
}

.send_msg textarea:focus
{
    outline: none !important;
    border:1px solid #a7c5f2;
    box-shadow: 0 0 10px #719ECE;
}
.send_msg button
{
    float: left;
    margin-top: 7px;
    margin-left: 5px;
}

button.button_send
{
    padding:5px;
    border: thin solid;
    border-radius : 3px;
    background-color: #4CAF50; /* Green */
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    margin-top: 5px;
}

.khung{display: block;
background: #fff;
position: fixed;
width: 50%;
left: 23%;
top: 25%;
border: 3px solid #AF8888;	
padding:0;}


.testTable li{list-style: none;}
.testRow{
	display: inline-table;
}
.testRow > span {
list-style: none;
display: table-cell;
border: 1px solid #000;
padding: 2px 6px;

width: 1%;

}
.testRow span:first-child{
	width: 20%;
}
.testTable li {
list-style: none;
width: 100%;

}
.testTable{
	height: 173px;
overflow-y: scroll;
padding:0px;
width: 100%;
}

.nd_title {
width: 65% !important;
float: left;
border: 1px solid;
text-align: center;
padding: 5px 0;
}
.gio {
width: 34% !important;
float: left;
border: 1px solid;
border-right: none;
padding: 5px 0;
text-align: center;
}
.header_list_work{
	text-align: center;
height: 17px !important;
font-size: 17px;
text-transform: capitalize;
padding: 9px 0;
background: rgb(137, 200, 247);
}
.header_list_work a{
	float: right;	
}
.header_list_work img{
	width: 20px;
	position: relative;
top: -9px;
margin-right: 0px !important;
}
.khung_hide{display: none;}
#show-popup img{width:30px;}
.show_cout li{float: left;padding: 9px 3px;list-style: none;font-weight: bold;}


/** design by manhnv **/
    #description_page_list {
        width: 70px;
        min-height: 70px;
        max-height: 450px;
        background: #FFFFFF;
        position: fixed;
        top: 120px;
        right: -75px;
        transition: all 0.5s linear;
        z-index: 99;
        border: 2px solid #ff4056;
        box-shadow: 0px 0px 3px #171717;
    }
    #description_page_list:hover {
        right: 0px;
    }
    .button_description_page {
        width: 50px;
        height: 50px;
        position: relative;
        padding: 0px;
        margin: 0px auto;
        clear: both;
        cursor: pointer;
    }
    .button_description_page > p {
        width: 100px;
        background: #ff4056;
        color: #FFFFFF;
        line-height: 50px;
        position: absolute;
        top: -12px;
        right: -500px;
        opacity: 0;
        text-align: center;
        transition: all 0.5s ease-out;
    }
    .button_description_page:hover > p {
        right: 100%;
        opacity: 1;
    }

/** and manhnv **/
.simple-layout-middle{width:100%;}
</style>
 <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery("#testRibbon").officebar({
        onSelectTab:function(e) { 
			jQuery.ajax({
						url:"form.php?block_id=<?php echo Module::block_id();?>",
						type:"POST",
						data:{page_id:e.id},
						success:function(html){
						}
					});	},
        onBeforeShowSplitMenu: function(e) { },
        onAfterShowSplitMenu:  function(e) { },
        onAfterHideSplit:      function(e) { },
        onShowDropdown:        function(e) { },
        onHideDropdown:        function(e) { },
        onClickButton:         function(e) { }
        })
      }).click(function(event){
		target = event.target;
		if(jQuery(target).parent().attr('class')!='opened')
		{
			jQuery('.buttonsplitmenu').each(function(i){
				if(jQuery(this).css('display')=='' || jQuery(this).css('display')=='block')
				{
					jQuery(this).css('display','none');
				}
			})
		}
	});
  </script>
<?php
    if(USING_CHAT && USING_CHAT == 1)
    {
?>  
<script src='http://118.70.151.38:3000/socket.io/socket.io.js' type='text/javascript'></script> 
<?php
    }
?>
<div id="testRibbon" class="officebar" style="display:none;">
  	<input  name="home_page" type="hidden" id="home_page" style="border: 30px solid #00b9f2;" />
    <ul class="menu-top" style="height: 100px; width: 100%;">
    <?php 
        $group_name ='';
        $group_name_cate_id = '';
        $i=1;
        $act = 0;
        $group_old='';
    ?>
    <!--LIST:categories-->
      <li 
      <?php 
          if($_SESSION['home_page'] == '' && $i==1)
          {
            echo 'class="current"';
          }
          else if($_SESSION['home_page'] !='' && $this->map['categories']['current']['id']==$_SESSION['home_page'])
          {
            echo 'class="current"';
          } 
          $i++; $k=0;
      ?> 
      rel="[[|categories.id|]]">
        <a href="#" rel="home" id="[[|categories.id|]]">
            <span id="Menu"><?php echo Portal::language()==1?[[=categories.name_1=]]:[[=categories.name_2=]]; ?></span>
        </a>
         <!--IF:cond_child_01([[=categories.child=]])-->
         <ul>
         	  <!--LIST:categories.child-->
               <?php 
                   if($group_name_cate_id != [[=categories.child.group_name=]]."_".[[=categories.id=]])
                   {
                        $t=0;
                        $group_old = $group_name;
                        $group_name_cate_id=[[=categories.child.group_name=]]."_".[[=categories.id=]];
                        $group=0; 
                        if($act==3 && $k!=0)
                        {
                            echo '</ul></div>';				
                        }
                   if($k!=0)
                   {
                        echo '</li>';
                   }
                   $act=0;
               ?>
                    <li>
                	<!--IF:cond_group([[=categories.child.group_name=]])-->
                        <span class="w3-text-light-gray">[[|categories.child.group_name|]]</span>
                    <!--/IF:cond_group-->
                    <?php 
                        } 
                            $group++; 
                            if($group<3)
                            {
                    ?>
                        <!--IF:cond_child_02([[=categories.child.child=]])-->
                          <?php 
                            if($act==3)
                            { 
                                echo '</ul></div>';
                            }
                            $act=1;
                          ?>  
                           <div class="button textlist">
                               <ul>
                                   <div class="dropdown" id="button_dropdown" style="padding: 0px !important;">
                                       <a href="#" rel="paste" style="text-align: center !important; padding: 0px !important;">                                	
                                           <!--IF:img_cond_03([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                                <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" style="width: 25px; height: 25px; margin-bottom: 2px;" />
                                           <!--/IF:img_cond_03-->
                                           <span>
                                                <?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>
                                           </span>
                                       </a>
                                       <div>
                                           <ul>
                                           <?php $y=1;?>
                                           <!--LIST:categories.child.child-->
                                                <?php 
                                                    if($y==1)
                                                    { 
                                                        echo '<li class="menutitle">'.$group_name.'</li>';
                                                    }
                                                    $y++;
                                                ?>
                                                <li> 
                                                    <a href="[[|categories.child.child.url|]]">
                                                        <!--IF:img_cond04([[=categories.child.child.icon_url=]] && [[=categories.child.child.icon_url=]]!=' ')-->
                                                            <img src="[[|categories.child.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.child.name_1=]]:[[=categories.child.child.name_2=]]; ?>" />
                                                        <!--/IF:img_cond04--><?php echo Portal::language()==1?[[=categories.child.child.name_1=]]:[[=categories.child.child.name_2=]]; ?>
                                                    </a>
                                                </li>
                                           <!--/LIST:categories.child.child-->
                                           </ul>
                                       </div>
                                   </div> 
                               </ul>
                           </div>
                            <!--ELSE-->
                            <!--IF:cond_position([[=categories.child.important=]]==1)-->
                            <?php 
                            if($act==3)
                            {
                                echo '</ul></div>';
                            }
                            $act=2;
                            ?> 
                            <div class="button textlist">
                                <ul>
                                <div class="dropdown" id="button_click">
                                    <a href="[[|categories.child.url|]]" id="button_[[|categories.child.id|]]">
                                        <!--IF:img_cond([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                            <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" />
                                        <!--/IF:img_cond-->
                                        <span><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></span>
                                    </a>
                                </div>
                            </div>
                             <!--ELSE-->
                             <?php 
                             if($act!=3)
                             { 
                                echo '<div class="button textlist" lang="'.$act.'">';echo '<ul>';
                             }

                             $act=3;
                             ?>
                             <li style="float: left;">
                                 <a href="[[|categories.child.url|]]" rel="paste" style="width: 65px !important; height: 60xp; text-align: center;">
                                 <!--IF:img_cond02([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                    <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" style="width: 25px; height: 25px; margin-bottom: 2px; text-align: center;" />
                                 <!--/IF:img_cond02-->
                                 <div style="width: 60px !important; text-align: center"><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></div>
                                 </a>
                             </li>
                           <!--/IF:cond_position-->
                         <!--/IF:cond_child_02-->
                  <?php 
                      }
                      else 
                      if($group>=3)
                      { 
                        if($group==3)
                        {
                            if($act==3)
                            {
                                echo '</ul></div>';
                            }
                            $act=0;
                  ?>  
					  	<div class="dropdown" id="button_more">
                          <a href="#" rel="paste">
                            <img src="packages/core/skins/default/css/jquery/images/button5.gif" title="More" style="height:14px; width:15px;"/>
                          </a>
                          <div>
                            <ul>
                                <li class="menutitle">More</li>
                  <?php }?>
                  <li>
                      <a href="[[|categories.child.url|]]">
                        <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" />
                        <?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>
                      </a>
                  </li>
                  <?php 
                    if(isset([[=categories.child.counnt=]]) && $group==[[=categories.child.counnt=]])
                    { 
                        echo '</ul></div></div>';
                    }
                  ?>
                  <?php 
                      }
                      $k++;
                      if(($k+1)==sizeof([[=categories.child=]]))
                      {
                        if($act==3)
                        {
                            echo '</ul></div>';
                        }
                        echo '</li>';
                      }
                  ?>  
              <!--/LIST:categories.child-->
              </ul>
    	 <!--/IF:cond_child_01-->
         </li>
    <!--/LIST:categories-->
    </ul> 
    </div>
    <?php
    //Luu Nguyen Giap edit change language in page edit or add
    $param_build_change_lang= array('href','language_id'=>((Portal::language()==1)?2:1),'ldd'=>Portal::$page['name']);
   
    //lay ra duong link va cac tham so tren duong link do
    $path = $_SERVER['REQUEST_URI'];
   // echo $path;
    $pos = strpos($path,'cmd');

    if($pos!=false)
    {
       
       $str = substr($path,$pos);
       //tach chuoi theo & lay ra cac tham so va cac gia tri
       $str_cmd = explode("&",$str);
       for($i=0;$i<count($str_cmd);$i++)
       {
           //tach theo dau =
           $s =explode("=",$str_cmd[$i]);
           $param_build_change_lang = $param_build_change_lang + array($s[0]=>$s[1]);
       }
       
    }
    //End Luu Nguyen Giap
    ?>    
	<div id="chang_language" style="color:#FFF;">
  
        <a onclick="var user ='<?php echo User::id(); ?>';printWebPart('printer',user);" title="[[.print.]]"><i class="fa fa-print w3-text-black" style="font-size: 40px;"></i></a> | 
        <a  onclick="ChangeLanguageMenu('<?php echo (Portal::language()==1)?2:1; ?>');" title="[[.change_language.]]" class="w3-text-black" style="font-size:15px;"><!--IF:l_cond(Portal::language()==1)-->EN<!--ELSE-->VN<!--/IF:l_cond--></a>
    </div>    
	<div id="sign-in" style="width: 100%; margin: 0px; padding: 0px; left: 0px; bottom: 0px; box-shadow: 0px 0px 5px #171717; <?php if(User::id()=='developer05'){ ?>background-color: rgba(236, 236, 236, 0.7);<?php } ?>">  
        <a href="<?php echo Url::build('home',array());?>" title="[[.home_page.]]" style="float: left; margin:0px 15px 0px 5px;"><i class="fa fa-home w3-text-orange" style="font-size: 30px;"></i></a>
	  	<!---<img src="packages/core/skins/default/css/jquery/images/banner_small.png" width="80px" height="30px" />--->
		<!--IF:login(User::is_login())--><span>[[.Welcome.]]: </span><a class="link-personal" href="<?php echo Url::build('personal')?>">[[|user_name|]]</a><span> | </span><a class="log-out" style="font-size:20px;" href="<?php echo Url::build('sign_out',array()); ?>">[[.sign_out.]]</a><!--ELSE--><a href="<?php echo Url::build('sign_in',array()); ?>">[[.sign_in.]]</a><!--/IF:login-->
  <div>
       <a href="#" id="show-popup">        
       <img src="resources/default/note_1.jpg"/>
       </a>
       <ul class="show_cout">
       <li style="color: red;">[[|count_note|]] |</li>
       <li style="color: blue;">[[|count2|]] |</li>
       <li style="color: black;">[[|count_all_note|]] </li>
       </ul>      
      <div class="khung khung_hide" id="khung">
<div class="header_list_work">
	công việc ngày  <?php echo date("d/m/Y"); ?>
	<a id="close_list" href="#"> <img src="resources/default/icon_close_1.png"/></a>
   
</div>
    
	<ul class="testTable">
     <!--LIST:list_work-->
        <li class="testRow" style='<?php if([[=list_work.status=]]==1){echo 'background:#B0C4DE';} if([[=list_work.mkt_start=]]>[[=time_now=]]){ echo 'background:#20B2AA';} if([[=list_work.mkt_start=]]<[[=time_now=]] && [[=list_work.status=]]==0){ echo 'background:#F4A460';} ?>' >
          <div class="bao"> 
          <div class="gio">[[|list_work.start_time|]]--[[|list_work.end_time|]]</div>
     	 	<div class="nd_title">[[|list_work.title|]]</div>
     	 	</div>
      
        </li>
        <!--/LIST:list_work--> 
        
 </ul>       
 </div>
 </div>
       <div style="float:left;margin-top:10px;margin-left:10px;">| <a style="font-size:16px; color:#03F; margin-top:15px; margin-left:10px;" onclick="switchHideMenu();">[[.show.]]/ [[.hide.]] <b>[[.Menu.]]</b></a></div>
	    <div style="float:right;margin-top:6px;margin-right:3px;"><strong style="color:#0000FF"> | <a href="javascript:void(0)" id="support">[[.support.]]</a></div>
	    <div style="float:right;margin-top:6px;margin-right:3px; display:none;"><strong style="color:#0000FF"> | <a target="_blank" href="?page=help">[[.help.]]</a></div>
	    <div id="portal" style="float:right;margin-top:6px;margin-right:3px;"><strong style="color:#0000FF">[[.Hotel_name.]]:</strong> <a style="color:#FF0000;font-weight:bold;font-size:14px;" href="<?php echo Url::build('select_portal')?>">[[|current_portal|]]</a> | <strong>IP : <?php echo $_SERVER['REMOTE_ADDR'];?></strong></div>
    </div>
  <style>
    #navigation{
        width: 220px;
        height: 180px;
        position: relative;
        overflow: hidden;
    }
    #nav{
        width: 220px;
       /*KIÊU-EDIT*/
       margin-top: 40px;
        height: 180px;
        position: absolute;
        top: 0px; left: 0px;
        transition: all .5s ease-out;
    }
    .contact_content{
        width: 220px;
        height: 90px;
        position: absolute;
        top: 220px; left: 0px;
        transition: all .5s ease-out;
    }
    table#table_nav tr td{
        padding: 5px 0px;
    }
    table#table_nav tr td.td_nav{
        transition: all .5s ease-out;
    }
    table#table_nav tr td.td_nav img{
        opacity: .7;
        transition: all .5s ease-out;
    }
    table#table_nav tr td.td_nav:hover{
        background-color: #00FA9A;
    }
    table#table_nav tr td.td_nav:hover img{
        opacity: 1;
    }
    
  </style>
  <script>
             <?php
    //if(User::id()=='developer09')
//    { //$phparray=array(1=>array('job'=>'tesst day 1','time'=>'10000'));
        ?>
        var jArray; 
        var a='';       
      jArray= <?php if(isset($_REQUEST) && isset($_REQUEST['show'])) echo json_encode($_REQUEST['show']);echo 'new Array()'; ?>; 
      if(jArray.length >0){
        for(var i in jArray){
        if(jArray[i]['time']>0){
            setTimeout("alert(jArray[i]['job']+'sẽ bắt đầu vào:'+jArray[i]['time_start'])",jArray[i]['time']);
        }
          
        }
      }
        <?php                      
    //} 
  ?>
  
    function back_nav(){
        jQuery("#nav").css('left','0px');
        jQuery("#skype_sale").css('top','220px');
        jQuery("#skype_develop").css('top','220px');
        jQuery("#yahoo_sale").css('top','220px');
        jQuery("#yahoo_develop").css('top','220px');
        jQuery("#email_sale").css('top','220px');
        jQuery("#email_develop").css('top','220px');
        jQuery("#phone_sale").css('top','220px');
        jQuery("#phone_develop").css('top','220px');
    }
    function show_skype(p){
        if(p==1){
            jQuery("#nav").css('left','220px');
            jQuery("#skype_sale").css('top','0px');
            console.log('1');
        }else{
            jQuery("#nav").css('left','220px');
            jQuery("#skype_develop").css('top','0px');
            console.log('0');
        }
    }
    function show_yahoo(p){
        if(p==1){
            jQuery("#nav").css('left','220px');
            jQuery("#yahoo_sale").css('top','0px');
            console.log('1');
        }else{
            jQuery("#nav").css('left','220px');
            jQuery("#yahoo_develop").css('top','0px');
            console.log('0');
        }
    }
    function show_email(p){
        if(p==1){
            jQuery("#nav").css('left','220px');
            jQuery("#email_sale").css('top','0px');
            console.log('1');
        }else{
            jQuery("#nav").css('left','220px');
            jQuery("#email_develop").css('top','0px');
            console.log('0');
        }
    }
    function show_phone(p){
        if(p==1){
            jQuery("#nav").css('left','220px');
            jQuery("#phone_sale").css('top','0px');
            console.log('1');
        }else{
            jQuery("#nav").css('left','220px');
            jQuery("#phone_develop").css('top','0px');
            console.log('0');
        }
    }
  </script>
 <?php
    if([[=check_use_chat=]]==1 && RATE_CODE==1)
    {
 ?> 
 <div id="support-bound">
    <div id="navigation" style="height: 600px; width: 100%; overflow: auto;">
        <div style="height: 25px;width: 100%;border-bottom:  black thin solid;line-height: 25px; padding-left: 20px; background: maroon; border-radius : 10px 10px 0px 0px; position: fixed;">
            <label style="display: block; float: left; margin-right: 20px; color:white;">Tất cả <input type="radio" name="status_chat" checked="" value="1" style="cursor: pointer;" onchange="getUserStatus(this.value);" /></label>
            <label style="display: block; float: left; margin-right: 20px; color:white;">On <input type="radio" name="status_chat" value="2" style="cursor: pointer;" onchange="getUserStatus(this.value);" /></label>
            <label style="display: block; float: left; color:white;">Off <input type="radio" name="status_chat" value="3" style="cursor: pointer;" onchange="getUserStatus(this.value);" /></label>
        </div>
        <div style="margin-top: 25px;">
        <!--LIST:user_list-->
            <div class='user_tag'>
                <span status="off" style='float:left; display:block;text-align:center; height:6px; width: 6px; margin: 12px; border-radius: 3px; background: red;'></span><span class='child_name' nickname='[[|user_list.full_name|]]' to_user='[[|user_list.user_id|]]' onclick='getContent(this);' style="font-size: 10px;">[[|user_list.full_name|]]</span>
            </div>
        <!--/LIST:user_list-->        
        <!-- KIEU-EDIT
        <div class="contact_content" id="skype_sale">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <div id="SkypeButton_Call_ngoc.do000_1">
                  <script type="text/javascript">
                    Skype.ui({
                      "name": "chat",
                      "element": "SkypeButton_Call_ngoc.do000_1",
                      "participants": ["ngoc.do000"],
                      "imageSize": 24
                    });
                  </script>
                </div>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 0px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        <div class="contact_content" id="skype_develop">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <div id="SkypeButton_Call_ngocdatbk_1">
                  <script type="text/javascript">
                    Skype.ui({
                      "name": "chat",
                      "element": "SkypeButton_Call_ngocdatbk_1",
                      "participants": ["ngocdatbk"],
                      "imageSize": 24
                    });
                  </script>
                </div>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 0px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        <div class="contact_content" id="yahoo_sale">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="ymsgr:sendim?toiyeucobematnau_73209">
                <img src="http://opi.yahoo.com/online?u=toiyeucobematnau_73209&m=g&t=14" alt="HELLO" />
                </a>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        <div class="contact_content" id="yahoo_develop">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="ymsgr:sendim?toiyeucobematnau_73209">
                <img src="http://opi.yahoo.com/online?u=toiyeucobematnau_73209&m=g&t=14" alt="HELLO" />
                </a>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        --> 
        <!--
        <div class="contact_content" id="email_sale">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="mailto:sales@tcv.vn" style="color: #ffffff; line-height: 30px; text-decoration: none; font-size: 18px; font-weight: bold;">sales@tcv.vn</a>
              </div>
               KIEU-EDIT
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="mailto:sales@tcv.vn" style="color: #ffffff; line-height: 30px; text-decoration: none; font-size: 18px; font-weight: bold;">info@tcv.vn</a>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        -->
        <!-- KIEU-EDIT
        <div class="contact_content" id="email_develop">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="mailto:ngocdat@tcv.vn" style="color: #ffffff; line-height: 30px; text-decoration: none; font-size: 18px; font-weight: bold;">ngocdat@tcv.vn</a>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
         
        <div class="contact_content" id="phone_sale">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
              <p>Call to Support:</p>
                <h1 class="w3-text-white">1900636116</h1>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p class="w3-blue w3-text-white" style="padding: 5px; margin: 5px auto; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        
        <div class="contact_content" id="phone_develop">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <h1>01666.040.696</h1>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        -->
        <!--<div id='chatting'>
            <div id='room'></div>
            <div id='room-list' style="display: none;"></div>
            <div id='messages'></div>
            <form id='send-form' action="/" method="POST">
                <div class="form-group">
                  <label for="comment">Comment:</label>
                  <textarea class="form-control" style="resize: none;" rows="5" id='send-message' name="sendmessage"></textarea>
                </div>
                <input id='send-button' type='submit' value='Send'/>
                <input type="hidden" value="<?php echo User::id(); ?>" name="username" id="username" />
                <input type="hidden" value="[[|user_name|]]" name="nickname" id="nickname" />
                <div id='help' style="display: none;">
                    Chat commands:
                    <ul>
                        <li>Change nickname: <code>/nick [username]</code></li>
                        <li>Join/create room: <code>/join [room name]</code></li>
                    </ul>
                </div>
            </form>
        </div>-->
        <input type="hidden" value="<?php echo User::id(); ?>" name="username" id="username" />
        <input type="hidden" value="[[|user_name|]]" name="nickname" id="nickname" /> 
        </div>     
    </div>
 </div>   
<div id="chat_content">
 
</div>
<?php
    }
?>       
 <!--manh_add_module-->
 <div id="description_page_list">
    
    <br clear="all" />
    <div class="button_description_page" onclick="fun_open_description_page([[|module_id|]]);" style="background: url('resources/icon_module/icon_add_module.png') top left no-repeat;">
        <p>VIEW MODULE</p>
    </div>
    <div style="width: 10px; height: 100%; position: absolute; top: 0px; left: -12px;">
    
    </div>
    <br clear="all" />
 </div>
 <!--end_manh-->
<script type="text/javascript">	
	jQuery(document).ready(function(){
	   
         jQuery('#show-popup').click(function(){
            jQuery(this).parent().find('#khung').removeClass('khung_hide');
        });  
         jQuery('#close_list').click(function(){
            jQuery(this).parents('#khung').addClass('khung_hide');
        }); 
                               
                
		jQuery('#testRibbon').css('display','');
		jQuery('#support').click(function(){
			right_width = (jQuery(document).width()-1080)/2;
			jQuery('#support-bound').css('right','5px');
            
            
			jQuery('#support-bound').slideToggle(500,function(){
                if(jQuery('#support-bound').is(":hidden"))
                {
                    jQuery(".chat_child").each(function(){
                        jQuery(this).remove();
                    });
                    open_room = 0; 
                }
                else
                {
                    open_room = 1; 
                }
                
                <?php
                //if(User::id()=='developer11' || User::id()=='developer06' || User::id()=='developer17')
                {
                ?>
                    socket.emit('open_room',{user_id:jQuery("#username").val(),open_room:open_room});  
                <?php
                }
                ?>
			});
            
		})
jQuery('.buttonsplitmenu').css('z-index',20000);
	});
	function switchHideMenu(){
		if(jQuery.cookie('hide_menu')==1){
			jQuery('#testRibbon').css('display','');
			jQuery.cookie('hide_menu',0);
		}else{
			jQuery('#testRibbon').css('display','none');
			jQuery.cookie('hide_menu',1);
		}
		jQuery('.jcarousel-clip-horizontal').width(jQuery("#order_full_screen").width()-80);		
	}
    
    function ChangeLanguageMenu($language_id){
        jQuery.ajax({
    					url:"form.php?block_id=<?php echo Module::block_id();?>",
    					type:"POST",
    					data:{change_language:$language_id},
    					success:function(html){
    					   location.reload();
    					}
    				});
    }
	if(jQuery.cookie('hide_menu')==1){
		jQuery('#testRibbon').css('display','none');  
	}
    
    
    function fun_open_description_page(module_id)
    {
        //console.log(module_id);
        var url="?page=docs&cmd=view_module&module_id=" + module_id;
        window.open(url);
    }
</script>
<?php
    if([[=check_use_chat=]]==1 && USING_CHAT && USING_CHAT == 1)
    {
?> 
<script>
    jQuery(document).ready(function(){
        var user_id = jQuery("#username").val();            
    
        if(typeof io !== 'undefined')
        {
            socket = io.connect('http://118.70.151.38:3000');
            
            socket.emit('open_session',{user_id:user_id});  
            
            socket.on('open_old_room', function (result) {    // Mo cac khung chat da mo tren trinh duyet  
                if(result.open_room==1)
                {
                    jQuery('#support-bound').css("display","block");
                }
                if(typeof result.open_chat !== 'undefined')
                {
                    setTimeout(function(){
                        //console.log(result.open_chat);
                        for(var item_user in result.open_chat)
                        {
            
                            if(result.open_chat[item_user]==1)
                            {
                                var obj = jQuery("span[to_user="+item_user+"]")[0];
                                
                                showChatContent(obj);  
                            }
                        }
                    }, 700);            
                }
            });
            
            socket.on('open_room', function (result) {      // Mo phan chat tren trinh duyet
                jQuery('#support-bound').slideToggle(500,function(){
                    if(jQuery('#support-bound').is(":hidden"))
                    {
                        jQuery(".chat_child").each(function(){
                            jQuery(this).remove();
                        });
                    }
                    else
                    {
                    }
        		});
            }); 
                
            
            
            
            socket.on('open_chat', function (result) { 
                var obj = jQuery("span[to_user="+result.to_user+"]")[0];
                showChatContent(obj);        
            });    
            
            socket.on('send_mes', function (result) { 
                //console.log(result);
                if(result['action']=='current')
                {
                    var obj = jQuery("button[send_to_user="+result.to_user+"]")[0];
                    show_message(obj,result.content,result.time_send,result['action']);
                }
                else if(result['action']=='from_user')
                {
                    //console.log(result);
                    if(jQuery('#support-bound').is(":hidden"))
                    {
                        jQuery('#support-bound').css("display","block");
                        //open_room = 0; 
                    }
                    
                    var check = false;
                    if(jQuery('div[id=support-bound][class*='+result['from_user']+']').length==0)
                    {
                        var obj_temp = jQuery("span[to_user="+result['from_user']+"]")[0];                      
                        showChatContent(obj_temp);
                        check = true;
                    }
                    
                    if(!check)
                    {
                        var obj = jQuery("button[send_to_user="+result['from_user']+"]")[0];
                        show_message(obj,result.content,result.time_send,result['action']);
                    }
                }    
                //console.log(result);     
            });    
            
            
            socket.on('close_chat', function (data) {
                var obj = jQuery('div#support-bound[class*='+data.to_user+'] .head_chat span');
                closeChat(obj);    
           });
           
           socket.on('online', function (data) {
                var obj = jQuery("span[to_user="+data.user_on+"]")[0];
                jQuery(obj).prev().css("background","blue"); 
                jQuery(obj).prev().attr("status","on");
                
                //var stt_chat = data.stt_chat;
                //jQuery("input[name=status_chat]").each(function(){
//                    jQuery(this).prop('checked', false);
//                    if(jQuery(this).attr("value")==stt_chat)
//                    {
//                        jQuery(this).prop('checked', true);
//                    }
//                });
                //console.log(data);
                //getUserStatus(stt_chat);
           });
           
           socket.on('offline', function (data) {
                var obj = jQuery("span[to_user="+data.user_off+"]")[0];
                jQuery(obj).prev().css("background","red"); 
                jQuery(obj).prev().attr("status","off");
                //var stt_chat = data.stt_chat;
//                jQuery("input[name=status_chat]").each(function(){
//                    jQuery(this).prop('checked', false);
//                    if(jQuery(this).attr("value")==stt_chat)
//                    {
//                        jQuery(this).prop('checked', true);
//                    }
//                });
                //getUserStatus(stt_chat);
           });
           
           socket.on('get_online', function (data) {
                var soc_room = data.socket_rooms;
                for(var items in data.socket_rooms)
                {
                    if(data.socket_rooms[items]['count_session']<=0)
                    {
                        var obj = jQuery("span[to_user="+items+"]")[0];
                        jQuery(obj).prev().css("background","red"); 
                        jQuery(obj).prev().attr("status","off");
                    }
                    else
                    {
                       var obj = jQuery("span[to_user="+items+"]")[0];
                        jQuery(obj).prev().css("background","blue"); 
                        jQuery(obj).prev().attr("status","on");
                    }
                }
           });
           
           socket.on('getUserStt_temp', function (data) {
                var status_chat = data.stt_chat;
                
                showStatus(status_chat,data.user_id);       
           });
            
            var temp = {};
            
            var nickname = jQuery("#nickname").val();
        }
        
    });
            
    function timeConverter(UNIX_timestamp){
      var a = new Date(UNIX_timestamp * 1000);
      var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      var year = a.getFullYear();
      var month = months[a.getMonth()];
      var date = a.getDate();
      var hour = a.getHours();
      var min = a.getMinutes();
      var sec = a.getSeconds();
      var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
      return time;
    }
    
    function getContent(obj)
    {
        var to_user = jQuery(obj).attr("to_user");
        var user_id = jQuery("#username").val();         
                           
        socket.emit('open_chat',{user_id:user_id,to_user:to_user}); 
          
        showChatContent(obj);    
        
        jQuery('div#support-bound[class*='+to_user+'] .text_chat').focus();
    }
    
    function showChatContent(obj)
    {
        var user_id = jQuery("#username").val();         
        var to_user = jQuery(obj).attr("to_user");
        var nickname = jQuery(obj).attr("nickname");
        var right_pos = 0;
        right_pos = jQuery(".chat_child").length * 305 + 255;
        if(jQuery("."+to_user).length==0){
            str = '<div id="support-bound" class="chat_child '+to_user+'" style="right: '+right_pos+'px; display:block; width:300px;">'
                  +  '<div id="chat">'
                  +     '<div class="head_chat">'+nickname+'<span onclick="closeChat(this);" close_user="'+to_user+'" style="color:white; float:right;height:25px; line-height:25px; text-align:center; width:25px; cursor:pointer;">X</span></div>'
                  +     '<div id="messages" numberScroll="1" onscroll="getScrollFun(this);" style="height:210px; overflow:auto; padding-bottom:15px; padding-top:15px;"></div>'
                  +     '<div class="send_msg"><textarea class="text_chat"></textarea><button type="button" class="button_send" send_to_user="'+to_user+'" onclick="sendMes(this);">Gửi</button></div>'
                  +  '</div>'
                  +'</div>';
                  
           jQuery("#chat_content").append(str);       
       } 
        //jQuery("#chat_content .chat_child").each(function(){
            //jQuery(this).css("display","block");
        //});
        
        setTimeout(function(){
            jQuery.when(
                jQuery.ajax({
                  url: 'http://118.70.151.38:3000/getMsg',
                  data : {'user_id':user_id,'nickname':nickname,to_user:to_user,numberScroll:0},
                  dataType: 'jsonp'
                })
            ).then(function(result){
                    temp = result;
                    var str = "";
                    var username = jQuery("#username").val();
                    for(var i in temp)
                    {            
                        if(username==temp[i]['user_id'])
                        {
                            str += "<div style='width:100%;'><span title='"+timeConverter(temp[i]['time'])+"' style='text-align: left;max-width: 60%;float:right;min-height:20px;font-size:11px; padding:5px; border-radius:3px; display:block; background:antiquewhite; margin-right:10px; margin-bottom:5px; word-break:break-word;white-space: pre-line; font-weight:normal;'>"+temp[i]['content']+"</span></div><div style='clear:both;'></div>";
                        }
                        else
                        {
                            str += "<div style='width:100%;'><span title='"+timeConverter(temp[i]['time'])+"' style='text-align:  left;max-width: 60%;float:left; padding-left:10px; min-height:20px;font-size:11px; padding:5px; border-radius:3px; display:block;  background:darkseagreen; margin-left:10px;  margin-bottom:5px; word-break:break-word;white-space: pre-line; font-weight:normal;'>"+temp[i]['content']+"</span></div><div style='clear:both;'></div>";
                        }
                        
                    }
                    jQuery('div#support-bound[class*='+to_user+'] #messages').append(str);
                    
                     jQuery('div#support-bound[class*='+to_user+'] #messages').scrollTop(jQuery('div#support-bound[class*='+to_user+'] #messages')[0].scrollHeight);
            },function(xhr,status,error){
            });
            
        }, 500);
        
        enterChat();    
    }            
    
    function sendMes(obj)
    {
       var content = jQuery(obj).prev().val();  
       if(content.trim()!="")
       {
           jQuery(obj).prev().val("");
           var d = new Date();
           var h = addZero(d.getHours());
           var m = addZero(d.getMinutes());
           time_send = h+":"+m; 
           
           var user_id = jQuery("#username").val();        
           var to_user = jQuery(obj).attr("send_to_user"); 
           
           socket.emit('send_mes',{user_id:user_id,to_user:to_user,content:content,time_send:time_send}); 
           show_message(obj,content, time_send,"current"); 
           
           jQuery.post("http://118.70.151.38:3000/", {user_id:user_id,to_user:to_user,content:content}, function(data) {
           });
       }
    }
    
    function show_message(obj,content,time_send,action)
    {                    
        var username = jQuery("#username").val();
         
        var str = "";
        
        if(action=='current')
        {
            str += "<div style='width:100%;'><span title='"+time_send+"' style='text-align: left;max-width: 60%;float:right;min-height:20px;font-size:11px; padding:5px; border-radius:3px; display:block; background:antiquewhite; margin-right:10px; margin-bottom:5px; word-break:break-word;white-space: pre-line; font-weight:normal;'>"+content+"</span></div><div style='clear:both;'></div>";
        }
        else
        {
            str += "<div style='width:100%;'><span title='"+time_send+"' style='text-align:  left;max-width: 60%;float:left; padding-left:10px; min-height:20px;font-size:11px; padding:5px; border-radius:3px; display:block;  background:darkseagreen; margin-left:10px;  margin-bottom:5px; word-break:break-word;white-space: pre-line; font-weight:normal;'>"+content+"</span></div><div style='clear:both;'></div>";
        }

        jQuery(obj).parent().prev().append(str);
        
        jQuery(obj).parent().prev().scrollTop(jQuery(obj).parent().prev()[0].scrollHeight);
    }
    
    function closeChat(obj)
    {
        var user_id = jQuery("#username").val();         
        var to_user = jQuery(obj).attr("close_user");
        socket.emit('close_chat',{user_id:user_id,to_user:to_user}); 
        jQuery(obj).parent().parent().parent().nextAll().each(function(){
            var current_right = jQuery(this).css("right");
            current_right = to_numeric(current_right.replace("px", ""));
            jQuery(this).css("right",(current_right-jQuery(this).outerWidth()-5));
        });
        jQuery(obj).parent().parent().parent().remove();
        
    }
    
    function addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    
    function convertToHour(UNIX_timestamp)
    {
      var a = new Date(UNIX_timestamp * 1000);
      var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      var year = a.getFullYear();
      var month = months[a.getMonth()];
      var date = a.getDate();
      var hour = a.getHours();
      var min = a.getMinutes();
      var sec = a.getSeconds();
      var time =  hour + ':' + min;
      return time;
    }
    
    function timeConverter(UNIX_timestamp){
      var a = new Date(UNIX_timestamp * 1000);
      var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      var year = a.getFullYear();
      var month = months[a.getMonth()];
      var date = a.getDate();
      var hour = a.getHours();
      var min = a.getMinutes();
      var sec = a.getSeconds();
      var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
      return time;
    }
    
    function enterChat()
    {
        jQuery("textarea.text_chat").each(function(){
            jQuery(this).keyup(function(event){
               //e.preventDefault();
               if (event.keyCode == 13) {
                    var content = this.value;  
                    var caret = getCaret(this);          
                    if(event.shiftKey){
                        this.value = content.substring(0, caret - 1) + "\n" + content.substring(caret, content.length);
                        event.stopPropagation();
                    } else {
                        if(jQuery(this).val().trim()!="")
                        {
                            this.value = content.substring(0, caret - 1) + content.substring(caret, content.length);
                            sendMes(jQuery(this).next());
                        }
                        else
                        {
                            jQuery(this).val("");
                        }
                    }
                }
            });
        });
       
    }
    
    function getCaret(el) { 
        if (el.selectionStart) { 
            return el.selectionStart; 
        } else if (document.selection) { 
            el.focus();
            var r = document.selection.createRange(); 
            if (r == null) { 
                return 0;
            }
            var re = el.createTextRange(), rc = re.duplicate();
            re.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', re);
            return rc.text.length;
        }  
        return 0; 
    }
    
    function getScrollFun(obj)
    {
        var user_id = jQuery("#username").val();         
        var to_user = jQuery(obj).prev().find("span").attr("close_user");
        var nickname = jQuery(obj).attr("nickname");
        var position = jQuery(obj).scrollTop();
        var numberScroll = jQuery(obj).attr("numberScroll");
        if(position==0)
        {
                setTimeout(function(){
                jQuery.when(
                    jQuery.ajax({
                      url: 'http://118.70.151.38:3000/getMsg',
                      data : {'user_id':user_id,to_user:to_user,numberScroll:to_numeric(numberScroll)*100},
                      dataType: 'jsonp'
                    })
                ).then(function(result){
                        temp = result;                       
                        var str = "";
                        var username = jQuery("#username").val();
                        var count_mes = 0;
                        for(var i in temp)
                        {            
                            if(username==temp[i]['user_id'])
                            {
                                str += "<div style='width:100%;'><span title='"+timeConverter(temp[i]['time'])+"' style='text-align: left;max-width: 60%;float:right;min-height:20px;font-size:11px; padding:5px; border-radius:3px; display:block; background:antiquewhite; margin-right:10px; margin-bottom:5px; word-break:break-all; font-weight:normal;'>"+temp[i]['content']+"</span></div><div style='clear:both;'></div>";
                            }
                            else
                            {
                                str += "<div style='width:100%;'><span title='"+timeConverter(temp[i]['time'])+"' style='text-align:  left;max-width: 60%;float:left; padding-left:10px; min-height:20px;font-size:11px; padding:5px; border-radius:3px; display:block;  background:darkseagreen; margin-left:10px;  margin-bottom:5px; word-break:break-all; font-weight:normal;'>"+temp[i]['content']+"</span></div><div style='clear:both;'></div>";
                            }
                            count_mes++;
                        }
                        
                         if(count_mes!=0)
                         {                           
                            var height_chat = jQuery("#messages div:first-child span").outerHeight();
                            jQuery(str).insertBefore(jQuery("#messages div:first-child"));
                            //console.log(str);
                             jQuery('div#support-bound[class*='+to_user+'] #messages').scrollTop(height_chat*count_mes);
                             numberScroll++;
                             jQuery(obj).attr("numberScroll",numberScroll);                              
                         }                        
                },function(xhr,status,error){
                });
                
            }, 500);
            
        }
    }
    
    function getUserStatus(status)
    {
        var user_id = jQuery("#username").val();         

        showStatus(status,user_id);
            
        socket.emit('getUserStt',{user_id:user_id,status_chat:status}); 
    }
    
    function showStatus(status,user_id)
    {
        
        jQuery("input[type=radio][name=status_chat]").each(function(){
            jQuery(this).prop('checked', false);
        });
        jQuery("input[type=radio][name=status_chat][value="+status+"]").prop('checked', true);

           
                                
        if(status==1)
        {
            jQuery("div.user_tag").each(function(){
                jQuery(this).css("display","block");
            });
        }
        else if(status==2)
        {
            jQuery("div.user_tag").each(function(){
                var stt_chat_child = jQuery(this).find("span:first-child").attr("status");

                if(stt_chat_child=="off")
                {
                    jQuery(this).css("display","none");
                }
                else
                {
                    jQuery(this).css("display","block");
                }
            });
        }
        else if(status==3)
        {
            jQuery("div.user_tag").each(function(){
                var stt_chat_child = jQuery(this).find("span:first-child").attr("status");
                if(stt_chat_child=="on")
                {
                    jQuery(this).css("display","none");
                }
                else
                {
                    jQuery(this).css("display","block");
                }
            });
        }
    }
    
</script>
<?php  
    }
?>