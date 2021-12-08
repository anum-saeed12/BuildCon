<?php
/*
use Illuminate\Support\Facades\Route;

use App\Models\Setting;
use Illuminate\Http\Request;
 */
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*//*

//Route::resource('user', UserController::class);

//Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
//});


Route::get('/', function(){
    return response("Direct access not allowed", 200);
});

# Route: website.com/login
# This API will be used to login in
Route::post('/login', '\App\Http\Controllers\LoginController@login');

# Route:: Admin
# Route: website.com/admin
# This API will be used for admin with admin middleware
Route::prefix('/admin')->middleware('admin')->group(function(){
    # To update or see his/her user
    Route::get('/profile', '\App\Http\Controllers\Api\Admin\ProfileController@show');
    Route::post('/profile/update', '\App\Http\Controllers\Api\Admin\ProfileController@update');
    # Route: website.com/admin/user
    # This API will be use for ADMIN (USER)
    # Admin can add,update,delete,see other admin too
    Route::prefix('/user')->group(function() {
        Route::get('/', '\App\Http\Controllers\Api\Admin\UserController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Admin\UserController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Admin\UserController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Admin\UserController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Admin\UserController@delete');
    });
    # Route: website.com/admin/client
    # This API will be use for ADMIN (CLIENT)
    # Admin can add,update,delete,see Client/Company/anything else
    Route::prefix('client')->group(function() {
        Route::get('/', '\App\Http\Controllers\Api\Admin\ClientController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Admin\ClientController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Admin\ClientController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Admin\ClientController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Admin\ClientController@delete');
    });
    #Client department ma add nhi krwaiyaa
    Route::prefix('/department')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Admin\DepartmentController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Admin\DepartmentController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Admin\DepartmentController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Admin\DepartmentController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Admin\DepartmentController@delete');
    });
    Route::prefix('/job')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Admin\JobPositionController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Admin\JobPositionController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Admin\JobPositionController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Admin\JobPositionController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Admin\JobPositionController@delete');
    });
    Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Admin\ProductController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Admin\ProductController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Admin\ProductController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Admin\ProductController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Admin\ProductController@delete');
    });
    Route::prefix('/products/category')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Admin\ProductCategoryController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Admin\ProductCategoryController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Admin\ProductCategoryController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Admin\ProductCategoryController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Admin\ProductCategoryController@delete');
    });

});

# Route:: Client
Route::prefix('/client')->middleware('client')->group(function(){
    Route::get('/profile', '\App\Http\Controllers\Api\Client\ProfileController@show');
    Route::post('/profile/update', '\App\Http\Controllers\Api\Client\ProfileController@update');
    #Route:: website.com/client/user
    Route::prefix('/user')->group(function() {
        Route::get('/', '\App\Http\Controllers\Api\Client\UserController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\UserController@index');
        #Route::post('/add', '\App\Http\Controllers\Api\Client\UserController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\UserController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\UserController@delete');
    });
    #Route:: website.com/client/manager
    Route::prefix('/manager')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\ManagerController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\ManagerController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\ManagerController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\ManagerController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\ManagerController@delete');
    });
    #Route:: website.com/client/accountant
    Route::prefix('/accountant')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\AccountantController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\AccountantController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\AccountantController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\AccountantController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\AccountantController@delete');
    });
    #Route:: website.com/client/employee
    Route::prefix('/employee')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\EmployeeController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\EmployeeController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\EmployeeController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\EmployeeController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\EmployeeController@delete');
    });
    Route::prefix('/department')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\DepartmentController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\DepartmentController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\DepartmentController@store');
        //Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\DeparmentController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\DepartmentController@delete');
    });
    Route::prefix('/job')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\JobPositionController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\JobPositionController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\JobPositionController@store');
        # 1. Insert main table 2. Bridge table
        #Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\JobPositionController@update');
        #Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\JobPositionController@delete');
    });
    Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\ProductController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\ProductController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\ProductController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\ProductController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\ProductController@delete');
    });
    Route::prefix('/products/category')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\ProductCategoryController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\ProductCategoryController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\ProductCategoryController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\ProductCategoryController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\ProductCategoryController@delete');
    });
    Route::prefix('/sales/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\SalesOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\SalesOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\SalesOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\SalesOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\SalesOrderController@delete');
    });

    Route::prefix('/purchase/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\PurchaseOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\PurchaseOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\PurchaseOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\PurchaseOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\PurchaseOrderController@delete');
    });

    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\InvoiceController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\InvoiceController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\InvoiceController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\InvoiceController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\InvoiceController@delete');
    });

    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Client\QuotationController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Client\QuotationController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Client\QuotationController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Client\QuotationController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Client\QuotationController@delete');
        Route::post('/reject/{id}', '\App\Http\Controllers\Api\Client\QuotationController@reject_at');
        Route::post('/accept/{id}', '\App\Http\Controllers\Api\Client\QuotationController@accept_at');
    });
});

# Route:: Manager
#Route:: website.com/manager
Route::prefix('/manager')->middleware('manager')->group(function(){
    Route::get('/profile', '\App\Http\Controllers\Api\Manager\ProfileController@show');
    Route::post('/profile/update', '\App\Http\Controllers\Api\Manager\ProfileController@update');
    #Route:: website.com/manager/accountant
    Route::prefix('/accountant')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\AccountantController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\AccountantController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\AccountantController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\AccountantController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\AccountantController@delete');
    });
    #Route:: website.com/manager/employee
    Route::prefix('/employee')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\EmployeeController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\EmployeeController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\EmployeeController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\EmployeeController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\EmployeeController@delete');
    });
    Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\ProductController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\ProductController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\ProductController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\ProductController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\ProductController@delete');
    });
    Route::prefix('/products/category')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\ProductCategoryController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\ProductCategoryController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\ProductCategoryController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\ProductCategoryController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\ProductCategoryController@delete');
    });
    Route::prefix('/sales/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\SalesOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\SalesOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\SalesOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\SalesOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\SalesOrderController@delete');
    });

    Route::prefix('/purchase/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\PurchaseOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\PurchaseOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\PurchaseOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\PurchaseOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\PurchaseOrderController@delete');
    });

    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\InvoiceController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\InvoiceController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\InvoiceController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\InvoiceController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\InvoiceController@delete');
    });

    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Manager\QuotationController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Manager\QuotationController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Manager\QuotationController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Manager\QuotationController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Manager\QuotationController@delete');
        Route::post('/reject/{id}', '\App\Http\Controllers\Api\Manager\QuotationController@reject_at');
        Route::post('/accept/{id}', '\App\Http\Controllers\Api\Manager\QuotationController@accept_at');
    });
});

# Route:: Accountant
#Route:: website.com/accountant
Route::prefix('/accountant')->middleware('accountant')->group(function(){
    Route::get('/profile', '\App\Http\Controllers\Api\Accountant\ProfileController@show');
    Route::post('/profile/update', '\App\Http\Controllers\Api\Accountant\ProfileController@update');

    Route::prefix('/sales/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Accountant\SalesOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Accountant\SalesOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Accountant\SalesOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Accountant\SalesOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Accountant\SalesOrderController@delete');
    });

    Route::prefix('/purchase/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Accountant\PurchaseOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Accountant\PurchaseOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Accountant\PurchaseOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Accountant\PurchaseOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Accountant\PurchaseOrderController@delete');
    });

    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Accountant\InvoiceController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Accountant\InvoiceController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Accountant\InvoiceController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Accountant\InvoiceController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Accountant\InvoiceController@delete');
    });

    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Accountant\QuotationController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Accountant\QuotationController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Accountant\QuotationController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Accountant\QuotationController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Accountant\QuotationController@delete');
        Route::post('/reject/{id}', '\App\Http\Controllers\Api\Accountant\QuotationController@reject_at');
        Route::post('/accept/{id}', '\App\Http\Controllers\Api\Accountant\QuotationController@accept_at');
    });
});

# Route:: Employee
#Route:: website/employee
Route::prefix('/employee')->middleware('employee')->group(function(){
    Route::get('/profile', '\App\Http\Controllers\Api\Employee\ProfileController@show');
    Route::post('/profile/update', '\App\Http\Controllers\Api\Employee\ProfileController@update');

    Route::prefix('/sales/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Employee\SalesOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Employee\SalesOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Employee\SalesOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Employee\SalesOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Employee\SalesOrderController@delete');
    });

    Route::prefix('/purchase/order')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Employee\PurchaseOrderController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Employee\PurchaseOrderController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Employee\PurchaseOrderController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Employee\PurchaseOrderController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Employee\PurchaseOrderController@delete');
    });

    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Employee\InvoiceController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Employee\InvoiceController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Employee\InvoiceController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Employee\InvoiceController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Employee\InvoiceController@delete');
    });

    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Api\Employee\QuotationController@index');
        Route::get('/index', '\App\Http\Controllers\Api\Employee\QuotationController@index');
        Route::post('/add', '\App\Http\Controllers\Api\Employee\QuotationController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Api\Employee\QuotationController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Api\Employee\QuotationController@delete');
        Route::post('/reject/{id}', '\App\Http\Controllers\Api\Employee\QuotationController@reject_at');
        Route::post('/accept/{id}', '\App\Http\Controllers\Api\Employee\QuotationController@accept_at');
    });
});



 */
