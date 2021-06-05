<?php

namespace DEV\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;

/**
 * Class WorkdayPauseTable
 * Журнал записи перерывов в процессе рабочего дня
 **/
class WorkdayPauseTable extends DataManager
{
    public static function getTableName()
    {
        return "workday_pause";
    }

    public static function getMap()
    {
        return [
            'id' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('WORKDAY_PAUSE_ID_TITLE'),
            ],
            'workday_id' => [
                'data_type' => 'integer',
                'title' => Loc::getMessage('WORKDAY_PAUSE_PROFILE_ID_TITLE'),
            ],
            'date_start' => [
                'data_type' => 'datetime',
                'title' => Loc::getMessage('WORKDAY_PAUSE_DATE_START_TITLE'),
            ],
            'date_stop' => [
                'data_type' => 'datetime',
                'title' => Loc::getMessage('WORKDAY_PAUSE_DATE_STOP_TITLE'),
            ],
        ];
    }
}
