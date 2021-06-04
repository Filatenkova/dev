<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/company/index.php");
$APPLICATION->SetTitle("Простой компонент");

$APPLICATION->IncludeComponent(
    'developer:simplecomponent',
    'simple',
    [
        'CACHE_TIME' => 3600
    ],
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
