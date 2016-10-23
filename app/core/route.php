<?php
class Route
{
    static function start()
    {
        $router = new Router();
        $router->add('/',['controller'=>'main','action'=>'main']);
        $router->add('/search',['controller'=>'main','action'=>'search']);

        $router->add('/create-data',['controller'=>'main','action'=>'create_data']);

        $parameters = $router->find_route();
        //names
        $controller_name = 'C_'.$parameters['controller'];
        $model_name = 'M_'.$parameters['controller'];
        //files
        $controller_file = strtolower($controller_name);
        $model_file = strtolower($model_name);
        //paths
        $controller_path = 'app/controllers/'. $controller_file.'.php';
        $model_path = 'app/model/'.$model_file.'.php';

        if(file_exists($controller_path))
        {
            require_once $controller_path;
        }else{
            Route::error404('cant find controller '. $controller_name);
        }
        $controller = new $controller_name;
        $controller->params = $parameters;
        if(file_exists($model_path))
        {
            require_once $model_path;
            $model  = new $model_name;
            $controller->model = $model;
        }
        $action = $parameters['action'];
        if(method_exists($controller,$action))
        {
           $controller->$action();
        }else{
            $message =  'cant find action '. $parameters['action'] .' in controller '. $controller_name;
            Route::error404($message);
        }
    }
    static function error404($message = '')
    {
        if(Router::isAjax())
        {
            echo json_encode(['errors'=>'Запрашиваемый адрес не существует']);
            die;
        }
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        require_once 'app/controllers/c_error.php';
        $error = new C_error();
        $error->index();
        die;
    }
}