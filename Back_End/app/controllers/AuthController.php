<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register()
    {
        $data = $this->decodePostData();
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            ResponseService::Error("Name, email & password are required", 400);
            return;
        }
    
        if ($this->userModel->findByEmail($data['email'])) {
            ResponseService::Error("Email already exists", 400);
            return;
        }
    
        try {
            $user = $this->userModel->create($data['name'], $data['email'], $data['password'], 'user');
            ResponseService::Success("User registered successfully", $user, 201);
        } catch (Exception $e) {
            ResponseService::Error("Registration failed: " . $e->getMessage(), 400);
        }
    }

    public function login()
    {
        $data = $this->decodePostData();
        if (empty($data['email']) || empty($data['password'])) {
            ResponseService::Error("Email & password required", 400);
            return;
        }

        try {
            $user = $this->userModel->findByEmail($data['email']);
            if (!$user || !password_verify($data['password'], $user['password'])) {
                ResponseService::Error("Invalid credentials", 401);
                return;
            }

            // Generate JWT
            $token = $this->generateJWT($user);
            ResponseService::Send(["token" => $token], 200);
        } catch (Exception $e) {
            ResponseService::Error("Login failed: " . $e->getMessage(), 400);
        }
    }

        private function generateJWT($user)
    {
        $issuedAt = time();
        $expire = $issuedAt + (3600 * 4); // 4 hours

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'sub' => $user['id'], // ✅ This is key
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'] ?? 'user'
            ]
        ];

        return JWT::encode($payload, $_ENV["JWT_SECRET"], 'HS256');
    }

    public function me()
    {
            $authUser = $this->getAuthenticatedUser();
        if (!$authUser) {
            ResponseService::Error("Not authenticated", 401);
            return;
        }

        // ✅ Fetch the latest user info from database
        $freshUser = $this->userModel->findById($authUser['id']);

        if (!$freshUser) {
            ResponseService::Error("User not found", 404);
            return;
        }

        ResponseService::Send($freshUser);
    }

    /**
     * EXAMPLE: /auth/is-me/123
     * Checks if the current authenticated user is the same as :id param
     */
    public function isMe($id)
    {
            $authUser = $this->getAuthenticatedUser();
        if (!$authUser) {
            ResponseService::Error("Not authenticated", 401);
            return;
        }

        // ✅ Fetch the latest info from DB instead of decoded token
        $userFromDb = $this->userModel->findById($authUser['id']);
        if (!$userFromDb) {
            ResponseService::Error("User not found", 404);
            return;
        }

        // ✅ Return updated user
        ResponseService::Send($userFromDb);
    }


    public function getAuthenticatedUser()
    {
        // Check the Authorization header for "Bearer <token>"
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return null;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, new Key($_ENV["JWT_SECRET"], 'HS256'));
            // cast $decoded->user (stdClass) to array
            return (array) $decoded->user;
        } catch (Exception $e) {
            return null;
        }
    }
}
