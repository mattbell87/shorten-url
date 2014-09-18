<?php
/*
 *  URL Shortener - Example script
 *  ------------------------------
 *  This serves as an example usage script for the URL shortener. It provides
 *  the basic functionality for the URL Shortener class as well as a basic
 *  form to generate the URLs.
 */


require_once('class/urlshorten.php');

//Create a new URL Shortener
$shorten = new URLShorten( array
(
    //Options go here

    //This should also be updated in .htaccess
    'urlprefix' => 'zz',

    //only store urls that start with http://myserver/ (regex)
    //'whitelist' => array('http:\/\/myserver\/')
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

//Show a form
else
{?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>URL Shortener</title>
        </head>
        <body>
            <h1>URL Shortener</h1>
            <form method="get" action="">
                <p>Enter your URL here:</p>
                <p><input type="text" name="store" size="50" /><input type="submit" value="Generate" /></p>
            </form>
        </body>
    </html>
<?php
}?>
