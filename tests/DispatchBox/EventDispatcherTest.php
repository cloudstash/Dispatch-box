<?php

class EventDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function testSingleTrigger()
    {
        $test_name = 'Test';
        $dsp = new \Cloudstash\DispatchBox\EventDispatcher();
        $dsp->register('test', function($name) {
            return $name;
        });
        $result = $dsp->fire('test', [
            'name' => $test_name
        ]);

        $this->assertEquals($result, $test_name);
    }

    public function testMultiTrigger()
    {
        $dsp = new \Cloudstash\DispatchBox\EventDispatcher();

        $test_iterator = 0;

        $dsp->register('test', function() use (&$test_iterator) {
            $test_iterator++;
        });


        $dsp->register('test', function() use (&$test_iterator) {
            $test_iterator = $test_iterator + 8;
        });

        $result = $dsp->fire('test');

        $this->assertTrue($result);
        $this->assertEquals($test_iterator, 9);
    }
}