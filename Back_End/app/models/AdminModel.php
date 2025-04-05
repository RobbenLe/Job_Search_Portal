<?php

namespace App\Models;

class AdminModel extends Model
{
    static $resultLimit = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllJobs()
    {
        $stmt = self::$pdo->query("SELECT * FROM jobs ORDER BY created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createJob($data)
    {
        $stmt = self::$pdo->prepare("
            INSERT INTO jobs (title, jobType, description, requirements, location, salary, status, admin_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['title'],
            $data['jobType'],
            $data['description'],
            $data['requirements'],
            $data['location'],
            $data['salary'],
            'open', // default status
            $data['admin_id'] // passed from JWT user ID
        ]);

        return $this->findJobById(self::$pdo->lastInsertId());
    }

    public function findJobById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateJob($id, $data)
    {
        $stmt = self::$pdo->prepare("
            UPDATE jobs SET 
                title = :title, 
                description = :description, 
                requirements = :requirements, 
                location = :location, 
                salary = :salary, 
                jobType = :jobType
            WHERE id = :id
        ");

        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'requirements' => $data['requirements'],
            'location' => $data['location'],
            'salary' => $data['salary'],
            'jobType' => $data['jobType'],
            'id' => $id
        ]);

        return $this->findJobById($id);
    }


    public function deleteJob($id)
    {
        $stmt = self::$pdo->prepare("DELETE FROM jobs WHERE id = ?");
        return $stmt->execute([$id]);
    }

//////////////////////////////////////////Application Part
public function getAllApplicationsSmart()
{
    $stmt = self::$pdo->query("
        SELECT 
            applications.id AS application_id,
            COALESCE(users.name, applications.name) AS name,
            COALESCE(users.email, applications.email) AS email,
            applications.cover_letter,
            applications.resume_path,
            applications.application_status,
            applications.created_at,
            jobs.title AS job_title  -- ✅ Add job title here
        FROM applications
        LEFT JOIN users ON applications.user_id = users.id
        LEFT JOIN jobs ON applications.job_id = jobs.id  -- ✅ Join jobs table
        ORDER BY applications.created_at DESC
    ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}


        public function updateApplicationStatus($applicationId, $status)
    {
        $stmt = self::$pdo->prepare("
            UPDATE applications
            SET application_status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$status, $applicationId]);
    }
}

