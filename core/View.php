<?php

namespace core;

use Exception;


class View
{

    /**
     * @var string the page title
     */
    public $title;
    public $asdasd;
    public $layout = 'default';


    /**
     * @param string $view
     * @param array $vars
     * @throws Exception
     */
    public function render($view, $vars = [])
    {
        $content = $this->renderPartial($view, $vars);
        return $this->renderContent($content);
    }

    public function renderPartial($view, $vars = [])
    {
        $viewFile = 'application/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            return $this->renderPhpFile($viewFile, $vars);
        } else {
            throw new Exception(sprintf('{ %s } this view not found', $viewFile));
        }
    }

    protected function renderContent($content)
    {
        $layoutFile = 'application/views/layouts/' . $this->layout . '.php';
        if (file_exists($layoutFile)) {
            try {
                return $this->renderPhpFile($layoutFile, ['content' => $content]);
            } catch (\Throwable $e) {
            }
        }
        return $content;
    }

    protected function renderPhpFile($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }




}