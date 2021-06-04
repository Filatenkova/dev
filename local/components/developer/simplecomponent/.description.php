<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arComponentDescription =[
    "NAME" => Loc::getMessage("SIMPLE_COMPONENT"),
    "DESCRIPTION" => Loc::getMessage("SIMPLE_COMPONENT_DESCRIPTION"),
    "ICON" => "/images/icon.gif",
    "COMPLEX" => "N",
    'PATH' => [
        'ID' => 'developer',
        'NAME' => 'DEVELOPER',
    ]
];
