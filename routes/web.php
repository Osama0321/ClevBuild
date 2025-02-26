<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AddTaskAmountController;
use App\Http\Controllers\LayerController;
use App\Http\Controllers\ForgeController;
use App\Http\Controllers\CadViewerController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\LayerTemplateController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return "cache:clear, config:clear, route:cache, view:clear, optimize:clear!";
});

Route::get('/login', [Controller::class, 'login'])->name('login');
Route::post('/login', [Controller::class, 'logedin'])->name('logedin');
Route::get('/register', [Controller::class, 'register'])->name('register');
Route::post('/register', [Controller::class, 'registration'])->name('registration');
Route::get('/logout', [Controller::class, 'logout'])->name('logout');

Route::get('/canvas', function () {
    return view('canvas');
});

Route::get('/edit-canvas', function () {
    return view('edit_canvas');
});

Route::post('/save-image', [Controller::class, 'saveImage'])->name('saveImage');

// Create Password
Route::get('/createpassword/{token}',	[PasswordController::class,"CreatePassword"])->name('create.password.get');
Route::post('create-password', 			[PasswordController::class, 'submitCreatePasswordForm'])->name('reset.password.post');

// Route::middleware(['auth'])->group( function () {
//     Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
// });

Route::middleware(['auth'])->group( function () {
 
    Route::get('/layout', function () { return view('layout'); })->name('layout');
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('dashboard-new',[DashboardController::class,'index'])->name('dashboard-new')->middleware('role:accessDashboard');

    // Companies
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies')->middleware('role:viewCompanies'); 
    Route::get('/companies/get', [CompanyController::class, 'getCompanies'])->name('companies.get')->middleware('role:viewCompanies');
    Route::get('/companies/add', [CompanyController::class, 'create'])->name('companies.add')->middleware('role:addCompanies');
    Route::post('/companies/add', [CompanyController::class, 'store'])->name('companies.store')->middleware('role:addCompanies');
    Route::get('/companies/{company:id}/edit/', [CompanyController::class, 'edit'])->name('companies.edit')->middleware('role:updateCompanies');
    Route::post('/companies/{company:id}/update/', [CompanyController::class, 'update'])->name('companies.update')->middleware('role:updateCompanies');
    Route::post('/companies/{company:id}/delete', [CompanyController::class, 'destroy'])->name('companies.delete')->middleware('role:deleteCompanies');

     //Layers Setting
    Route::get('/layer-settings', [CompanyController::class, 'layerSettings'])->name('layers.settings')->middleware('role:viewLayers');
    Route::post('/upload-file', [CompanyController::class, 'uploadFile'])->name('layers.uploadFile')->middleware('role:viewLayers');
    Route::post('/save-layer-settings', [CompanyController::class, 'saveLayerSettings'])->name('layers.saveSettings')->middleware('role:addLayers');

    //Layers Templates
    Route::get('/layer-templates', [LayerTemplateController::class, 'index'])->name('layer-templates')->middleware('role:viewLayerTemplates');
    Route::get('/get-layer-templates', [LayerTemplateController::class, 'getLayersTemplates'])->name('layer-templates.get')->middleware('role:viewLayerTemplates');
    Route::get('/layer-templates/add', [LayerTemplateController::class, 'create'])->name('layer-templates.add')->middleware('role:addLayerTemplates');
    Route::post('/layer-templates/upload-and-process', [LayerTemplateController::class, 'uploadAndProcess'])->name('layer-templates.uploadAndProcess')->middleware('role:viewLayerTemplates');
    Route::post('/layer-templates/add', [LayerTemplateController::class, 'store'])->name('layer-templates.store')->middleware('role:addLayerTemplates');
    Route::get('/layer-templates/{layerTemplate:template_id}/view', [LayerTemplateController::class, 'view'])->name('layer-templates.view')->middleware('role:viewLayerTemplates');
    Route::get('/layer-templates/{layerTemplate:template_id}/edit', [LayerTemplateController::class, 'edit'])->name('layer-templates.edit')->middleware('role:updateLayerTemplates');
    
    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects')->middleware('role:viewProjects'); 
    Route::get('/projects/get', [ProjectController::class, 'getprojects'])->name('projects.get')->middleware('role:viewProjects');
    Route::get('/projects/add', [ProjectController::class, 'create'])->name('projects.add')->middleware('role:addProjects');
    Route::post('/projects/add', [ProjectController::class, 'store'])->name('projects.store')->middleware('role:addProjects');
    Route::get('/projects/{projects:project_id}/edit/', [ProjectController::class, 'edit'])->name('projects.edit')->middleware('role:editProjects');
    Route::post('/projects/{projects:project_id}/update/', [ProjectController::class, 'update'])->name('projects.update')->middleware('role:updateProjects');
    Route::post('/projects/{project:id}/delete', [ProjectController::class, 'destroy'])->name('projects.delete')->middleware('role:deleteProjects');
    Route::get('/projects/getcities/{id}', [ProjectController::class, 'getCities'])->name('get.cities');
    Route::get('/completed-projects', [ProjectController::class, 'completedProjects'])->name('projects.completed');
    Route::get('/projects/completed-projects', [ProjectController::class, 'getCompletedProjects'])->name('completed-projects.get');   
    
    // Floors
    Route::get('/floors', [FloorController::class, 'index'])->name('floors')->middleware('role:viewFloors');
    Route::get('/floors/get', [FloorController::class, 'getFloors'])->name('floors.get')->middleware('role:viewFloors');
    Route::get('/floors/add', [FloorController::class, 'create'])->name('floors.add')->middleware('role:addFloors');
    Route::post('/floors/add', [FloorController::class, 'store'])->name('floors.store')->middleware('role:addFloors');
    Route::get('/floors/floors:floor_id}/edit/', [FloorController::class, 'edit'])->name('floors.edit')->middleware('role:editFloors');
    Route::post('/floors/floors:floor_id}/update/', [FloorController::class, 'update'])->name('floors.update')->middleware('role:updateFloors');
    Route::post('/floors/{floor:id}/delete', [FloorController::class, 'destroy'])->name('floors.delete')->middleware('role:deleteFloors');
    Route::post('/floors/generateTasks/', [FloorController::class, 'generateTasks'])->name('floors.generateTasks')->middleware('role:addFloors');

    // Managers
    Route::get('/managers', [ManagerController::class, 'index'])->name('managers')->middleware('role:viewManagers');   
    Route::get('/managers/get', [ManagerController::class, 'getmanagers'])->name('managers.get')->middleware('role:viewManagers');   
    Route::get('/managers/get-by-company-id', [ManagerController::class, 'getManagersByComapnyId'])->name('managers.getByComapnyId')->middleware('role:viewManagers');   
    Route::get('/managers/add', [ManagerController::class, 'create'])->name('managers.add')->middleware('role:addManagers');  
    Route::post('/managers/add', [ManagerController::class, 'store'])->name('managers.store')->middleware('role:addManagers');
    Route::get('/managers/{manager:id}/edit/', [ManagerController::class, 'edit'])->name('managers.edit')->middleware('role:editManagers');
    Route::post('/managers/{manager:id}/update/', [ManagerController::class, 'update'])->name('managers.update')->middleware('role:updateManagers');
    Route::post('/managers/{manager:id}/delete', [ManagerController::class, 'destroy'])->name('managers.delete')->middleware('role:deleteManagers');
    Route::get('/managers/getcities', [ManagerController::class, 'getGetCityByCountry'])->name('getcities.get');   

    // Members
    Route::get('/members', [MemberController::class, 'index'])->name('members')->middleware('role:viewMembers');
    Route::get('/members/get', [MemberController::class, 'getMembers'])->name('members.get')->middleware('role:viewMembers');
    Route::get('/members/add', [MemberController::class, 'create'])->name('members.add')->middleware('role:addMembers');
    Route::post('/members/add', [MemberController::class, 'store'])->name('members.store')->middleware('role:addMembers');
    Route::get('/members/{member:id}/edit/', [MemberController::class, 'edit'])->name('members.edit')->middleware('role:editMembers');
    Route::post('/members/{member:id}/update/', [MemberController::class, 'update'])->name('members.update')->middleware('role:updateMembers');
    Route::post('/members/{member:id}/delete', [MemberController::class, 'destroy'])->name('members.delete')->middleware('role:deleteMembers');

    // Followers
    Route::get('/followers', [FollowerController::class, 'index'])->name('followers')->middleware('role:viewFollowers');
    Route::get('/followers/get', [FollowerController::class, 'getFollowers'])->name('followers.get')->middleware('role:viewFollowers');
    Route::get('/followers/add', [FollowerController::class, 'create'])->name('followers.add')->middleware('role:addFollowers');
    Route::post('/followers/add', [FollowerController::class, 'store'])->name('followers.store')->middleware('role:addFollowers');
    Route::get('/followers/{follower:id}/edit/', [FollowerController::class, 'edit'])->name('followers.edit')->middleware('role:editFollowers');
    Route::post('/followers/{follower:id}/update/', [FollowerController::class, 'update'])->name('followers.update')->middleware('role:updateFollowers');
    Route::post('/followers/{follower:id}/delete', [FollowerController::class, 'destroy'])->name('followers.delete')->middleware('role:viewMembers');

    // Accountants
    Route::get('/accountants', [AccountantController::class, 'index'])->name('accountants')->middleware('role:viewAccountants');
    Route::get('/accountants/get', [AccountantController::class, 'getAccountants'])->name('accountants.get')->middleware('role:viewAccountants');
    Route::get('/accountants/add', [AccountantController::class, 'create'])->name('accountants.add')->middleware('role:addAccountants');
    Route::post('/accountants/add', [AccountantController::class, 'store'])->name('accountants.store')->middleware('role:addAccountants');
    Route::get('/accountants/{accountant:id}/edit/', [AccountantController::class, 'edit'])->name('accountants.edit')->middleware('role:editAccountants');
    Route::post('/accountants/{accountant:id}/update/', [AccountantController::class, 'update'])->name('accountants.update')->middleware('role:updateAccountants');
    Route::post('/accountants/{accountant:id}/delete', [AccountantController::class, 'destroy'])->name('accountants.delete')->middleware('role:deleteAccountants');

    // Tasks
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/get', [TaskController::class, 'gettask'])->name('tasks.get');
    Route::get('/tasks/getTaskByName', [TaskController::class, 'getTaskByName'])->name('tasks.getTaskByName');
    Route::get('/tasks/getTaskByFilters', [TaskController::class, 'getTaskByFilters'])->name('tasks.getTaskByFilters');
    Route::get('/tasks/getTaskDetailsByFilters', [TaskController::class, 'getTaskDetailsByFilters'])->name('tasks.getTaskDetailsByFilters');
    Route::get('/tasks/add', [TaskController::class, 'create'])->name('tasks.add');
    Route::post('/tasks/add', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task:task_id}/edit/', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::post('/tasks/{task:task_id}/update/', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{task:id}/delete', [TaskController::class, 'destroy'])->name('tasks.delete');
    Route::post('/tasks/updateAll', [TaskController::class, 'updateAll'])->name('tasks.updateAll');
    Route::post('/tasks/updateById', [TaskController::class, 'updateById'])->name('tasks.updateById');
    Route::get('/tasks/getTasksWithStatusByProjectId', [TaskController::class, 'getTasksWithStatusByProjectId'])->name('tasks.getTasksWithStatusByProjectId');

    Route::get('/tasks/getStatusByTaskType', [TaskController::class, 'getStatusByTaskType'])->name('tasks.getStatusByTaskType');


    // Accounts
    Route::get('/invoices',[InvoiceController::class, 'index'])->name('invoices')->middleware('role:viewInvoices');
    Route::get('/invoices/get',[InvoiceController::class, 'getInvoices'])->name('invoices.get')->middleware('role:viewInvoices');
    Route::get('/invoices/add', [InvoiceController::class, 'create'])->name('invoices.add')->middleware('role:addInvoices');
    Route::post('/invoices/add', [InvoiceController::class, 'store'])->name('invoices.store')->middleware('role:addInvoices');
    Route::get('/invoices/getFollowers/{id}', [InvoiceController::class, 'getFollowers'])->name('get.followers')->middleware('role:viewInvoices');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles')->middleware('role:viewRoles');
    Route::get('/roles/get', [RoleController::class, 'getRoles'])->name('roles.get')->middleware('role:viewRoles');
    Route::get('/roles/{id}/edit/', [RoleController::class, 'edit'])->name('roles.edit')->middleware('role:updateRoles');
    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware('role:updateRoles');


    // AddTaskAmount
    Route::get('/addtaskamount',[AddTaskAmountController::class, 'index'])->name('addtaskamount');
    Route::get('/addtaskamount/get',[AddTaskAmountController::class, 'getaddtaskamount'])->name('getaddtaskamount.get');
    Route::get('/addtaskamount/add', [AddTaskAmountController::class, 'create'])->name('addtaskamount.add');
    Route::post('/addtaskamount/add', [AddTaskAmountController::class, 'store'])->name('addtaskamount.store');
    Route::get('/addtaskamount/getTasks/{id}', [AddTaskAmountController::class, 'getTasks'])->name('get.tasks');

   // Route::get('/convert-dwg', [Controller::class, 'ConvertDwg'])->name('convert.dwg');
   // Route::post('/convert-dwg', [Controller::class, 'ConvertDwgSave'])->name('convert.dwg.save');

   Route::get('/cadeditor', [CadViewerController::class, 'index'])->name("cadeditor");
   Route::get('/cadeditor-new', [CadViewerController::class, 'indexNew'])->name("cadeditorNew");
   Route::get('/cadeditor-app', [CadViewerController::class, 'indexApp'])->name("cadeditorApp");
   Route::get('/layers', [CadViewerController::class, 'layer'])->name("layers");

});

Route::post('/delete-layer', [LayerController::class, 'deleteLayer'])->name('delete-layer');
Route::post('/save-layer', [LayerController::class, 'save'])->name('save-layer');
// Already defined
// Route::get('/layers', [LayerController::class, 'index'])->name('layers');
Route::get('/testeditor', [Controller::class, 'TestEditor'])->name('test.editor');
//Route::get('/editor', [Controller::class, 'Editor'])->name('convert.dwg');
Route::get('/pdftoimage', [Controller::class, 'pdftoimage'])->name('pdftoimage');
Route::post('/show-data', [LayerController::class, 'ShowData'])->name('show-data');

Route::get('/layers/getCompanyLayers', [LayerController::class, 'getCompanyLayers'])->name('layers.getCompanyLayers');

Route::get('/upload', function () {
    return view('upload');
});

Route::post('/upload', [ForgeController::class, 'upload'])->name('forge.upload');
Route::get('/cadtoken', [ForgeController::class, 'cadToken'])->name('cad.token');

Route::get('/api/forge/token', function () {
    return response()->json([
        'access_token' => app(\App\Http\Controllers\ForgeController::class)->authenticate(),
        'expires_in' => 3600 // Token expiry time
    ]);
});

// Route::get('/cadeditor', [CadViewerController::class, 'index'])->name("cadeditor");
// Route::get('/cadeditor-new', [CadViewerController::class, 'indexNew'])->name("cadeditorNew");
// Route::get('/cadeditor-app', [CadViewerController::class, 'indexApp'])->name("cadeditorApp");
// Route::get('/layers', [CadViewerController::class, 'layer'])->name("layers");
