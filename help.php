<?php
     define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    $link = "http://192.168.1.4:8082/demo88/";
?>
<style>
    .container{
        background: #ffffff;
    }
    .container:hover{
        background: #eeeeee;
    }
    .container h3{
        padding-left: 10px;
    }
    .container h5{
        padding-left: 25px;
    }
    .container p{
        padding-left: 50px;
    }
</style>
<div style="width: 100%; padding-top: 10px;">   
    <div style="width: 960px; height: auto; background: #ffffff; margin: 0px auto; border: 1px double #00b2f9;">
        <h1 style="width: 100%; text-align: center; border-bottom: 1px dashed #dddddd; line-height: 50px;">API TÍCH HỢP WEBSITE</h1>
        <div id="container_1" class="container" style="width: 100%; border-bottom: 1px dashed #dddddd; cursor: pointer;" >
            <h3 onclick="fun_toggle(1);">* API xác thực khách hàng: xác thực tài khoản và trả về thông tin của khách</h3>
            <div id="content_1">
                <h5>+ ĐƯỜNG DẪN: <?php echo $link; ?>api.php?cmd=validation_member&account=xxx&password=xxx</h5>
                <h5>+ THAM SỐ:</h5>
                    <p>- <b>cmd=validation_member</b>: Tham số <b>cmd</b> truyền vào tham biến <b>validation_member</b> để điều hướng đến xử lý xác thực khách hàng.</p>
                    <p>- <b>account=xxx</b>: tham số <b>account</b> tham biến <b>xxx</b> truyền vào là mã khách hàng.</p>
                    <p>- <b>password=xxx</b>: tham số <b>password</b> tham biến <b>xxx</b> truyền vào là mật khẩu của khách hàng.</p>
                <h5>+ KẾT QUẢ TRẢ VỀ:</h5>
                    <p>- Kết quả trả về trong mảng <b>[refund]</b></p>
                    <p>- Mảng <b>[refund]</b> có 3 phần tử: <b>['login']</b>,<b>['title']</b>,<b>['detail']</b></p>
                    <p>- <b>[fefund]['login']</b>: trả về giá trị <b>true</b> hoặc <b>false</b> : <b>true - </b> đăng nhập thành công. <b>false - </b> đăng nhập thất bại</p>
                    <p>- <b>[fefund]['title']</b>: trả về thông báo ứng với trạng thái của <b>[fefund]['login']</b></p>
                    <p>- <b>[fefund]['detail']</b>: trả về một mảng array() trong đó chứa thông tin của khách và mảng <b>[fefund]['detail']['member_discount']</b> chứa chương trình giảm giá dành cho thành viên đó trong trường hợp <b>[fefund]['login']</b> là <b>true</b>. ngược lại đăng nhập thất bại, <b>[fefund]['login']</b> là <b>false</b> sẽ trả về một mảng rỗng</p>
                <h5>+ Ví Dụ:</h5>
                    <p>- trong database có một tài khoản là: <b>account</b>: 201400005, <b>password</b>: 12345678.</p>
                    <p>- khi gửi xác thực tài khoảng <b><?php echo $link; ?>api.php?cmd=validation_member&account=201400005&password=12345678</b></p>
                    <p>- kết quả sẽ nhận được là:<br />
                        <b>[fefund]['login'] => true</b><br />
                        <b>[fefund]['title'] => 'login is success'</b><br />
                        <b>[fefund]['detail'] => array([id]=>10,[first_name]=>Nguyễn,[last_name]=>Mạnh,...,[member_discount]=>array()))</b>
                    </p>
                    <hr style="width: 50%;" />
                    <p>- Nếu gửi xác thực tài khoản <b><?php echo $link; ?>api.php?cmd=validation_member&account=201400005&password=123</b> </p>
                    <p>- kết quả sẽ nhận được là:<br />
                        <b>[fefund]['login'] => false</b><br />
                        <b>[fefund]['title'] => 'is not password'</b><br />
                        <b>[fefund]['detail'] => array()</b>
                    </p>
            </div><!-- end content_1 -->     
        </div><!-- end container_1 -->
        
        <div id="container_2" class="container" style="width: 100%; border-bottom: 1px dashed #dddddd; cursor: pointer;" >
            <h3 onclick="fun_toggle(2);">* API Đổi mật khẩu: đổi mật khẩu cho khách hàng</h3>
            <div id="content_2">
                <h5>+ ĐƯỜNG DẪN: <?php echo $link; ?>api.php?cmd=change_password&account=xxx&password=xxx&password_new=xxx</h5>
                <h5>+ THAM SỐ:</h5>
                    <p>- <b>cmd=change_password</b>: Tham số <b>cmd</b> truyền vào tham biến <b>change_password</b> để điều hướng đến xử lý đổi mật khẩu.</p>
                    <p>- <b>account=xxx</b>: tham số <b>account</b> tham biến <b>xxx</b> truyền vào là mã khách hàng.</p>
                    <p>- <b>password=xxx</b>: tham số <b>password</b> tham biến <b>xxx</b> truyền vào là mật khẩu cũ của khách hàng.</p>
                    <p>- <b>password_new=xxx</b>: tham số <b>password_new</b> tham biến <b>xxx</b> truyền vào là mật khẩu mới của khách hàng.</p>
                <h5>+ KẾT QUẢ TRẢ VỀ:</h5>
                    <p>- Kết quả trả về trong mảng <b>[refund]</b></p>
                    <p>- Mảng <b>[refund]</b> có 3 phần tử: <b>['login']</b>,<b>['title']</b>,<b>['change']</b></p>
                    <p>- <b>[fefund]['login']</b>: trả về giá trị <b>true</b> hoặc <b>false</b> : <b>true - </b> đăng nhập thành công. <b>false - </b> đăng nhập thất bại</p>
                    <p>- <b>[fefund]['title']</b>: trả về thông báo ứng với trạng thái của <b>[fefund]['login']</b></p>
                    <p>- <b>[fefund]['detail']</b>: trả về giá trị <b>true</b> hoặc <b>false</b> : <b>true - </b> Đổi mật khẩu thành công. <b>false - </b> đổi mật khẩu thất bại</p>
                <h5>+ Ví Dụ:</h5>
                    <p>- trong database có một tài khoản là: <b>account</b>: 201400005, <b>password</b>: 12345678.</p>
                    <p>- khi gửi đổi mật khẩu <b><?php echo $link; ?>api.php?cmd=change_password&account=2014000005&password=12345678&password_new=1234</b></p>
                    <p>- kết quả sẽ nhận được là:<br />
                        <b>[fefund]['login'] => true</b><br />
                        <b>[fefund]['title'] => 'change password is success'</b><br />
                        <b>[fefund]['change'] => true</b>
                    </p>
                    <hr style="width: 50%;" />
                    <p>- Nếu gửi đổi mật khẩu <b><?php echo $link; ?>api.php?cmd=change_password&account=2014000005&password=12345&password_new=1234</b> </p>
                    <p>- kết quả sẽ nhận được là:<br />
                        <b>[fefund]['login'] => false</b><br />
                        <b>[fefund]['title'] => 'is not password'</b><br />
                        <b>[fefund]['detail'] => false</b>
                    </p>
            </div><!-- end content_2 -->     
        </div><!-- end container_2 -->
        
        
        <div id="container_3" class="container" style="width: 100%; border-bottom: 1px dashed #dddddd; cursor: pointer;" >
            <h3 onclick="fun_toggle(3);">* API Lấy lại mật khẩu cho khách hàng</h3>
            <div id="content_3">
                <h5>+ ĐƯỜNG DẪN: <?php echo $link; ?>api.php?cmd=forgot_password&account=xxx</h5>
                <h5>+ THAM SỐ:</h5>
                    <p>- <b>cmd=forgot_password</b>: Tham số <b>cmd</b> truyền vào tham biến <b>forgot_password</b> để điều hướng đến xử lý lấy lại mật khẩu.</p>
                    <p>- <b>account=xxx</b>: tham số <b>account</b> tham biến <b>xxx</b> truyền vào là mã khách hàng.</p>
                <h5>+ KẾT QUẢ TRẢ VỀ:</h5>
                    <p>- Kết quả trả về là <b> true </b> Hoặc <b> false </b></p>
                <h5>+ Ví Dụ:</h5>
                    <p>- trong database có một tài khoản là: <b>account</b>: 201400005, <b> có mật khẩu là</b>: 12345678.</p>
                    <p>- khi gửi lấy lại mật khẩu <b><?php echo $link; ?>api.php?cmd=forgot_password&account=2014000005</b></p>
                    <p>- kết quả sẽ nhận được là:<b> true </b></p>
                    <hr style="width: 50%;" />
                    <p>- Nếu gửi lấy lại mật khẩu <b><?php echo $link; ?>api.php?cmd=forgot_password&account=20140000</b> </p>
                    <p>- kết quả sẽ nhận được là:<b> false </b></p>
            </div><!-- end content_3 -->     
        </div><!-- end container_3 -->
        
        
        <div id="container_4" class="container" style="width: 100%; border-bottom: 1px dashed #dddddd; cursor: pointer;" >
            <h3 onclick="fun_toggle(4);">* API Lấy lịch sử booking của khách</h3>
            <div id="content_4">
                <h5>+ ĐƯỜNG DẪN: <?php echo $link; ?>api.php?cmd=history_booking&account=xxx&password=xxx&arrival=xxx&departure=xxx</h5>
                <h5>+ THAM SỐ:</h5>
                    <p>- <b>cmd=history_booking</b>: Tham số <b>cmd</b> truyền vào tham biến <b>history_booking</b> để điều hướng đến xử lý lấy lịch sử booking của khách.</p>
                    <p>- <b>account=xxx</b>: tham số <b>account</b> tham biến <b>xxx</b> truyền vào là mã khách hàng.</p>
                    <p>- <b>password=xxx</b>: tham số <b>password</b> tham biến <b>xxx</b> truyền vào là mật khẩu.</p>
                    <p>- <b>arrival=xxx</b>: tham số <b>arrival</b> tham biến <b>xxx</b> truyền vào là ngày bắt đầu. - <span style="color: #00b2f9;">tham số không bắt buộc</span></p> 
                    <p>- <b>departure=xxx</b>: tham số <b>departure</b> tham biến <b>xxx</b> truyền vào là ngày kết thúc. - <span style="color: #00b2f9;">tham số không bắt buộc</p>
                    <p>- nếu không truyền vào ngày bắt đầu vào ngày kết thúc. kết quả trả về sẽ lấy toàn bộ booking của khách</p>
                <h5>+ KẾT QUẢ TRẢ VỀ:</h5>
                    <p>- Kết quả trả về: nếu xác thực tài khoản đúng, hàm trả về một mảng chưa các phần tử con, mỗi phần tử con lại là một lịch sử của khách được sắp xếp theo thời gian.<br />
                        Ngược lại hàm trả về một mảng rỗng nếu không thể xác thực tài khoản.
                    </p>
                <h5>+ Ví Dụ:</h5>
                    <p>- trong database có một tài khoản là: <b>account</b>: 201400005, <b> có mật khẩu là</b>: 12345678.</p>
                    <p>- khi gửi lấy lại mật khẩu <b><?php echo $link; ?>api.php?cmd=history_booking&account=201400005&password=12345678</b></p>
                    <p>- kết quả sẽ nhận được là một mảng chứa toàn bộ lịch sử của khách</p>
                    <hr style="width: 50%;" />
                    <p>- Nếu gửi lấy lại mật khẩu <b><?php echo $link; ?>api.php?cmd=history_booking&account=201400005&password=12345</b> </p>
                    <p>- kết quả sẽ nhận được là một mảng rỗng</p>
            </div><!-- end content_4 -->     
        </div><!-- end container_4 -->
        
        <div id="container_5" class="container" style="width: 100%; border-bottom: 1px dashed #dddddd; cursor: pointer;" >
            <h3 onclick="fun_toggle(5);">* API Đăng kí thành viên</h3>
            <div id="content_5">
                <h5>+ ĐƯỜNG DẪN: <?php echo $link; ?>api.php?cmd=registe_member&first_name=xxx&last_name=xxx&password=xxx&passport=xxx&email=xxx&gender=xxx&birth_date=xxx&address=xxx&phone=xxx&fax=xxx&nationality_id=xxx&note=xxx&is_vn=xxx&traveller_level_id=xxx&province_id=xxx</h5>
                <h5>+ THAM SỐ:</h5>
                    <p>- <b>cmd=registe_member</b>: Tham số <b>cmd</b> truyền vào tham biến <b>registe_member</b> để điều hướng đến xử lý đăng kí thành viên mới.</p>
                    <p>- <b>first_name=xxx</b>: tham số <b>first_name</b> tham biến <b>xxx</b> truyền vào là họ của thành viên.</p>
                    <p>- <b>last_name=xxx</b>: tham số <b>last_name</b> tham biến <b>xxx</b> truyền vào là tên của thành viên.</p>
                    <p>- <b>password=xxx</b>: tham số <b>password</b> tham biến <b>xxx</b> truyền vào là mật khẩu do thành viên tạo.</p> 
                    <p>- <b>passport=xxx</b>: tham số <b>passport</b> tham biến <b>xxx</b> truyền vào là hộ chiếu/cmnd của thành viên.</p>
                    
                    <p>- <b>email=xxx</b>: tham số <b>email</b> tham biến <b>xxx</b> truyền vào là email của thành viên.</p>
                    <p>- <b>gender=xxx</b>: tham số <b>gender</b> tham biến <b>xxx</b> truyền vào là mã giới tính của thành viên (0->nữ,1->nam).- <span style="color: #00b2f9;">tham số không bắt buộc</p>
                    <p>- <b>birth_date=xxx</b>: tham số <b>birth_date</b> tham biến <b>xxx</b> truyền vào là ngày sinh.- <span style="color: #00b2f9;">tham số không bắt buộc</p> 
                    <p>- <b>address=xxx</b>: tham số <b>address</b> tham biến <b>xxx</b> truyền vào là địa chỉ. - <span style="color: #00b2f9;">tham số không bắt buộc</p>
                    <p>- <b>phone=xxx</b>: tham số <b>phone</b> tham biến <b>xxx</b> truyền vào là số điện thoại.- <span style="color: #00b2f9;">tham số không bắt buộc</p> 
                    <p>- <b>fax=xxx</b>: tham số <b>fax</b> tham biến <b>xxx</b> truyền vào là số FAX. - <span style="color: #00b2f9;">tham số không bắt buộc</p> 
                    <p>- <b>nationality_id=xxx</b>: tham số <b>nationality_id</b> tham biến <b>xxx</b> truyền vào là mã quốc gia.<span style="color: red;"> (để lấy danh sách mã quốc gia . truy cập vào API <?php echo $link; ?>api.php?cmd=get_list&list=nationality_id ) </span> - <span style="color: #00b2f9;">tham số không bắt buộc</p> 
                    <p>- <b>note=xxx</b>: tham số <b>note</b> tham biến <b>xxx</b>  truyền vào là ghi chú của thành viên.- <span style="color: #00b2f9;">tham số không bắt buộc</p> 
                    <p>- <b>is_vn=xxx</b>: tham số <b>is_vn</b> tham biến <b>xxx</b>truyền vào là mã (0,1,2)[0=>'Alien'],[1=>'Overseas Vietnamese'],[2=>'Vietnamese']. hoặc <span style="color: red;">( để lấy danh sách mã is_vn, truy cập vào API <?php echo $link; ?>api.php?cmd=get_list&list=is_vn )</span> - <span style="color: #00b2f9;">tham số không bắt buộc</p>
                    <p>- <b>traveller_level_id=xxx</b>: tham số <b>traveller_level_id</b> tham biến <b>xxx</b> truyền vào là mã hạng khách.<span style="color: red;"> (để lấy danh sách mã hạng khách . truy cập vào API <?php echo $link; ?>api.php?cmd=get_list&list=traveller_level_id ) </span></p>
                    <p>- <b>province_id=xxx</b>: tham số <b>province_id</b> tham biến <b>xxx</b> truyền vào là mã province.<span style="color: red;"> (để lấy danh sách mã province . truy cập vào API <?php echo $link; ?>api.php?cmd=get_list&list=province_id ) </span> - <span style="color: #00b2f9;">tham số không bắt buộc</p>
                    
                <h5>+ KẾT QUẢ TRẢ VỀ:</h5>
                    <p>- Kết quả trả về là một mảng gồm các phần tử:</p>
                    <p>- <b>[status]</b>: trả về giá trị <b>true: đăng kí thành công</b> hoặc <b>false: đăng kí thất bại</b></p>
                    <p>- <b>[title]</b>: trả về thông báo ứng với <b>[status]</b></p>
                    <p>- <b>[detail]</b>: trả về một mảng. là mảng rỗng nếu đăng kí thất bại, là mảng chứa tên, mật khẩu, tên đăng nhập nếu đăng kí thành công</p>
            </div><!-- end content_5 -->     
        </div><!-- end container_5 -->
        
        
        <div id="container_6" class="container" style="width: 100%; border-bottom: 1px dashed #dddddd; cursor: pointer;" >
            <h3 onclick="fun_toggle(6);">* API thanh toán bằng điểm</h3>
            <div id="content_6">
                <h5>+ ĐƯỜNG DẪN: <?php echo $link; ?>api.php?cmd=payment_point&account=XXX&total_amount=xxx&note=xxx</h5>
                <h5>+ THAM SỐ:</h5>
                    <p>- <b>cmd=payment_point</b>: Tham số <b>cmd</b> truyền vào tham biến <b>payment_point</b> để điều hướng đến xử lý thanh toán bằng điểm của khách.</p>
                    <p>- <b>account=xxx</b>: tham số <b>account</b> tham biến <b>xxx</b> truyền vào là mã khách hàng.</p>
                    <p>- <b>total_amount=xxx</b>: tham số <b>total_amount</b> tham biến <b>xxx</b> truyền vào là số tiền thanh toán.</p>
                    <p>- <b>note=xxx</b>: tham số <b>note</b> tham biến <b>xxx</b> truyền vào là ghi chú.</p>
                <h5>+ KẾT QUẢ TRẢ VỀ:</h5>
                    <p>- Kết quả trả về là một mảng chứa các phần tử</p>
                    <p>- <b>[status]</b>: trả về <b>true</b> nếu thanh toán thành công, <b>false</b> nếu thanht oán thất bại</p>
                    <p>- <b>[title]</b>: trả về thông báo ứng với <b>[status]</b></p>
            </div><!-- end content_6 -->     
        </div><!-- end container_6 -->
        
        
    </div><!-- end #div.width:960px -->
</div><!-- end #div.width:100% -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    jQuery("#content_1").slideUp();
    jQuery("#content_2").slideUp();
    jQuery("#content_3").slideUp();
    jQuery("#content_4").slideUp();
    jQuery("#content_5").slideUp();
    jQuery("#content_6").slideUp();
    function fun_toggle(id){
        jQuery("#content_"+id).slideToggle();
    }
</script>
<?php
?>