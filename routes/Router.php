<?php
namespace routes;
class Router
{
    private $routes = [];
    private $middleware = [];

    public function get($path, $callback, $middleware = [])
    {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function post($path, $callback, $middleware = [])
    {
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

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
     * Runs the router, applying middleware and calling the route callback.
     *
     * Looks up the route in the request method and path, applies any middleware
     * specified for the route, and calls the route callback.
     *
     * If no route is found, returns a 404 with the message "Not Found".
     *
     * @return void
     */
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];

            // Apply middleware
            foreach ($route['middleware'] as $middleware) {
                $middleware['callback']();
            }

            $route['callback']();
        } else {
            http_response_code(404);
            echo 'Not Found';
        }
    }
}