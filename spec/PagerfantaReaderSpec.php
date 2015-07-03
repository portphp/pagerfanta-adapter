<?php

namespace spec\Port\Pagerfanta;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;

class PagerfantaReaderSpec extends ObjectBehavior
{
    function let(Pagerfanta $pagerfanta)
    {
        $this->beConstructedWith($pagerfanta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Pagerfanta\PagerfantaReader');
    }

    function it_is_a_countable_reader()
    {
        $this->shouldImplement('Port\Reader\CountableReader');
    }
}
