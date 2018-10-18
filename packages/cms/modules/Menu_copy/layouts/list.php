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
	width:220px;
	display:none;
	border:1px solid #00b9f2;
	background-color: #008080;
	padding:10px;
    z-index: 99;
    border-radius: 10px;
    box-shadow: 0px 0px 5px #171717;
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

       // jQuery("#testRibbon").officebarBind("textboxes", function(e) {  } );        
       // jQuery("#testRibbon").officebarBind("home>blablabutton", function(e) {  } );
        //jQuery("#testRibbon").officebarBind("insert>new", function(e) {  } );
      }
    ).click(function(event){
			target = event.target;
			if(jQuery(target).parent().attr('class')!='opened')
			{
				jQuery('.buttonsplitmenu').each(function(i){
					//alert(jQuery(this).css());
					if(jQuery(this).css('display')=='' || jQuery(this).css('display')=='block')
					{
						jQuery(this).css('display','none');
					}
				})
			}
	});
  </script>
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
                        <span>[[|categories.child.group_name|]]</span>
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
                                   <div class="dropdown" id="button_dropdown">
                                       <a href="#" rel="paste">                                	
                                           <!--IF:img_cond_03([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                                <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" />
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
                                </div></div>
                             <!--ELSE-->
                             <?php 
                             if($act!=3)
                             { 
                                echo '<div class="button textlist" lang="'.$act.'">';echo '<ul>';
                             }

                             $act=3;
                             ?>
                             <li>
                                 <a href="[[|categories.child.url|]]" rel="paste">
                                 <!--IF:img_cond02([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                    <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" />
                                 <!--/IF:img_cond02-->
                                 <?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>
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
                        <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" style="display: none;"/>
                        <img src="[[|categories.child.icon_url|]]"/>
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
  
        <a onclick="var user ='<?php echo User::id(); ?>';printWebPart('printer',user);" title="[[.print.]]"><img src="packages/core/skins/default/images/printer.png" height="40"/></a> | 
        <a href="<?php echo Url::build('change_language',$param_build_change_lang);?>" title="[[.change_language.]]" style="font-size:15px;color:#FFF;"><!--IF:l_cond(Portal::language()==1)-->EN<!--ELSE-->VN<!--/IF:l_cond--></a>
    </div>
         
    
	<div id="sign-in" style="width: 100%; margin: 0px; padding: 0px; left: 0px; bottom: 0px; box-shadow: 0px 0px 5px #171717; <?php if(User::id()=='developer05'){ ?>background-color: rgba(236, 236, 236, 0.7);<?php } ?>">  
        <a href="<?php echo Url::build('home',array());?>" title="[[.home_page.]]"><img src="skins\default\images\frontpage.png" height="40"/></a>
	  	<img src="packages/core/skins/default/css/jquery/images/banner_small.png" width="80px" height="30px" />
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
 <div id="support-bound">
    <div id="navigation">
        <div id="nav">
            <table id="table_nav" style="width: 100%;">
                <tr>
                    <th style="text-align: center;">EMAIL</th>
                    <th style="text-align: center;">PHONE</th>
                </tr>
                <tr>
                    <td class="td_nav" style="text-align: center;"><img src="packages\cms\skins\default\images\icon_email.png" style="width: 40px; height: 40px; cursor: pointer;" onclick="show_email(1);"/></td>
                    <td class="td_nav" style="text-align: center;"><img src="packages\cms\skins\default\images\icon_phone.png" style="width: 40px; height: 40px; cursor: pointer;" onclick="show_phone(1);" /></td>
                </tr>
            </table>
        </div>
        <div class="contact_content" id="email_sale">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="mailto:sales@tcv.vn" style="color: #ffffff; line-height: 30px; text-decoration: none; font-size: 18px; font-weight: bold;">sales@tcv.vn</a>
              </div>
              <!-- KIEU-EDIT-->
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <a href="mailto:sales@tcv.vn" style="color: #ffffff; line-height: 30px; text-decoration: none; font-size: 18px; font-weight: bold;">info@tcv.vn</a>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
        <div class="contact_content" id="phone_sale">
              <div id="content_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <h1>1900636116</h1>
              </div>               
              <div class="back_nav" style="width: 100%; margin: 0px auto; text-align: center;">
                <p style="padding: 5px; margin: 5px auto; background: #00b9f2; color: #ffffff; line-height: 25px; cursor: pointer;" onclick="back_nav();">BACK</p>
              </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        </div>
    </div>
          -->
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
			jQuery('#support-bound').slideToggle(500);
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
		//jQuery('#info_summary').width(jQuery("#order_full_screen").width()-10);		
	}
	if(jQuery.cookie('hide_menu')==1){
		jQuery('#testRibbon').css('display','none');  
	}
    
    
    function fun_open_description_page(module_id)
    {
        console.log(module_id);
        var url="?page=docs&cmd=view_module&module_id=" + module_id;
        window.open(url);
    }
</script>