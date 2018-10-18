<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script language="javascript" src="http://code.jquery.com/jquery-2.0.0.min.js"></script>
    </head>
    <body>
      <h1>Ví dụ 1</h1>
      <strong>Sở thích của bạn là gì? </strong> <br/>
      <input type="checkbox" id="an" name="sothich" value="1"/> Ăn <br/>
       
      <input type="button" id="view1" value="Xem Name và Type"/>
      <input type="button" id="view2" value="Đổi type thành textbox"/>
      <input type="button" id="view3" value="Đổi type thành checkbox"/>
       
      <script language="javascript">
         
          // Bắt đầu code jquery
          $(document).ready(function(){
             
              // Khi click vào button có id = view1
              $('#view1').click(function(){
 
                  // Lấy tên của checkbox có id là an
                  var name = $('#an').attr('name');
 
                  // lấy type của checkbox
                  var type = $('#an').attr('type');
                 
                  alert('Name là ' + name + ' và type là ' + type);
 
              });
             
                 
              // Khi click vào button có id = view2
              $('#view2').click(function(){
                  // Thay đổi kiểu thành textbox
                  $('#an').attr('type', 'textbox');
                 
              });
             
             
              // Khi click vào button có id = view3
              $('#view3').click(function(){
                  // Thay đổi kiểu thành radio
                  $('#an').attr('type', 'radio');
                 
              });
             
          });
         
      </script>
       
    </body>
</html>