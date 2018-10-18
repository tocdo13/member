<?php
$date_now = getdate();
?>
<!--IF:page_no([[=page_no=]])--><center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center><!--/IF:page_no--><br>
		<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
        <!--IF:payment(isset([[=payment=]]))-->
        <div align="left"></div>
        <!--/IF:payment-->        
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;">[[.creator.]]</td>
                <td style="text-align: center;">[[.general_accountant.]]</td>
                <td style="text-align: center;">[[.date.]] <?php echo $date_now["mday"]; ?> [[.month.]] <?php echo $date_now["mon"]; ?> [[.year.]] <?php echo $date_now["year"]; ?></td>
            </tr>
        </table>
        
		<!--/IF:first_page-->
 </td>
</tr>
</table>
<!--<DIV style="page-break-before:always;page-break-after:always;"></DIV>-->
</div>
