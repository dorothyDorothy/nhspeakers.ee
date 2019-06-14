<?php

//namespace CT_lasting_impressions\Tests;

require_once 'constants.php';

require_once(PATH_THIRD . 'lasting_impressions/mod.lasting_impressions.php');

//use CT_Lasting_impressions\Lasting_impressions;

/*
 * n.b. make sure to prepend the parent class with a slash!
 */
class Lasting_impressionsTest extends \PHPUnit_Framework_TestCase {

    protected $li_mock;

    public function setUp() {
        $this->li_mock = $this->getMockBuilder('Lasting_impressions')
                ->disableOriginalConstructor()
                ->setMethods(array(
                    'get_cookie',
                    'is_enabled',
                    'get_entry_id',
                    'setExpires',
                    'set_cookie',
                    'get_template_params',
                    'return_no_results',
                    'return_error'
                ))
                ->getMock();
        $this->li_mock->expects($this->any())
                ->method('get_cookie')
                ->will($this->returnValue('345|567|784|123'));
    }

    public function testImpressionCountGreaterThanZero() {
        $num = $this->li_mock->count();
        $this->assertGreaterThan(0, $num);
    }

    public function testRemoveItemFromCookie() {
        $this->li_mock->expects($this->once())
                ->method('get_entry_id')
                ->will($this->returnValue('567'));

        $cookie = $this->li_mock->remove_item_from_cookie();
        $cookie_array = explode('|', $cookie);
        $this->assertEquals(3, count($cookie_array));
    }

    public function testEntriesHasNoException() {
        $this->setExpectations();
        $obj = $this->li_mock->entries();
    }

    private function setExpectations() {
        $this->li_mock->expects($this->any())
                ->method('is_enabled')
                ->will($this->returnValue('y'));

        $this->li_mock->expects($this->any())
                ->method('get_template_params')
                ->will($this->returnValueMap(
                                array(
                                    'channel' => 'shop_products',
                                    'status' => 'open'
                                )
        ));
    }

    static function main() {
        echo "\r\nin main\r\n";
        $suite = new PHPUnit_Framework_TestSuite(__CLASS__);
        PHPUnit_TextUI_TestRunner::run($suite);
    }

}

if (!defined('PHPUnit_MAIN_METHOD')) {
    Lasting_impressionsTest::main();
}