<link rel="stylesheet" href="skins/default/report.css">
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<script>//full_screen();</script>
<table cellpadding="5" cellspacing="0" width="100%" border="1"  bordercolor="#CCCCCC" style="background-color: #FFFFFF;" class="table-bound">
    <tr>
        <td colspan="<?php echo (10 + count([[=categories_invi=]])+1+count([[=categories_hidd=]])+(Url::get('type_report')=='date'?0:2)); ?>">
            <!--HEADER-->
            <table cellspacing="0" width="100%">
                <tr valign="top">
                    <td align="left" width="65%">
            			<strong><?php echo [[=hotel_name=]];?></strong><br />
            			<?php echo [[=hotel_address=]];?>
                    </td>
                    <td align="right" nowrap width="35%">
            			<strong>[[.template_code.]]</strong><br />
            			<i>[[.promulgation.]]</i>
                    </td>
                </tr>	
            </table>
            <table cellpadding="10" cellspacing="0" width="100%">
                <tr>
                	<td align="center">
                		<b>
                            <span style="font-size: 25px;" >
                            <?php
                                switch(Url::get('type_report'))
                                 {
                                    case 'invoice': echo "BÁO CÁO DOANH THU NHA HÀNG THEO HÓA ĐƠN"; break;
                                    case 'date': echo "BÁO CÁO DOANH THU NHA HÀNG THEO NGÀY"; break;
                                    default : echo "BÁO CÁO DOANH THU NHA HÀNG THEO HÓA ĐƠN"; break;
                                }
                            ?>
                            </span>
                            <br />
                            [[.from_date.]] <?php echo $_REQUEST['from_date'] ?> [[.to_date.]] <?php echo $_REQUEST['to_date'] ?>
                            <br />
                            <?php if([[=karaoke_names=]]) { echo [[=karaoke_names=]]; ?>
                            <br />
                            <?php } ?>
                            [[.time.]] : <?php echo $_REQUEST['from_time'] ?> - <?php echo $_REQUEST['to_time'] ?>
                        </b>
                    </td>
                </tr>
            </table>
            <!--/HEADER-->
        </td>
    </tr>
