<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AssociationController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->scopeBindings()
    ->group(function () {
        Route::apiResource(
            'organizations',
            OrganizationController::class
        );

        Route::apiResource(
            'organizations.clubs',
            ClubController::class
        );

        Route::apiResource(
            'organizations.associations',
            AssociationController::class
        );
    });
