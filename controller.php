<?php
namespace simphplio;

use simphplio\helpers\TemplateHelper;

abstract class Controller extends System
{
    private TemplateHelper $templateHelper;
    public function __construct()
    {
        parent::__construct();
        $this->templateHelper = new TemplateHelper();
    }

    protected function view($nome, $vars = null)
    {
        $this->templateHelper->tags = $this->tags;
        $this->templateHelper->tags_count = $this->tags_count;
        $this->templateHelper->open(VIEWS . "/" . $nome . ".html")->view();
    }
}
