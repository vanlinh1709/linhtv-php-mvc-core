<?php
namespace app\core;

use app\core\exception\ForbiddenException;
use app\core\exception\NotFoundException;

class Router
{

    public Request $request;
    public Response $response;
    //route: tuyen duong
    protected array $routes = [];
    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    //C/n: khởi tạo ten và cảnh quan của tuyến đường dạng get
    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }
    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }
    public function resolve()
    {
        //lay ten duong nguoi dung yeu cau
        $path = $this->request->getPath();
        $method = $this->request->method();
        //lay canh quan cua tuyen duong
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {//khong ton tai noi dung tuyen duong
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }
        if(is_string($callback)) {
            return Application::$app->view->renderView($callback);
        };
        $controller = $callback[0];
        Application::$app->controller = $controller;
        $controller->action = $callback[1];
        $callback[0] = new $controller();
        foreach ($controller->getMiddlewares() as $middleware) {
            $middleware->execute();
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}