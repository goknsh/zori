<?php

// FIX: php warnings when directories are present, already exists

header("Content-type: application/json");

try {
    $hashListArray = array();
    $hashLengthArray = array();
    
    foreach (hash_algos() as $hashAlgo) {
        $hashLength = strlen(hash($hashAlgo, time()));
        $hashListArray[$hashAlgo] = $hashLength;
        if (!is_array($hashLengthArray[$hashLength])) {
            $hashLengthArray[$hashLength] = array();
        }
        array_push($hashLengthArray[$hashLength], $hashAlgo);
    }
    
    ksort($hashListArray);
    ksort($hashLengthArray);
    $algorithmListCode = array();
    $lengthListCode = array();

    // Generate files for hashes based on algorithm
    foreach ($hashListArray as $hash => $length) {
        if (count(explode("/", $hash)) >= 2) {
            $hashDir = explode("/", $hash);
            array_pop($hashDir);
            $hashDir = implode("/", $hashDir);
            if (!is_dir("./../generate/hash/algorithm/" . $hashDir)) {
                if (!mkdir("./../generate/hash/algorithm/" . $hashDir, 0777, true)) {
                    echo json_encode(array(
                        "ok" => false,
                        "message" => "Required folders could not be created"
                    ));
                    exit;
                }
            }
        }
        file_put_contents(
            __DIR__ . "./../generate/hash/algorithm/$hash.php",
            '<?php
			header("Access-Control-Allow-Origin: *");
			header("Content-type: application/json");
			if (json_decode(file_get_contents("php://input"), true) !== null) {
				$body = json_decode(file_get_contents("php://input"), true);
			} else {
				$body = $_GET;
			}
			$data = time();
			if (isset($body["data"])) {
				$data = $body["data"];
				if (isset($body["dataType"])) {
					$dataType = strtolower($body["dataType"]);
					if ($dataType === "int" || $dataType === "integer" || $dataType === "num" || $dataType === "number") {
						$data = (int) $data;
					}
					if ($dataType === "bool" || $dataType === "boolean") {
						$data = (bool) $data;
					}
					if ($dataType === "str" || $dataType === "string") {
						$data = (string) $data;
					}
					if ($dataType === "double" || $dataType === "decimal") {
						$data = (double) $data;
					}
				}
			}
			$raw = false;
			if (isset($body["raw"]) && $body["raw"] === "true") {
				$raw = true;
			}
			try {
				echo json_encode(array(
					"ok" => true,
					"algorithm" => "'.$hash.'",
					"data" => $data,
					"hash" => utf8_encode(hash("'.$hash.'", $data, $raw)),
					"length" => '.$length.'
				));
			} catch (Error $e) {
				echo json_encode(array(
					"ok" => false,
					"message" => "An error has occured"
				));
			}'
        );
        
        array_push($algorithmListCode, array(
            "hash" => $hash,
            "path" => $_SERVER["HTTP_HOST"]."/wuhan/api/v1/generate/hash/algorithm/$hash.php",
            "length" => $length
        ));
    }
    $algorithmListCode = array(
        "ok" => true,
        "algorithms" => $algorithmListCode
    );

    // Generate list of hashes based on algorithm
    file_put_contents(
        __DIR__ . "./../generate/hash/algorithm/list.php",
        '<?php
		header("Access-Control-Allow-Origin: *");
		header("Content-type: application/json");
		echo \''.json_encode($algorithmListCode).'\';
		exit;'
    );
    
    // Generate files for hashes based on length
    foreach ($hashLengthArray as $length => $hashes) {
        if (count($hashes) > 1) {
            $selectHashCode = '$hash = '.json_encode($hashes).'[rand(0, count('.json_encode($hashes).')-1)];';
        } else {
            $selectHashCode = '$hash = "'.$hashes[0].'";';
        }
        file_put_contents(
            __DIR__ . "./../generate/hash/length/$length.php",
            '<?php
			header("Access-Control-Allow-Origin: *");
			header("Content-type: application/json");
			if (json_decode(file_get_contents("php://input"), true) !== null) {
				$body = json_decode(file_get_contents("php://input"), true);
			} else {
				$body = $_GET;
			}
			$data = time();
			if (isset($body["data"])) {
				$data = $body["data"];
				if (isset($body["dataType"])) {
					$dataType = strtolower($body["dataType"]);
					if ($dataType === "int" || $dataType === "integer" || $dataType === "num" || $dataType === "number") {
						$data = (int) $data;
					}
					if ($dataType === "bool" || $dataType === "boolean") {
						$data = (bool) $data;
					}
					if ($dataType === "str" || $dataType === "string") {
						$data = (string) $data;
					}
					if ($dataType === "double" || $dataType === "decimal") {
						$data = (double) $data;
					}
				}
			}
			$raw = false;
			if (isset($body["raw"]) && $body["raw"] === "true") {
				$raw = true;
			}
			try {
				'.$selectHashCode.'
				echo json_encode(array(
					"ok" => true,
					"algorithm" => "$hash",
					"data" => $data,
					"hash" => utf8_encode(hash("$hash", $data, $raw)),
					"length" => '.$length.'
				));
			} catch (Error $e) {
				echo json_encode(array(
					"ok" => false,
					"message" => "An error has occured"
				));
			}'
        );
        
        array_push($lengthListCode, array(
            "hashes" => $hashes,
            "path" => $_SERVER["HTTP_HOST"]."/wuhan/api/v1/generate/hash/length/$length.php",
            "length" => $length
        ));
    }
    $lengthListCode = array(
        "ok" => true,
        "lengths" => $lengthListCode
    );

    // Generate list of hashes based on length
    file_put_contents(
        __DIR__ . "./../generate/hash/length/list.php",
        '<?php
		header("Access-Control-Allow-Origin: *");
		header("Content-type: application/json");
		echo \''.json_encode($lengthListCode).'\';
		exit;'
    );

    echo json_encode(array(
        "ok" => true,
        "message" => "Files have been generated"
    ));
    exit;
} catch (Error $e) {
    echo json_encode(array(
        "ok" => false,
        "message" => "An error has occured",
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
