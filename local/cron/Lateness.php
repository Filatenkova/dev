<?php
/*
 * Скрипт должен проверять, начал ли пользователь рабочий день в 9-00 по местному времени.
 * Если не начал, записывать его в журнал опозданий.
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
use DEV\ORM\LatenessTable;
use DEV\ORM\ProfileTable;
use Bitrix\Main\Type\DateTime;

// Установим константу начала дня
define('TIME_START', ' 09:00:00');

// Текущая дата
$curDate = date("d.m.Y");
$curDateTime = date("d.m.Y") . TIME_START;

// В массив $arProfileId запишем id всех пользователей
$arProfileId = [];
$obResProfile = ProfileTable::getList([
    'select' => [
        'id',
    ],
]);

while ($arRow = $obResProfile->fetch()) {
    $arProfileId[$arRow['id']] = $arRow['id'];
}

// Получим данные о пришедших на работу за текущий день
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

    // Конвертируем дату начала работы с учетом UTC пользователя
    $timezoneUser = $arRow['DEV_ORM_WORKDAY_OFFSET_offset'] * 0.01;
    $dateTimeUser = gmdate("d.m.Y H:i:s", $dateTimeDefault + 3600 * ($timezoneUser + date("I")));

    // Если пользователь пришел вовремя, то исключим его из массива опоздавших
    if (array_key_exists($arRow['profile_id'], $arProfileId) && $dateTimeUser == $curDateTime) {
        unset($arProfileId[$arRow['profile_id']]);
    }
}

// Получим данные по опоздавшим за текущий день
$arLatenessError = [];
$obResLateness = LatenessTable::getList([
    'select' => [
        'id',
        'profile_id',
        'date',
    ],
    'filter' => [
        'date' => $curDate,
    ]
]);

while ($arRow = $obResLateness->fetch()) {
    // Если после обновления данных оказалось, что пользователь пришел вовремя, то запишем id полей на удаление
    if (!array_key_exists($arRow['profile_id'], $arProfileId)) {
        $arLatenessError[] = $arRow['id'];
    } else {
        // Если опоздавший уже есть в журнале опозданий, то исключим его из массива опоздавших
        unset($arProfileId[$arRow['profile_id']]);
    }
}

// Добавим опоздавших в журнал опозданий
foreach ($arProfileId as $profile_id) {
    $arFields = [
        'profile_id' => (int)$profile_id,
        'date' => new DateTime(),
    ];
    $res = LatenessTable::add($arFields);
}

// Удалим ошибочные записи
foreach ($arLatenessError as $id) {
    $res = LatenessTable::delete($id);
}

// TODO: добавить запись логов, в задаче не было условий на запись логов
