<?php

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\superadmin\MenuController;
use App\Http\Controllers\superadmin\RoleController;
use App\Http\Controllers\superadmin\SubMenuController;
use App\Http\Controllers\superadmin\ResourceController;
use App\Http\Controllers\superadmin\SuperadminController;
use App\Http\Controllers\superadmin\LoginDetailController;
use App\Http\Controllers\superadmin\SuperadminProfileController;
use Illuminate\Support\Facades\Route;

// |--------------------------------------------------------------------------
// | CLEAR CACHE ROUTES
// |--------------------------------------------------------------------------
Route::get('/clear-cache', function () {
    // Clear application cache
    Artisan::call('cache:clear');

    // Clear route cache
    Artisan::call('route:clear');

    // Clear configuration cache
    Artisan::call('config:clear');

    // Clear compiled views
    Artisan::call('view:clear');

    // You can add more cache clearing commands as needed
    echo "Cache cleared successfully!";
});

// |--------------------------------------------------------------------------
// | SUPERADMIN ROUTES
// |--------------------------------------------------------------------------
Route::middleware(['checkRole:SUPERADMIN'])->group(function () {
    // ================================================
    Route::post('/ajax/superadmin-profile/update', [SuperadminController::class, 'updateProfileDetail']);
    Route::post('/ajax/superadmin-profile/change-password', [SuperadminController::class, 'changePassword']);
    // USERS CONTROLLER
    Route::get('/superadmin-all-users', [UserController::class, 'superadminAllUsers']);

    // SUPER ADMIN CONTROLLER
    Route::get('/superadmin/dashboard', [SuperadminController::class, 'dashboard']);

    // SUPER ADMIN PROFILE CONTROLLER
    Route::get('/superadmin-profile', [SuperadminProfileController::class, 'profile']);

    //ROLE CONTROLLER
    Route::get('/all-roles', [RoleController::class, 'allRoles']);
    Route::post('/ajax/get/all-roles', [RoleController::class, 'getAllRolesList']);
    Route::post('/ajax/role/store', [RoleController::class, 'storeRole']);
    Route::post('/ajax/role/update', [RoleController::class, 'updateRole']);
    Route::post('/ajax/get/role-details', [RoleController::class, 'roleDetails']);
    Route::post('/ajax/role/delete', [RoleController::class, 'deleteRole']);


    //Resource Controller
    Route::get('/all-resource', [ResourceController::class, 'allResources']);
    Route::post('/ajax/get/all-resource', [ResourceController::class, 'getAllResourceList']);
    Route::post('/ajax/resource/store', [ResourceController::class, 'storeResource']);
    Route::post('/ajax/resource/update', [ResourceController::class, 'updateResource']);
    Route::post('/ajax/get/resource-details', [ResourceController::class, 'resourceDetails']);
    Route::post('/ajax/resource/delete', [ResourceController::class, 'deleteResource']);

    //Menu Controller
    Route::get('/all-menus', [MenuController::class, 'allMenu']);
    Route::post('/ajax/get/all-menus', [MenuController::class, 'getAllMenuList']);
    Route::post('/ajax/menu/store', [MenuController::class, 'storeMenu']);
    Route::post('/ajax/menu/update', [MenuController::class, 'updateMenu']);
    Route::post('/ajax/get/menu-details', [MenuController::class, 'menuDetails']);
    Route::post('/ajax/menu/delete', [MenuController::class, 'deleteMenu']);
    Route::post('/ajax/getParentMenu', [MenuController::class, 'getParentMenu']);

    // Sub Menu Controller
    Route::get('/all-sub-menus', [SubMenuController::class, 'allMenu']);
    Route::post('/ajax/get/all-sub-menus', [SubMenuController::class, 'getAllMenuList']);
    Route::post('/ajax/sub-menu/store', [SubMenuController::class, 'storeMenu']);
    Route::post('/ajax/sub-menu/update', [SubMenuController::class, 'updateMenu']);
    Route::post('/ajax/get/sub-menu-details', [SubMenuController::class, 'subMenuDetails']);
    Route::post('/ajax/sub-menu/delete', [SubMenuController::class, 'deleteMenu']);

    // Login Details Controller
    Route::get('/all-login-details', [LoginDetailController::class, 'index']);
    Route::post('/ajax/get/all-login-details', [LoginDetailController::class, 'getAllLoginDetails']);
});
