<?php

class Router
{
    private $group;
    private $controller;
    private $method;
    private $param;

    public function __construct()
    {
        $this->matchRoute();
    }

    private function matchRoute()
    {
        $url = explode('/', URL);

        if (preg_match('/^\/admin/', URL)) {
            if (isset($_SESSION[SESS_USER]['id'])) {
                if (($_SESSION[SESS_USER]['company_id'] ?? 0) > 0) {
                    $this->method = !empty($url[3]) ? $url[3] : 'home';
                    $this->controller = !empty($url[2]) ? $url[2] : 'Home';
                    $this->group = 'admin/';
                } else {
                    $this->controller = 'Page';
                    $this->method = 'error403';
                    $this->group = '';
                }
            } else {
                if ($this->requestIsJson()) {
                    http_response_code(403);
                    die();
                } else {
                    $this->method = 'login';
                    $this->controller = 'User';
                }
            }
        } else if (preg_match('/^\/inner/', URL)) {
            if (isset($_SESSION[SESS_USER]['id'])) {
                if (($_SESSION[SESS_USER]['is_inner'] ?? 0) == 1) {
                    $this->method = !empty($url[3]) ? $url[3] : 'home';
                    $this->controller = !empty($url[2]) ? $url[2] : 'Inner';
                    $this->group = 'inner/';
                } else {
                    $this->controller = 'Page';
                    $this->method = 'error403';
                    $this->group = '';
                }
            } else {
                if ($this->requestIsJson()) {
                    http_response_code(403);
                    die();
                } else {
                    $this->method = 'innerLogin';
                    $this->controller = 'User';
                }
            }
        } else {
            $this->method = !empty($url[2]) ? $url[2] : 'home';
            $this->controller = !empty($url[1]) ? $url[1] : 'Page';
        }

        $this->controller = ucwords($this->controller) . 'Controller';
        if (!is_file(CONTROLLER_PATH . "/{$this->group}{$this->controller}.php")) {
            $this->group = '';
            $this->controller = 'PageController';
            // $this->method = 'error404';
            if (count($url) === 2) {
                $this->method = 'home';
            } else {
                $this->method = 'error404';
            }
        }

        require_once(CONTROLLER_PATH . "/{$this->group}{$this->controller}.php");
        if (!method_exists($this->controller, $this->method)) {
            $this->controller = 'PageController';
            $this->method = 'error404';
            require_once(CONTROLLER_PATH . "/{$this->controller}.php");
        }
    }

    private function requestIsJson()
    {
        if (strtolower($_SERVER['HTTP_ACCEPT'] ?? '') === 'application/json' || strtolower($_SERVER['CONTENT_TYPE'] ?? '') === 'application/json') {
            return true;
        }
        return false;
    }

    private function setHeaders(string $contentType = 'json'){
        switch ($contentType) {
            case 'json':
                header('Content-Type: application/json; charset=utf-8');
                break;
            default:
                # code...
                break;
        }
    }

    public function run()
    {
        $database = new Database();
        $res = new Result();

        try {
            $controller = new $this->controller($database->getConnection());
            $method = $this->method;
            $response = $controller->$method($this->param);

            if ($response instanceof Result) {
                $this->setHeaders('json');
                echo json_encode($response);
            }
        } catch (ForbiddenException $e) {
            $res->message = $e->getMessage();
            $res->errorType = 'warning';
            $res->title = 'RESTRICCIÓN';

            if ($this->requestIsJson()) {
                $this->setHeaders('json');
                echo json_encode($res);
                die();
            } else {
                $parameter['message'] = $res->message;
                $content = requireToVar(VIEW_PATH . '/403.view.php', $parameter);
                require_once(VIEW_PATH . "/layouts/site.layout.php");
            }
        } catch (ControlledException $e) {
            $res->message = $e->getMessage();
            $res->errorType = 'warning';
            $res->title = 'VALIDACIÓN';

            if ($this->requestIsJson()) {
                $this->setHeaders('json');
                echo json_encode($res);
                die();
            } else {
                $parameter['message'] = $res->message;
                $content = requireToVar(VIEW_PATH . '/403.view.php', $parameter);
                require_once(VIEW_PATH . "/layouts/site.layout.php");
            }
        } catch (Exception $e) {
            $id = $this->saveException($database->getConnection(), $e);
            $res->errorType = 'error';
            $res->title = 'ERROR NO CONTROLADO';
            $res->message = 'Ha ocurrido un problema no controlado, por favor comuniquese con TI y proporcionele éste numero para que lo ayuden : ' . $id;

            if ($this->requestIsJson()) {
                $this->setHeaders('json');
                echo json_encode($res);
                die();
            } else {
                $parameter['message'] = $res->message;
                $content = requireToVar(VIEW_PATH . '/500.view.php', $parameter);
                require_once(VIEW_PATH . "/layouts/site.layout.php");
            }
        }
    }

    private function saveException(PDO $conection, Exception $e)
    {
        $content = '';
        if ($this->requestIsJson()) {
            $content = file_get_contents('php://input');
        } else {
            $content = json_encode($_POST);
        }

        $stmt = $conection->prepare('INSERT INTO log_exceptions (content, host, path, stack, message, created_at, created_user)
                                                    VALUES (:content, :host, :path, :stack, :message, :created_at, :created_user)');
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':host', HOST . PORT);
        $stmt->bindValue(':path', URL_PATH);
        $stmt->bindValue(':stack', $e->getTraceAsString());
        $stmt->bindValue(':message', $e->getMessage());
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':created_user', $_SESSION[SESS_USER]['user_name'] ?? '');

        $stmt->execute();

        return $conection->lastInsertId();
    }
}
