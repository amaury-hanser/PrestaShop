<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\PrestaShop\Adapter\Meta;

use Db;
use DbQuery;
use Meta;
use PrestaShop\PrestaShop\Core\Meta\MetaDataProviderInterface;

/**
 * Class MetaDataProvider is responsible for providing data related with meta entity.
 */
class MetaDataProvider implements MetaDataProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getIdByPage($pageName)
    {
        $query = new DbQuery();
        $query->select('`id_meta`');
        $query->from('meta');
        $query->where('`page`= "' . pSQL($pageName) . '"');

        $idMeta = 0;
        $result = Db::getInstance()->getValue($query);

        if ($result) {
            $idMeta = $result;
        }

        return $idMeta;
    }

    /**
     * Gets default pages which are not configured in Seo & urls page.
     *
     * @return array
     */
    public function getDefaultPagesExcludingFilled()
    {
        $pages = Meta::getPages(true);

        $result = [];
        foreach ($pages as $pageName => $fileName) {
            if (!$this->isModuleFile($fileName)) {
                $result[$pageName] = $fileName;
            }
        }

        return $result;
    }

    /**
     * Gets module pages which are not configured in Seo & urls page.
     *
     * @return array
     */
    public function getModulePagesExcludingFilled()
    {
        $pages = Meta::getPages(true);

        $result = [];
        foreach ($pages as $pageName => $fileName) {
            if ($this->isModuleFile($fileName)) {
                $result[$pageName] = $fileName;
            }
        }

        return $result;
    }

    /**
     * Checks whenever the file contains module file pattern.
     *
     * @param string $fileName
     *
     * @return bool
     */
    private function isModuleFile($fileName) {
        return 0 === strncmp($fileName, 'module-', 7);
    }
}
