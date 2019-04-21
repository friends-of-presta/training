<?php

namespace PrestaShop\Training\Menu;

use Tab;
use Language;

/**
 * Allows to insert a menu in PrestaShop Back Office
 */
final class TabManager
{
    /**
     * @param $className
     * @param $tabName
     * @param $moduleName
     * @param $parentClassName
     *
     * @return Tab
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function addTab($className, $tabName, $moduleName, $parentClassName)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }
        $tab->id_parent = (int) Tab::getIdFromClassName($parentClassName);
        $tab->module = $moduleName;
        $tab->add();

        return $tab;
    }

    /**
     * @param $className
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function removeTab($className)
    {
        $id_tab = (int) Tab::getIdFromClassName($className);
        $tab = new Tab($id_tab);
        if ($tab->name !== '') {
            $tab->delete();
        }
    }
}
