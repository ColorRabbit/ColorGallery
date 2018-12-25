<?php

namespace ColorGallery\tests;
require_once '../vendor/autoload.php';

use ColorGallery\IdentityCard;

$idcard = 'xxxxxxxxxxxxxxxxxx';
$idcard = '31023019950923951x';

$identityCard = new IdentityCard();
$identityCard->setIdentityCard($idcard);
var_dump($identityCard->checkIdentity(), $identityCard->getLastNum());