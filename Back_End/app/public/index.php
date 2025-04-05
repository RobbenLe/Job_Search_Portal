<?php
/**
 * Setup
 */

// require autoload file to autoload vendor libraries
require_once __DIR__ . '/../vendor/autoload.php';

// require local classes
use App\Services\EnvService;
use App\Services\ErrorReportingService;
use App\Services\ResponseService;
use App\Controllers\AuthController;
use App\Controllers\JobController;
use App\Controllers\ApplicationController;
use App\Controllers\AdminController;
use Steampixel\Route;

// Initialize global environment variables
EnvService::Init();

// Initialize error reporting (on in local env)
ErrorReportingService::Init();

// Set CORS headers
ResponseService::SetCorsHeaders();

/**
 * Main application routes
 */
try {
    // --- Auth Routes ---
    Route::add('/auth/register', function () {
        $authController = new AuthController();
        $authController->register();
    }, ["post"]);

    Route::add('/auth/login', function () {
        $authController = new AuthController();
        $authController->login();
    }, ["post"]);

    Route::add('/auth/me', function () {
        $authController = new AuthController();
        $authController->me();
    }, ["get"]);

    Route::add('/auth/is-me/([0-9]*)', function ($id) {
        $authController = new AuthController();
        $authController->isMe($id);
    }, 'get');
    Route::add('/auth/update', function () {
        $userCtrl = new \App\Controllers\UserController();
        $userCtrl->updateMe();
    }, ['put']);

    // --- Job Routes ---
    // GET /api/jobs (with pagination support via ?page=)
    Route::add('/api/jobs', function() {
        $jobCtrl = new JobController();
        $jobCtrl->index();
    }, ['get']);

    Route::add('/api/jobs/types', function() {
        $jobCtrl = new JobController();
        $jobCtrl->types();
    }, ['get']);

    // Job Title Search filtering
    Route::add('/api/jobs/search', function() {
        $jobCtrl = new JobController();
        $jobCtrl->getAllFilterByTitle();
    }, ['get']);

    // GET /api/jobs/:id
    Route::add('/api/jobs/([0-9]*)', function($id) {
        $jobCtrl = new JobController();
        $jobCtrl->show($id);
    }, ['get']);

    // POST /api/jobs
    Route::add('/api/jobs', function() {
        $jobCtrl = new JobController();
        $jobCtrl->store();
    }, ['post']);

    // PUT /api/jobs/:id
    Route::add('/api/jobs/([0-9]*)', function($id) {
        $jobCtrl = new JobController();
        $jobCtrl->update($id);
    }, ['put']);

    // DELETE /api/jobs/:id
    Route::add('/api/jobs/([0-9]*)', function($id) {
        $jobCtrl = new JobController();
        $jobCtrl->destroy($id);
    }, ['delete']);

    //////////////////////////////////Application

    // POST /api/applications
    Route::add('/api/applications', function () {
        $appCtrl = new ApplicationController();
        $appCtrl->store();
    }, ['post']);

    Route::add('/admin/applications/([0-9]*)/status', function ($id) {
        (new \App\Controllers\AdminController())->updateApplicationStatus($id);
    }, ['put']);

        // GET /api/admin/applications
    Route::add('/api/admin/applications', function () {
        (new \App\Controllers\AdminController())->listApplications();
    }, ['get']);


        // --- ADMIN ROUTES ---
    Route::add('/admin/jobs', function () {
        (new \App\Controllers\AdminController())->listJobs();
    }, ['get']);

    // Admin creates a job
    Route::add('/api/admin/jobs', function() {
        $adminCtrl = new AdminController();
        $adminCtrl->store();
    }, ['post']);

    Route::add('/admin/jobs/([0-9]*)', function ($id) {
    (new \App\Controllers\AdminController())->updateJob($id);
    }, ['put']);

    Route::add('/admin/jobs/([0-9]*)', function ($id) {
        (new \App\Controllers\AdminController())->deleteJob($id);
    }, ['delete']);

    Route::add('/admin/applications', function () {
        (new \App\Controllers\AdminController())->listApplications();
    }, ['get']);



    // 404 route handler
    Route::pathNotFound(function () {
        ResponseService::Error("route is not defined", 404);
    });
} catch (\Throwable $error) {
    if (isset($_ENV["ENV"]) && $_ENV["ENV"] === "LOCAL") {
        var_dump($error);
    } else {
        error_log($error);
    }
    ResponseService::Error("A server error occurred");
}

Route::run();
