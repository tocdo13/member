<link rel="stylesheet" href="skins/default/report.css">
<style>
.date_date{
    display: none;
}
/*full màn hình*/
.simple-layout-middle{width:100%;}
@media print
{
    .search{display: none;}
    .date_date{display: block;}
}
</style>
<script>//full_screen();</script>
<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1"  bordercolor="#CCCCCC" style="background-color: #FFFFFF;" class="table-bound">
    <tr id="search">
        <td>
            <div class="search" >
            <!--HEADER-->
            <table cellspacing="0" width="100%">
                <tr valign="top">
                    <td align="left" width="65%">
            			<strong><?php echo $this->map['hotel_name'];?></strong><br />
            			<?php echo $this->map['hotel_address'];?>
                    </td>
                    <td align="right" nowrap width="35%">
            			<strong><?php echo Portal::language('template_code');?></strong><br />
            			<i><?php echo Portal::language('promulgation');?></i><br />
                        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                    </td>
                </tr>
            </table>
            <table cellpadding="10" cellspacing="0" width="100%">
                <tr>
                	<td align="center">
                		<b>
                            <span style="font-size: 25px;" >
                            <?php echo Portal::language('unpaid_room_list');?>
                            </span>
                        </b>
                    </td>
                </tr>
            </table>
            
            <form name="SearchForm" method="post">
            <table cellpadding="10" cellspacing="0" width="100%" style=" border: 1px solid ;">
                <tr>
                    <td align="center">
                        <?php echo Portal::language('line_per_page');?>
                        <input  name="line_per_page" id="line_per_page" size="4" maxlength="2" style="text-align:center"/ type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>">
                        <?php echo Portal::language('no_of_page');?>
                        <input  name="no_of_page1" id="no_of_page1" size="4" maxlength="2" style="text-align:center"/ type ="text" value="<?php echo String::html_normalize(URL::get('no_of_page1'));?>">
                        <?php echo Portal::language('from_page');?>
                        <input  name="start_page" id="start_page" size="4" maxlength="4" style="text-align:center"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>">
                        &nbsp;
                        <?php echo Portal::language('from_date');?> : 
                        <input  name="from_date" id="from_date" onchange="changevalue();" style="width:70px;text-align: center;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
                        &nbsp;
                        <?php echo Portal::language('to_date');?> : 
                        <input  name="to_date" id="to_date" onchange="changefromday();" style="width:70px;text-align: center;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">              &nbsp;  
                        <?php echo Portal::language('hotel');?>  :
                        <select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select>
                        <input type="submit" value="<?php echo Portal::language('search');?>"/>
                        <button id="export"><?php echo Portal::language('export');?></button>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
            <div style="text-align: center;" class="date_date">
                <?php echo Portal::language('from_date');?> :<?php if(isset($_POST['from_date'])){echo $_POST['from_date'];}else{echo date('d/m/Y');}?> <?php echo Portal::language('to_date');?> : <?php if(isset($_POST['to_date'])){echo $_POST['to_date'];}else{echo date('d/m/Y');}?>
            </div>
            </div>
            <!--/HEADER-->
        </td>
    </tr>
<script>
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>
