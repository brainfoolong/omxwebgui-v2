<?php

namespace Nullix\Omxwebgui;

/**
 * Class Github
 *
 * @package Nullix\Omxwebgui
 */
class Github
{
    /**
     * The stream options for the requests
     *
     * @var array
     */
    public static $streamOptions
        = [
            'http' => [
                'method' => "GET",
                "timeout" => 10,
                'header' => "Accept-language: en\r\nCookie: foo=bar\r\nUser-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n"
            ]
        ];

    /**
     * Just do an api request to github
     *
     * @param string $url
     * @return mixed|array
     */
    public static function apiRequest($url)
    {
        $apiData = file_get_contents(
            $url,
            null,
            stream_context_create(self::$streamOptions)
        );
        if ($apiData) {
            $apiData = json_decode($apiData, true);
            return $apiData;
        }
        return null;
    }

    /**
     * Download a file and save to disk
     *
     * @param string $url
     * @param string $path
     */
    public static function download($url, $path)
    {
        file_put_contents(
            $path,
            file_get_contents(
                $url,
                null,
                stream_context_create(self::$streamOptions)
            )
        );
    }
}
