<?php

require_once "./../../libs/errors.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
if (json_decode(file_get_contents("php://input"), true) !== null) {
    $body = json_decode(file_get_contents("php://input"), true);
} else {
    $body = $_GET;
}

try {
    switch ($_SERVER["REQUEST_METHOD"]) {
        case ("POST" || "GET"):
            // Check if URL or UID is given
            if (isset($body["url"]) || isset($body["uid"])) {
                // Check if URL and UID exists, if it does, throw error
                if (isset($body["url"]) && isset($body["uid"])) {
                    ParisError::urlAndUidGiven();
                } else {
                    // If URL is given
                    if (isset($body["url"])) {
                        // Check if given URL matches pattern, if it does, confirm URL
                        if (preg_match("/http[s]?:\/\/www\.literotica\.com\/stories\/memberpage\.php\?uid=\d+&page=submissions/", $body["url"], $match)) {
                            $url = $match[0];
                        } else {
                            ParisError::invalidURL();
                        }
                        // If UID is given, generate URL
                    } elseif (isset($body["uid"])) {
                        $url = "https://www.literotica.com/stories/memberpage.php?uid=" . $body["uid"] . "&page=submissions";
                    }
                    // Load HTML file for parsing
                    $dom = new DOMDocument;
                    $dom->loadHTML(file_get_contents($url));
                    $xpath = new DOMXpath($dom);
                    
                    // Outline response
                    $response = array(
                        "ok" => true,
                        "author" => $xpath->query("//td[contains(@class, 'header topmost-header')]/span[contains(@class, 'unameClick')]/a[contains(@class, 'contactheader')]")->item(0)->textContent,
                        "count" => 0,
                        "singles" => array(
                            "story" => array()
                        ),
                    );
                    
                    // series: Find if story, poetry or illustration exists
                    foreach ([1, 2, 3] as $num) {
                        if ($xpath->query("//html/body/div[$num]")->item(0)->previousSibling->textContent === strtoupper("story") . " SUBMISSIONS") {
                            foreach ($xpath->query("//html/body/div[$num]/div/table/tr[contains(@class, 'root-story')]") as $key => $singles) {
                                $response["singles"]["story"][$key]["title"] = $singles->childNodes->item(0)->childNodes->item(0)->textContent;
                                $response["singles"]["story"][$key]["link"] = $singles->childNodes->item(0)->childNodes->item(0)->getAttribute("href");
                                $response["singles"]["story"][$key]["rating"] = (double) str_replace(")", "", str_replace("&nbsp;(", "", htmlentities($singles->childNodes->item(0)->childNodes->item(1)->textContent)));
                                $response["singles"]["story"][$key]["description"] = preg_replace("/\n                   /", "", $singles->childNodes->item(1)->textContent);
                                $response["singles"]["story"][$key]["category"] = $singles->childNodes->item(2)->childNodes->item(1)->textContent;
                                $response["singles"]["story"][$key]["date"] = $singles->childNodes->item(3)->textContent;
                            }
                        }
                    }
                }

                // Count number of stories by author
                $totalStories = count($response["singles"]["story"]);
                if ($totalStories !== null) {
                    $response["count"] = $totalStories;
                }
                
                // Sorting options
                if (isset($body["sortBy"])) {
                    // Sort by key
                    if ($body["sortBy"] === "key") {
                        // Sort alphabetically by keys
                        if ($body["sortType"] === "ksort" || $body["sortType"] === "alpha" || $body["sortType"] === "alphabetize" || $body["sortType"] === "alphabetical") {
                            ksort($response);
                            ksort($response["singles"]);
                        }
                        // Sort reverse-alphabetically by keys
                        if ($body["sortType"] === "krsort" || $body["sortType"] === "ralpha" || $body["sortType"] === "ralphabetize" || $body["sortType"] === "ralphabetical") {
                            krsort($response);
                            krsort($response["singles"]);
                        }
                    }
                    // Sort by value
                    if ($body["sortBy"] === "val" || $body["sortBy"] === "value") {
                        // Sort alphabetically by values
                        if ($body["sortType"] === "asort" || $body["sortType"] === "alpha" || $body["sortType"] === "alphabetize" || $body["sortType"] === "alphabetical") {
                            asort($response);
                            asort($response["singles"]);
                        }
                        // Sort reverse-alphabetically by keys
                        if ($body["sortType"] === "arsort" || $body["sortType"] === "ralpha" || $body["sortType"] === "ralphabetize" || $body["sortType"] === "ralphabetical") {
                            arsort($response);
                            arsort($response["singles"]);
                        }
                    }
                }
                echo json_encode($response);
                exit;
            } else {
                ParisError::incompleteBody(["url", "uid"], ["sortBy", "sortType", "sortDepth"]);
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
