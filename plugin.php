<?php
/**
 * AI Weather Plugin
 */

return [
    "default" => function(&$request){
        $this->header->allow();

        if(!isset($request["latitude"], $request["longitude"], $request["unit"]))
            response(500);

        try {
            $getTime = json_decode($this->guzzle->post(titansys_api . "/zender/ai/plugins/getWeather", [
                "form_params" => [
                    "code" => system_purchase_code,
                    "site_url" => rtrim(site_url(false, true), "/"),
                    "latitude" => $request["latitude"],
                    "longitude" => $request["longitude"],
                    "unit" => $request["unit"]
                ],
                "allow_redirects" => true,
                "http_errors" => false,
                "verify" => false
            ])->getBody()->getContents(), true);

            response(200, false, $getTime);
        } catch(Exception $e){
            response(500);
        }
    },
    "schema" => [
        "name" => "getWeather",
        "description" => "Get the current weather for a specified location.",
        "parameters" => [
            "type" => "object",
            "properties" => [
                "latitude" => [
                    "type" => "string",
                    "description" => "Latitude of the location"
                ],
                "longitude" => [
                    "type" => "string",
                    "description" => "Longitude of the location"
                ],
                "unit" => [
                    "type" => "string",
                    "description" => "The temperature unit to use. Infer this from the provided location.",
                    "enum" => ["celsius", "fahrenheit"]
                ]
            ],
            "required" => [
                "latitude", 
                "longitude", 
                "unit"
            ]
        ]
    ]
];