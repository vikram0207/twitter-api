# twitter-api



How To Use
------

#### Set access tokens in lib/config.php ####


    const ACCESS_TOKEN = "___ACCESS__TOKEN___";
    const ACCESS_TOKEN_SECRET = "___ACCESS__TOKEN__SECRET___";
    const CONSUMER_KEY = "___CONSUMER__KEY___";
    const CONSUMER_SECRET = "___CONSUMER__SECRET___";


#### Include autoloader  ####

    require_once('autoload.php');


#### Choose URL and Request Method ####

    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $requestMethod = 'GET';


#### Perform the request! ####
    $twitter = new TwitterApi($url);
    echo $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->request()
    ->getResponse();
