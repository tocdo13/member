<style type="text/css">
@media print{
@page{
	size:landscape;	
	-webkit-transform: rotate(90deg);
	-webkit-transform: rotate(90deg);
	-ms-transform: rotate(90deg);
	-moz-transform:rotate(-90deg);
	-o-transform: rotate(90deg);
	-o-transform: rotate(90deg);
	-o-transform:rotate(-90deg);
}
.landscape { 
    width: 100%; 
    height: 100%; 
    margin: 0% 0% 0% 0%;
	filter: progid:DXImageTransform.Microsoft.BasicImage(Rotation=3);
} 
.page
    {
      -webkit-transform: rotate(90deg); -moz-transform:rotate(-90deg);
     filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
@page port {size: portrait;}
@page land {size: landscape;}
.landscape {page: land;}
}
</style>
<div class="landscape">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br /></td>
			</tr>	
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
			<td align="center" style="font-weight:bold;font-size:18px;text-transform:uppercase;">[[.cong_hoa_xa_hoi_chu_nghia_viet_name.]]</td>
		  </tr>
		   <tr>
			<td align="center" style="font-weight:bold;font-size:17px;">[[.doc_lap_tu_do_hanh_phuc.]]</td>
		  </tr>
		   <tr>
		     <td align="center">&nbsp;</td>
	      </tr>
		   <tr>
			<td align="center" style="font-weight:bold;text-transform:uppercase;font-size:18px;">[[.temporaty_absent_list.]]</td>
		  </tr>
		   <tr>
		     <td align="center" style="font-size:14px;">[[.date.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?></td>
	      </tr>		  
		   <tr>
			<td><table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
              <tr>
                <th rowspan="2" align="center"><strong>[[.stt.]]</strong></th>
                <th width="130" rowspan="2" align="center"><strong>H&#7885; v&agrave; t&ecirc;n</strong></th>
                <th colspan="2" align="center"><strong>[[.birth_day.]]</strong></th>
                <th rowspan="2" align="center" width="60">Qu&#7889;c t&#7883;ch </th>
                <th rowspan="2" align="center"><strong>S&#7889; h&#7897; chi&#7871;u </strong></th>
                <th colspan="2" align="center">Th&#7883; th&#7921;c c&#7911;a Vi&#7879;t Nam </th>
                <th rowspan="2" align="center">Ng&agrave;y nh&#7853;p c&#7843;nh </th>
                <th rowspan="2" align="center"><strong>S&#7889; phi&#7871;u XNC </strong></th>
                <th rowspan="2" align="center">&#272;&#432;&#7907;c ph&eacute;p t&#7841;m tr&uacute; t&#7841;i <br />
                Vi&#7879;t Nam &#273;&#7871;n ng&agrave;y </th>
                <th colspan="2" align="center">Th&#7901;i gian t&#7841;m tr&uacute; t&#7841;i KS </th>
                <th rowspan="2" align="center">C&#417; quan, t&#7893; ch&#7913;c, c&aacute; nh&acirc;n c&#7911;a VN<br />
                  Ho&#7863;c n&#432;&#7899;c ngo&agrave;i t&#7841;i VN &#273;&oacute;n ti&#7871;p </th>
                <th rowspan="2" align="center">[[.note.]]</th>
              </tr>
              <tr>
                <th width="60" align="center">[[.male.]]</th>
                <th width="60" align="center">[[.female.]]</th>
                <th width="60" align="center"><strong>S&#7889;</strong></th>
                <th width="60" align="center"><strong>Th&#7901;i h&#7841;n </strong></th>
                <th align="center"><strong>[[.arrive_date.]]</strong></th>
                <th align="center"><strong>[[.depart_date.]]</strong></th>
              </tr>
              <?php $i=0;?>
              <!--LIST:items-->
              <tr>
                <td><?php echo ++$i;?></td>
                <td>[[|items.first_name|]] [[|items.last_name|]]</td>
                <td align="center"><!--IF:cond([[=items.gender=]]==1)-->
                  [[|items.birth_date|]]
                    <!--/IF:cond--></td>
                <td align="center"><!--IF:cond([[=items.gender=]]!=1)-->
                  [[|items.birth_date|]]
                    <!--/IF:cond--></td>
                <td align="center">[[|items.nationality|]]</td>
                <td align="center">[[|items.passport|]]</td>
                <td align="center">[[|items.visa_number|]]</td>
                <td align="center">[[|items.back_date|]]</td>
                <td align="center">[[|items.entry_date|]]</td>
                <td align="center">[[|items.expire_date_of_visa|]]</td>
                <td align="center">[[|items.back_date|]]</td>
                <td align="center"><?php echo date('d/mY',[[=items.arrival_time=]]);?></td>
                <td align="center"><?php echo date('d/mY',[[=items.departure_time=]]);?></td>
                <td>[[|items.go_to_office|]]</td>
                <td></td>
              </tr>
              <!--/LIST:items-->
            </table></td>
		  </tr>
		  <tr>
		  	<td align="right">[[|paging|]]</td>
		  </tr>
		  <tr>
		  	<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td width="52%">[[.total_guest_new.]] : [[|total_guest_new|]]</td>
			    <td width="24%">[[.nguoilap.]]</td>
			    <td width="24%">[[.manager_hotel.]]</td>
			  </tr>
			  <tr>
				<td colspan="3">- [[.male.]] : [[|total_guest_male|]] </td>
			  </tr>
			  <tr>
				<td colspan="3">- [[.female.]] : [[|total_guest_female|]]</td>
			  </tr>
			  <tr>
				<td colspan="3">[[.total_guest_old.]] : [[|total_guest_old|]]</td>
			  </tr>
			  <tr>
				<td colspan="3">- [[.male.]] : [[|total_guest_old_male|]] </td>
			  </tr>
			   <tr>
				<td colspan="3">- [[.female.]] [[|total_guest_old_female|]]</td>
			  </tr>
			</table>

			</td>
		  </tr>
		</table>
	</td>
</tr>
</table>

</div>