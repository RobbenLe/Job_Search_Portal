<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Services\ResponseService;
use Exception;

class AdminController extends Controller
{
    private $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function listJobs()
    {
        $jobs = $this->adminModel->getAllJobs();
        ResponseService::Send($jobs);
    }

    public function updateJob($id)
    {
        $data = $this->decodePostData();
        $job = $this->adminModel->updateJob($id, $data);
        ResponseService::Success("Job updated", $job);
    }

    public function deleteJob($id)
    {
        $this->adminModel->deleteJob($id);
        ResponseService::Success("Job deleted successfully");
    }

    public function listApplications()
    {
        $apps = $this->adminModel->getAllApplicationsSmart();
        ResponseService::Send($apps);
    }

        public function store()
    {
        try {
            $admin = $this->getAuthenticatedUser();
            if ($admin->role !== 'admin') { // Accessing property using '->'
                ResponseService::Error("Unauthorized", 403);
                return;
            }

            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['title']) || empty($data['jobType']) || empty($data['description']) || empty($data['requirements']) || empty($data['location']) || empty($data['salary'])) {
                ResponseService::Error("All fields are required", 400);
                return;
            }

            $data['admin_id'] = $admin->id; // Accessing property using '->'

            $job = $this->adminModel->createJob($data);

            ResponseService::Success("Job created successfully", $job, 201);
        } catch (\PDOException $e) {
            ResponseService::Error("Database error: " . $e->getMessage(), 500);
        } catch (Exception $e) {
            ResponseService::Error("Server error: " . $e->getMessage(), 500);
        }
    }

        public function updateApplicationStatus($id)
    {
        $data = $this->decodePostData();
        if (empty($data['status'])) {
            ResponseService::Error("Status is required", 400);
            return;
        }

        $validStatuses = ['pending', 'reviewed', 'accepted'];
        if (!in_array($data['status'], $validStatuses)) {
            ResponseService::Error("Invalid status provided", 400);
            return;
        }

        $success = $this->adminModel->updateApplicationStatus($id, $data['status']);
        if ($success) {
            ResponseService::Success("Application status updated successfully");
        } else {
            ResponseService::Error("Failed to update application status", 500);
        }
    }


}
