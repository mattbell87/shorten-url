<?php
require_once('class/urlshorten.php');

$shorten = new URLShorten( array
(
    //Options go here
    'urlprefix' => 'zz', //This should also be updated in .htaccess
    //'whitelist' => array('http:\/\/myserver\/') //only store urls that start with http://myserver/ (regex)
) );

//Handle redirects to full URLS
if (isset($_REQUEST['id']))
{
    $shorten->forward( $_REQUEST['id'] );
}

//Store a URL and return it in plain text
else if (isset($_REQUEST['store']))
{
    header('Content-Type: text/plain');
    echo $shorten->store( $_REQUEST['store'] );
}

?>
