<?php
# Guest / Public pages
Route::get('/', 'PublicController@index');
Route::get('/impress', 'PublicController@impress');
Route::get('/privacy', 'PublicController@privacy');

# User => Loged-in (Userrole Management)
Route::get('dashboard', 'DashboardController@index');
Route::get('user', 'DashboardController@index');
Route::get('user/reject/{project_name}', 'DashboardController@reject');
Route::get('user/confirm/{project_name}', 'DashboardController@confirm');
Route::post('user', 'AjaxController@search');

Route::get('user/firstlogin', 'DashboardController@firstlogin');
Route::get('user/profile', 'DashboardController@userprofile');
Route::get('user/profile/overview', 'DashboardController@user_overview');
Route::get('user/profile/{student_name}', 'DashboardController@userprofile_show');

Route::get('user/project/reject/{project_name}/{user_id}', 'DashboardController@reject_project_apply');
Route::get('user/project/confirm/{project_name}/{user_id}', 'DashboardController@confirm_project_apply');

# Social Credit Points
Route::get('user/scp', 'ScpController@index');
Route::post('user/scp', 'ScpController@index');
Route::get('user/scp/download/{filter_id}', 'ScpController@download');
Route::get('user/scp/print/{filter_id}', 'ScpController@printout');
#Route Patterns
Route::pattern('filter_id', '[0-3]');

# User Projects drafts
Route::get('user/project-drafts', 'ProjectsController@index_draft');
Route::get('user/project-drafts/{project_name}', 'ProjectsController@show');
Route::get('user/project-drafts/{project_name}/edit', 'ProjectsController@edit_draft');
Route::get('user/project-drafts/{project_name}/release', 'ProjectsController@release_draft');
Route::get('user/project-drafts/{project_name}/destroy', 'ProjectsController@destroy_draft');
Route::post('user/project-drafts/{project_name}/update', 'ProjectsController@update_draft');

# User Projects suggestion
Route::get('user/project-suggestions', 'ProjectsController@index_suggestion');
Route::get('user/project-suggestions/{project_name}', 'ProjectsController@show_suggestion');
Route::get('user/project-suggestions/{project_name}/edit', 'ProjectsController@edit_suggestion');
Route::post('user/project-suggestions/{project_name}/update', 'ProjectsController@update_suggestion');
Route::get('user/project-suggestions/{project_name}/withdraw', 'ProjectsController@withdraw_suggestion');

# User Projects
Route::get('user/projects', 'DashboardController@myprojects');
Route::get('user/projects/{project_name}', 'ProjectsController@show');
Route::get('user/projects/{project_name}/documentation', 'DashboardController@myprojects_documentation');
Route::post('user/projects/{project_name}/documentation', 'DashboardController@myprojects_documentation_post');

// Studiengangsleiter => Dokumentation der Studenten
Route::get('projects/documentation', 'ProjectsController@documentation');
Route::get('projects/documentation/{project_name}', 'ProjectsController@documentation_show');

# Project Marketplace
Route::get('projects', 'ProjectsController@index');
Route::get('projects/create', 'ProjectsController@create');
Route::post('projects/create', 'ProjectsController@store');
Route::get('projects/{project_name}', 'ProjectsController@show');
Route::post('projects/{project_name}/apply', 'ProjectsController@apply');
Route::post('projects/{project_name}/withdraw', 'ProjectsController@withdraw');

# Logion and Sign-up
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
