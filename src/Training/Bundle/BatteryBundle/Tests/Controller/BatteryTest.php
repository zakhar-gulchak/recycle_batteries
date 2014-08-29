<?php
namespace Training\BatteryBundle\Tests\Controller\BatteryTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test for battery add and statistics
 *
 * @group functional
 */
class BatteryTest extends WebTestCase
{
    const BATTERY_ADD_PAGE_URL = '/batterypack/new';
    const SUCCESS_PAGE_URL = '/success';
    const STATISTICS_PAGE_URL = '/statistics';

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $_client;

    public function setUp()
    {
        $this->_client = static::createClient();
    }

    public function tearDown()
    {
        unset($this->_client);
    }

    /**
     * Test for battery add
     */
    public function testBatteryAdd()
    {
        $this->purgeFixtures();

        $this->batteryAdd('AA', 4);
        $this->batteryAdd('AAA', 3);
        $this->batteryAdd('AA', 1);
    }

    /**
     * Test check battery statistics page
     */
    public function testStatistics()
    {
        $crawler = $this->_client->request('GET', self::STATISTICS_PAGE_URL);
        $this->assertEquals(200, $this->_client->getResponse()->getStatusCode());
        $this->assertEquals(
            1,
            $crawler->filterXPath("//tr[td[contains(@class, 'battery-type')]='AA' and td[contains(@class, 'battery-count')]='5']")->count()
        );
        $this->assertEquals(
            1,
            $crawler->filterXPath("//tr[td[contains(@class, 'battery-type')]='AAA' and td[contains(@class, 'battery-count')]='3']")->count()
        );
    }

    /**
     * Battery add action
     *
     * @param string $batteryType
     * @param int $batteryCount
     */
    private function batteryAdd($batteryType, $batteryCount)
    {
        $crawler = $this->_client->request('GET', self::BATTERY_ADD_PAGE_URL);
        $this->assertEquals(200, $this->_client->getResponse()->getStatusCode());
        $this->assertEquals(
            1,
            $crawler->filterXPath("//button[contains(@name, 'save')]")->count()
        );

        $form = $crawler->selectButton('form[save]')->form();
        $form['form[type]'] = $batteryType;
        $form['form[count]'] = $batteryCount;
        $form['form[name]'] = 'default_name';
        $this->_client->submit($form);

        $this->assertTrue($this->_client->getResponse()->isRedirect(self::SUCCESS_PAGE_URL));
    }

    /**
     * Truncate table `battery`
     */
    private function purgeFixtures()
    {
        shell_exec('mysql -u root -proot -e "USE `recycle_batteries`; TRUNCATE `battery`;"');
    }
}
