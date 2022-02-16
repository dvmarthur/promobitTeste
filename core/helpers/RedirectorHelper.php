<?php
namespace simphplio\helpers;

class RedirectorHelper
{
    protected array $parameters = array();


    protected function go($data)
    {
        header("Location: /" . $data);
    }

    public function setUrlParameter($name, $value): RedirectorHelper
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    protected function getUrlParameters(): string
    {
        $parms = "";
        foreach ($this->parameters as $name => $value) {
            $parms .= $name . '/' . $value . '/';
        } //Analisar troca de '/' por '&'
        return $parms;
    }
    public function showUrlParameters()
    {
        return $this->getUrlParameters();
    }

    public function goToController($controller): void
    {
        $this->go($controller . 'index' . $this->getUrlParameters());
    }

    public function goToAction($action): void
    {
        $this->go($this->getCurrentController() . '/' . $action . '/' . $this->getUrlParameters());
    }

    public function goToControllerAction($controller, $action): void
    {
        $this->go($controller . '/' . $action . '/' . $this->getUrlParameters());
    }

    public function goToIndex(): void
    {
        $this->goToController('index');
    }

    public function goToUrl($url): void
    {
        //header("Content-type:application/json");
        header("location: " . $url);
    }

    public function getCurrentController(): string
    {
 //protected
        global $start;
        return $start->_controller;
    }

    public function getCurrentAction(): string
    {
 //protected
        global $start;
        return $start->_action;
    }
}
