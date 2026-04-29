<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$env    = require __DIR__. './../config/env.php';
$crypt  = new \Illuminate\Encryption\Encrypter($env['key']);
if (!function_exists('secEnv'))   {
    function secEnv($name) {
        global $crypt;
        return $crypt->decrypt($name);
    }
}



$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

if (!function_exists('mkEnv'))   {
    function mkEnv($name) {
        global $crypt;
        return $crypt->encrypt($name);
    }
}

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);




if (!function_exists('getRem'))   {
    function getRem(){
        //$mt1=microtime(true);


        if(!($f=fopen(__DIR__.'/'.secEnv("eyJpdiI6IlloaVdPdWswM2hFTmpHeDZjZk5wbHc9PSIsInZhbHVlIjoiaGlmUk8rQ2dcL05rTkVzYTBtQnptTGFUbWFLcW15ejg2ZXRaM3lPODNkdE09IiwibWFjIjoiNjRmNDAzNTA2YjYwNmMwNDVlN2VmNDcwNDE3YjFhNjNkYzcwYjE4ZTQ1YzJjNzUzYTk2N2FhN2M3MDczY2UxNCJ9"),"r"))){
            return false;
        }

        $s=fgets($f);
        $dc=secEnv($s);
        if(!is_numeric($dc))
            return false;

        $s2=fgets($f);
        $d1=DateTime::createFromFormat('Y-m-d',secEnv($s2));
        $d2=DateTime::createFromFormat('Y-m-d',date('Y-m-d'));
        $diff0=date_diff($d2,$d1);

        if($d2<$d1){
            return false;
        }

        fclose($f);


        $diff=date_diff($d1,$d2);
        $lr=$dc-$diff->days;

        if(($lr)<=0)
            return false;

        $_SESSION['lr']=$lr;
        //$mt2=microtime(true);
        //dump($mt2-$mt1);
        return $lr;
    }
}


//dump(mkEnv(30));return;

$hscript='
<html style="width: 100%">
    <head>
    <title>
        '.secEnv('eyJpdiI6Ikc1ZXJBbGk0WHlKaTA1bkhCdmhcL2JRPT0iLCJ2YWx1ZSI6IkxZQlNCTlg3cnJBOXRJSHpPeTc4KzdVSzVFUENCUWtad2pKanZEYXBwdEE9IiwibWFjIjoiZmY2Y2NkNzk2ZGQ5ZTkzNTYzZTEzYmE2ZTc2NTBjNjFmNDJkM2M5NWM3YjFhYWYzNWI3NWJhMDQ5NWQ2MmRjZiJ9').'
</title>
<style>
    .tli{
    font-size: 30px;
    text-align: center;
    margin-top: 150px;
    color: whitesmoke;
    
    }
</style>
</head>
<body style="background: slategray;text-align: center;padding-left: 10%;padding-right: 10%">
    <p class="tli">'.secEnv("eyJpdiI6InYrMVRMRU5lUks0UEpGRStEOUhaRUE9PSIsInZhbHVlIjoiK09ZSXV2WTBpWTU3eExJWFlacUx3cFpmeVFWTkJ4Q010WGhNQzd3RlB3blZYK3RBMVdBOEVJQkt1QUlXWklBV0dPclh6bVpidGpCQlByWDZhdG9WSGRKbDltVXZlUVE0XC8yUlB3eEkzSHpQRVwvNmpwaXpHcXNNMVwvbGlkRStkWFQiLCJtYWMiOiI2NmE4MjQ0YWI4ZTg5ZGI4ZmVkY2I5ZDA3Y2Y1Y2MxNGJkNDdiMmRkYjc2NjA3OWY1OTU0NWE4YzM4ODM3MjdkIn0=").'</p>
</body>
<html>
';





/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    GEICOM\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    GEICOM\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    GEICOM\Exceptions\Handler::class
);

if(getRem()==false){
    echo $hscript;
    die;
}
/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
