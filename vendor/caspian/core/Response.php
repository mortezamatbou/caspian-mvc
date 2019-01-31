<?php

class Response {

    public $statusCode;

    function __construct() {
        $this->statusCode = 200;
        $this->removeAllHeaders();
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        http_response_code($statusCode);
        return $this;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function addHeader($string, $replace = TRUE) {
        header($string, $replace);
        return $this;
    }

    public function removeHeader($headerName) {
        header_remove($headerName);
        return $this;
    }

    public function removeAllHeaders() {
        header_remove();
    }

    public function send($data, $statusCode = '') {
        if ($statusCode) {
            $this->setStatusCode($statusCode);
        }

        if (empty($data)) {
            if (DebugInfo::isEnable()) {
                $this->addHeader('Content-type: application/json; charset=utf-8');
                // Bad request
                $this->setStatusCode(400);
                echo json_encode(DebugInfo::getMessage());
            } else {
                // No content
                $this->setStatusCode(204);
            }
        } else {
            $this->addHeader('Content-type: application/json; charset=utf-8');
            echo json_encode($data);
        }

        exit;
    }

}
