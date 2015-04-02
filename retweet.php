<?php
/**
 * Search tweet by #hashtag and Retweet Count
 * 
 * @package  Twitter-API
 * @link https://github.com/vikram0207/twitter-api
 */
ini_set('display_errors', 1);
require_once('autoload.php');


try {


$url = 'https://api.twitter.com/1.1/search/tweets.json';
$tag = '#custserv';

/**
 * The number of tweets to return per page, up to a maximum of 100. Defaults to 15. 
 * This was formerly the “rpp” parameter in the old Search API.
 */
$count = 5;

/**
 * Optional. Specifies what type of search results you would prefer to receive. The current default is “mixed.” Valid values include:
  * mixed: Include both popular and real time results in the response.
  * recent: return only the most recent results in the response
  * popular: return only the most popular results in the response.
    Example Values: mixed, recent, popular
 */
$resultType = 'recent';


$getfield = '?q='.$tag.'&count='.$count.'&result_type='.$resultType;
$requestMethod = 'GET';



$twitter = new TwitterApi($url);
$twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->request();
$response = $twitter->getOnlyRetweet();
//echo '<pre>';print_r($response);

$strHTML =  '<html><head><body>';
$strHTML .= '<table><tr><td>URL :</td><td>'.$url.$getfield.'</td></tr>'
        . '<tr><td>Search :</td><td>'.$tag.'</td></tr></table>';
$strHTML .= '<table border=2><tr><td>Id</td><td>Tweet</td><td>Retweet Count</td></tr>';
foreach ($response as $tweet) {
    $strHTML.='<tr>'
            . '<td>'.$tweet->id.'</td>'
            . '<td>'.$tweet->text.'</td>'
            . '<td>'.$tweet->retweet_count.'</td></tr>';
}


$strHTML .='</table></body></html>';

echo $strHTML;

} catch (Exception $e) {
    //echo '<pre>';print_r($e);
    echo 'Exception :' . $e->getMessage();
}

