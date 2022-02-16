<?php
namespace simphplio\helpers;
use Exception;

class TemplateHelper
{

    public string|false $file = false;
    public array $tags = array();
    public int $tags_count = 0;

    public function open($file): TemplateHelper
    {
        try {
            if (file_exists($file)) {
                $this->file = file_get_contents($file);
            }

            if (!$this->file) {
                throw new Exception("Arquivo view não foi encontrado ou não foi possível ler.");
            }
        } catch (Exception $exception) {
            var_dump($exception);
        }
        return $this;
    }

    public function set($tag, $value): TemplateHelper
    {
            $this->tags[$this->tags_count++] = array(
                    "Name" => $tag,
                    "Value" => $value
            );

            return $this;
    }

    private function render(): TemplateHelper
    {
        $Count_Sets = 0;
        for (; $Count_Sets < count($this->tags); ++$Count_Sets) {
                $this->file = str_replace("{#" . $this->tags[$Count_Sets]['Name'] . "}", $this->tags[$Count_Sets]['Value'], $this->file);
        }
        return $this;
    }

    public function templateToString(): string
    {
        return $this->file;
    }
    public function view(): void
    {
        $this->render();
        print_r($this->file);
    }
}
