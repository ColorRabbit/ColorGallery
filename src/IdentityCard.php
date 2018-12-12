<?php
namespace ColorGallery;

/**
 * 效验身份证号码
 * Class IdentityCard
 */
class IdentityCard
{
    /**
     * 身份证号
     *
     * @var string
     */
    private $identityCard = '';

    /**
     * 生成的最后一位身份证号
     *
     * @var string
     */
    private $lastNum = '';

    /**
     * IdentityCard constructor.
     */
    public function __construct()
    {
    }

    /**
     * 获取身份证号
     *
     * @return string
     */
    public function getIdentityCard() :string
    {
        return $this->identityCard;
    }

    /**
     * 设置身份证号
     *
     * @param string $identityCard
     *
     * @throws \Exception
     */
    public function setIdentityCard(string $identityCard)
    {
        if ( ! preg_match_all('/^[\d]{17}[\d|x]{1}$/i', $identityCard, $match)) {
            throw new \Exception('身份证格式不合法');
        }

        $this->identityCard = $identityCard;
    }

    /**
     * 获取生成的最后一位身份证号
     *
     * @return string
     */
    public function getLastNum()
    {
        return $this->lastNum;
    }

    /**
     * 进行效验身份，匹配最后一位的身份证
     */
    public function identityLastNum()
    {
        $count = 0;
        $identityArr = [];

        for ($i = 0; $i <= 16; $i++) {
            $identityArr[$i] = substr($this->getIdentityCard(), $i, 1);
            // $mod = (pow(2, 17 - $i) % 11) * $this->identityArr[$i];
            $count += (pow(2, 17 - $i) % 11) * $identityArr[$i];
        }

        $avg = $count % 11;

        switch ($avg) {
        case 0:
            $this->lastNum = 1;
            break;
        case 1:
            $this->lastNum = 0;
            break;
        case 2:
            $this->lastNum = 'X';
            break;
        default:
            $this->lastNum = 12 - $avg;
            break;
        }
    }

    /**
     * 核对身份证号
     *
     * @return bool
     */
    public function checkIdentity()
    {
        $lastNum = substr($this->getIdentityCard(), -1, 1);

        if (empty($this->lastNum)) {
            $this->identityLastNum();
        }
        if ($this->lastNum != $lastNum) {
            return false;
        }

        return true;
    }
}
