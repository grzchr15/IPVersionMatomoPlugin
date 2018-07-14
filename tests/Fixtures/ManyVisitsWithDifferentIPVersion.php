<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link    http://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\IPVersion\tests\Fixtures;

use Piwik\Date;
use Piwik\Tests\Framework\Fixture;

class ManyVisitsWithDifferentIPVersion extends Fixture
{
    public $idSite = 1;
    public $dateTime = '2020-09-01 07:00:00';

    public $ipVersions = array('192.168.1.1',
                '::', '::1', '2003:c1:1722:3900:9d3d:136:aad:9380',
                '2003:c1:1722:3900:d30a:41ef:b83c:c781', 'fe80::d3c:4a3e:3dc2:4d9c'
            ); // invalid IPs are caught by the tracker framework already, so cannot test "unknown"

    public function setUp(): void
    {
        $this->setUpWebsitesAndGoals();
        $this->trackVisitsInTimespan();
        $this->trackLaterVisits();
    }

    private function setUpWebsitesAndGoals()
    {
        if (!self::siteCreated($idSite = 1)) {
            self::createWebsite($this->dateTime, 0, "Site 1");
        }
    }

    private function trackVisitsInTimespan()
    {
        static $calledCounter = 0;
        $calledCounter++;

        $dateTime = $this->dateTime;
        $idSite = $this->idSite;

        $t = self::getTracker($idSite, $dateTime);
        $t->setTokenAuth(self::getTokenAuth());
        for ($i = 0; $i < count($this->ipVersions); ++$i) {
            $t->setVisitorId(substr(md5($i + $calledCounter * 1000), 0, $t::LENGTH_VISITOR_ID));
			$t->setIp($this->ipVersions[$i]);

            // first visit
            $date = Date::factory($dateTime)->addHour($i);
            $t->setForceVisitDateTime($date->getDatetime());
            $t->setUrl("http://matomo.net/grue/lair");

            $r = $t->doTrackPageView('Page tracked on day');
            self::checkResponse($r);
        }
    }

    private function trackLaterVisits()
    {
        $dateTime = $this->dateTime;
        $idSite = $this->idSite;

        $t = self::getTracker($idSite, $dateTime, $defaultInit = true);
        $t->setTokenAuth(self::getTokenAuth());
        $t->setForceVisitDateTime(Date::factory($dateTime)->addDay(20)->getDatetime());
        $t->setIp('194.57.91.215');  // TODO
        $t->setUserId('userid.email@example.org');
        $t->setCustomTrackingParameter('ipVersion', '1.0');
        $t->setUrl("http://matomo.net/grue/lair");
        self::checkResponse($t->doTrackPageView('Page tracked later'));
    }
}
