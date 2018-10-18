<div class="row">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">[[.list_rate_code.]]</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-2 col-md-offset-10">
                    <button style="margin-right: 30px;" class="btn btn-primary" type="button" onclick="window.open('?page=rate_code_new&cmd=edit','_blank')">[[.add.]]</button>
                    <button class="btn btn-danger" type="button" onclick="deleteAll();">[[.delete.]]</button>
                </div>
                <br />
                <br />
                <form method="POST" onsubmit="return checkForm();">
                <div style="margin-top: 30px;">
                    <div class="form-group" style="float:left; width:10%; margin-right:2%;">
                        <label>[[.code.]] :</label>
                        <input name="code" autocomplete="off" class="form-control" list="code" />
                        <datalist id="code">
                            <!--LIST:rate_code_list-->
                                <option value="[[|rate_code_list.code|]]">[[|rate_code_list.code|]]</option>
                            <!--/LIST:rate_code_list-->
                        </datalist>
                    </div>
                    <div class="form-group" style="float:left; width:15%; margin-right:2%;">
                        <label>[[.name.]] :</label>
                        <input name="name" autocomplete="off" class="form-control" list="name" />
                        <datalist id="name">
                            <!--LIST:rate_code_list-->
                                <option value="[[|rate_code_list.name|]]">[[|rate_code_list.name|]]</option>
                            <!--/LIST:rate_code_list-->
                        </datalist>
                    </div>
                    <div style="float:left; width:35%;">
                        <div class="form-group" style="float: left; width:44%; margin-right: 5%;" >
                            <label>[[.start_date.]] :</label>
                            <input name="start_date" id="start_date" class="form-control" type="date" />
                        </div>
                        <div class="form-group" style="float: left; width:44%;" >
                            <label>[[.end_date.]] :</label>
                            <input name="end_date" id="end_date" class="form-control" type="date" />
                        </div>
                    </div>
                    <div class="form-group" style="float:left; width:20%; margin-right:2%;">
                        <label>[[.customer_group.]] :</label>
                        <input autocomplete="off" class="form-control" name="customer_group" list="customer_group" />
                        <datalist id="customer_group">
                            <!--LIST:customer_group-->
                                <option value="[[|customer_group.name|]]">[[|customer_group.name|]]</option>
                            <!--/LIST:customer_group-->
                        </datalist>
                    </div>
                    <input style="margin: 22px 0 0 30px;" type="submit" class="btn btn-info btn-sm" value="[[.search.]]" />
                </div>
                </form>
                <br />
                <br />
                <div class="col-md-12" style="margin-top: 30px;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:5%;"><input id="checkAll" onclick="checkAll();" type="checkbox" /></th>
                                <th style="width:7%;">[[.code.]]</th>
                                <th>[[.name.]]</th>
                                <th style="width:15%">[[.time_slot.]]</th>
                                <th>[[.loop_in.]]</th>
                                <th style="width:12%">[[.priority.]]</th>
                                <th>[[.customer_groups.]]</th>
                                <th style="width:5%;">[[.edit.]]</th>
                                <th style="width:5%;">[[.delete.]]</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; ?>
                           <!--LIST:rate_code_time--> 
                                <tr>
                                    <?php
                                        if(isset([[=rate_code_time.count=]])){
                                    ?>
                                        <td rowspan="[[|rate_code_time.count|]]"><input rName="" name="check_<?php echo $i; ?>" type="checkbox" value="[[|rate_code_time.rate_code_id|]]" /></td>
                                        <td rowspan="[[|rate_code_time.count|]]">[[|rate_code_time.code|]]</td>
                                        <td rowspan="[[|rate_code_time.count|]]">[[|rate_code_time.name|]]</td>
                                        <td><?php echo Date_Time::convert_orc_date_to_date([[=rate_code_time.start_date=]],'/'); ?> - <?php echo Date_Time::convert_orc_date_to_date([[=rate_code_time.end_date=]],'/'); ?></td>
                                        <td>[[|rate_code_time.brief|]]</td>
                                        <td>[[|rate_code_time.priority_str|]]</td>
                                        <td rowspan="[[|rate_code_time.count|]]">
                                            <?php
                                             $customer_group_id = "";
                                            ?>
                                            <!--LIST:rate_code_time.rate_customer_group_list-->
                                                <?php
                                                    if([[=rate_code_time.rate_customer_group_list.customer_group_id=]]!=$customer_group_id)
                                                    {
                                                ?>                                                      
                                                          <p customer_id="[[|rate_code_time.rate_customer_group_list.customer_group_id|]]" style="font-weight:bold; color: blue; cursor:pointer;" onclick="showDetail(this);" >[[|rate_code_time.rate_customer_group_list.customer_group_name|]] <span style="padding-left:10px; cursor: pointer;" class="glyphicon glyphicon-chevron-down"></span></p>
                                                          <p customer_id="[[|rate_code_time.rate_customer_group_list.customer_group_id|]]" style="display: none;">[[|rate_code_time.rate_customer_group_list.name|]]</p>    
                                                <?php  
                                                        $customer_group_id = [[=rate_code_time.rate_customer_group_list.customer_group_id=]];      
                                                    }
                                                    else
                                                    {
                                                ?>
                                                      <p customer_id="[[|rate_code_time.rate_customer_group_list.customer_group_id|]]" style="display:none;">[[|rate_code_time.rate_customer_group_list.name|]]</p>     
                                                <?php  
                                                        
                                                    }  
                                                ?>
                                                
                                            <!--/LIST:rate_code_time.rate_customer_group_list-->
                                        </td>
                                        <td rowspan="[[|rate_code_time.count|]]"><button type="button" onclick="window.open('?page=rate_code_new&cmd=edit&r_id=[[|rate_code_time.rate_code_id|]]','_blank')"><span class="glyphicon glyphicon-pencil" style="color: black;"></span></button></td>
                                        <td rowspan="[[|rate_code_time.count|]]"><button type="button" onclick="if(confirm('[[.are_you_sure.]]')) window.location='?page=rate_code_new&cmd=delete&r_id=[[|rate_code_time.rate_code_id|]]'"><span class="glyphicon glyphicon-remove" style="color: red;"></span></button></td>
                                    <?php        
                                        }
                                        else{
                                    ?>
                                        <td><?php echo Date_Time::convert_orc_date_to_date([[=rate_code_time.start_date=]],'/'); ?> - <?php echo Date_Time::convert_orc_date_to_date([[=rate_code_time.end_date=]],'/'); ?></td>
                                        <td>[[|rate_code_time.brief|]]</td>
                                        <td>[[|rate_code_time.priority_str|]]</td>
                                    <?php        
                                        }
                                    ?>
                                </tr>
                                <?php $i++; ?>
                           <!--/LIST:rate_code_time-->  
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
        if(confirm("[[.are_you_sure.]]")){
        var id_list="";
        jQuery("input[rName]:checked").each(function(){
            id_list+=jQuery(this).val()+",";
        });
        if(id_list!=""){
            id_list=id_list.substr(0,id_list.length-1);
            window.location='?page=rate_code_new&cmd=delete&r_id='+id_list;  
        }
        else{
            alert("[[.are_you_sure.]]");
        }
       }
    }
    function checkForm(){
        if(jQuery("input#start_date").val()>jQuery("input#end_date").val()){
            alert("[[.start_date_must_be_less_than_the_end_date.]]");
            return false;
        }
    }

</script>