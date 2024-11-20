<?
namespace Views;

class Response{
    public $status;
    public $errors;
    public $body;

    /**
     * Constructs a new Response object.
     *
     * @param int $status HTTP status code for the response
     * @param mixed $body The body content of the response
     * @param array|null $errors Optional errors associated with the response
     */
    public function __construct($status, $body, $errors = null) {
        header('Content-Type: application/json');
        $this->status = http_response_code($status);
        $this->body = $body;
        $this->errors = $errors;
    }
    /**
     * Magic method to return JSON response.
     * 
     * @return string a JSON string representing the response.
     */
    public function __toJSON() {
        return json_encode([
            'status' => $this->status,
            'body' => $this->body,
            'errors' => $this->errors
        ]);
    }
}
