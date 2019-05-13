<?php

require_once "./../libs/errors.php";
header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json");
if (json_decode(file_get_contents("php://input"), true) !== null) {
    $body = json_decode(file_get_contents("php://input"), true);
} else {
    $body = $_GET;
}

try {
    switch ($_SERVER["REQUEST_METHOD"]) {
        case ("POST" || "GET"):
            if ($body["type"] && $body["url"]) {
                if ($body["type"] === "s" || $body["type"] === "p" || $body["type"] === "i" || $body["type"] === "story" || $body["type"] === "poetry" || $body["type"] === "illustration") {
                    $url = "https://literotica.com/".$body["type"][0]."/".$body["url"];
                    $dom = new DOMDocument;
                    $dom->loadHTML(file_get_contents($url));
                    $xpath = new DOMXpath($dom);
                    
                    // Outline response
                    $response = array(
                        "ok" => true,
                        "author" => $xpath->query("//*[@id='content']/div[2]/span[1]/a")->item(0)->textContent,
                        "count" => 1,
                        "pages" => array()
                    );
                    
                    $response["pages"][0]["link"] = $url;
                    $response["pages"][0]["content"] = $xpath->query("//*[@id='content']/div[3]")->item(0)->textContent;
                    // var_dump($xpath->query("//*[@id='content']/div[3]")->item(0)->textContent);
                    
                    foreach ($xpath->query("//*[@id='sbar-l-wrp']/div/div/a") as $key => $page) {
                        $response["pages"][$key+1]["link"] = $page->getAttribute("href");
                        $dom2 = new DOMDocument;
                        $dom2->loadHTML(file_get_contents($page->getAttribute("href")));
                        $xpath2 = new DOMXpath($dom2);
                        $response["pages"][$key+1]["content"] = $xpath2->query("//*[@id='content']/div[3]")->item(0)->textContent;
                    }
                    
                    $response["count"] = count($response["pages"]);
                    
                    echo json_encode($response);
                } else {
                    ParisError::invalidStoryType($body["type"]);
                }
            } else {
                ParisError::incompleteBody(["type", "url"], []);
            }
            break;
        default:
            ParisError::requestMethod(["POST", "GET"], $_SERVER["REQUEST_METHOD"]);
            break;
        break;
}
} catch (Error $e) {
    ParisError::serverCodeError($e);
}
