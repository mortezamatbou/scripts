<?php

/**
 * @property \database $database
 * @property $token
 *
 * @property $params
 * @property $method
 * @property $uri
 * @property $header
 *
 *
 * @property inputLoader $node
 */
class RequestHandler
{

    private $params = [];
    private $header = [];
    private $inputFiles = [];
    private $contentType = '';
    private $method = 'get';
    private $uri = '';

    function __construct($database, $token)
    {
        $this->database = $database;
        $this->token = $token;
        $this->node = new inputLoader('SysOneHandler', ['token' => $token]);
    }

    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    public function uri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function inputFiles($files)
    {
        $this->inputFiles = $files;
        return $this;
    }

    public function content_type($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function header($header)
    {
        $this->header = $header;
        return $this;
    }

    public function exec($auth = TRUE, $decode = TRUE, $export = TRUE)
    {
        $token = $auth ? $this->token : '';
        $this->node->token($token)->pre_request($this->uri, $this->params, $this->header, $this->inputFiles);
        $result = $this->node->exec($this->method, $decode, $export, $this->contentType);

        $this->uri = '';
        $this->method = 'get';
        $this->params = [];
        $this->header = [];

        return $result;
    }


}