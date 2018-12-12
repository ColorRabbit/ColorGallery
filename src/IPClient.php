<?php
/**
 * Created by PhpStorm.
 * User: colorrabbit
 * Date: 2017/8/1
 * Time: 14:49
 */

namespace ColorGallery;

class IPClient
{
    const IP_URL = 'https://ipleak.net/json/';

    /**
     * @var
     */
    private $ip;

    /**
     * @var mixed|string
     */
    private $ipClient;

    /**
     * IPClient constructor.
     *
     * @param string $ip
     */
    public function __construct($ip = '')
    {
        if ( ! empty($ip)) {
            $this->setIp($ip);
        }
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return self::IP_URL . $this->ip;
    }

    /**
     * Curl
     */
    private function curlIpClient()
    {
        $curl = new Curl();
        $curl->setUrl($this->getUrl());

        try {
            $this->ipClient = $curl->curl();
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param string $ip
     *
     * @return mixed|string
     */
    public function getIpClient($ip = '')
    {
        if ( ! empty($ip)) {
            $this->setIp($ip);
        }
        $this->curlIpClient();

        return $this->ipClient;
    }
}