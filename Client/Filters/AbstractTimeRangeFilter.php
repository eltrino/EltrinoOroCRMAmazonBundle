<?php

namespace OroCRM\Bundle\AmazonBundle\Client\Filters;

abstract class AbstractTimeRangeFilter implements FilterInterface
{
    /**
     * Date Format ISO8601
     */
    const DATE_FORMAT = 'Y-m-d\TH:i:sO';

    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @var \DateTime
     */
    protected $to;

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     */
    public function __construct(\DateTime $from, \DateTime $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * @inheritdoc
     */
    abstract public function process(array $parameters);
}
