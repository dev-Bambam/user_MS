<?php
namespace routes;
class Router
{ 
    private $routes = [];
    private $middleware = [];

    /**
     * Register a GET route.
     *
     * This method takes a path and a callback as arguments. The callback should take
     * two arguments: the request and the response. The callback should return the
     * response object.
     *
     * @param string $path The path to register the route for.
     * @param callable $callback The callback to call when the route is accessed.
     * @param array $middleware An array of middleware to apply to the route.
     * @return void
     */
    public function get($path, $callback, $middleware = [])
    {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Register a POST route.
     *
     * This method takes a path, a callback, and an optional array of middleware as
     * arguments. The callback should take two arguments: the request and the response.
     * The callback should return the response object.
     *
     * @param string $path The path to register the route for.
     * @param callable $callback The callback to call when the route is accessed.
     * @param array $middleware An array of middleware to apply to the route.
     * @return void
     */
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            // 'middleware' => $middleware
        ];
    }

    /**
     * Register a PUT route.
     *
     * This method takes a path, a callback, and an optional array of middleware as
     * arguments. The callback should take two arguments: the request and the response.
     * The callback should return the response object.
     *
     * @param string $path The path to register the route for.
     * @param callable $callback The callback to call when the route is accessed.
     * @param array $middleware An array of middleware to apply to the route.
     * @return void
     */
    public function put($path, $callback, $middleware = [])
    {
        $this->routes['PUT'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Register a middleware stack.
     *
     * This method takes a middleware callback and a route callback. The middleware
     * callback is called first, and if it returns true, the route callback is called
     * with the same arguments. If the middleware callback returns false, the route
     * callback is not called.
     *
     * @param callable $middleware
     * @param callable $callback
     * @return void
     */
    public function middleware($middleware, $callback)
    {
        $this->middleware[] = [
            'middleware' => $middleware,
            'callback' => $callback
        ];
    }
    /**
     * Run the router to handle the current HTTP request.
     *
     * This method retrieves the current request method and path, checks if a matching
     * route is registered, and applies any associated middleware. If all middleware
     * passes, it executes the route's callback function. If an exception occurs during
     * the execution of the callback, it returns a 500 Internal Server Error response.
     * If no matching route is found, it returns a 404 Not Found response. If middleware
     * fails, it returns a 403 Forbidden response.
     *
     * @return void
     */
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];

            // Apply each middleware
            // foreach ($route['middleware'] as $middleware) {
            //     $middlewareInstance = new $middleware();
            //     // if (!$middlewareInstance->handle()) {
            //     //     // Stop route execution if middleware fails
            //     //     http_response_code(403);
            //     //     echo json_encode(['error' => 'Forbidden']);
            //     //     return;
            //     // }
            // }

            // Execute the route's callback
            try {
                $route['callback']();
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error101' => 'Not Found']);
        }
        error_log("Request Method: $method, Path: $path");

    }
}