<div class="row">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo Portal::language('list_rate_code');?></h4>
            </div>
            <div class="panel-body">
                <div class="col-md-2 col-md-offset-10">
                    <button style="margin-right: 30px;" class="btn btn-primary" type="button" onclick="window.open('?page=rate_code_new&cmd=edit','_blank')"><?php echo Portal::language('add');?></button>
                    <button class="btn btn-danger" type="button" onclick="deleteAll();"><?php echo Portal::language('delete');?></button>
                </div>
                <br />
                <br />
                <form method="POST" onsubmit="return checkForm();">
                <div style="margin-top: 30px;">
                    <div class="form-group" style="float:left; width:10%; margin-right:2%;">
                        <label><?php echo Portal::language('code');?> :</label>
                        <input name="code" autocomplete="off" class="form-control" list="code" />
                        <datalist id="code">
                            <?php if(isset($this->map['rate_code_list']) and is_array($this->map['rate_code_list'])){ foreach($this->map['rate_code_list'] as $key1=>&$item1){if($key1!='current'){$this->map['rate_code_list']['current'] = &$item1;?>
                                <option value="<?php echo $this->map['rate_code_list']['current']['code'];?>"><?php echo $this->map['rate_code_list']['current']['code'];?></option>
                            <?php }}unset($this->map['rate_code_list']['current']);} ?>
                        </datalist>
                    </div>
                    <div class="form-group" style="float:left; width:15%; margin-right:2%;">
                        <label><?php echo Portal::language('name');?> :</label>
                        <input name="name" autocomplete="off" class="form-control" list="name" />
                        <datalist id="name">
                            <?php if(isset($this->map['rate_code_list']) and is_array($this->map['rate_code_list'])){ foreach($this->map['rate_code_list'] as $key2=>&$item2){if($key2!='current'){$this->map['rate_code_list']['current'] = &$item2;?>
                                <option value="<?php echo $this->map['rate_code_list']['current']['name'];?>"><?php echo $this->map['rate_code_list']['current']['name'];?></option>
                            <?php }}unset($this->map['rate_code_list']['current']);} ?>
                        </datalist>
                    </div>
                    <div style="float:left; width:35%;">
                        <div class="form-group" style="float: left; width:44%; margin-right: 5%;" >
                            <label><?php echo Portal::language('start_date');?> :</label>
                            <input name="start_date" id="start_date" class="form-control" type="date" />
                        </div>
                        <div class="form-group" style="float: left; width:44%;" >
                            <label><?php echo Portal::language('end_date');?> :</label>
                            <input name="end_date" id="end_date" class="form-control" type="date" />
                        </div>
                    </div>
                    <div class="form-group" style="float:left; width:20%; margin-right:2%;">
                        <label><?php echo Portal::language('customer_group');?> :</label>
                        <input autocomplete="off" class="form-control" name="customer_group" list="customer_group" />
                        <datalist id="customer_group">
                            <?php if(isset($this->map['customer_group']) and is_array($this->map['customer_group'])){ foreach($this->map['customer_group'] as $key3=>&$item3){if($key3!='current'){$this->map['customer_group']['current'] = &$item3;?>
                                <option value="<?php echo $this->map['customer_group']['current']['name'];?>"><?php echo $this->map['customer_group']['current']['name'];?></option>
                            <?php }}unset($this->map['customer_group']['current']);} ?>
                        </datalist>
                    </div>
                    <input style="margin: 22px 0 0 30px;" type="submit" class="btn btn-info btn-sm" value="<?php echo Portal::language('search');?>" />
                </div>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                <br />
                <br />
                <div class="col-md-12" style="margin-top: 30px;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:5%;"><input id="checkAll" onclick="checkAll();" type="checkbox" /></th>
                                <th style="width:7%;"><?php echo Portal::language('code');?></th>
                                <th><?php echo Portal::language('name');?></th>
                                <th style="width:15%"><?php echo Portal::language('time_slot');?></th>
                                <th><?php echo Portal::language('loop_in');?></th>
                                <th style="width:12%"><?php echo Portal::language('priority');?></th>
                                <th><?php echo Portal::language('customer_groups');?></th>
                                <th style="width:5%;"><?php echo Portal::language('edit');?></th>
                                <th style="width:5%;"><?php echo Portal::language('delete');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; ?>
                           <?php if(isset($this->map['rate_code_time']) and is_array($this->map['rate_code_time'])){ foreach($this->map['rate_code_time'] as $key4=>&$item4){if($key4!='current'){$this->map['rate_code_time']['current'] = &$item4;?> 
                                <tr>
                                    <?php
                                        if(isset($this->map['rate_code_time']['current']['count'])){
                                    ?>
                                        <td rowspan="<?php echo $this->map['rate_code_time']['current']['count'];?>"><input rName="" name="check_<?php echo $i; ?>" type="checkbox" value="<?php echo $this->map['rate_code_time']['current']['rate_code_id'];?>" /></td>
                                        <td rowspan="<?php echo $this->map['rate_code_time']['current']['count'];?>"><?php echo $this->map['rate_code_time']['current']['code'];?></td>
                                        <td rowspan="<?php echo $this->map['rate_code_time']['current']['count'];?>"><?php echo $this->map['rate_code_time']['current']['name'];?></td>
                                        <td><?php echo Date_Time::convert_orc_date_to_date($this->map['rate_code_time']['current']['start_date'],'/'); ?> - <?php echo Date_Time::convert_orc_date_to_date($this->map['rate_code_time']['current']['end_date'],'/'); ?></td>
                                        <td><?php echo $this->map['rate_code_time']['current']['brief'];?></td>
                                        <td><?php echo $this->map['rate_code_time']['current']['priority_str'];?></td>
                                        <td rowspan="<?php echo $this->map['rate_code_time']['current']['count'];?>">
                                            <?php
                                             $customer_group_id = "";
                                            ?>
                                            <?php if(isset($this->map['rate_code_time']['current']['rate_customer_group_list']) and is_array($this->map['rate_code_time']['current']['rate_customer_group_list'])){ foreach($this->map['rate_code_time']['current']['rate_customer_group_list'] as $key5=>&$item5){if($key5!='current'){$this->map['rate_code_time']['current']['rate_customer_group_list']['current'] = &$item5;?>
                                                <?php
                                                    if($this->map['rate_code_time']['current']['rate_customer_group_list']['current']['customer_group_id']!=$customer_group_id)
                                                    {
                                                ?>                                                      
                                                          <p customer_id="<?php echo $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['customer_group_id'];?>" style="font-weight:bold; color: blue; cursor:pointer;" onclick="showDetail(this);" ><?php echo $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['customer_group_name'];?> <span style="padding-left:10px; cursor: pointer;" class="glyphicon glyphicon-chevron-down"></span></p>
                                                          <p customer_id="<?php echo $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['customer_group_id'];?>" style="display: none;"><?php echo $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['name'];?></p>    
                                                <?php  
                                                        $customer_group_id = $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['customer_group_id'];      
                                                    }
                                                    else
                                                    {
                                                ?>
                                                      <p customer_id="<?php echo $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['customer_group_id'];?>" style="display:none;"><?php echo $this->map['rate_code_time']['current']['rate_customer_group_list']['current']['name'];?></p>     
                                                <?php  
                                                        
                                                    }  
                                                ?>
                                                
                                            <?php }}unset($this->map['rate_code_time']['current']['rate_customer_group_list']['current']);} ?>
                                        </td>
                                        <td rowspan="<?php echo $this->map['rate_code_time']['current']['count'];?>"><button type="button" onclick="window.open('?page=rate_code_new&cmd=edit&r_id=<?php echo $this->map['rate_code_time']['current']['rate_code_id'];?>','_blank')"><span class="glyphicon glyphicon-pencil" style="color: black;"></span></button></td>
                                        <td rowspan="<?php echo $this->map['rate_code_time']['current']['count'];?>"><button type="button" onclick="if(confirm('<?php echo Portal::language('are_you_sure');?>')) window.location='?page=rate_code_new&cmd=delete&r_id=<?php echo $this->map['rate_code_time']['current']['rate_code_id'];?>'"><span class="glyphicon glyphicon-remove" style="color: red;"></span></button></td>
                                    <?php        
                                        }
                                        else{
                                    ?>
                                        <td><?php echo Date_Time::convert_orc_date_to_date($this->map['rate_code_time']['current']['start_date'],'/'); ?> - <?php echo Date_Time::convert_orc_date_to_date($this->map['rate_code_time']['current']['end_date'],'/'); ?></td>
                                        <td><?php echo $this->map['rate_code_time']['current']['brief'];?></td>
                                        <td><?php echo $this->map['rate_code_time']['current']['priority_str'];?></td>
                                    <?php        
                                        }
                                    ?>
                                </tr>
                                <?php $i++; ?>
                           <?php }}unset($this->map['rate_code_time']['current']);} ?>  
                           <input type="hidden" name="stt_checkbox" value="<?php echo $i; ?>" />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showDetail(obj)
    {
        var customer_id = jQuery(obj).attr("customer_id");
        jQuery(obj).parent().find("p[customer_id="+customer_id+"]").not(jQuery(obj)).toggle();
    }
    function checkAll(){
        var status = jQuery("input#checkAll:checked");
        if(!status.length){
            jQuery("input[rName]").each(function(){
                jQuery(this).removeAttr("checked");
            });
        }
        else{
            jQuery("input[rName]").each(function(){
                jQuery(this).attr("checked","checked");
            });
        }
    }
    function deleteAll(){
        if(confirm("<?php echo Portal::language('are_you_sure');?>")){
        var id_list="";
        jQuery("input[rName]:checked").each(function(){
            id_list+=jQuery(this).val()+",";
        });
        if(id_list!=""){
            id_list=id_list.substr(0,id_list.length-1);
            window.location='?page=rate_code_new&cmd=delete&r_id='+id_list;  
        }
        else{
            alert("<?php echo Portal::language('are_you_sure');?>");
        }
       }
    }
    function checkForm(){
        if(jQuery("input#start_date").val()>jQuery("input#end_date").val()){
            alert("<?php echo Portal::language('start_date_must_be_less_than_the_end_date');?>");
            return false;
        }
    }

</script>