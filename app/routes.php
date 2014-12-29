<?php

/*
|--------------------------------------------------------------------------
| Application Routers
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['before' => 'Sentinel\auth'], function()
{
	Route::resource('projects', 'ProjectsController');
	Route::resource('tickets', 'TicketsController');
	Route::resource('invoices', 'InvoicesController');
	Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
	# Override Sentinel's Default user routes with our own filter requirement
	Route::get('/users/{id}', ['as' => 'sl_user.show', 'uses' => 'SimpleLance\UserController@show'])->where('id', '[0-9]+');

});

Route::group(['before' => 'Sentinel\inGroup:Admins'], function()
{
	Route::resource('priorities', 'PrioritiesController', ['except' => ['show']]);
	Route::resource('statuses', 'StatusesController', ['except' => ['show']]);
});
