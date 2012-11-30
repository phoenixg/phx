<?php

class Request {
  public $url_elements;
  public $verb;
  public $parameters;
  public $headers;
  public $uri;
  public $is_ajax;

  public function __construct() {
    $this->verb = $_SERVER['REQUEST_METHOD'];
    $this->uri = isset($_SERVER['PATH_INFO']) ? rtrim($_SERVER['PATH_INFO'], '/') : '/';
    $this->headers = apache_request_headers();
    $this->is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');

    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    $this->url_elements = explode('/', $path_info);
    array_shift($this->url_elements);

    // parse the request for query params and request-content
    $this->parseIncomingParams();
  }

  // get URL element at a specific position
  public function getUrlElement($index, $retval = NULL) {
    $index = (int)$index;

    if (isset($this->url_elements[$index])) {
      $retval = $this->url_elements[$index];
    }

    return $retval;
  }

  public function getParameter($param, $retval = NULL) {
    if (isset($this->parameters[$param])) {
      $retval = $this->parameters[$param];
    }

    return $retval;
  }

  public function getHeader($header, $retval = NULL) {
    if (isset($this->headers[$header])) {
      $retval = $this->headers[$header];
    }

    return $retval;
  }

  // parses the request for query params and request content body
  public function parseIncomingParams() {
    if (isset($_SERVER['QUERY_STRING'])) {
      parse_str($_SERVER['QUERY_STRING'], $this->parameters);
    }

    // parse request body
      $body = file_get_contents('php://input');
      $content_type = false;

      if(isset($_SERVER['CONTENT_TYPE'])) {
        $content_type = $_SERVER['CONTENT_TYPE'];
      }

      $this->request_body = $body;
  }
}

?>
