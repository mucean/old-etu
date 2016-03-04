<?php
namespace Etu\Mvc\View;

class Json extends \Etu\Mvc\View {
    /**
     * convent array to json
     *
     * @return string
     **/
    public function render(array $data) {
        return json_encode($data);
    }
}
