<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lolkittens" />

	<title>Untitled 16</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="http://getbootstrap.com/assets/css/docs.min.css" rel="stylesheet">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">   
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="numeral.min.js"></script>
    <style>
        *{
            margin: 0px;
            padding: 0px;
        }
        body{
            overflow-x: hidden;
            
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="row">
                <div class="container">
                    <div class="col-md-12" style="background: url('http://tcv.vn/upload/tcv/icon/1331693871_header1_.jpg') no-repeat center; height:100px;">
                    </div>
                </div>
            </div>
        </div>
        <hr style="width: 50%; margin: 20px auto; height:3px;" />
        <div class="content" style="margin-top: 30px;">
            <div class="row">
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h5 class="panel-title">Thông tin d?t phòng</h5>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-10 col-md-offset-1" style="height: auto;">
                                <fieldset>
                                    <legend>Thông tin cá nhân</legend>
                                    <div>
                                        <div class="form-group col-md-4 col-md-offset-1">
                                            <label for="customer_name">H? và tên : </label>
                                            <input class="form-control" name="customer_name" id="customer_name" type="text" />
                                        </div>
                                        <div class="form-group col-md-4 col-md-offset-1">
                                            <label for="phone_number">S? di?n tho?i : </label>
                                            <input class="form-control" name="phone_number" id="phone_number" type="text" />
                                        </div>
                                        <div class="form-group col-md-4 col-md-offset-1">
                                            <label for="email">Email : </label>
                                            <input class="form-control" name="email" id="email" type="email" />
                                        </div>
                                        <div class="form-group col-md-4 col-md-offset-1">
                                            <label for="tax_code">Mã s? thu? : </label>
                                            <input class="form-control" name="tax_code" id="tax_code" type="text" />
                                        </div>
                                        <div class="form-group col-md-6 col-md-offset-1">
                                            <label for="address">Ð?a ch? : </label>
                                            <textarea class="form-control" name="address" id="address" style="resize: none;"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div  id="result" class="col-md-10 col-md-offset-1" style="height: auto; margin-top: 20px; display: none;">
                                <table class="table table-hover">
                                    <thead>
                                        <th>H?ng phòng</th>
                                        <th style="width:150px;">Giá VNÐ</th>
                                        <th class="col-md-1">Giá USD</th>
                                        <th style="width:100px;">SL tr?ng</th>
                                        <th class="col-md-1">Ng.L</th>
                                        <th class="col-md-1">Tr.E</th>
                                        <th class="col-md-1">SL d?t</th>
                                    </thead>
                                    <tbody id="search_result">
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                        <div class="panel-footer">
                               <div class="col-md-offset-10">
                                    <button type="button" class="btn btn-success" onclick="if(confirm('B?n có ch?c ch?n không?')) reservation();">Ð?t phòng</button>
                               </div> 
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
        <div class="footer"></div>
    </div>
</body>
</html>