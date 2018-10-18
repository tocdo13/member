/**
 * http module, chứa các functions cần thiết cho server và client làm việc với nhau
 */
var http = require('http');
/**
 * File system module, chứa các functions liên quan tới filesystem - làm việc với file
 */
var fs  = require('fs');
/**
 * path các file, nó cung cấp các functions liên quan tới file path để chúng ta làm việc
 */
var path = require('path');
/**
 * Cung cấp các function dể chúng ta có thể biết được file là thuộc lối nào đưa vào file extension - duôi của file
 */
var mime = require('mime');

var utf8 = require('utf8');
/**
 * Luu trữ nội dung của file
 * @type {{}}
 */
var cache = {};


var mongoClient = require('mongodb').MongoClient;

var express = require('express');
var bodyParser = require('body-parser');
var app     = express();

app.use(bodyParser.urlencoded({ extended: true })); 



app.post('/', function(req, res) {

    
    mongoClient.connect('mongodb://127.0.0.1:27017/project', function(err, db) {
        if (err) throw err;
        //use product collection
        
        var products = db.collection('log_chat');
        var data =req.body;
        data['time'] = (Math.floor(Date.now() / 1000));
        
        products.insert(data, function (err,res) {
            //neu xay ra loi
            if (err) throw err;
            //neu khong co loi
        });
        db.close();
    });
    res.sendStatus(200);
    
});



function send404(response) {
  response.writeHead(404, {'Content-Type': 'text/plain'});
  response.write('Error 404: resource not found.');
  response.end();
}

function sendFile(response, filePath, fileContents) {
  response.writeHead(
    200, 
    {"content-type": mime.lookup(path.basename(filePath))}
  );
  response.end(fileContents);
}



var server = app.listen(3000, function () {

  var host = server.address().ip
  var port = server.address().port

  console.log("Ung dung Node.js dang lang nghe tai dia chi: http:%s:%s", host, port);
  

});
var chatServer = require('./lib/chat_server');
chatServer.listen(server);


app.set('views', __dirname + '/public');
app.engine('html', require('ejs').renderFile);
app.set('view engine', 'html');

app.get('/getUser', function(req, res, next) {
  var conversion;
    mongoClient.connect('mongodb://127.0.0.1:27017/project', function (err, db) {
        if (err) throw err;
          
          db.collection('user').find({user_id: req.query.user_id}).count()
            .then(function(numItems) {
              if(numItems==0)
              {                
                   var data = {};
                   data['user_id'] = req.query.user_id;
                   data['nickname'] = req.query.nickname;                                      
                                      
                   db.collection("user").insert(data, function (err,res) {
                        if (err) throw err;
                    });
               
              //db.close();                              
              }
            
             db.collection("user").find({}).toArray(function(err, result) {
                if (err) throw err;
                  res.jsonp(result);
                //db.close();
              });        
        });
    //    
        //res.sendStatus(200);             
    }); 
});

app.get('/getMsg', function(req, res, next) {
  //var conversion;
    mongoClient.connect('mongodb://127.0.0.1:27017/project', function (err, db) {
        if (err) throw err;
        db.collection("log_chat").find({
              $or: [
                 {user_id: req.query.user_id,to_user:req.query.to_user}, {user_id: req.query.to_user,to_user:req.query.user_id}
              ]
           }).skip(parseInt(req.query.numberScroll)).limit(100).sort( { time: 1 } ).toArray(function(err, result) {
              if (err) throw err;
              //console.log(result);
              res.jsonp(result);
              db.close();
        });   
        
        //res.sendStatus(200);             
    });

  
});

app.use(express.static('public'));
