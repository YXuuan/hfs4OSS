<?php

function check_var($var, $default = ''){
	return (isset($var) && $var !== "");
    //return((isset($var) and !empty($var)) ? $var : (!empty($default) ? $default : null));
}