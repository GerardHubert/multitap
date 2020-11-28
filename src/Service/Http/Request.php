<?php

declare(strict_types=1);

namespace App\Service\Http;

class Request
{
    private $get;
    private $post;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
    }

    public function cleanGet(): array
    {
        return $cleanedGet = filter_var_array($this->get, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function cleanPost(): array
    {
        /*return filter_var_array($this->post, [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_STRIP_LOW
            ]);*/
        
        foreach ($this->post as $key => $value) {
            [$this->post[$key] => [
                'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                'flags' => FILTER_FLAG_STRIP_LOW
                ]
            ];
        }
        return $this->post;
    }
}
