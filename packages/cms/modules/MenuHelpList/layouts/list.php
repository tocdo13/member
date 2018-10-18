<style>
#help_list p{margin:0;padding:0}
</style>
<div class="content-bound">
<div class="help-list-title">[[.help_list_content.]]</div>
<ul id="help_list">
<!--LIST:items-->
<li>[[|items.name|]]
<!--IF:cond([[=items.childs=]])-->
<ul>
<!--LIST:items.childs-->
	<li>[[|items.childs.name|]]<!--IF:cond_attachment([[=items.childs.attachment_file=]])--> [ <a href="[[|items.childs.attachment_file|]]"><?php $pathinfo = pathinfo([[=items.childs.attachment_file=]]); echo $pathinfo['basename'];?></a>]<!--/IF:cond_attachment-->
        <!--IF:cond_child([[=items.childs.childs=]])-->
        <ul>
        <!--LIST:items.childs.childs-->
        <li>[[|items.childs.childs.name|]]<!--IF:cond_attachment([[=items.childs.childs.attachment_file=]])-->  [ <a href="[[|items.childs.childs.attachment_file|]]"><?php $pathinfo = pathinfo([[=items.childs.childs.attachment_file=]]); echo $pathinfo['basename'];?></a> ]<!--/IF:cond_attachment--><br /><div class="help-list-description">[[|items.childs.childs.description|]]</div></li>
    	<!--/LIST:items.childs.childs-->
        </ul>
        <!--/IF:cond_child-->
    </li>
<!--/LIST:items.childs-->
</ul>
<!--IF:cond-->
</li>

<!--/LIST:items-->
</ul>
<p style="color:#FF6600; font-size:18px;line-height:22px;text-align:justify;"><i><b>Note:</b> Để sử dụng thành thạo phần mềm trong thời gian ngắn nhất, đề nghị quý khách hàng đọc kỹ hướng dẫn sử dụng của từng bộ phận theo các tài liệu ở trên. Mọi vướng mắc trong quá trình sử dụng xin vui lòng liên hệ với các số điện thoại sau để được giải đáp</i></p>
<p style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FF0033;line-height:22px;">Hỗ trợ kỹ thuật:<b> Mr Khúc Ngọc Đạt</b> 01666.040.696; email: ngocdat@tcv.vn<br>Hỗ trợ nghiệp vụ:<b> Ms Đỗ Thị Bích Ngọc</b> 0968.672.665; email:sales@tcv.vn</p>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#help_list").treeview();	
})
</script>
