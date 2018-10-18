<style>
    p
    {
        padding-left: 40px;
    }
    table.lisst tr:nth-child(2n+1)
    {
        background: #eeeeee;
    }
    table.lisst tr:hover
    {
        background: #dddddd;    
    }
</style>
<script>
jQuery(document).ready(function(){
    jQuery(".list_payment_type").slideUp();
    jQuery(".list_bank").slideUp();
    jQuery(".list_currency").slideUp();
    jQuery("#list_payment_type").click(function(){
        jQuery(".list_payment_type").slideToggle("4000");
        jQuery(".list_bank").slideUp("1000");
        jQuery(".list_currency").slideUp("1000");
    });
    jQuery("#list_bank").click(function(){
        jQuery(".list_payment_type").slideUp("1000");
        jQuery(".list_bank").slideToggle("4000");
        jQuery(".list_currency").slideUp("1000");
    });
    jQuery("#list_currency").click(function(){
        jQuery(".list_payment_type").slideUp("1000");
        jQuery(".list_bank").slideUp("1000");
        jQuery(".list_currency").slideToggle("4000");
    });
});
</script>
<form name="EditImportPaymentForm" method="post" enctype="multipart/form-data">
    
    <table cellpadding="15" cellspacing="0" style="width: 100%;">
        <tr style="border-bottom: 1px dashed #EEEEEE;">
            <td class="form-title">[[.import_member_for_excel.]]</td>
            <td style="text-align: right;">
                <?php if(isset([[=list_payment=]]) AND sizeof([[=list_payment=]])>0){ ?>
                <?php if(User::can_add(false,ANY_CATEGORY)){ ?><input name="save" type="submit" onclick="return check_period();" id="save" value="[[.save_and_stay.]]" style="padding: 10px;" /> <?php } ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;border-right: 1px dashed #EEEEEE;;">
                <?php if(!isset([[=error_payment=]]) OR !isset([[=list_payment=]])){ ?>
                <span style="color: red; font-weight: bold; text-transform: uppercase;">Chú ý:</span> Download file excel mẫu và làm theo hướng dẫn bên dưới.<br /><br /><br />
                <a href="packages/hotel/packages/reception/modules/Traveller/file_import_member.rar" style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]</a><br /><br /><br />
                <b>Định dạng trường dữ liệu</b>
                <p>Cột <strong>A</strong>: Nhập Họ (tên đệm) của thành viên <span style="color: red;">(*)</span></p>
                <p>Cột <strong>B</strong>: Nhập Tên của thành viên <span style="color: red;">(*)</span></p>
                <p>Cột <strong>C</strong>: Nhập Giới tính của thành viên (1 hoặc 2): 1 là nam, 2 là nữ <span style="color: red;">(*)</span></p>
                <p>Cột <strong>D</strong>: Nhập ngày / tháng / năm sinh (định dạng dd/mm/YYYY)</p>
                <p>Cột <strong>E</strong>: Nhập hộ chiếu hoặc Chứng minh nhân dân của thành viên</p>
                <p>Cột <strong>F</strong>: Nhập địa chỉ của thành viên</p>
                <p>Cột <strong>G</strong>: Nhập Số điện thoại của thành viên</p>
                <p>Cột <strong>H</strong>: Nhập Email của thành viên</p>
                <p>Cột <strong>I</strong>: Nhập Mã của khách hàng <span style="color: red;">(*)</span></p>
                <p>Cột <strong>J</strong>: Nhập Mã từ <span style="color: red;">(*)</span></p>
                <p>Cột <strong>K</strong>: Nhập Mã nhóm khách hàng (vào module khai báo nhóm thành viên, cột ID chính là mã loại khách hàng) <a target="_blank" href="?page=group_traveller">[link]</a>  <span style="color: red;">(*)</span></p>
                <p>Cột <strong>L</strong>: Nhập ngày tạo mã thành viên </p>
                <p>Cột <strong>M</strong>: Mã của hạng thẻ (Tên viết tắt của hạng thẻ)  <a target="_blank" href="?page=member_level">[link]</a>  <span style="color: red;">(*)</span></p>
                <p>Cột <strong>N</strong>: Điểm tích lũy (Điểm dùng để xét hạng thành viên) </p>
                <p>Cột <strong>O</strong>: Điểm sử dụng (Điểm để quy đổi ra tiền sử dụng trong các dịch vụ) </p>
                <p>Cột <strong>P</strong>: ngày phát hành thẻ thành viên (định dạng dd/mm/YYYY) <span style="color: red;">(*)</span></p>
                <p>Cột <strong>Q</strong>: Mã thành viên của thẻ chính ( phân biệt thẻ chính phụ, nếu là thẻ chính thì để trống còn nếu là thẻ phụ thì nhập mã của thẻ cha) </p>
                <p>Cột <strong>R</strong>: ngày có hiệu lực thẻ thành viên (định dạng dd/mm/YYYY) <span style="color: red;">(*)</span></p>
                <p>Cột <strong>S</strong>: ngày hết hạn thẻ thành viên (định dạng dd/mm/YYYY) </p>
                <p>Cột <strong>T</strong>: Mã Lô </p>
                <p style="font-weight: bold; text-transform: uppercase; padding: 0px;"><span style="color: red;">(*): là bắt buộc phải nhập</span></p>
                <p style="font-weight: bold; text-transform: uppercase; padding: 0px;"><span style="color: red;">Mặc định trạng thái thành viên khi import bằng excel là đang hoạt động</span></p>
                <?php } ?>
            </td>
            <td style="vertical-align: top;">
                <fieldset>
                    <legend style="text-transform: uppercase;">[[.upload_file_import.]]</legend>
                    <?php if(User::can_add(false,ANY_CATEGORY)){ ?>
                    <input id="path" name="path" type="file" value="[[.file.]]" style="float: left; padding: 10px;" onclick="fun_select_file();" />
                    <input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px; float: right; padding: 10px;"/>
                    <?php } ?>
                </fieldset>
            </td>
        </tr>
    </table>
    
    
    <?php if(isset([[=error_payment=]]) AND sizeof([[=error_payment=]])>0){ ?>
        <fieldset><!-- danh sách file bị lỗi -->
            <legend>[[.list_error.]]</legend>
            <table class="lisst" cellpadding="10" cellspacing="0" border="1" bordercolor="#CCCCCC" style="width: 110%;">
                <tr style="text-align: center; text-transform: uppercase; background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1));">
                    <th>[[.first_name.]]</th>
                    <th>[[.last_name.]]</th>
                    <th>[[.gender.]]</th>
                    <th>[[.birthdate.]]</th>
                    <th>[[.passport.]]</th>
                    <th>[[.address.]]</th>
                    <th>[[.phone_number.]]</th>
                    <th>[[.email.]]</th>
                    <th>[[.traveller_code.]]</th>
                    <th>[[.member_code.]]</th>
                    <th>[[.group_traveller.]]</th>
                    <th>[[.create_member_date.]]</th>
                    <th>[[.member_level.]]</th>
                    <th>[[.point.]]</th>
                    <th>[[.point_user.]]</th>
                    <th>[[.releases_date.]]</th>
                    <th>[[.is_parent.]]</th>
                    <th>[[.effective_date.]]</th>
                    <th>[[.expiration_date.]]</th>
                    <th>[[.plot_code.]]</th>
                    <th style="width: 200px;">[[.error.]]</th>
                </tr>
                <!--LIST:error_payment-->
                <tr style="text-align: center;">
                    <td>[[|error_payment.A|]]</td>
                    <td>[[|error_payment.B|]]</td>
                    <td>[[|error_payment.C|]]</td>
                    <td>[[|error_payment.D|]]</td>
                    <td>[[|error_payment.E|]]</td>
                    <td>[[|error_payment.F|]]</td>
                    <td>[[|error_payment.G|]]</td>
                    <td>[[|error_payment.H|]]</td>
                    <td>[[|error_payment.I|]]</td>
                    <td>[[|error_payment.J|]]</td>
                    <td>[[|error_payment.K|]]</td>
                    <td>[[|error_payment.L|]]</td>
                    <td>[[|error_payment.M|]]</td>
                    <td>[[|error_payment.N|]]</td>
                    <td>[[|error_payment.O|]]</td>
                    <td>[[|error_payment.P|]]</td>
                    <td>[[|error_payment.Q|]]</td>
                    <td>[[|error_payment.R|]]</td>
                    <td>[[|error_payment.S|]]</td>
                    <td>[[|error_payment.T|]]</td>
                    <td>[[|error_payment.error|]]</td>
                </tr>
                <!--/LIST:error_payment-->
            </table>
        </fieldset>
    <?php } ?>
    
    <?php if(isset([[=list_payment=]]) AND sizeof([[=list_payment=]])>0){ ?>
        <fieldset><!-- danh sách file khả dụng -->
            <legend>[[.list_confirm.]]</legend>
            <table class="lisst" cellpadding="10" cellspacing="0" border="1" bordercolor="#CCCCCC" style="width: 100%;">
                <tr style="text-align: center; text-transform: uppercase;background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1));">
                    <th>[[.first_name.]]</th>
                    <th>[[.last_name.]]</th>
                    <th>[[.gender.]]</th>
                    <th>[[.birthdate.]]</th>
                    <th>[[.passport.]]</th>
                    <th>[[.address.]]</th>
                    <th>[[.phone_number.]]</th>
                    <th>[[.email.]]</th>
                    <th>[[.traveller_code.]]</th>
                    <th>[[.member_code.]]</th>
                    <th>[[.group_traveller.]]</th>
                    <th>[[.create_member_date.]]</th>
                    <th>[[.member_level.]]</th>
                    <th>[[.point.]]</th>
                    <th>[[.point_user.]]</th>
                    <th>[[.releases_date.]]</th>
                    <th>[[.is_parent.]]</th>
                    <th>[[.effective_date.]]</th>
                    <th>[[.expiration_date.]]</th>
                    <th>[[.plot_code.]]</th>
                </tr>
                <!--LIST:list_payment-->
                <tr style="text-align: center;">
                    <td>[[|list_payment.A|]]<input style="width: 80px; display: none;" name="payment[[[|list_payment.id|]]][first_name]" type="text" value="[[|list_payment.A|]]" /></td>
                    <td>[[|list_payment.B|]]<input style="width: 80px; display: none;" name="payment[[[|list_payment.id|]]][last_name]" type="text" value="[[|list_payment.B|]]" /></td>
                    <td>[[|list_payment.C|]]<input style="width: 80px; display: none;" name="payment[[[|list_payment.id|]]][gender]" type="text" value="[[|list_payment.C|]]" /></td>
                    <td>[[|list_payment.D|]]<input style="width: 80px; display: none;" name="payment[[[|list_payment.id|]]][birth_date]" type="text" value="[[|list_payment.D|]]" /></td>
                    <td>[[|list_payment.E|]]<input style="width: 50px; display: none;" name="payment[[[|list_payment.id|]]][passport]" type="text" value="[[|list_payment.E|]]" /></td>
                    <td>[[|list_payment.F|]]<input style="width: 40px; display: none;" name="payment[[[|list_payment.id|]]][address]" type="text" value="[[|list_payment.F|]]" /></td>
                    <td>[[|list_payment.G|]]<input style="width: 80px; display: none;" name="payment[[[|list_payment.id|]]][phone]" type="text" value="[[|list_payment.G|]]" /></td>
                    <td>[[|list_payment.H|]]<input style="width: 50px; display: none;" name="payment[[[|list_payment.id|]]][email]" type="text" value="[[|list_payment.H|]]" /></td>
                    <td>[[|list_payment.I|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][traveller_code]" type="text" value="[[|list_payment.I|]]" /></td>
                    <td>[[|list_payment.J|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][member_code]" type="text" value="[[|list_payment.J|]]" /></td>
                    <td>[[|list_payment.K|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][group_traveller_id]" type="text" value="[[|list_payment.K|]]" /></td>
                    <td>[[|list_payment.L|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][member_create_date]" type="text" value="[[|list_payment.L|]]" /></td>
                    <td>[[|list_payment.M|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][member_level_id]" type="text" value="[[|list_payment.M|]]" /></td>
                    <td>[[|list_payment.N|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][point]" type="text" value="[[|list_payment.N|]]" /></td>
                    <td>[[|list_payment.O|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][point_user]" type="text" value="[[|list_payment.O|]]" /></td>
                    <td>[[|list_payment.P|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][releases_date]" type="text" value="[[|list_payment.P|]]" /></td>
                    <td>[[|list_payment.Q|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][is_parent_id]" type="text" value="[[|list_payment.Q|]]" /></td>
                    <td>[[|list_payment.R|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][effective_date]" type="text" value="[[|list_payment.R|]]" /></td>
                    <td>[[|list_payment.S|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][expiration_date]" type="text" value="[[|list_payment.S|]]" /></td>
                    <td>[[|list_payment.T|]]<input style="width: 30px; display: none;" name="payment[[[|list_payment.id|]]][plot_code]" type="text" value="[[|list_payment.T|]]" /></td>
                </tr>
                <!--/LIST:list_payment-->
            </table>
        </fieldset>
    <?php } ?>
</form>
<script>
    <?php if(isset([[=list_payment=]]) AND sizeof([[=list_payment=]])>0){ echo 'var list_payment_js='.String::array2js([[=list_payment=]]).';'; } ?>
    //console.log(list_payment_js);
    jQuery(document).ready(function(){
        jQuery('#do_upload').click(function(){
            if(!jQuery('#path').val())
            {
                alert('[[.you_must_choose_file.]]')
                return false;
            }
            
        })
    })
    function check_period()
    {
        var check=true; var ids = '';
        for(var i in list_payment_js)
        {
            if(jQuery("#period_id_"+list_payment_js[i]['id']).val()=='')
            {
                check=false; ids += list_payment_js[i]['id']+",";
                jQuery("#period_id_"+list_payment_js[i]['id']).css('background','red');
            }
        }
        if(check==false)
            alert("Các hàng "+ids+" Chưa chọn kì hạn thanh toán!");
        return check;
    }
    function fun_select_period(id)
    {
        if(jQuery("#period_id_"+id).val()!='')
            jQuery("#period_id_"+id).css('background','#FFFFFF');
        else
            jQuery("#period_id_"+id).css('background','red');
    }
</script>
