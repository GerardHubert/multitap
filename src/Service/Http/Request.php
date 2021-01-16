<?php

declare(strict_types=1);

namespace App\Service\Http;

class Request
{
    private $get;
    private $post;
    private $requestUrl;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->requestUrl = $_SERVER['REQUEST_URI'];
    }

    public function cleanGet(): array
    {
        return $cleanedGet = filter_var_array($this->get, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function cleanPost(): array
    {
        $clean = filter_var_array($this->post, FILTER_SANITIZE_SPECIAL_CHARS);

        return $clean;
    }

    public function cleanUrl(): string
    {
        return $cleanRequest = filter_var_array($this->requestUrl, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
