<?php
namespace Etu\Mvc\View;

use \Etu\Mvc\Exception;

class Html extends \Etu\Mvc\View {
    /**
     * application root path
     *
     * @var string
     **/
    protected $root = null;

    /**
     * view file path
     *
     * @var string
     **/
    protected $path = null;

    protected function __construct($root) {
        $root = rtrim(str_replace('\\', '/', $root), '/');
        if(!is_dir($root)) {
            throw new ViewException('ROOT directory not exists');
        }
    }

    /**
     * render view
     *
     * @param string $path
     * @param array $data
    **/
    public function render($path, array $data) {
        return $this->includeView($path, $data, true);
    }

    /**
     * include the view
     *
     * @return string OR null
     * @author MoceanLiu
     **/
    public function includeView($path, array $data, $is_return = true) {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if(!file_exists($file_path = sprintf('%s/%s.php', $this->root, $path))) {
            throw new ViewException(sprintf('file "%s" not exists!', $file_path));
        }
        
        $level = ob_get_level();
        ob_start();

        try {
            extract($data);
            require($path);
        } catch(\Exception $e) {
            if(ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }

        $content = ob_get_clean();

        if($is_return) {
            return $content;
        }

        echo $content;
    }
    //TODO 增加方便输出html的函数
}
