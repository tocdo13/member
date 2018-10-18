<style>
.simple-layout-middle{width:100%;}
.sp_module
{
    margin-right: 20px;
}
textarea,input {
    font-size: 16px;
}
</style>

<div class="warehouse-bound">
<form name="ViewModuleForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="form-title">Chi tiết module <span style="color: blue; font-weight: bold; font-size: 14px;"><a  href="<?php if(isset($_REQUEST['url'])) { echo $_REQUEST['url']; }?>" target="_blank"><?php if(isset($_REQUEST['module_name'])) { echo $_REQUEST['module_name']; }?></a></span></td>
            <td width="20%" align="right" nowrap="nowrap">
            <a href="<?php echo Url::build_current();?>" class="button-medium-back">Quay lại</a>
            </td>
        </tr>
    </table>
    <div class="content">
    <!--start: module -->
        <div onclick="jQuery('.view_module').slideToggle();" style="padding-left: 20px; margin: 2px;background-color: #DEDEDE; height: 30px;">
             <?php
                if(User::id()=='daibt' || User::id()=='thumv' || User::id()=='developer04'||(isset($_REQUEST['user_id']) && User::id()==$_REQUEST['user_id']))
                {
                    ?>
                     <span class="sp_module"><a href="<?php echo Url::build_current(array('cmd'=>'edit_module','module_id'=>$_REQUEST['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" title="Edit"/></a></span>
                    <?php 
                } 
             ?>
             <span class="sp_module">Người thực hiện:<strong style="color: red;"> <?php if(isset($_REQUEST['account'])) { echo $_REQUEST['account']; }?></strong> </span>
             <span class="sp_module">Trạng thái 1:<strong style="color: red;"><?php if(isset($_REQUEST['status_1_module'])) { echo $_REQUEST['status_1_module']==1?' OK':' Not OK'; }?> </strong></span>
             <span class="sp_module">Trạng thái 2: <strong style="color: red;"><?php if(isset($_REQUEST['status_2_module'])) { echo $_REQUEST['status_2_module']==1?' OK':' Not OK'; }?></strong></span>
        </div>
        <div  class="view_module" >
        <fieldset>
        <table width="100%" border="0" cellspacing="0" cellpadding="2" align="left">
            <tr>
                <td valign="middle" width="100px;"><span style="font-weight: bold;">Tiêu đề:</span></td>
                <td><input  name="title_module"  id="title_module" readonly="readonly" style="width:80%; height: 26px; background-color: #EDE8E4; border: 0px;" / type ="text" value="<?php echo String::html_normalize(URL::get('title_module'));?>"></td>                              
            </tr>
            <tr>
                <td valign="top"><span style="font-weight: bold;">Mô tả quy trình:</span></td>
                <td><textarea  name="description_module"  id="description_module" rows="5" readonly="readonly" style="padding:0px;margin:0px;width:98%; height: 250px; background-color: #EDE8E4; border: 0px;" ><?php echo String::html_normalize(URL::get('description_module',''));?></textarea></td>                              
            </tr>
            <tr>
                <td valign="top"><span style="font-weight: bold;">Ghi chú:</span></td>
                <td><textarea  name="note_module"  id="note_module" rows="5" readonly="readonly" style="padding:0px;margin:0px;width:80%; height: 100px; background-color: #EDE8E4; border: 0px;"><?php echo String::html_normalize(URL::get('note_module',''));?></textarea></td>                              
            </tr>
        </table>
        
        </fieldset>
        </div>
        <!--End module-->
        <!--start Form: list form detail-->
        <?php
            
            if(isset($_REQUEST['doc_module_id']))
            {
                ?>
                <div onclick="jQuery('.list_form').slideToggle();" style="padding-left: 20px; margin: 2px;background-color: #DEDEDE; height: 25px; vertical-align: middle; margin-top: 5px; padding-top: 5px;">
                     <span class="sp_module">Form</span>
                     <?php
                        if(User::id()=='developer04'||(isset($_REQUEST['user_id']) && User::id()==$_REQUEST['user_id']))
                        {
                            ?>
                             <span class="sp_module"><a href="<?php echo Url::build_current(array('cmd'=>'add_form','module_id'=>$_REQUEST['doc_module_id']));?>"><img src="packages/core/skins/default/images/buttons/add_button.gif" title="Add" /></a></span>
                            <?php 
                        } 
                     ?>
                     
                     <span class="sp_module">Trạng thái 1:<strong style="color: red;"><?php echo $_REQUEST['status_1_form']?' OK': ' Not OK';?></strong></span>
                     <span class="sp_module">Trạng thái 2: <strong style="color: red;"><?php echo $_REQUEST['status_2_form']?' OK': ' Not OK';?></strong></span>
                </div>
                <?php 
            } 
        ?>
        
        <div  class="list_form" >
        <fieldset>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <div onclick="jQuery('.view_form_'+<?php echo $this->map['items']['current']['id'];?>).slideToggle();" style="padding-left: 20px; margin: 2px;background-color: #DEDEDE; height: 25px; vertical-align: middle; margin-top: 5px; padding-top: 5px;">
             <span class="sp_module"><?php echo $this->map['items']['current']['name'];?></span>
             <?php
                 if(User::id()=='developer04'||(isset($_REQUEST['user_id']) && User::id()==$_REQUEST['user_id']) || (isset($this->map['items']['current']['user_id']) && User::id()==$this->map['items']['current']['user_id']))
                 {
                     ?>
                      <span class="sp_module"><a href="<?php echo Url::build_current(array('cmd'=>'edit_form','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" title="Edit" /></a></span>
                     <?php 
                 }
             ?>
             
             <span class="sp_module">Người thực hiện:<strong style="color: red;"> <?php echo $this->map['items']['current']['account'];?></strong> </span>
             <span class="sp_module">Trạng thái 1:<strong style="color: red;"> <?php echo ($this->map['items']['current']['status_1']==1)?' OK':' Not OK'; ?></strong></span>
             <span class="sp_module">Trạng thái 2: <strong style="color: red;"> <?php echo ($this->map['items']['current']['status_2']==1)?' OK':' Not OK'; ?></strong></span>
            </div>
            <div  class="view_form_<?php echo $this->map['items']['current']['id']?>" >
            <fieldset>
            <table width="100%" border="0" cellspacing="0" cellpadding="2" align="left">
                <tr>
                    <td valign="middle" width="100px;"><span style="font-weight: bold;">Tiêu đề:</span></td>
                    <td><input name="title" type="text"  id="title" readonly="readonly" value="<?php echo $this->map['items']['current']['title'];?>" style="padding:0px;margin:0px;width:80%; height: 18px; background-color: #EDE8E4;" /></td>                              
                </tr>
                <tr>
                    <td valign="top"><span style="font-weight: bold;">Mô tả quy trình:</span></td>
                    <td><textarea  name="description_form"  id="description_form" onkeyup="textAreaAdjust(this)"  rows="5" readonly="readonly" style="padding:0px;margin:0px;width:98%; height: 250px; background-color: #EDE8E4;" ><?php echo String::html_normalize(URL::get('description_form',''.$this->map['items']['current']['description_form']));?></textarea></td>                              
                </tr>
                
                <tr>
                    <td valign="top"><span style="font-weight: bold;">Diễn giải code:</span></td>
                    <td><textarea  name="interpert_code"  id="interpert_code" rows="5" readonly="readonly" style="padding:0px;margin:0px;width:98%; height: 250px; background-color: #EDE8E4;" ><?php echo String::html_normalize(URL::get('interpert_code',''.$this->map['items']['current']['interpert_code']));?></textarea></td>                              
                </tr>
                <tr>
                    <td valign="top"><span style="font-weight: bold;">Ghi chú:</span></td>
                    <td><textarea  name="note"  id="note" rows="5" readonly="readonly" style="padding:0px;margin:0px;width:80%; height: 100px; background-color: #EDE8E4;"><?php echo String::html_normalize(URL::get('note',''.$this->map['items']['current']['note']));?></textarea></td>                              
                </tr>
            </table>
            </fieldset>
            </div>
            <?php }}unset($this->map['items']['current']);} ?>
        </fieldset>
        </div>
        <!--end: form-->
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
    jQuery(document).ready(function(){
        var height = document.getElementById('description_module').scrollHeight;
        document.getElementById('description_module').style.height = (5+height)+"px";
    });
    
</script>