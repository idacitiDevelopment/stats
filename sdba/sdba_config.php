<?php

class Sdba_config
{
    public static $dbname = 'idaciti'; // Your database name
    public static $dbuser = 'root'; // Your database username
    public static $dbpass = 'root'; // // Your database password
    public static $dbhost = 'localhost'; // Your database host, 'localhost' is default.
    public static $dbencoding = 'utf8'; // Your database encoding, default is 'utf8'. Do not change, if not sure.
    
    public static $autoreset = true; // Auto-resets conditions when you try to set new (after some db action, true is recommended);
}