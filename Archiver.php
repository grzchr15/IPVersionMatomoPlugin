<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link http://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IPVersion;

use Piwik\DataTable;
use Piwik\Metrics;

/**
 * Class Archiver
 * @package Piwik\Plugins\IPVersion
 *
 * Archiver is class processing raw data into ready ro read reports.
 * It must implement two methods for aggregating daily reports
 * aggregateDayReport() and other for summing daily reports into periods
 * like week, month, year or custom range aggregateMultipleReports().
 *
 * For more detailed information about Archiver please visit Matomo developer guide
 * http://developer.matomo.org/api-reference/Piwik/Plugin/Archiver
 */
class Archiver extends \Piwik\Plugin\Archiver
{
    const IPVERSION_DIMENSION = "log_visit.ip_version";
    const IPVERSION_ARCHIVE_RECORD = "IPVersion_archive_record";

    public function aggregateDayReport()
    {
        $visitorMetrics = $this
             ->getLogAggregator()
             ->getMetricsFromVisitByDimension(self::IPVERSION_DIMENSION)
             ->asDataTable();
        $this->getProcessor()->insertBlobRecord(self::IPVERSION_ARCHIVE_RECORD, $visitorMetrics->getSerialized());
    }

    public function aggregateMultipleReports()
    {
         $this->getProcessor()->aggregateDataTableRecords(self::IPVERSION_ARCHIVE_RECORD);
    }
}
