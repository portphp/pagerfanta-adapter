<?php

namespace Port\Pagerfanta;

use Pagerfanta\Pagerfanta;
use Port\Reader\CountableReader;

/**
 * Read a dataset paginated from it's original source if iteration is not possible
 *
 * Example use case: large amount of data from database
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class PagerfantaReader implements CountableReader
{
    /**
     * @var Pagerfanta
     */
    protected $pagerfanta;

    /**
     * Current dataset
     *
     * @var array
     */
    protected $data;

    /**
     * Currently processed item index
     *
     * @var integer
     */
    protected $currentItem = 0;

    /**
     * @param Pagerfanta $pagerfanta
     */
    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->pagerfanta = $pagerfanta;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return array_keys($this->current());
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $this->paginate();

        return current($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->currentItem++;

        next($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        $this->paginate();

        return key($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $key = $this->key();

        return ($key !== null && $key !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->pagerfanta->setCurrentPage(1);

        $this->loadData();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->pagerfanta->getNbResults();
    }

    /**
     * Paginate to the next dataset if possible
     */
    protected function paginate()
    {
        if ($this->currentItem == $this->pagerfanta->getMaxPerPage() and $this->pagerfanta->hasNextPage()) {
            $this->pagerfanta->setCurrentPage($this->pagerfanta->getNextPage());
            $this->loadData();
        }
    }

    /**
     * Loads current dataset
     */
    protected function loadData()
    {
        $this->currentItem = 0;
        $this->data = $this->pagerfanta->getCurrentPageResults();
    }
}
