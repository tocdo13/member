<style>
#RegisterContent {
    position: relative;
    width: 1150px;
    overflow: hidden;
}


/*thead*/
#RegisterContent thead {
    position: relative;
    display: block; /*seperates the header from the body allowing it to be positioned*/
    width: 1300px;
    overflow: visible;
}

#RegisterContent thead th {
    min-width: 40px;
    height: 25px;
    line-height: 25px;
     text-align: center;
}

#RegisterContent .fixed th:nth-child(1) {/*first cell in the header*/
    position: relative;
    display: table-cell; /*seperates the first cell in the header from the header*/
}
#RegisterContent .fixed th:nth-child(2) {/*second cell in the header*/
    position: relative;
    display: table-cell; /*seperates the second cell in the header from the header*/
}
#RegisterContent .fixed th:nth-child(3) {/*three cell in the header*/
    position: relative;
    display: table-cell; /*seperates the three cell in the header from the header*/
}

/*tbody*/
#RegisterContent tbody {
    position: relative;
    display: block; /*seperates the tbody from the header*/
    width: 1300px;
    height: 600px;
    overflow: scroll;
}

#RegisterContent tbody td {
    min-width: 40px;
    height: 25px;
    line-height: 25px;
     text-align: center;
}

#RegisterContent .tbody_fixed td:nth-child(1) {  /*the first cell in each tr*/
    position: relative;
    display: table-cell; /*seperates the first column from the tbody*/
    height: 25px;
     text-align: center;
}
#RegisterContent .tbody_fixed td:nth-child(2) {  /*the first cell in each tr*/
    position: relative;
    display: table-cell; /*seperates the first column from the tbody*/
    height: 25px;
    line-height: 25px;
     text-align: center;
}
#RegisterContent .tbody_fixed_next td:nth-child(1) {  /*the first cell in each tr*/
    position: relative;
    display: table-cell; /*seperates the first column from the tbody*/
    height: 25px;
    line-height: 25px;
     text-align: center;
}
#RegisterContent .tbody_fixed_new td:nth-child(1) {  /*the first cell in each tr*/
    position: relative;
    display: table-cell; /*seperates the first column from the tbody*/
    height: 25px;
    line-height: 25px;
    text-align: center;
}
</style>
<div style="margin: 0px auto;text-transform: uppercase;text-align: center;"><h2>[[.product_amenities_norm_quantity.]]</h2></div>
<br />
<form id="AddHrmRegisterWorkShiftNewForm" method="post">                        
    <table id="RegisterContent" cellspacing="1" cellpadding="1" style="margin: 0 auto;" border="1" bordercolor="#5F9EA0">
        <thead>
            <tr class="fixed" >
                <th rowspan="3" valign="center" style="background-color: #A9A9A9;">[[.room.]]</th>
                <th  colspan="<?php echo count($this->map['items'])*2; ?>" style="text-align: center; font-size: 15px;">[[.product_amenities.]]</th>
            </tr>
            <tr>
            <!--LIST:items-->
            <th colspan="2" >[[|items.name|]]</th>
            <!--/LIST:items-->
            </tr>
            <tr>
                <!--LIST:items-->
                <th>[[.norm_quantity.]]</th>
                <th>[[.used.]]</th>
                <!--/LIST:items-->
            </tr>
        </thead>
        <tbody class="tbody">
            <!------------------------------------ALL--------------------------------------->
                <!--LIST:rooms-->
                <tr class="tbody_fixed">
                    <td style="background-color: #A9A9A9;"><strong>[[|rooms.name|]]</strong></td>
                    <!--LIST:rooms.product-->
                        <td >[[|rooms.product.norm_quantity|]]</td>
                        <td >[[|rooms.product.use_quantity|]]</td>
                    <!--/LIST:rooms.product-->
                </tr>
            <!--/LIST:rooms-->
        </tbody>
    </table>
</form>
<br /><br /><br />
<script> 
jQuery(document).ready(function(){
    /*setInterval(function(){
        for(var i in items_js)
        {     
            staff_id = items_js[i]['id'];
            department_id = items_js[i]['department_id'];
            jQuery.ajax({
    				url:"form.php?block_id="+block_id,
    				type:"POST",
                    dataType: "json",
    				data:{action:'CheckTransfer',staff_id:staff_id,department_id:department_id,get_month:get_month},
    				success:function(res)
                    {
                        if(res['status']=='error' && +res['user'] != '' && +res['time'] != '' && res['full_name'] != '')
                        {
                            alert('L�u ?, Nh�n vi�n '+res['full_name']+' �? ��?c t�i kho?n '+res['user']+' �?i ph?ng ban, v�o l�c :'+res['time']+' \n D? li?u hi?n t?i c?a b?n ch�a ��?c c?p nh?p n?i dung ch?nh s?a �� \n \n �? ti?p t?c thao t�c b?n vui l?ng t?i l?i trang.\n Xin c?m �n!');
                        }
                        return false;
                    }
            });
        }
    }, 3000);*/
})
jQuery('tbody').scroll(function(e) { //detect a scroll event on the tbody
  	/*
    Setting the thead left value to the negative valule of tbody.scrollLeft will make it track the movement
    of the tbody element. Setting an elements left value to that of the tbody.scrollLeft left makes it maintain 			it's relative position at the left of the table.    
    */
    jQuery('thead').css("left", -jQuery(".tbody").scrollLeft()); //fix the thead relative to the body scrolling
    jQuery('thead th:nth-child(1)').css("left", jQuery(".tbody").scrollLeft()); //fix the first cell of the header
    jQuery('thead th:nth-child(2)').css("left", jQuery(".tbody").scrollLeft()); //fix the second cell of the header
    //jQuery('thead th:nth-child(3)').css("left", jQuery(".tbody").scrollLeft()); //fix the three cell of the header
    jQuery('tbody td:nth-child(1)').css("left", jQuery(".tbody").scrollLeft()); //fix the first column of tdbody
    //jQuery('tbody td:nth-child(2)').css("left", jQuery(".tbody").scrollLeft()); //fix the first column of tdbody
    //jQuery('tbody td:nth-child(3)').css("left", jQuery(".tbody").scrollLeft()); //fix the first column of tdbody
});
jQuery('#ShowHideWorkShiftStatus').click(function () {
    jQuery('#work_shift_status').animate({'width': 'toggle', 'right': '0px'}, "fast")
});    

<?php echo 'var block_id = '.Module::block_id().';';?>

    


    









</script>