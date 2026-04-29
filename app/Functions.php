<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class Functions extends Model
{
    public static function  contain($Arr,$val){
        $res=false;

        $i=0;
        while ($i<sizeof($Arr)){
            if ($Arr[$i]==$val){
                $res=true;
                break;
            }
            $i++;
        }

        return $res;

    }

    public static function  pp_exists($Arr,$val){
        $res=false;

        $i=0;
        while ($i<sizeof($Arr)){
            if ($Arr[$i]<=$val){
                $res=true;
                break;
            }
            $i++;
        }

        return $res;

    }
}
