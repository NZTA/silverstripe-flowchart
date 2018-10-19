<?php

namespace ChTombleson\Flowchart\Test;

class FlowchartTest extends SapphireTest
{
    protected static $fixture_file = 'FlowchartTest.yml';

    public function testInstance()
    {
        $flowchart = $this->objFromFixture('Flowchart', 'Default');

        $this->assertInstanceOf('Flowchart', $flowchart);
        $this->assertEquals($flowchart->Questions()->count(), 4);
        $this->assertEquals($flowchart->Feedback()->count(), 2);
        $this->assertEquals($flowchart->Votes()->count(), 3);
        $this->assertEquals($flowchart->averageVote(), 2);
    }
}
