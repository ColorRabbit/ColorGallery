<?php
namespace ColorGallery;

/**
 * 效验身份证号码
 * Class IdentityCard
 */
class IdentityCard
{
    /**
     * @var string
     */
    private $identityCard = '';

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var int
     */
    private $mod = 0;

    /**
     * @var array
     */
    private $identityArr = array();

    /**
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
     * @return string
     */
    public function getIdentityCard()
    {
        return $this->identityCard;
    }

    /**
     * @param string $identityCard
     */
    public function setIdentityCard(string $identityCard)
    {
        $this->identityCard = $identityCard;
    }

    /**
     * @return string
     */
    public function getLastNum()
    {
        return $this->lastNum;
    }

    /**
     * 进行效验身份
     */
    public function identityLastNum()
    {
        for ($i = 0; $i <= 16; $i++) {
            $this->identityArr[$i] = substr($this->getIdentityCard(), $i, 1);
            $this->mod = (pow(2, 17 - $i) % 11) * $this->identityArr[$i];
            $this->count = $this->count + $this->mod;
        }

        $avg = $this->count % 11;

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
     * @return bool
     */
    public function checkIdentity()
    {
        $lastNum = substr($this->getIdentityCard(), -1, 1);
        if ($this->lastNum != $lastNum) {
            return false;
        }

        return true;
    }

}
