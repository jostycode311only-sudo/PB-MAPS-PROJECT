<?php
// app/Models/Response.php

class Response {
    private $message;
    private $statusCode;

    public function __construct(string $message, int $statusCode = 200) {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function send() {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        
        $data = [
            'status' => $this->statusCode,
            'message' => $this->message,
        ];

        echo json_encode($data);
    }
}