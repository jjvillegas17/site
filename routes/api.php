<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'API\RegisterController@register');
Route::post('login', 'API\RegisterController@login');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('details', 'API\RegisterController@details')->name('register.details');
    Route::post('logout', 'API\RegisterController@logout');    
});


// TODO: add auth:api middleware to the ff routes
// laravel will automatically add the query string to this route based from the passed
// array from the redirect()->route()
Route::get('/fb/{pageId}/likes', 'FacebookController@getPageLikes')->name('fb.getPageLikes');
Route::get('/fb/{pageId}/views', 'FacebookController@getPageViews')->name('fb.getPageViews');
// Route::get('/fb/{pageId}/engagements', 'FacebookController@getPageEngagements')->name('fb.getPageEngagements');
Route::get('/fb/{pageId}/impressions', 'FacebookController@getPageImpressions')->name('fb.getPageImpressions');
Route::get('/fb/{pageId}/postsDetails', 'FacebookController@getPagePostsDetails')->name('fb.getPagePostsDetails');


// // 1
// Route::get('/fb/{pageId}/engagements', 'FacebookController@getPageEngagements')->name('fb.getPagePostEngagements');
// // 2
Route::get('/fb/{pageId}/engagements?pageToken={pageToken}', 'FacebookController@getPageEngagements')->name('fb.getPageEngagements');

Route::get('/fb/{pageId}/tryLikes', 'FacebookController@tryLikes')->name('fb.tryLikes');