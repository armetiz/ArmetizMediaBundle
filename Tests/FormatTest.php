<?php

namespace Armetiz\MediaBundle\Tests\DependencyInjection;

use Armetiz\MediaBundle\Format;

class FormatTest extends \PHPUnit_Framework_TestCase {
    public function testFormat()
    {
        $transformer = $this->getMock("Armetiz\MediaBundle\Transformer\TransformerInterface");
                
        $format = new Format("fake_name", $transformer, array(
            "key_a" => "value_a",
            "key_b" => "value_b",
        ));
        
        $this->assertEquals("fake_name", $format->getName());
        $this->assertNotNull($format->getTransformer());
        $this->assertNotNull($format->getOptions());
    }
}
