<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Diag\Debug;
use Bitrix\Main\UI\Extension;
Extension::load("ui.alerts");

if (!empty($arResult['ERRORS'])) {
    foreach ($arResult['ERRORS'] as $errorMessage) {
        ?>
        <div class="ui-alert ui-alert-danger">
            <span class="ui-alert-message">
                <?= $errorMessage ?>
            </span>
        </div>
        <?php
    } return;
} else {
    Debug::dump($arResult['ITEMS']);
}
