<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middlewareGroups' => ['web']], function () {

	Route::get('/', function () {
	    return view('welcome');
	});
	Route::get('/home', function () {
	    return view('welcome');
	});
	Route::get('home', function () {
	    return view('welcome');
	});
	Route::get('/login', function () {
	    return view('welcome');
	});
	Route::get('/albumtracks', ["uses"=>"HomeController@getAlbumTracks","as"=>"albumtracks"]);
	Route::get('/artistalbum', ["uses"=>"HomeController@getArtistalbum","as"=>"artistalbum"]);
	Route::get('/getartistdata', ["uses"=>"HomeController@getArtistData","as"=>"getartistdata"]);

	Route::get('/spotifycallback',function() {
		// Check first for things that could be wrong
			/*var_dump(Input::get('state'));
			var_dump(session()->get('login_key'));
			exit;*/
		  /*if (strcmp(session()->get('login_key'), Input::get('state')) != 0) {
		  	return view('welcome');
		    return "Incorrect state. This request is invalid.";
		  } else if (!empty(Input::get('error'))) {
		    return "Some kind of error occurred.";
		  }*/
		  if (!empty(Input::get('error'))) {
		    return "Some kind of error occurred.";
		  }

		  $postUrl = 'https://accounts.spotify.com/api/token';
		  $params = array(
		    'grant_type' => 'authorization_code',
		    'code' => Input::get('code'),
		    'redirect_uri' => config("spotify.s_redirect_uri"),
		    'client_id' => config("spotify.s_client_id"),
		    'client_secret' => config("spotify.s_client_secret"),
		  );

		  // use key 'http' even if you send the request to https://...
		  $options = array(
		      'http' => array(
		          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		          'method'  => 'POST',
		          'content' => http_build_query($params),
		      ),
		  );
		  $context  = stream_context_create($options);
		  $result = json_decode(file_get_contents($postUrl, false, $context));
		  session()->put('access_token', $result->access_token);
		  session()->put('access_token_time', time());
		  return Redirect::to('/home');	
	});

	/*Route::get('/albumtracks', function () {
		$album_id=Input::get("albumid") ;
	    return view('albumtracks',["album_id"=>$album_id]);
	});*/

});
