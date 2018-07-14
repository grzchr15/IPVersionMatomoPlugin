<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link http://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\IPVersion;

use Piwik\Archive;
use Piwik\DataTable;
use Piwik\DataTable\Row;
use Piwik\Piwik;

/**
 * API for plugin IPVersion
 *
 * @method static \Piwik\Plugins\IPVersion\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    /**
     * @param int    $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getIPVersion($idSite, $period, $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);
        $archive = Archive::build($idSite, $period, $date, $segment);
        /** @var DataTable|DataTable\Map $dataTable */
        $dataTable = $archive->getDataTable(Archiver::IPVERSION_ARCHIVE_RECORD);
        $dataTable->queueFilter('ReplaceColumnNames');
        $dataTable->queueFilter('ReplaceSummaryRowLabel');
        $dataTable->filter('Piwik\Plugins\IPVersion\DataTable\Filter\ReplaceNullByUnknown');
        $dataTable->filter('AddSegmentValue');

        return $dataTable;
    }
}
