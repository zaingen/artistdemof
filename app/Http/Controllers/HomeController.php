<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
//use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use App\Helpers\Spotify;



class HomeController extends Controller 
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

    }
    /*
    */
    function getArtistalbum() {

        $artist_id=Input::get("artistid");
        $end_point='/v1/artists/'.$artist_id.'/albums';
        $data=$this->callSimpleSpotify('https://api.spotify.com'.$end_point);
        return view('artistablum',["artist_id"=>$album_id,'artist_albums'=>json_decode($data)]);
    }
    /*
    get the spotify album tracks
    */
    function getAlbumTracks() {
        //$album_id=Input::get("albumid") ;
        $album_id='0sNOF9WDwhWunNAHPD3Baj';
        $artist_id=Input::get("artistid");
        $artist_end_point='/v1/artists/'.$artist_id;
        $artist_data=$this->callSimpleSpotify('https://api.spotify.com'.$artist_end_point);
        $album_track_end_point='/v1/albums/'.$album_id;
        $album_track_data=$this->callSimpleSpotify('https://api.spotify.com'.$album_track_end_point);
        return view('albumtracks',["album_id"=>$album_id,'artist_data'=>json_decode($artist_data),'album_tracks'=>json_decode($album_track_data)]);
        /*if( ! session()->has('access_token') ){
            $this->requestAuthorization();
        }else{
            if(Spotify::checkTokenExpire()) {
                $this->requestAuthorization();
            }
            var_dump(session()->get('access_token_time'));
        }*/ 
    }
    function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }    
    function requestAuthorization() {
        session_start() ;
        $scopes = 'playlist-modify-public playlist-modify-private playlist-read-private user-read-email';
        //$login_key = Hash::make("". mt_rand() . config("spotify.s_client_id") );
        $login_key = $this->generateRandomString();
        //config('spotify.state',$login_key);
        session()->put('login_key', $login_key);
        //var_dump(session()->get('login_key'));

        $params = array(
            'client_id' => config('spotify.s_client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('spotify.s_redirect_uri'),
            'state' => $login_key,
            'scope' => $scopes,
        );
        $url='https://accounts.spotify.com/authorize?' . http_build_query($params);
        //return Redirect::to('/spotifycallback?' . http_build_query($params))->send();
        return Redirect::to($url)->send();
        /*return Redirect::to('/redirectcheck'); 
        return Redirect::to('/redirectcheck')->send(); 
        return Redirect::to('home');
        die(Redirect::away($url));*/
        //$redirect_obj=new RedirectResponse($url);  // and this my external link
        //die($redirect_obj);
        //return $redirect_obj;
        //var_dump($redirect_obj);
        //die();
        //return new RedirectResponse($url);  // and this my external link
        //return Redirect::to($url);
        //header('Location : '.$url);        
    }
    function callSimpleSpotify($url){
      $opts = array(
        'http'=>array(
          'method'=>"GET"
        )
      );
      $context = stream_context_create($opts);
      // Open the file using the HTTP headers set above
      return file_get_contents($url, false, $context);
    }

    function callSpotify($url) {

      $headerStr = "Authorization: Bearer ". session()->get('access_token') ."\r\n";
      // Create a stream
      $opts = array(
        'http'=>array(
          'method'=>"GET",
          'header'=>$headerStr,
        )
      );
      $context = stream_context_create($opts);
      // Open the file using the HTTP headers set above
      return file_get_contents($url, false, $context);
    }    
    function getSpotifyEndPointsData($end_point){
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.spotify.com'.$end_point,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request',
            CURLOPT_SSL_VERIFYHOST=> 0,
            CURLOPT_SSL_VERIFYPEER=> 0
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);    
        return json_decode($resp);
    }
    function getSpotifyToken(){
        $url = 'https://accounts.spotify.com/api/token';
        $method = 'POST';
        $client_id=config("spotify.s_client_id"); 
        $client_secret=config("spotify.s_client_secret"); 
        $spot_api_redirect = config("spotify.s_redirect_uri");

        $credentials = "{$client_id}:{$client_secret}";
        dd($credentials);
        $headers = array(
                "Accept: */*",
                "Content-Type: application/x-www-form-urlencoded",
                "User-Agent: runscope/0.1",
                "Authorization: Basic " . base64_encode($credentials));
        $data = 'grant_type=client_credentials';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);        
        var_dump(json_decode($response));
        exit;
    }
}
