<?php

namespace DEV\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;

/**
 * Class LatenessTable
 * Журнал опозданий
 **/
class LatenessTable extends DataManager
{
    public static function getTableName()
    {
        return "lateness";
    }

    public static function getMap()
    {
        return [
            'id' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('LATENESS_ID_TITLE'),
            ],
            'profile_id' => [
                'data_type' => 'integer',
                'title' => Loc::getMessage('LATENESS_PROFILE_ID_TITLE'),
            ],
            'date' => [
                'data_type' => 'date',
                'title' => Loc::getMessage('LATENESS_DATE_TITLE'),
            ],
        ];
    }
}
