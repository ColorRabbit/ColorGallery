<?php

namespace ColorGallery;

/**
 * Class ShunFeng V3.5
 *
 * @package ColorGallery
 */
class ShunFeng
{
    private $accessCode;

    private $checkWord;

    private $url;

    private $debug = false;

    /**
     * @return mixed
     */
    public function getAccessCode()
    {
        return $this->accessCode;
    }

    /**
     * @param $accessCode
     *
     * @return $this
     */
    public function setAccessCode($accessCode)
    {
        $this->accessCode = $accessCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckWord()
    {
        return $this->checkWord;
    }

    /**
     * @param $checkWord
     *
     * @return $this
     */
    public function setCheckWord($checkWord)
    {
        $this->checkWord = $checkWord;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
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
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param $debug
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    public function express($data)
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8" ?>
<Request service="OrderService" lang="zh-CN">
    <Head>{$this->getAccessCode()}</Head>
    <Body>
        <Order 
          orderid="{$data['order_id']}" 
          j_company="{$data['j_company']}" 
          j_contact="{$data['j_contact']}" 
          j_tel="{$data['j_tel']}" 
          j_country="{$data['j_country']}" 
          j_province="{$data['j_province']}" 
          j_county="{$data['j_county']}" 
          j_city="{$data['j_city']}" 
          j_address="{$data['j_address']}" 
          d_company="{$data['d_company']}" 
          d_contact="{$data['d_contact']}" 
          d_tel="{$data['d_tel']}" 
          d_province="{$data['d_province']}" 
          d_city="{$data['d_city']}" 
          d_county="{$data['d_county']}" 
          d_address="{$data['d_address']}" 
          pay_method="{$data['pay_method']}" 
          express_type="1" parcel_quantity="1"
          is_gen_bill_no="{$data['is_gen_bill_no']}" 
          sendstarttime="{$data['send_start_time']}" 
          is_docall="{$data['is_docall']}" 
          remark="" />
        </Order>
    </Body>
</Request>
EOF;

        return $this->request($xml);
    }

    public function route($data)
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8" ?>
    <Request service="RouteService" lang="zh-CN">
    <Head>{$this->getAccessCode()}</Head>
    <Body>
        <RouteRequest 
          tracking_type="{$data['tracking_type']}"
          method_type = "{$data['method_type']}"
          tracking_number = "{$data['tracking_number']}" />
    </Body>
</Request>
EOF;

        return $this->request($xml);
    }

    private function request($xml)
    {
        $verifyCode = base64_encode(md5($xml . $this->getCheckWord(), true));

        $curl = new Curl();

        $info = $curl->setUrl($this->url)
                     ->setCurlType('POST')
                     ->setParameter(['xml' => $xml, 'verifyCode' => $verifyCode])
                     ->curl()
        ;

        return $info;
    }

}