<form name="LogListForm"  method="post">
    <table width="100%" style="text-align: center;">
        <tr>
            <th><h1 style="text-transform: uppercase;">[[.log.]] Recode <?php echo Url::get('recode'); ?></h1></th>
        </tr>
    </table>
    <table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
        <tr class="table-header">
        	<th width="1%"></th>
        	<th nowrap align="left">
        		[[.time.]]
        	</th>
        	<th nowrap align="left">
        		[[.type.]]
        	</th>
        	<th nowrap align="left">
        		[[.user_id.]]
        	</th>
        	<th align="left">
        		[[.title.]]
        	</th>
        	<th align="left" nowrap="nowrap">
        		[[.note.]]
        	</th>
        </tr>
        <?php $last_date = false;?>
        <!--LIST:items-->
        <?php
        if($last_date!=[[=items.in_date=]])
        {
        	$last_date=[[=items.in_date=]];
        	echo '<tr bgcolor="#FFFF80"><td colspan="9">'.[[=items.in_date=]].'</td></tr>';
        }
        ?><tr bgcolor="white" valign="top">
        	<td></td>
        	<td nowrap align="left">
        		<?php echo date('d/m/Y H:i:s',[[=items.time=]]);?>
        	</td>
        	<td nowrap align="left">
        		[[|items.type|]]
        	</td>
        	<td nowrap align="left">
        	  <strong>[[|items.user_id|]]						    </strong></td>
        	
        	<td align="left" width="100%">
        		[[|items.title|]]
        	</td>
        	<td nowrap align="left">
        		[[|items.note|]]
        	</td>
        </tr>
        <tr bgcolor="#EEEEEE">
            <td colspan="6">[[|items.description|]]</td>
        </tr>
        <!--/LIST:items-->
    </table>	
</form>
