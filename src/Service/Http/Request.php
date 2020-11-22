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
        foreach ($this->post as $key => $value) {
        
            $clean = [
                $key => filter_var($value, FILTER_SANITIZE_STRING, ['flags' => FILTER_FLAG_STRIP_LOW])
            ];
            array_replace($this->post, $clean);
        }
        
        return $this->post;
    }
}
