<?php

namespace DEV\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Entity;

/**
 * Class ProfileTable
 * Данные о пользователях
 **/
class ProfileTable extends DataManager
{
    public static function getTableName()
    {
        return "profile";
    }

    public static function getMap()
    {
        $curDate = date("d.m.Y");

        return [
            'id' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('PROFILE_ID_TITLE'),
            ],
            'login' => [
                'data_type' => 'string',
                'title' => Loc::getMessage('PROFILE_LOGIN_TITLE'),
            ],
            'name' => [
                'data_type' => 'string',
                'title' => Loc::getMessage('PROFILE_NAME_TITLE'),
            ],
            'last_name' => [
                'data_type' => 'string',
                'title' => Loc::getMessage('PROFILE_LAST_NAME_TITLE'),
            ],
            'offset' => [
                'data_type' => 'string',
                'title' => Loc::getMessage('PROFILE_OFFSET_TITLE'),
            ],
        ];
    }
}
