<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link http://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\IPVersion\tests\System;

use Piwik\Plugins\IPVersion\tests\Fixtures\ManyVisitsWithDifferentIPVersion;
use Piwik\Tests\Framework\TestCase\SystemTestCase;

/**
 * @group IPVersion
 * @group Plugins
 */
class ApiTest extends SystemTestCase
{
    /**
     * @var ManyVisitsWithDifferentIPVersion
     */
    public static $fixture = null; // initialized below class definition

    public static function getOutputPrefix()
    {
        return '';
    }

    public static function getPathToTestDirectory()
    {
        return dirname(__FILE__);
    }

    /**
     * @dataProvider getApiForTesting
     */
    public function testApi($api, $params)
    {
        $this->runApiTests($api, $params);
    }

    public function getApiForTesting()
    {
        $apiToTest[] = array(
            array('IPVersion'),
            array(
                'idSite'  => self::$fixture->idSite,
                'date'    => self::$fixture->dateTime,
                'periods' => array('day'),
            )
        );

        return $apiToTest;
    }
}

ApiTest::$fixture = new ManyVisitsWithDifferentIPVersion();
