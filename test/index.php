<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Тестирование скрипта записи опоздавших");

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/cron/Lateness.php');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
