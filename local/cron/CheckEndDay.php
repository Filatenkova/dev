<?php
/*
 * Скрипт должен автоматически завершать рабочий день пользователя в полночь по местному времени.
 */

// Примерные настройки для крона
// TODO: настроить запуск скрипта по расписанию через cron, в задаче на было условий это сделать
//$_SERVER['DOCUMENT_ROOT'] = '/home/bitrix/ext_www/filatenkova.ru';
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('CHK_EVENT', true);
define('SITE_ID', 's1');

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
@set_time_limit(0);
@ignore_user_abort(true);

// Подключение классов
use DEV\ORM\WorkdayTable;
use DEV\ORM\WorkdayPauseTable;

// Определим константу с количеством рабочих часов пользователя.
// Предполагаем, что время обеда не учитываем. В задаче не было условий про обеденное время, и учитывать ли его при рассчете пауз пользователя в течение дня
define('WORKING_DAY', 28800);

// Установим константу окончания дня
define('TIME_STOP', ' 23:59:59');

// Текущая дата
$curDate = date("d.m.Y");

// Запишем данные о перерывах пользователей. Предполагаем, что если пользователь случайно не отметил начало или окончание паузы, то не учитываем данные записи
$arWorkdayPause = [];
$obResWorkdayPause = WorkdayPauseTable::getList([
    'select' => [
        'id',
        'workday_id',
        'date_start',
        'date_stop',
        'date_start_formatting',
    ],
    'filter' => [
        'date_start_formatting' => $curDate,
        '!date_start' => false,
        '!date_stop' => false,
    ]
]);

while ($arRow = $obResWorkdayPause->fetch()) {

    // Преобразуем дату начала паузы в Unix формат
    $dateTimeStart = MakeTimeStamp($arRow['date_start'], "DD.MM.YYYY HH:MI:SS");

    // Преобразуем дату окончания паузы в Unix формат
    $dateTimeEnd = MakeTimeStamp($arRow['date_stop'], "DD.MM.YYYY HH:MI:SS");

    // Учтем, что пауз может быть несколько
    $arWorkdayPause[$arRow['workday_id']] += $dateTimeEnd - $dateTimeStart;
}

// Получим данные о пользователяx, которые открыли текущий день.
$arWorkdayEnd = [];
$obResWorkday = WorkdayTable::getList([
    'select' => [
        'id',
        'profile_id',
        'date_start',
        'date_start_formatting',
        'OFFSET.offset'
    ],
    'filter' => [
        'date_start_formatting' => $curDate,
    ]
]);

while ($arRow = $obResWorkday->fetch()) {
    // Преобразуем дату начала работы в Unix формат
    $dateTimeDefault = MakeTimeStamp($arRow['date_start'], "DD.MM.YYYY HH:MI:SS");

    $pauseValue = 0;
    // Учтем паузы в течение рабочего дня.
    if (array_key_exists($arRow['id'], $arWorkdayPause)) {
        $pauseValue = $arWorkdayPause[$arRow['id']];
    }

    // Определим UTM пользователя
    $timezoneUser = $arRow['DEV_ORM_WORKDAY_OFFSET_offset'] * 0.01;

    // Определим местное время для запуска добавления записей об окончании рабочего дня
    $LocalDateTime = MakeTimeStamp(date("d.m.Y H:i:s"), "DD.MM.YYYY HH:MI:SS");
    $LocalDateTime = gmdate("d.m.Y H:i:s", $LocalDateTime + 3600 * ($timezoneUser + date("I")));

    // Определим дату окончания работы с учетом UTC пользователя
    $dateTimeEndUser = $dateTimeDefault + WORKING_DAY + $pauseValue;
    $dateTimeEndUser = new \Bitrix\Main\Type\DateTime(date("d.m.Y H:i:s", $dateTimeEndUser),"d.m.Y H:i:s");
    $arWorkdayEnd[$arRow['id']]['ID'] = $arRow['id'];
    $arWorkdayEnd[$arRow['id']]['DATE_STOP'] = $dateTimeEndUser;

    // Если по местному времени пользователя полночь, то установим окончание рабочего дня
    if ($LocalDateTime > $curDate . TIME_STOP) {
        $arFields = [
            'date_stop' => $dateTimeEndUser,
        ];
        $res = WorkdayTable::update($arRow['id'], $arFields);
    }
}

