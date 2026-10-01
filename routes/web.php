<?php

use App\Http\Controllers\Front\EmailTemplatePreviewController;
use App\Http\Controllers\Front\IframePageController;
use App\Http\Controllers\SMSController;
use App\Http\Middleware\IframeContactMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/register', function () {
    abort(404);
});
Route::post('/register', function () {
    abort(404);
});

Route::get('routes', function () {
    Artisan::call('route:list');

    return '<pre>' . Artisan::output() . '</pre>';
});

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // Pobierz użytkownika na podstawie ID z URL
    $user = User::find($id);

    if (!$user) {
        throw new AuthorizationException('User not found');
    }

    // Sprawdź, czy hash e-maila zgadza się z tym, który jest w URL
    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        throw new AuthorizationException('Invalid verification link');
    }

    // Jeśli hash pasuje, zweryfikuj e-mail użytkownika
    $user->markEmailAsVerified();
    $user->active = 1;
    $user->save();

    // Przekieruj użytkownika na stronę logowania
    return redirect('/login')->with('verified', true);
})->middleware(['signed'])->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Wiadomość z link aktywacyjnym wysłana!');
})->middleware(['throttle:6,1'])->name('verification.send');


Route::middleware(['restrictIp'])->group(function () {
    Route::group(['namespace' => 'Front', 'prefix' => '{locale?}', 'where' => ['locale' => '(?!admin)*[a-z]{2}'],], function () {

        Route::get('/', 'IndexController@index')->name('index');

        // Oferta
//        Route::get('/oferta-mieszkan', 'Investments\IndexController@index')->name('offer.index');
//
//        Route::get('/i/downtown', 'Investments\IndexController@downtown')->name('offer.downtown');
//        Route::get('/i/downtown/plan-inwestycji', 'Investments\IndexController@downtownProperties')->name('offer.downtown.properties');
//
//        Route::get('/i/na-skraju', 'Investments\IndexController@naSkraju')->name('offer.na-skraju');
//        Route::get('/i/na-skraju/plan-inwestycji', 'Investments\IndexController@naSkrajuProperties')->name('offer.na-skraju.properties');
//
//        Route::get('/i/slonimska-residence-ii', 'Investments\IndexController@slonimskaResidenceII')->name('offer.slonimska-residence-ii');
//        Route::get('/i/slonimska-residence-ii/plan-inwestycji', 'Investments\IndexController@slonimskaResidenceIIProperties')->name('offer.slonimska-residence-ii.properties');
//
//        Route::get('/i/ogrody-andersena', 'Investments\IndexController@ogrodyAndersena')->name('offer.ogrody-andersena');
//        Route::get('/i/ogrody-andersena/plan-inwestycji', 'Investments\IndexController@ogrodyAndersenaProperties')->name('offer.ogrody-andersena.properties');


        Route::get('/kredyty', 'MenuController@kredyty')->name('kredyty');
        Route::get('/programy-wykonczeniowe', 'MenuController@wykonczeniowe')->name('programy-wykonczeniowe');

        Route::get('/kontakt', 'ContactController@index')->name('contact');
        Route::post('/kontakt', 'ContactController@send')->name('contact.send');

        Route::post('/kontakt/{property}', 'ContactController@property')->name('contact.property');

        Route::post('/clipboard', 'Clipboard\IndexController@store')->name('clipboard.store');
        Route::post('/clipboard/send', 'Clipboard\IndexController@send')->name('clipboard.send');
        Route::get('/schowek', 'Clipboard\IndexController@index')->name('clipboard.index');
        Route::delete('/clipboard', 'Clipboard\IndexController@destroy')->name('clipboard.destroy');

        Route::resources([
            '/aktualnosci' => 'ArticleController',
            '/gallery' => 'GalleryController'
        ]);

        //        // Client area
        //        Route::middleware('guest.client')->group(function () {
        //            Route::get('client/login', 'Auth\LoginController@showLoginForm')->name('login');
        //            Route::post('client/login/request-code', 'Auth\LoginController@requestCode')->name('login.request-code');
        //            Route::post('client/login/verify-code', 'Auth\LoginController@verifyCode')->name('login.verify-code');
        //        });
        //
        //        Route::middleware(['auth.client'])->group(function () {
        //            Route::get('client/area', 'Client\IndexController@index')->name('client.area');
        //
        //            Route::get('client/area/chat', 'Client\Chat\IndexController@index')->name('client.area.chat');
        //            Route::get('client/area/chat/messages/{clientId}', 'Client\Chat\IndexController@fetchMessages')->name('client.area.fetchMessages');
        //            Route::post('client/area/chat/send', 'Client\Chat\IndexController@sendMessage')->name('client.area.sendMessage');
        //
        //            Route::get('client/area/file', 'Client\Files\IndexController@index')->name('client.area.files');
        //            Route::get('client/area/calendar', 'Client\Calendar\IndexController@index')->name('client.area.calendar');
        //            Route::get('client/area/calendar/events', 'Client\Calendar\IndexController@show')->name('client.area.calendar.events.show');
        //
        //            Route::get('client/area/offer', 'Client\Offer\IndexController@index')->name('client.area.offer');
        //
        //            Route::get('client/area/special', 'Client\Special\IndexController@index')->name('client.area.special');
        //
        //            Route::get('client/area/rodo', 'Client\Rodo\IndexController@index')->name('client.area.rodo');
        //            Route::post('client/area/rodo/change-rule', 'Client\Rodo\IndexController@editRule')->name('client.area.rodo.change-rule');
        //
        //            Route::post('client/logout', 'Auth\LoginController@logout')->name('client.logout');
        //        });

        // DeveloPro
        Route::group(['namespace' => 'Developro', 'as' => 'developro.'], function () {

            Route::post('{property}/notifications', 'Property\NotificationController@store')->name('properties.notifications.store');
            Route::get('/unsubscribe/{hash}', 'Property\NotificationController@unsubscribe')->name('properties.notifications.unsubscribe');

            Route::get('/oferta-mieszkan', 'IndexController@index')->name('index');
            Route::get('/wyniki-wyszukiwania', 'SearchController@index')->name('search.index');

            Route::get('/inwestycje-planowane', 'IncomingController@index')->name('incomming');

            Route::get('/inwestycje-zrealizowane', 'CompletedController@index')->name('completed');
            Route::get('/inwestycje-zrealizowane/{slug}', 'CompletedController@show')->name('completed.city');

            Route::get('/i/{slug}/dziennik-inwestycji', 'Article\IndexController@index')->name('investment.news');
            Route::get('/i/{slug}/dziennik-inwestycji/{article}', 'Article\IndexController@show')->name('investment.news.show');

            Route::get('/i/{slug}/galeria', 'Gallery\IndexController@index')->name('investment.gallery');

            Route::get('/i/{slug}', 'InvestmentController@show')->name('show');
            Route::get('/i/{slug}/plan-inwestycji', 'InvestmentPlanController@index')->name('plan');
            Route::get('/i/{slug}/plan-inwestycji-3d', 'InvestmentPlanController@mockup')->name('mockup');

            #Inwestycja budynkowa - pietro
            Route::get('/i/{slug}/{floor},{floorSlug}', 'InvestmentFloorController@index')->name('floor');

            #Inwestycja budynkowa - pietro - mieszkanie
            Route::get('/i/{slug}/{floor},{floorSlug}/{property},{propertySlug},{propertyRooms},{propertyArea}', 'InvestmentPropertyController@index')->name('property');

            #Inwestycja osiedlowa - budynek
            Route::get('/i/{slug}/b/{building},{buildingSlug}', 'InvestmentBuildingController@index')->name('building');

            #Inwestycja osiedlowa - budynek - pietro
            Route::get('/i/{slug}/b/{building},{buildingSlug}/{floor},{floorSlug}', 'InvestmentBuildingFloorController@index')->name('building.floor');

            #Inwestycja osiedlowa - budynek - pietro - mieszkanie
            Route::get('/i/{slug}/b/{building},{buildingSlug}/{floor},{floorSlug}/{property},{propertySlug},{propertyRooms},{propertyArea}', 'InvestmentBuildingPropertyController@index')->name('building.floor.property');

            // Inwestycja domkowa
            Route::get('/{slug}/d/{property}', 'InvestmentHouseController@index')->name('house');

            //Historia cen
            Route::get('/historia/{property}', 'History\IndexController@show')->name('history');
            Route::get('/przynalezne/{property}', 'History\IndexController@others')->name('others');
            Route::get('/przynalezne/{property}/show', 'History\IndexController@other')->name('others.show');
            Route::get('/przynalezne/{property}/table', 'History\IndexController@otherTable')->name('others.table');

            //Pages
            Route::get('/{slug}/{page}', 'Page\IndexController@index')->name('page');

            Route::get('/i/{slug}/json', 'Api\IndexController@json')->name('investment.json');
        });

        // Inline
        Route::group(['prefix' => '/inline', 'as' => 'front.inline.'], function () {
            Route::get('/', 'InlineController@index')->name('index');
            Route::get('/loadinline/{inline}', 'InlineController@show')->name('show');
            Route::post('/update/{inline}', 'InlineController@update')->name('update');
        });

        Route::get('{uri}', 'MenuController@index')
            ->where('uri', '([A-Za-z0-9\-\/]+)')
            ->name('menu.show');
    });
});
