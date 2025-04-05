<?php
namespace App\Controllers;

use App\Models\Job;
use App\Services\ResponseService;
use Exception;

class JobController extends Controller
{
    private $jobModel;

    public function __construct()
    {
        $this->jobModel = new Job();
    }

    /**
     * GET /api/jobs - List all jobs (with pagination)
     */
    public function index()
    {
        $page = (int)($_GET["page"] ?? 1);
        try {
            $jobs = $this->jobModel->getAll($page);
            ResponseService::Send($jobs);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }

    /**
     * GET /api/jobs/:id - Get a single job by ID
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            ResponseService::Error("Invalid job ID", 400);
            return;
        }
        try {
            $job = $this->jobModel->getOne($id);
            if (!$job) {
                ResponseService::Error("Job not found", 404);
                return;
            }
            ResponseService::Send($job);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }

            /**
     * Filtering clearly by dropdown JobType
     * GET /api/jobs?page=1&jobType=...
     */
    public function getAllFilterByType()
    {
        $page = (int)($_GET["page"] ?? 1);
        $jobType = $_GET["jobType"] ?? null;

        try {
            $jobs = $this->jobModel->getAllFilteredByType($page, $jobType);
            ResponseService::Send($jobs);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }

        public function types()
    {
        try {
            $types = $this->jobModel->getJobTypes();
            ResponseService::Send($types);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }

    /**
     * Filtering clearly by typing in search bar
     * GET /api/jobs/search?page=1&search=...
     */
    public function getAllFilterByTitle()
    {
        $page = (int)($_GET["page"] ?? 1);
        $searchText = $_GET["search"] ?? null;

        try {
            $jobs = $this->jobModel->getAllFilteredByTitle($page, $searchText);
            ResponseService::Send($jobs);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }

    /**
     * POST /api/jobs - Create a new job
     */
    public function store()
    {
        try {
            $data = $this->decodePostData();
            $this->validateInput(["title", "description", "location", "salary"], $data);
            $newId = $this->jobModel->create($data);
            $newJob = $this->jobModel->getOne($newId);
            ResponseService::Send($newJob, 201);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 400);
        }
    }

    /**
     * PUT /api/jobs/:id - Update an existing job
     */
    public function update($id)
    {
        if (!is_numeric($id)) {
            ResponseService::Error("Invalid job ID", 400);
            return;
        }
        try {
            $data = $this->decodePostData();
            $this->validateInput(["title", "description", "location", "salary"], $data);
            $this->jobModel->updateOne($id, $data);
            $updatedJob = $this->jobModel->getOne($id);
            if (!$updatedJob) {
                ResponseService::Error("Job not found after update", 404);
                return;
            }
            ResponseService::Send($updatedJob);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 400);
        }
    }

    /**
     * DELETE /api/jobs/:id - Delete a job
     */
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            ResponseService::Error("Invalid job ID", 400);
            return;
        }
        try {
            $job = $this->jobModel->getOne($id);
            if (!$job) {
                ResponseService::Error("Job not found", 404);
                return;
            }
            $this->jobModel->deleteOne($id);
            ResponseService::Send([], 204);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 400);
        }
    }
}
