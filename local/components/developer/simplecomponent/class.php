<?php

use DEV\ORM\SimpleTable;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;

/**
 * Класс для решения задания №1
 */
class SimpleComponent extends \CBitrixComponent
{
    private $errorCollection = null;

    public function executeComponent()
    {
        if ($this->StartResultCache($this->arParams['CACHE_TIME'])) {

            if (empty($this->errorCollection->toArray())) {
                $this->arResult["ITEMS"] = $this->getData();
            } else {
                $this->arResult["ERRORS"] = $this->errorCollection->toArray();
            }

            $this->IncludeComponentTemplate();
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        $this->errorCollection = new ErrorCollection();

        try {
            if (empty($arParams['CACHE_TIME'])) {
                $arParams['CACHE_TIME'] = 3600;
            }

        } catch (Throwable $e) {
            $this->errorCollection->setError(new Error(Loc::getMessage("CMP_SIMPLE_PARAMETERS_ERROR")));
        }

        return $arParams;
    }

    /**
     * Метод возвращает данные из таблицы b_dev_simple
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getData(): array
    {
        try {
            $obResult = SimpleTable::getList([
                'select' => [
                    'ID',
                    'UF_NAME',
                    'UF_DATE_INSERT'
                ]
            ]);

            while ($arRow = $obResult->fetch()) {
                $arResult[$arRow['ID']] = $arRow;
            }

        } catch(Throwable $e) {
            $this->errorCollection->setError(new Error(Loc::getMessage("CMP_SIMPLE_NOT_DATA")));
        }

        return $arResult ?? [];
    }
}
