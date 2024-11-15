<?php
namespace Middleware;

class LoggingMiddleware
{
    public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        error_log("[$method] $path");
        // Additional logging can go here
    }
}
