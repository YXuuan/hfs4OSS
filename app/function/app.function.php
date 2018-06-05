<?php

function check_var($var, $default = ''){
    return (isset($var) && $var !== "");
    //return((isset($var) and !empty($var)) ? $var : (!empty($default) ? $default : null));
}

function str_replace_limit($search, $replace, $subject, $limit = -1){
    // constructing mask(s)...
    if(is_array($search)){
        foreach($search as $k => $v) {
            $search[$k] = '`'.preg_quote($search[$k], '`').'`';
        }
    }else{
        $search = '`'.preg_quote($search, '`').'`';
    }
    // replacement
    return preg_replace($search, $replace, $subject, $limit);
}