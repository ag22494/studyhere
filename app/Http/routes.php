<?php


use App\User;
use App\Profile;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------

| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/','HomeController@start');
Route::get('/home','HomeController@start');
Route::get('/dashboard','DashboardController@start');
//Route::get('/fb-login','FbLoginController@handle');
Route::get('login/fb', function() {
    $facebook = new Facebook(Config::get('facebook'));
    $params = array(
        'redirect_uri' => url('/login/fb/callback'),
        'scope' => 'email,public_profile',
    );
    return Redirect::to($facebook->getLoginUrl($params));
});

 Route::controllers([
 	'auth' => 'Auth\AuthController',
 'password' => 'Auth\PasswordController',
 ]);

 Route::get('login/fb/callback', function() {
    $code = Input::get('code');
    if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

    $facebook = new Facebook(Config::get('facebook'));
    $uid = $facebook->getUser();


    if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');

    $me = $facebook->api('/me?fields=id,name,email');
    $profile = Profile::whereUid($uid)->first();
    if (empty($profile)) {

        $user = new User;

        $user->name = $me['name'];
        $user->email = $me['email'];
        $user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';
        $user->save();

        $profile = new App\Profile();
        $profile->uid = $uid;
        $profile->username = $me['name'];
        $profile = $user->profiles()->save($profile);
    }

    $profile->access_token = $facebook->getAccessToken();
    $profile->save();

    $user = $profile->user;

  Auth::login($user);

   return \View::make('dashboard')->with('name',$user->name);
});
