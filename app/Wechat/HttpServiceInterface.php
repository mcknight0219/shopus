<?php
namespace App\Wechat;

interface HttpServiceInterface
{
    /**
     * Request or refresh access token
     */
    public function refresh();

    /**
     * Get the current valid access token
     *
     * @return string
     */
    public function token();

    /**
     * Make request to Wechat service. On failure (e.g. refresh token expired)
     * , make recursive call if $retry is set.
     *
     * @param $method
     * @param $path
     * @param $body
     * @param bool $retry
     * @return mixed
     */
    public function request($method, $path, $body, $retry = true);
}