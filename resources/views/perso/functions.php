<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 10/08/2018
 * Time: 23:23
 */

function sanitize($str) {

    $str=str_replace("'","\\'",$str);
    $str=str_replace("\n","\\n",$str);
    $str=str_replace("\r","\\r",$str);

    return $str;
}

function getRole($val){
    if($val==1){
        return 'Administrateur';
    }
    elseif ($val==2)
    {
        return 'Editeur';

    }
    elseif ($val==16)
    {
        return 'Vendeur';

    }

    else
        return '';
}

function toDateString($date) {
    return (new DateTime($date))->format('d-m-Y');
}
