<?php

namespace Eskrano\QuickbaseRest\Sections;

use Eskrano\QuickbaseRest\Interfaces\SectionInterface;
use Eskrano\QuickbaseRest\QuickbaseREST;

class AbstractSection implements SectionInterface
{
    /**
     * @var QuickbaseREST
     */
    protected $client;

    public function setClient(QuickbaseREST $quickbaseREST)
    {
        $this->client = $quickbaseREST;
    }

}