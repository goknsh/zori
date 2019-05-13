<?php

class ParisError
{
    public static function requestMethod(array $accepted = array(), string $unaccepted)
    {
        http_response_code(405);
        echo json_encode(array(
            "ok" => false,
            "type" => "clientError",
            "error" => array(
                "id" => "requestMethod",
                "message" => "The $unaccepted method is an invalid method of accessing this page",
                "acceptedMethods" => $accepted,
            ),
        ));
        exit;
    }
    public static function invalidURL()
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "type" => "userError",
            "error" => array(
                "id" => "invalidURL",
                "message" => "Given URL is invalid",
            ),
        ));
        exit;
    }
    public static function urlAndUidGiven()
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "type" => "clientError",
            "error" => array(
                "id" => "urlAndUidGiven",
                "message" => "Both URL and uid is given; only one is required",
            ),
        ));
        exit;
    }
    public static function invalidStoryType($type)
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "type" => "clientError",
            "error" => array(
                "id" => "invalidStoryType",
                "message" => "Type $type is an invalid story type",
            ),
        ));
        exit;
    }
    public static function serverCodeError($e = array())
    {
        http_response_code(500);
        echo json_encode(array(
            "ok" => false,
            "message" => "An error has occured",
            "type" => "serverCodeError",
            "error" => array(
                "message" => $e->getMessage(),
                "code" => $e->getCode(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTrace(),
                "traceAsString" => $e->getTraceAsString(),
                "previous" => $e->getPrevious()
            )
        ));
        exit;
    }
    public static function invalidType($type, $variable)
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "invalidType",
                "message" => "$variable must be of type $type",
            ),
        ));
        exit;
    }
    public static function incompleteBody($required, $optional)
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "incompleteBody",
                "message" => "The required body is not present",
                "headers" => array(
                    "required" => $required,
                    "optional" => $optional,
                ),
            ),
        ));
        exit;
    }
    public static function incompleteHeader($required, $optional)
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "incompleteHeader",
                "message" => "The required headers are not present",
                "headers" => array(
                    "required" => $required,
                    "optional" => $optional,
                ),
            ),
        ));
        exit;
    }
    public static function incompleteParams($required, $optional)
    {
        http_response_code(400);
        echo json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "incompleteParams",
                "message" => "The required params are not present",
                "params" => array(
                    "required" => $required,
                    "optional" => $optional,
                ),
            ),
        ));
        exit;
    }
}
