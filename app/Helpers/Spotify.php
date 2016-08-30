<?php 
namespace App\Helpers;


class Spotify
{
    public static function checkTokenExpire() {
	    $cur_time=time()+(60 * 60);
	    var_dump($cur_time);
	    if($cur_time > session()->get('access_token_time')){
	        return true;
	    }else{
	        return false;
	    }
    }
}


 ?>