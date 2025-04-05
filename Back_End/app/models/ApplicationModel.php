<?php

namespace App\Models;

class ApplicationModel extends Model
{
    static $resultLimit = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($application)
    {
        $query = "
            INSERT INTO applications (user_id, name, email, job_id, resume_path, cover_letter)
            VALUES (:user_id, :name, :email, :job_id, :resume_path, :cover_letter)
        ";
    
        $statement = self::$pdo->prepare($query);
        $statement->execute([
            "user_id"      => $application["user_id"],
            "name"         => $application["name"],
            "email"        => $application["email"],
            "job_id"       => $application["job_id"],
            "resume_path"  => $application["resume_path"],
            "cover_letter" => $application["cover_letter"]
        ]);
    
        return $this->get(self::$pdo->lastInsertId());
    }
    

    public function get($id)
    {
        $statement = self::$pdo->prepare("SELECT * FROM applications WHERE id = :id");
        $statement->execute(["id" => $id]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}
