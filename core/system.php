<?php

namespace simphplio;

use simphplio\helpers\TemplateHelper;
use Throwable;

class System
{
    private static System $instance;
    private string $_url;
    private array|false $_explode;
    public string $_controller;
    public string $_action;
    public array $_params;
    public array $tags = array();
    public int $tags_count = 0;


    public function __construct()
    {
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();
        $this->setEnvironmentVars();
    }

    public static function getInstance(): System
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function setMessages(): void
    {
        if (!empty($_SESSION['return_messages'])) {
            $this->set("RETURN_MESSAGES", $_SESSION['return_messages']);
            unset($_SESSION['return_messages']);
        } else {
            $this->set("RETURN_MESSAGES", "");
        }
    }

    public function array_change_key_case_recursive($arr, $case = CASE_LOWER): array
    {
        return array_map(function ($item) use ($case) {

            if (is_array($item)) {
                $item = System::array_change_key_case_recursive($item, $case);
            }
            return $item;
        }, array_change_key_case($arr, $case));
    }


    public function setEnvironmentVars(): void
    {
        $this->set('APP_URL', APP_URL);
        $this->set('APP_NAME', APP_NAME);
        $this->set('CACHE_TIMESTAMP', CACHE_TIMESTAMP);
    }

    public function set($tag, $value): void
    {
            $this->tags[$this->tags_count++] = array(
                    "Name" => $tag,
                    "Value" => $value
            );
    }

    private function setUrl(): void
    {
        $_GET['url'] = ($_GET['url'] ?? 'index/index_action');
        $this->_url = $_GET['url'] . '/' ;
    }

    private function setExplode(): void
    {
        $this->_explode = explode('/', $this->_url);
    }

    private function setController(): void
    {
        $this->_controller = $this->_explode[0];
    }

    private function setAction(): void
    {
        $ac = (!isset($this->_explode[1]) || $this->_explode[1] == null || $this->_explode[1] == 'index' ? 'index_action' : $this->_explode[1]);
        $this->_action = $ac;
    }

    private function setParams(): void
    {
        $value = array();
        $ind = array();
        unset($this->_explode[0], $this->_explode[1]);
        array_pop($this->_explode);
        if (end($this->_explode) == null) {
            array_pop($this->_explode);
        }

        $i = 0;
        if (!empty($this->_explode)) {
            foreach ($this->_explode as $val) {
                if ($i % 2 == 0) {
                    $ind[] = $val;
                } else {
                    $value[] = $val;
                }
                $i++;
            }
        } else {
            $ind = array();
            $value = array();
        }
        if (count($ind) == count($value) && !empty($ind) && !empty($value)) {
            $this->_params = array_combine($ind, $value);
        } else {
            $this->_params = array();
        }
    }

    public function getParam($name = null): string|bool|array
    {
        if ($name != null) {
            if (array_key_exists($name, $this->_params)) {
                return $this->_params[$name];
            } else {
                return false;
            }
        } else {
            return $this->_params;
        }
    }

    private function show_404(): void
    {
        header("HTTP/1.0 404 Not Found");
        $templateHelper = new TemplateHelper();
        $templateHelper->open(VIEWS . "/404.html")
        ->set('APP_URL', APP_URL)
        ->set('APP_NAME', APP_NAME)
        ->render()->view();
        exit;
    }

    public function run(): void
    {

        try {
            $controller_path = CONTROLLERS . $this->_controller . 'Controller.php';
            if (!file_exists($controller_path)) {
                $this->show_404();
            }
            require_once($controller_path);
            $app = new $this->_controller();

            if (!method_exists($app, $this->_action)) {
                $this->show_404();
            }

            $action = $this->_action;
            $app->$action();
        } catch (Throwable $exception) {
            var_dump($exception);
        }
    }
}