<?php
namespace Middleware;

class AuthMiddleware
{
    public function handle()
    {
        // Check if the user is authenticated by verifying a token or session
        if (!isset($_SESSION['user_id'])) { // example check
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }
    }
}
