<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::crud('user', 'UserCrudController');
    Route::crud('client-conf-info', 'ClientConfInfoCrudController');
    Route::crud('count-request', 'CountRequestCrudController');
    Route::crud('image-metadata', 'ImageMetadataCrudController');
    Route::crud('face-liveness-log', 'FaceLivenessLogCrudController');
    Route::crud('face-matching-log', 'FaceMatchingLogCrudController');
    Route::crud('id-spoof-log', 'IdSpoofLogCrudController');
    Route::crud('id-type-log', 'IdTypeLogCrudController');
    Route::crud('id-card-log', 'IdCardLogCrudController');
    Route::crud('image-quality-log', 'ImageQualityLogCrudController');
    Route::crud('role', 'RoleCrudController');
}); // this should be the absolute last line of this file
