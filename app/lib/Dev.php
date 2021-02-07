<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    function dd($e) {
    	echo '<pre style="background: #272727; padding: 10px 15px; color: #088000; text-align: left; font-size: 13px;">';
    	    var_dump ($e);
    	echo '</pre>';
    	die ();
	}