<?php
/**
 * @author     Jan Schneider <jan@horde.org>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @category   Horde
 * @package    Vcs
 * @subpackage UnitTests
 */
namespace Horde\Vcs;
use Horde_Test_Case as TestCase;

class TestBase extends TestCase
{
    static $conf;

    public static function setUpBeforeClass(): void
    {
        self::$conf = self::getConfig('VCS_TEST_CONFIG');
    }
}
