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


//feeds
Route::get('/', 'HomeController@getGlobalFeed');

//logout
Route::get('/logout/facebook', 'HomeController@logout');

//comments
Route::get('/comments/image/{post_id}','HomeController@getImageComments');
Route::get('/comments/compliment/{post_id}','HomeController@getComplimentComments');
Route::post('/comments/image','HomeController@postImageComments');
Route::post('/comments/compliment','HomeController@postComplimentComments');

//facebook login redirect
Route::get('/login/facebook', 'Auth\AuthController@login');

//other login
Route::post('/login', 'HomeController@login');
Route::post('/create', 'HomeController@createUser');

Route::group(['middleware' => 'auth'], function(){

	Route::get('/{user}/sent', 'HomeController@getMySentFeed');
	Route::get('/{user}/recieved', 'HomeController@getMyRecievedFeed');

	//get comp and pic
	Route::get('/{user}/comp','HomeController@getRandomCompliment');
	Route::get('/{user}/pic','HomeController@getRandPic');

	//post comp and pic
	Route::post('/{user}/comp','HomeController@postRandomCompliment');
	Route::post('/{user}/pic','HomeController@postRandPic');
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
