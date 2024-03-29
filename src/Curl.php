<?php
/**
 * Created by PhpStorm.
 * User: ColorRabbit
 * Date: 6/7/2017
 * Time: 3:18 PM
 */
namespace ColorGallery;

/**
 * Class Curl
 *
 * @package ColorRabbitHome\ColorClass
 */
class Curl
{
    /**
     * @var
     */
    private $url;

    /**
     * @var
     */
    private $curlType;

    /**
     * @var
     */
    private $function;

    /**
     * @var
     */
    private $cookies;

    /**
     * @var
     */
    private $cookieFile;

    /**
     * @var
     */
    private $cookieJar;

    /**
     * @var
     */
    private $referer;

    /**
     * @var
     */
    private $userAgent;

    /**
     * @var
     */
    private $ssl = false;

    /**
     * @var
     */
    private $header = false;
    /**
     * @var
     */
    private $httpHeader = [];

    /**
     * @var array
     */
    private $parameter = array();

    /**
     * Curl constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getCookieFile()
    {
        return $this->cookieFile;
    }

    /**
     * @param string $cookieFile
     *
     * @return $this
     */
    public function setCookieFile($cookieFile)
    {
        $this->cookieFile = $cookieFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getCookieJar()
    {
        return $this->cookieJar;
    }

    /**
     * @param string $cookieJar
     *
     * @return $this
     */
    public function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar;

        return $this;
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getCurlType()
    {
        return $this->curlType;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setCurlType($type)
    {
        if (strtoupper($type) == 'GET') {
            $this->curlType = 'GET';
        }

        if (strtoupper($type) == 'POST') {
            $this->curlType = 'POST';
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param string $referer
     *
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return bool|array
     */
    public function getSsl()
    {
        return $this->ssl;
    }

    /**
     * @param bool $ssl
     *
     * @return $this
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;

        return $this;
    }

    /**
     * @return string
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param string $cookies
     *
     * @return $this
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     *
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpHeader()
    {
        return $this->httpHeader;
    }

    /**
     * @param mixed $httpHeader
     *
     * @return Curl
     */
    public function setHttpHeader($httpHeader)
    {
        $this->httpHeader = $httpHeader;

        return $this;
    }

    /**
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param $function
     *
     * @return $this
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * @param null|integer $key
     *
     * @return string
     */
    public function getRequestUrl($key = null)
    {
        if (is_null($key)) {
            return $this->url . $this->function;
        }

        return $this->url[$key] . $this->function[$key];
    }

    /**
     * @param $parameter
     *
     * @return string
     */
    public function getParameter()
    {
        return json_encode($this->parameter);
    }
    
    /**
     * @param $parameter
     *
     * @return string
     */
    public function getXmlParameter()
    {
        $param = '';
        if ( ! empty($this->parameter)) {
            foreach ($this->parameter as $key => $value) {
                $param .= $key . '=' . $value . '&';
            }
            return rtrim($param, '&');
        }

        return $param;
    }
    
    /**
     * @param $parameter
     *
     * @throws \Exception
     * @return $this
     */
    public function setParameter($parameter)
    {
        if (!is_array($parameter)) {
            throw new \Exception('It must be array!!!');
        }

        $this->parameter = $parameter;

        return $this;
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function curl($type = 'json')
    {
        if (empty($this->url)) {
            throw new \Exception('Please use setUrl(URL) set url!!!');
        }

        $curl = curl_init();

        $curlParameter = array(
            CURLOPT_URL => $this->getRequestUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $this->cookieJar,
            CURLOPT_COOKIEFILE => $this->cookieFile,
            CURLOPT_COOKIE => $this->cookies,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => $this->header,
            CURLOPT_SSL_VERIFYPEER => $this->ssl,
            CURLOPT_SSL_VERIFYHOST => $this->ssl,
        );

        if ($this->referer) {
            $curlParameter[CURLOPT_REFERER] = $this->referer;
        }

        if ($this->userAgent) {
            $curlParameter[CURLOPT_USERAGENT] = $this->userAgent;
        }

        if ($this->curlType === 'POST') {
            $curlParameter[CURLOPT_CUSTOMREQUEST] = 'POST';
            if ($type == 'xml') {
                $curlParameter[CURLOPT_POSTFIELDS] = $this->getXmlParameter();
            } else {
                $curlParameter[CURLOPT_POSTFIELDS] = $this->getParameter();
            }
        }

        curl_setopt_array($curl, $curlParameter);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'cURL Error #:' . $err;
        } else {
            return $response;
        }
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function curlMulti()
    {
        if (empty($this->url)) {
            throw new \Exception('Please use setUrl(URL) set url!!!');
        }

        $curl = curl_multi_init();

        $conn = [];
        foreach ($this->url as $key => $value) {
            $conn[$key] = curl_init($value);
            $curlParameter = array(
                CURLOPT_URL => $this->getRequestUrl($key),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_COOKIEJAR => $this->cookieJar[$key],
                CURLOPT_COOKIEFILE => $this->cookieFile[$key],
                CURLOPT_COOKIE => $this->cookies[$key],
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPHEADER => $this->httpHeader,
                CURLOPT_SSL_VERIFYPEER => $this->ssl[$key],
                CURLOPT_SSL_VERIFYHOST => $this->ssl[$key],
            );

            if ( ! empty($this->referer[$key])) {
                $curlParameter[CURLOPT_REFERER] = $this->referer[$key];
            }

            if ( ! empty($this->userAgent[$key])) {
                $curlParameter[CURLOPT_USERAGENT] = $this->userAgent[$key];
            }

            if ($this->curlType[$key] === 'POST') {
                $curlParameter[CURLOPT_CUSTOMREQUEST] = 'POST';
                $curlParameter[CURLOPT_POSTFIELDS] = $this->getParameter();
            }

            curl_setopt_array($conn[$key], $curlParameter);
            curl_multi_add_handle($curl, $conn[$key]);
        }

        do {
            curl_multi_exec($curl, $active);
        } while ($active);

        /*do {
            $mrc = curl_multi_exec($curl, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($curl) != -1) {
                do {
                    $mrc = curl_multi_exec($curl, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }*/

        $errReport = $info = $result = [];
        foreach ($conn as $k => $v) {
            $err = curl_error($v);
            if ($err) {
                $errReport[$k] = 'cURL Error #:' . $err;
            }

            $info[$k] = curl_getinfo($v);
            $result[$k] = curl_multi_getcontent($v);
            curl_multi_remove_handle($curl, $v);
            curl_close($v);
        }

        curl_multi_close($curl);

        return array(
            'info'      => $info,
            'result'    => $result,
            'error'     => $errReport,
        );
    }

}

