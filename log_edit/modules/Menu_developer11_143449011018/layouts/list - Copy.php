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
        onBeforeShowSplitMenu: function(e) {  },
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
    );
  </script>
  <div id="testRibbon" class="officebar" style="display:none;">
  	<input  name="home_page" type="hidden" id="home_page" />
    <ul>
    <?php $group_name ='';$i=1;$act = 0;$group_old='';?>
    <!--LIST:categories-->
      <li <?php if($_SESSION['home_page'] == '' && $i==1){echo 'class="current"';}else if($_SESSION['home_page'] !='' && $this->map['categories']['current']['id']==$_SESSION['home_page']){echo 'class="current"';} $i++; $k=0;?> rel="[[|categories.id|]]">
        <a href="#" rel="home" id="[[|categories.id|]]"><span id="menu"><?php echo Portal::language()==1?[[=categories.name_1=]]:[[=categories.name_2=]]; ?></span></a>
         <!--IF:cond_child_01([[=categories.child=]])-->
         	<ul>
         	  <!--LIST:categories.child-->
                	<?php if($group_name != [[=categories.child.group_name=]]){$t=0;$group_old = $group_name;$group_name=[[=categories.child.group_name=]];$group=0;
					if($act==3 && $k!=0){echo '</ul></div>';
					}
					if($k!=0){echo '</li>';}$act=0;
					?>
                    <li>
                	<!--IF:cond_group([[=categories.child.group_name=]])--><span>[[|categories.child.group_name|]]</span><!--/IF:cond_group-->
                    <?php } $group++;
					if($group<3){?>	               
                         <!--IF:cond_child_02([[=categories.child.child=]])-->
                          <?php if($act==3){
                                    echo '</ul></div>';
                                 }$act=1;?>  
                           <div class="button textlist"><ul>      
                            <div class="dropdown" id="button_dropdown">
                                <a href="#" rel="paste">
                                	<!--IF:img_cond_03([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                    <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>"  /><!--/IF:img_cond_03--><span><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></span>
                                </a>
                                <div><ul>
                                <?php $y=1;?>
                                <!--LIST:categories.child.child-->
                                <?php if($y==1){
                                        echo '<li class="menutitle">'.$group_name.'</li>';}$y++;
                                        ?>
                                    <li> <a href="[[|categories.child.child.url|]]">
                                    <!--IF:img_cond04([[=categories.child.child.icon_url=]] && [[=categories.child.child.icon_url=]]!=' ')-->
                                     <img src="[[|categories.child.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.child.name_1=]]:[[=categories.child.child.name_2=]]; ?>" /><!--/IF:img_cond04-->
                                       <?php echo Portal::language()==1?[[=categories.child.child.name_1=]]:[[=categories.child.child.name_2=]]; ?></a>
                                    </li>
                                <!--/LIST:categories.child.child-->
                                </ul></div>
                            </div>
                             </ul></div>
                            <!--ELSE-->
                            <!--IF:cond_position([[=categories.child.important=]]==1)-->
                            <?php if($act==3){
                                    echo '</ul></div>';
                                 }$act=2;?>  
                             <div class="button textlist"><ul>    
                             <div class="dropdown" id="button_click">
                                <a href="[[|categories.child.url|]]" id="button_[[|categories.child.id|]]">
                                <!--IF:img_cond([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                    <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" /><!--/IF:img_cond--><span><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></span>
                                </a>
                             </div>
                            </div>
                             <!--ELSE-->
                             <?php if($act!=3){
                                    echo '<div class="button textlist" lang="'.$act.'">';
                                    echo '<ul>';
                                 }$act=3;?>
                                    <li>
                                    <a href="[[|categories.child.url|]]" rel="paste">
                                     <!--IF:img_cond02([[=categories.child.icon_url=]] && [[=categories.child.icon_url=]]!=' ')-->
                                     <img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" /><!--/IF:img_cond02--><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></a>
                                    </li>
                           <!--/IF:cond_position-->
                         <!--/IF:cond_child_02-->
                  <?php }else if($group>=3){ ?>
                  <?php if($group==3){
					  if($act==3){echo '</ul></div>';}
					  $act=0;?>  
					  	<div class="dropdown" id="button_more">
                                <a href="#" rel="paste">
                                    <img src="packages/core/skins/default/css/jquery/images/button5.gif" title="More" style="height:14px; width:15px;"/></a>
                                <div><ul>
                                <li class="menutitle">More</li>
                            <?php }?>
                                    <li>
                                        <a href="[[|categories.child.url|]]"><img src="[[|categories.child.icon_url|]]" title="<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>" /><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></a>
                                    </li>
                            <?php if(isset([[=categories.child.counnt=]]) && $group==[[=categories.child.counnt=]]){ echo '</ul></div></div>';}?>        
					       
                  <?php }$k++;if(($k+1)==sizeof([[=categories.child=]])){
					  if($act==3){echo '</ul></div>';}
					  echo '</li>';}?>  
              <!--/LIST:categories.child-->
            </ul>
    	 <!--/IF:cond_child_01-->
      </li>
    <!--/LIST:categories-->
    </ul>
  </div>
  <div id="chang_language"><a href="<?php echo Url::build('change_language',array('href','language_id'=>((Portal::language()==1)?2:1)));?>" title="[[.change_language.]]" style="font-size:11px;color:#FFF;"><!--IF:l_cond(Portal::language()==1)-->EN<!--ELSE-->VN<!--/IF:l_cond--> |</a> <a onclick="printWebPart('printer');" title="[[.print.]]"><img src="packages/core/skins/default/images/printer.png" height="20"></a></div>
  <div id="sign-in"><img src="packages/core/skins/default/css/jquery/images/banner_small.png"  /><!--IF:login(User::is_login())--><span>[[.Welcome.]]: </span><a class="link-personal" href="<?php echo Url::build('personal')?>"><?php echo Session::get('user_id'); ?></a><span> | </span><a class="log-out" href="<?php echo Url::build('sign_out',array()); ?>">[[.sign_out.]]</a><!--ELSE--><a href="<?php echo Url::build('sign_in',array()); ?>">[[.sign_in.]]</a><!--/IF:login--></div>
<script>	
	jQuery(document).ready(function(){
		jQuery('#testRibbon').css('display','block');
	})
</script>