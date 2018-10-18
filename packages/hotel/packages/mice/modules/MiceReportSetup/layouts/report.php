<style>
    .simple-layout-middle{width:100%;}
    .simple-layout-content {
        background: #EEEEEE;
    }
    div {
        margin: 0px;
        padding: 0px;
    }
    #MiceReservationBody {
        width: 95%;
        height: auto;
        padding: 10px;
        margin: 5px auto;
        background: #FFFFFF;
        box-shadow: 0px 1px 4px 0px rgba(0,0,0,0.2);
    }
</style>
<div id="setup_report">
    <table style="width: 100%;">
        <tr>
            <th style="text-align: center;"><h1 style="text-transform: uppercase;">[[.report_setup_mice.]]</h1></th>
        </tr>
        <tr>
            <th style="text-align: center;">
                <form name="MiceReportSetupForm" method="POST">
                    [[.location.]]: 
                        <select name="location" id="location" class="datainput" style="text-align: center; padding: 5px; font-weight: bold;" >[[|location_option|]]</select>
                        <script>jQuery("#location").val([[|location|]]);</script>
                        <label class="datalabel" style="display: none;">[[|location_name|]]</label>
                    [[.department.]]: 
                        <select name="department" id="department" class="datainput" style="text-align: center; padding: 5px; font-weight: bold;" >[[|department_option|]]</select>
                        <script>jQuery("#department").val([[|department|]]);</script>
                        <label class="datalabel" style="display: none;">[[|department_name|]]</label>
                    [[.mice.]]: 
                        <select name="mice" id="mice" class="datainput" style="text-align: center; padding: 5px; font-weight: bold;" >[[|mice_option|]]</select>
                        <script>jQuery("#mice").val([[|mice|]]);</script>
                        <label class="datalabel" style="display: none;">[[|mice_name|]]</label>
                    [[.customer.]]: 
                        <select name="customer" id="customer" class="datainput" style="text-align: center; padding: 5px; font-weight: bold; max-width: 200px;" >[[|customer_option|]]</select>
                        <script>jQuery("#customer").val([[|customer|]]);</script>
                        <label class="datalabel" style="display: none;">[[|customer_name|]]</label>
                    [[.from_date.]]:
                        <input name="from_date" type="text" id="from_date" class="datainput" style="text-align: center; padding: 5px; font-weight: bold; width: 80px;" />
                        <label class="datalabel" style="display: none;">[[|from_date|]]</label>
                    [[.to_date.]]:
                        <input name="to_date" type="text" id="to_date" class="datainput" style="text-align: center; padding: 5px; font-weight: bold; width: 80px;" />
                        <label class="datalabel" style="display: none;">[[|to_date|]]</label>
                    <button onclick="MiceReportSetupForm.submit();" style="padding: 5px;" class="datainput"><i class="fa fa-bar-chart fa-fw"></i>[[.view_report.]]</button>
                    <button onclick="print_report();" style="padding: 5px;" class="datainput"><i class="fa fa-print fa-fw"></i>[[.print_report.]]</button>
                </form>
            </th>
        </tr>
    </table>
    <div id="MiceReservationBody">
        <table style="width: 99%; margin: 3px auto;" cellpadding="10" cellSpacing="0" border="1" bordercolor="#EEEEEE">
            <tr style="text-transform: uppercase; text-align: center;">
                <th>[[.date.]]</th>
                <th>[[.hour.]]</th>
                <th>[[.location.]]</th>
                <th>MICE</th>
                <th>[[.customer.]]</th>
                <th>[[.contact_name.]]</th>
                <th>[[.contact_phone.]]</th>
                <th>[[.content.]]</th>
                <th>[[.department.]]</th>
                <th class="datainput">[[.detail.]]</th>
            </tr>
            <!--LIST:items-->
                <tr>
                    <td rowspan="[[|items.count|]]">[[|items.id|]]</td>
                    <?php $child = ''; ?>
                    <!--LIST:items.child-->
                        <?php $child = [[=items.child.id=]]; ?>
                        <td rowspan="[[|items.child.count|]]">[[|items.child.id|]]</td>
                            <?php $child_child=''; ?>
                            <!--LIST:items.child.child-->
                                <?php $child_child=[[=items.child.child.id=]]; ?>
                                    <td>[[|items.child.child.location_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.child.child.mice_id|]]">[[|items.child.child.mice_id|]]</a></td>
                                    <td>[[|items.child.child.customer_name|]]</td>
                                    <td>[[|items.child.child.contact_name|]]</td>
                                    <td>[[|items.child.child.contact_phone|]]</td>
                                    <td>
                                        <textarea id="items_[[|items.child.child.id|]]" style="display: none;">[[|items.child.child.description|]]</textarea>
                                        <label id="lbl_items_[[|items.child.child.id|]]"></label>
                                        <script>jQuery('#lbl_items_[[|items.child.child.id|]]').html(jQuery('#items_[[|items.child.child.id|]]').val().replace(/\r?\n/g, '<br />'));</script>
                                    </td>
                                    <td>[[|items.child.child.department_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=beoform&id=[[|items.child.child.mice_id|]]"><span class="fa fa-fw fa-file-text-o"></span></a></td>
                                </tr>
                                <?php break; ?>
                            <!--/LIST:items.child.child-->
                            <!--LIST:items.child.child-->
                                <?php if($child_child!=[[=items.child.child.id=]]){ ?>
                                <tr>
                                    <td>[[|items.child.child.location_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.child.child.mice_id|]]">[[|items.child.child.mice_id|]]</a></td>
                                    <td>[[|items.child.child.customer_name|]]</td>
                                    <td>[[|items.child.child.contact_name|]]</td>
                                    <td>[[|items.child.child.contact_phone|]]</td>
                                    <td>
                                        <textarea id="items_[[|items.child.child.id|]]" style="display: none;">[[|items.child.child.description|]]</textarea>
                                        <label id="lbl_items_[[|items.child.child.id|]]"></label>
                                        <script>jQuery('#lbl_items_[[|items.child.child.id|]]').html(jQuery('#items_[[|items.child.child.id|]]').val().replace(/\r?\n/g, '<br />'));</script>
                                    </td>
                                    <td>[[|items.child.child.department_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=beoform&id=[[|items.child.child.mice_id|]]"><span class="fa fa-fw fa-file-text-o"></span></a></td>
                                </tr>
                                <?php } ?>
                            <!--/LIST:items.child.child-->
                        <?php break; ?>
                    <!--/LIST:items.child-->
                    <!--LIST:items.child-->
                        <?php if($child!=[[=items.child.id=]]){ ?>
                        <tr>
                        <td rowspan="[[|items.child.count|]]">[[|items.child.id|]]</td>
                            <?php $child_child=''; ?>
                            <!--LIST:items.child.child-->
                                <?php $child_child=[[=items.child.child.id=]]; ?>
                                    <td>[[|items.child.child.location_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.child.child.mice_id|]]">[[|items.child.child.mice_id|]]</a></td>
                                    <td>[[|items.child.child.customer_name|]]</td>
                                    <td>[[|items.child.child.contact_name|]]</td>
                                    <td>[[|items.child.child.contact_phone|]]</td>
                                    <td>
                                        <textarea id="items_[[|items.child.child.id|]]" style="display: none;">[[|items.child.child.description|]]</textarea>
                                        <label id="lbl_items_[[|items.child.child.id|]]"></label>
                                        <script>jQuery('#lbl_items_[[|items.child.child.id|]]').html(jQuery('#items_[[|items.child.child.id|]]').val().replace(/\r?\n/g, '<br />'));</script>
                                    </td>
                                    <td>[[|items.child.child.department_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=beoform&id=[[|items.child.child.mice_id|]]"><span class="fa fa-fw fa-file-text-o"></span></a></td>
                                </tr>
                                <?php break; ?>
                            <!--/LIST:items.child.child-->
                            <!--LIST:items.child.child-->
                                <?php if($child_child!=[[=items.child.child.id=]]){ ?>
                                <tr>
                                    <td>[[|items.child.child.location_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=edit&id=[[|items.child.child.mice_id|]]">[[|items.child.child.mice_id|]]</a></td>
                                    <td>[[|items.child.child.customer_name|]]</td>
                                    <td>[[|items.child.child.contact_name|]]</td>
                                    <td>[[|items.child.child.contact_phone|]]</td>
                                    <td>
                                        <textarea id="items_[[|items.child.child.id|]]" style="display: none;">[[|items.child.child.description|]]</textarea>
                                        <label id="lbl_items_[[|items.child.child.id|]]"></label>
                                        <script>jQuery('#lbl_items_[[|items.child.child.id|]]').html(jQuery('#items_[[|items.child.child.id|]]').val().replace(/\r?\n/g, '<br />'));</script>
                                    </td>
                                    <td>[[|items.child.child.department_name|]]</td>
                                    <td><a target="_blank" href="?page=mice_reservation&cmd=beoform&id=[[|items.child.child.mice_id|]]"><span class="fa fa-fw fa-file-text-o"></span></a></td>
                                </tr>
                                <?php } ?>
                            <!--/LIST:items.child.child-->
                        <?php } ?>
                    <!--/LIST:items.child-->
            <!--/LIST:items-->
        </table>
    </div>
</div>
<script>
    jQuery("#chang_language").css('display','none');
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function print_report()
    {
        jQuery(".datalabel").css('display','');
        jQuery(".datainput").css('display','none');
        var user ='<?php echo User::id(); ?>';
        printWebPart('setup_report',user);
        jQuery(".datalabel").css('display','none');
        jQuery(".datainput").css('display','');
    }
</script>