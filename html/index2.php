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
?>
<?php
  $response = $twitter->search->tweets('#saints');
  $responses = $response->toValue()->statuses;
  //$tweets = array();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Colors of New Orleans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/assets/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet/less" type="text/css" href="/css/style.less" />

    <script src="/javascript/less.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/bootstrap/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/bootstrap/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/bootstrap/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="/assets/bootstrap/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="/assets/bootstrap/ico/favicon.png">
  </head>

  <body>

    <div class="container">
      <div class='row'>

        <img src="/assets/img/banner.png" />

      </div>
    </div>

    <div class="container">
      <div class="row">
        <ul class='nav nav-tabs'>
          <li><a href='/index2.php?c=home'>Home</a>
            <li><a href='/index2.php?c=sports'>Sports</a>
            <li><a href='/index2.php?c=music'>Music</a>
            <li><a href='/index2.php?c=festivals'>Festivals</a>
            <li><a href='/index2.php?c=community'>Community</a>
        </li>
      </ul>
    </div>

    <div class="container">
      <div class="row">

        <div class="tweets">
          <?php foreach ($responses as $index => $tweet) { ?>

      <blockquote class="tweet">
          <a href="https://twitter.com/{{account}}">
            <img src="<?php echo $tweet->user->profile_image_url ?>">
            <span class="full-name"><?echo $tweet->user->name ?></span>
            <span class="account-name"><?echo $tweet->user->screename ?></span>
          </a>
        </div>
          <p class="message"><?php echo $tweet->text ?></p>
          <div class="date"><?php echo date('m/d/Y H:i:s', strtotime($tweet->user->created_at)) ?></div>
          <div>
            <ul class="tweet-actions">
              <li><a href="https://twitter.com/intent/tweet?in_reply_to={{id}}" title="Reply">Reply</a></li>
              <li><a href="https://twitter.com/intent/retweet?tweet_id={{id}}" title="Retweet">Retweet</a></li>
              <li><a href="https://twitter.com/intent/favorite?tweet_id={{id}}" title="Favorite">Favorite</a></li>
            </ul>
          </div>
      </blockquote>
      <?php } ?>

        </div>

        <hr>

        <div class="footer">
          <p>&copy; Company 2013</p>
        </div>

      </div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/bootstrap/js/jquery.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/javascript/scripts.js"></script>

  </body>
</html>
