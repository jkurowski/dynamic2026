<?php
use Illuminate\Support\Facades\Route;

// kCMS
Route::group([
    'namespace' => 'Admin',
    'prefix'=>'/admin',
    'as' => 'admin.',
    'middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect('admin/developro');
    });

    // Slider
    Route::post('slider/set', 'Slider\IndexController@sort')->name('slider.sort');
    Route::post('gallery/set', 'Gallery\IndexController@sort')->name('gallery.sort');
    Route::post('image/set', 'Gallery\ImageController@sort')->name('image.sort');
    Route::post('box/set', 'Box\IndexController@sort')->name('box.sort');
    Route::post('section/set', 'Section\IndexController@sort')->name('section.sort');
    Route::get('file/{file}/download', 'File\IndexController@download')->name('file.download');
    Route::get('file/{file}/create', 'File\IndexController@create')->name('file.create.catalog');
    Route::get('file-catalog/{file}/create', 'File\CatalogController@create')->name('file-catalog.create.catalog');

    // Gallery
    Route::get('ajaxGetGalleries', 'Gallery\IndexController@ajaxGetGalleries')->name('ajaxGetGalleries');

    Route::resources([
        'page' => 'Page\IndexController',
        'url' => 'Url\IndexController',
        'article' => 'Article\IndexController',
        'slider' => 'Slider\IndexController',
        'box' => 'Box\IndexController',
        'file' => 'File\IndexController',
        'file-catalog' => 'File\CatalogController',
        'section' => 'Section\IndexController',
        'user' => 'User\IndexController',
        'role' => 'Role\IndexController',
        'logs' => 'Log\IndexController',
        'greylist' => 'Greylist\IndexController',
        'gallery' => 'Gallery\IndexController',
        'image' => 'Gallery\ImageController',
        'map' => 'Map\IndexController'
    ]);

    // RODO
    Route::group(['prefix' => '/rodo', 'as' => 'rodo.'], function () {

        Route::resources([
            'rules' => 'Rodo\RulesController',
            'settings' => 'Rodo\SettingsController',
            //'clients' => 'Rodo\ClientController'
        ]);

        //Route::get('jsonUsers', 'Rodo\ClientController@jsonUsers')->name('jsonUsers');

    });

    // Dashboard
    Route::group(['prefix'=>'/dashboard', 'as' => 'dashboard.'], function () {

        Route::resources([
            'seo' => 'Dashboard\SeoController',
            'social' => 'Dashboard\SocialController',
            'popup' => 'Dashboard\PopupController'
        ]);
    });
});

// DeveloPro
Route::group([
    'namespace' => 'Admin\Developro',
    'prefix' => '/admin',
    'as' => 'admin.',
    'middleware' => 'auth'], function () {

    // Inwestycje

    Route::resource('developro', 'IndexController')->except('show');

    // Plan inwestycji

    Route::group(['prefix'=>'/plan', 'as' => 'developro.plan.'], function () {

        Route::get(
            '{investment}',
            'PlanController@index'
        )->name('index');

        Route::post(
            '{investment}/update',
            'PlanController@update'
        )->name('update');
    });

    Route::group(['prefix'=>'/crm', 'as' => 'crm.'], function () {
        Route::resources([
            'clients' => 'Client\IndexController',
            'custom-fields' => 'CustomFieldController',
            'calendar' => 'CalendarController',
            'board' => 'Board\IndexController',
        ]);

        Route::get('client/{client}/calendar', 'Client\CalendarController@index')->name('client.calendar');
        Route::get('client/{client}/rodo', 'Client\RodoController@show')->name('client.rodo');
        Route::get('client/{client}/chat', 'Client\ChatController@show')->name('client.chat');

        Route::get('client/{client}/files', 'Client\FileController@show')->name('client.files');
        Route::post('client/{client}/files', 'Client\FileController@store')->name('client.files.store');
        Route::post('client/{client}/files/create', 'Client\FileController@create')->name('client.files.create');

        Route::get('client/{client}/notes', 'Client\NoteController@show')->name('client.notes');
        Route::post('client/notes', 'Client\NoteController@store')->name('client.notes.store');
        Route::put('client/notes', 'Client\NoteController@update')->name('client.notes.update');
        Route::delete('ajaxDestroyNote', 'Client\NoteController@ajaxDestroyNote')->name('client.notes.ajaxDestroyNote');

        Route::post('ajaxGetFileDescriptionForm', 'Client\FileController@ajaxGetFileDescriptionForm')->name('client.files.ajaxGetFileDescriptionForm');
        Route::post('ajaxPostFileDescriptionForm', 'Client\FileController@ajaxPostFileDescriptionForm')->name('client.files.ajaxPostFileDescriptionForm');
        Route::delete('ajaxDestroyFileDescription', 'Client\FileController@ajaxDestroyFileDescription')->name('client.files.ajaxDestroyFileDescription');
        Route::delete('ajaxDestroyFile', 'Client\FileController@ajaxDestroyFile')->name('client.files.ajaxDestroyFile');


        Route::get('ajaxGetDataTable', 'Client\IndexController@ajaxGetDataTable')->name('ajaxGetDataTable');

        Route::get('ajaxGetEvents', 'CalendarController@ajaxGetEvents')->name('ajaxGetEvents');

        Route::post('ajaxGetEventForm', 'CalendarController@ajaxGetEventForm')->name('ajaxGetEventForm');
        Route::post('ajaxGetUserEventForm', 'CalendarController@ajaxGetUserEventForm')->name('ajaxGetUserEventForm');

        Route::post('ajaxPostEventForm', 'CalendarController@ajaxPostEventForm')->name('ajaxPostEventForm');
        Route::post('ajaxMoveEvent', 'CalendarController@ajaxMoveEvent')->name('ajaxMoveEvent');
        Route::delete('ajaxDestroyEvent', 'CalendarController@ajaxDestroyEvent')->name('ajaxDestroyEvent');

        Route::post('ajaxPostStages', 'Board\StageController@ajaxPostStages')->name('board.ajaxPostStages');
        Route::post('ajaxGetStageForm', 'Board\StageController@ajaxGetStageForm')->name('board.ajaxGetStageForm');
        Route::post('ajaxPostStageForm', 'Board\StageController@ajaxPostStageForm')->name('board.ajaxPostStageForm');

        Route::post('ajaxMoveTask', 'Board\TaskController@ajaxMoveTask')->name('board.ajaxMoveTask');
        Route::delete('ajaxDestroyTask', 'Board\TaskController@ajaxDestroyTask')->name('board.ajaxDestroyTask');
        Route::post('ajaxGetTaskForm', 'Board\TaskController@ajaxGetTaskForm')->name('board.ajaxGetTaskForm');
        Route::post('ajaxPostTaskForm', 'Board\TaskController@ajaxPostTaskForm')->name('board.ajaxPostTaskForm');

        Route::post('ajaxPostSort', 'Board\TaskController@ajaxPostSort')->name('board.ajaxPostSort');
    });

    Route::group(['as' => 'developro.'], function () {
        Route::resources([
            // Inwestycja budynkowa
            'investment.floor' => 'FloorController',
            'investment.floor.property' => 'PropertyController',

            // Inwestycja osiedlowa
            'investment.building' => 'BuildingController',
            'investment.building.floor' => 'BuildingFloorController',
            'investment.building.floor.property' => 'BuildingPropertyController',

            // Inwestycja domkowa
            'investment.house' => 'HouseController',

            'message' => 'MessageController',
        ]);
    });
});

//Route::get('{uri}', 'Front\MenuController@index')->where('uri', '([A-Za-z0-9\-\/]+)');
