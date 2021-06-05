<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Тестирование скрипта завершения рабочего дня пользователя в полночь по местному времени.");

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/cron/CheckEndDay.php');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
