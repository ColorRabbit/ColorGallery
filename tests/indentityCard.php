<?php

namespace ColorGallery\tests;

use ColorGallery\IdentityCard;

$idcard = 'xxxxxxxxxxxxxxxxxx';
$idcard = '31023019950923951x';

$identityCard = new IdentityCard();
$identityCard->setIdentityCard($idcard);
var_dump($identityCard->checkIdentity(), $identityCard->getLastNum());