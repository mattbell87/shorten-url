<?php

class URLShorten
{
    private $options;
    private $db;

    function __construct($options = array())
    {
        //Default options
        $this->options = array_merge( array
        (
            'urlprefix' => '',
            'blacklist' => array(),
            'whitelist' => array('.*'), //allow all by default
            'db' => 'sqlite:urls.db',
            'dbuser' => null,
            'dbpw' => null,
        ), $options);

        $this->connectDB();
    }

    //Store a URL in the database
    function store($url)
    {
        $sql = $this->db->prepare
        (
            "SELECT * FROM shorturls WHERE url=?"
        );
        $sql->bindValue(1, $url);
        $sql->execute();

        $results = $sql->fetch(PDO::FETCH_ASSOC);
        if ($results)
        {
            //If the url exists return the existing short url
            return  $this->getShortURL($results['id']);
        }
        else
        {
            //Insert the new record and generate url from the automatic ID
            if ($this->checkURL($url))
            {
                $sql = $this->db->prepare
                (
                    "INSERT INTO shorturls (`url`) VALUES (?)"
                );
                $sql->bindValue(1, $url);
                $sql->execute();

                //Return the ShortURL to the browser
                return $this->getShortURL($this->db->lastInsertId());
            }
            //If the URL is not allowed to be stored return a 403 page
            else
            {
                header("HTTP/1.0 403 Forbidden");
                echo("The requested URL cannot be stored.");
            }
        }
    }

    //Check the blacklist and whitelist to see if the URL is allowed
    function checkURL($url)
    {
        foreach ($this->options['blacklist'] as $item)
        {
            if (preg_match('/'.$item.'/', $url))
                return false;
        }

        foreach ($this->options['whitelist'] as $item)
        {
            if (preg_match('/'.$item.'/', $url))
                return true;
        }

        return false;
    }

    //Redirect to the full url
    function forward($id)
    {
        //Convert the ID to a number
        $id = $this->baseToId($id);

        //Get the record from the database
        $sql = $this->db->prepare
        (
            "SELECT * FROM shorturls WHERE id=?"
        );
        $sql->bindValue(1, $id);
        $sql->execute();

        $results = $sql->fetch(PDO::FETCH_ASSOC);
        if ($results)
        {
            //Redirect the browser
            header('Location: '.$results['url']);
            echo('You are being redirected to: '.$results['url']);
        }
        else
        {
            //If the record wasnt found return a 404
            header("HTTP/1.0 404 Not Found");
            echo("The requested URL was not found.");
        }
    }

    //Connect to the database
    private function connectDB()
    {
        $this->db = new PDO
        (
            $this->options['db'],
            $this->options['dbuser'],
            $this->options['dbpw']
        );

        //Create the url table if it doesnt exist
        $this->db->exec //SQLite
        (
            "CREATE TABLE IF NOT EXISTS shorturls
            (
                id INTEGER PRIMARY KEY,
                url TEXT
            )"
        );
    }

    //Return a full short URL
    private function getShortURL($num)
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://";
        $path = $protocol . $_SERVER['SERVER_NAME'] . str_replace(realpath($_SERVER['DOCUMENT_ROOT']), "", getcwd());
        $path = str_replace("\\", "/", $path);
        return $path . "/" . $this->options['urlprefix'] . $this->idToBase($num);
    }

    //Define the alphabet for the URL
    private $alphabet = 'abcdefghijklmnopqrstuvwxyz';

    private function idToBase($id)
    {
        $str = "";

        //Get number of iterations
        $iters = floor($id / strlen($this->alphabet) );

        //Get the remainder of the division
        $remain = $id % strlen($this->alphabet);

        //If the number is more than the base length, call this function on the iterations left
        if ($iters > 0)
           $str .= $this->idToBase($iters);

        //Store the symbol based on the remainder
        $str .= $this->alphabet[$remain];

        return $str;
    }

    private function baseToId($str)
    {
        $num = 0;
        $multiplier = 1;

        //iterate through the string backwards
        for ($i = strlen($str) - 1; $i > -1; $i--)
        {
            //find the index of the symbol
            $bIndex = strpos($this->alphabet, $str[$i]);

            //update the result
            $num += $bIndex * $multiplier;

            //update the multiplier
            $multiplier = $multiplier * strlen($this->alphabet);
        }
        return $num;
    }
}

?>
