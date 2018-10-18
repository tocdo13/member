require_once('Curl.php');

$params = array(
	'include_entities' => false,
	'include_rts' => false,
	'screen_name' => 'dfreerksen',
	//'count' => 2
);

$twitter = Curl::get('https://api.twitter.com/1/statuses/user_timeline.json', $params)
	->param('count', 2)
	->call();

//$twitter = Curl::get('https://api.twitter.com/1/statuses/user_timeline.json?include_entities=false&include_rts=false&screen_name=dfreerksen&count=2')
//->call();

var_dump( $twitter->result );
var_dump( $twitter->info );
var_dump( $twitter->error );
var_dump( $twitter->error_code );