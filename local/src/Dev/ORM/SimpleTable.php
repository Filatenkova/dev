<?php

namespace DEV\ORM;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;

/**
 * Class SimpleTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_NAME string
 * <li> UF_DATE_INSERT datetime
 * </ul>
 *
 **/
class SimpleTable extends DataManager
{
    public static function getTableName()
    {
        return "b_dev_simple";
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'title' => Loc::getMessage('UF_ID_TITLE'),
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Entity\StringField('UF_NAME', [
                'title' => Loc::getMessage('UF_NAME_TITLE'),
            ]),
            new Entity\DatetimeField('UF_DATE_INSERT', [
                'title' => Loc::getMessage('UF_DATE_INSERT_TITLE'),
            ]),
        ];
    }
}
