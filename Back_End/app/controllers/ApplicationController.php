<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Services\ResponseService;
use Exception;

class ApplicationController extends Controller
{
    private $applicationModel;

    public function __construct()
    {
        $this->applicationModel = new ApplicationModel();
    }

    public function store()
    {
        try {
            if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Resume file is required");
            }
    
            // === Create resume folder if not exists ===
            $resume = $_FILES['resume'];
            $uploadDir = __DIR__ . '/../public/uploads/resumes/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // === Sanitize filename ===
            $originalName = pathinfo($resume['name'], PATHINFO_FILENAME);
            $extension = pathinfo($resume['name'], PATHINFO_EXTENSION);
            $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
            $fileName = uniqid() . '_' . $sanitizedName . '.' . $extension;
    
            // === Validate extension (optional) ===
            $allowedExts = ['pdf', 'doc', 'docx'];
            if (!in_array(strtolower($extension), $allowedExts)) {
                throw new Exception("Invalid file type. Only PDF, DOC, and DOCX are allowed.");
            }
    
            // === Save file ===
            $targetPath = $uploadDir . $fileName;
            move_uploaded_file($resume['tmp_name'], $targetPath);
            $resumePath = '/uploads/resumes/' . $fileName; // ✅ relative path
    
            // === POST fields ===
            $userId = !empty($_POST['user_id']) ? $_POST['user_id'] : null;
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $jobId = $_POST['job_id'] ?? null;
            $coverLetter = $_POST['coverLetter'] ?? null;
    
            if (!$name || !$email || !$jobId) {
                throw new Exception("Missing required fields (name, email, or job ID)");
            }
    
            // === Create application ===
            $createdApplication = $this->applicationModel->create([
                "user_id" => $userId,
                "name" => $name,
                "email" => $email,
                "job_id" => $jobId,
                "resume_path" => $resumePath,
                "cover_letter" => $coverLetter
            ]);
    
            ResponseService::Success("Application submitted successfully", $createdApplication);
        } catch (Exception $e) {
            ResponseService::Error($e->getMessage(), 500);
        }
    }
    

}
