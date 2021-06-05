<?php

namespace DEV\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Entity;

/**
 * Class WorkdayTable
 * Журнал записи начала и окончания рабочего дня
 **/
class WorkdayTable extends DataManager
{
    public static function getTableName()
    {
        return "workday";
    }

    public static function getMap()
    {
        return [
            'id' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('WORKDAY_ID_TITLE'),
            ],
            'profile_id' => [
                'data_type' => 'integer',
                'title' => Loc::getMessage('WORKDAY_PROFILE_ID_TITLE'),
            ],
            'date_start' => [
                'data_type' => 'datetime',
                'title' => Loc::getMessage('WORKDAY_DATE_START_TITLE'),
            ],
            'date_stop' => [
                'data_type' => 'datetime',
                'title' => Loc::getMessage('WORKDAY_DATE_STOP_TITLE'),
            ],
            new Entity\ReferenceField(
                'OFFSET',
                '\DEV\ORM\ProfileTable',
                ['this.profile_id' => 'ref.id']
            ),
            new Entity\ExpressionField(
                'date_start_formatting',
                "DATE_FORMAT(date_start, '%%d.%%m.%%Y')"
            ),
        ];
    }
}
