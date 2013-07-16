<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config.php';

$app = array();

foreach ($config as $key => $value)
{
	$app[$key] = $value;
}

//Load database stuff
//require_once __DIR__ . '/../db.php';

$twitter =  new ZendService\Twitter\Twitter(array(
    'accessToken' => array(
      'token' => $app['twitter_access_token'],
      'secret' => $app['twitter_access_secret'],
    ),
    'oauth_options' => array(
        'username' => $app['twitter_username'],
        'consumerKey' => $app['twitter_consumerkey'],
        'consumerSecret' => $app['twitter_consumersecret'],
    ),
    'http_client_options' => array(
	'adapter' => '\Zend\Http\Client\Adapter\Curl',
    ),

  ));


  $tags = array(
    'home' => '#nola',
    'food' => '#nolafood',
    'sports' => '#nolasaints',
    'festivals' => '#mardigras',
    'music' => '#nolamusic',
    );

  if(isset($_GET['c']))
    $param = $_GET['c'];
  else
    $param = 'home';

  if (isset($tags[$param]))
    $category = $tags[$param];
  else
    $category = $tags['home'];


  $response = $twitter->search->tweets($category);
  $responses = $response->toValue()->statuses;
  //$tweets = array();


function find_category($hashtags){

  $tags = array(
    'home' => 'nola',
    'food' => 'nolafood',
    'sports' => 'nolasaints',
    'festivals' => 'mardigras',
    'music' => 'nolamusic',
  );

  $haystack = array();
  foreach($hashtags as $hashtag){
    $haystack[] = $hashtag->text;

  }

  foreach($tags as $key => $tag){
    if(in_array($tag,$haystack)){
      return $key;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Colors of New Orleans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="/css/screen.css">


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <!--<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/apple-touch-icon-144-precomposed.png">-->
    <!--<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/apple-touch-icon-114-precomposed.png">-->
    <!--<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/apple-touch-icon-72-precomposed.png">-->
    <!--<link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon-57-precomposed.png">-->
    <!--<link rel="shortcut icon" href="/images/favicon.png">-->
  </head>

  <body class="page-<?php echo $param ?>">
    <div id="page"><div class='limiter'>

    <div id="header">
      <a href='/'><img src='/img/logo.png' /></a>
    </div>

    <div id="menu">
      <ul>
        <li><a class='home' href='/index.php?c=home'>All</a></li>
        <li><a class='food' href='/index.php?c=food'>Food</a></li>
        <li><a class='sports' href='/index.php?c=sports'>Sports</a></li>
        <li><a class='music' href='/index.php?c=music'>Music</a></li>
        <li><a class='festivals' href='/index.php?c=festivals'>Festivals</a></li>
        <li><a class='community' href='/index.php?c=community'>Community</a></li>
      </ul>
    </div>


    <div id="tweets">

      <?php foreach ($responses as $index => $tweet): ?>

      <div class="tweet <?php print find_category($tweet->entities->hashtags); ?>">
        <a href="https://twitter.com/{{account}}">
          <img class='pic' src="<?php echo $tweet->user->profile_image_url ?>">
          <span class="full-name"><?php echo $tweet->user->name ?></span>
          <span class="account-name"><?php echo $tweet->user->screen_name ?></span>
        </a>
        <p class="message"><?php echo $tweet->text ?></p>
        <div class="date"><?php echo date('m/d/Y H:i:s', strtotime($tweet->user->created_at)) ?></div>
        <ul class="tweet-actions">
          <li><a href="https://twitter.com/intent/tweet?in_reply_to=<? echo $tweet->id ?>" title="Reply">Reply</a></li>
          <li><a href="https://twitter.com/intent/retweet?tweet_id=<? echo $tweet->id ?>" title="Retweet">Retweet</a></li>
          <li><a href="https://twitter.com/intent/favorite?tweet_id=<? echo $tweet->id ?>" title="Favorite">Favorite</a></li>
        </ul>
      </div>

      <?php endforeach; ?>

    </div>
    <div id="footer">
      <p>&copy; Company 2013</p>
    </div>

    </div></div>

    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="/js/scripts.js"></script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php print $app['google_analytics_id']; ?>', 'colorsofneworleans.com');
      ga('send', 'pageview');

    </script>
  </body>
</html>
