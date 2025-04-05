<?php
namespace App\Models;

use Exception;
use PDO;

class Job extends Model
{
    static $resultLimit = 10;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all jobs (ordered by created_at DESC) with pagination.
     */
    public function getAll($page = 1)
    {
        $offset = ($page - 1) * self::$resultLimit;
        try {
            $stmt = self::$pdo->prepare("SELECT * FROM jobs ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', self::$resultLimit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching jobs: " . $e->getMessage());
        }
    }

    /**
     * Get a single job by ID.
     */
    public function getOne($id)
    {
        try {
            $stmt = self::$pdo->prepare("SELECT * FROM jobs WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching job $id: " . $e->getMessage());
        }
    }

        public function getJobTypes()
    {
        try {
            $stmt = self::$pdo->query("SELECT DISTINCT jobType FROM jobs WHERE jobType IS NOT NULL AND jobType <> ''");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            throw new Exception("Error fetching job types: " . $e->getMessage());
        }
    }

        /**
     * Get jobs filtered clearly by JobType with pagination.
     */
    public function getAllFilteredByType($page = 1, $jobType = null)
    {
        $offset = ($page - 1) * self::$resultLimit;

        $sql = "SELECT * FROM jobs";
        $params = [];

        if (!empty($jobType)) {
            $sql .= " WHERE jobType = :jobType";
            $params[':jobType'] = $jobType;
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        try {
            $stmt = self::$pdo->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', self::$resultLimit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching jobs by type: " . $e->getMessage());
        }
    }

    
    /**
     * Get jobs filtered clearly by Job Title with pagination.
     */
    public function getAllFilteredByTitle($page = 1, $searchText = null)
    {
        $offset = ($page - 1) * self::$resultLimit;

        $sql = "SELECT * FROM jobs";
        $params = [];

        if (!empty($searchText)) {
            $sql .= " WHERE title LIKE :searchText";
            $params[':searchText'] = "%" . $searchText . "%";
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        try {
            $stmt = self::$pdo->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', self::$resultLimit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching jobs by title: " . $e->getMessage());
        }
    }


    /**
     * Create a new job.
     * Expects fields like 'title', 'description', 'location', 'salary' in $data.
     */
    public function create(array $data)
    {
        if (empty($data['title'])) {
            throw new Exception("Job title is required");
        }

        try {
            $stmt = self::$pdo->prepare("
                INSERT INTO jobs (title, description, location, salary, created_at)
                VALUES (:title, :description, :location, :salary, NOW())
            ");
            $stmt->execute([
                'title'       => $data['title'],
                'description' => $data['description'] ?? '',
                'location'    => $data['location']    ?? '',
                'salary'      => $data['salary']      ?? 0.00
            ]);
            return self::$pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error creating job: " . $e->getMessage());
        }
    }

    /**
     * Update an existing job.
     */
    public function updateOne($id, array $data)
    {
        try {
            $stmt = self::$pdo->prepare("
                UPDATE jobs
                SET title = :title,
                    description = :description,
                    location = :location,
                    salary = :salary,
                    updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                'id'          => $id,
                'title'       => $data['title']       ?? '',
                'description' => $data['description'] ?? '',
                'location'    => $data['location']    ?? '',
                'salary'      => $data['salary']      ?? 0.00
            ]);
        } catch (Exception $e) {
            throw new Exception("Error updating job $id: " . $e->getMessage());
        }
    }

    /**
     * Delete a job by ID.
     */
    public function deleteOne($id)
    {
        try {
            $stmt = self::$pdo->prepare("DELETE FROM jobs WHERE id = ?");
            $stmt->execute([$id]);
        } catch (Exception $e) {
            throw new Exception("Error deleting job $id: " . $e->getMessage());
        }
    }

}
