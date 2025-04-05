<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function updateMe()
{
    try {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!$authHeader) {
            throw new Exception("Authorization header missing");
        }

        $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION'] ?? '');
        $payload = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        $userId = $payload->sub ?? null;

        if (!$userId) {
            throw new Exception("Invalid token: missing user ID (sub)");
        }
        
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['email']) && empty($input['password'])) {
            throw new Exception("At least one field (email or password) must be provided.");
        }

        $updatedUser = $this->userModel->update($userId, $input);

        ResponseService::Success("Profile updated successfully", $updatedUser);
    } catch (\InvalidArgumentException $e) {
        ResponseService::Error("Validation Error: " . $e->getMessage(), 400);
    } catch (\Firebase\JWT\ExpiredException $e) {
        ResponseService::Error("Token expired", 401);
    } catch (\Exception $e) {
        ResponseService::Error("Update failed: " . $e->getMessage(), 500);
    }
}
}
