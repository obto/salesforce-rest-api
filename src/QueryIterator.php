<?php
namespace Obto\Salesforce;

class QueryIterator implements \Iterator, \Countable
{
    /** @var Client */
    protected $client;
    /** @var QueryResults */
    protected $currentResultsSet;
    /** @var QueryResults */
    protected $firstResultsSet;
    /** @var bool */
    protected $valid;
    /** @var int */
    protected $position;

    /**
     * @param Client $client Client connected to Redis.
     * @param QueryResults $firstResultsSet
     */
    public function __construct(Client $client, QueryResults $firstResultsSet)
    {
        $this->client = $client;

        $this->firstResultsSet = $firstResultsSet;
        $this->currentResultsSet = $firstResultsSet;
        $this->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->reset();
        $this->next();
    }

    /**
     * Resets the inner state of the iterator.
     */
    protected function reset()
    {
        $this->valid = true;
        $this->position = -1;
        $this->currentResultsSet = $this->firstResultsSet;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $results = $this->currentResultsSet->getResults();
        if (++$this->position < count($results)) {
            return;
        }

        if ($this->currentResultsSet->isDone()) {
            $this->valid = false;

            return;
        }

        $this->currentResultsSet = $this->getNextResultsSet();
        $this->position = 0;
    }

    /**
     * Gets the next results set
     * @return QueryResults
     * @throws Exception\SalesforceNoResults
     */
    protected function getNextResultsSet()
    {
        try {
            return $this->client->getNextQueryResults($this->currentResultsSet);
        } catch (Exception\SalesforceNoResults $e) {
            return new QueryResults(array(), $this->firstResultsSet->getTotalSize(), true, null);
        }
    }

    /**
     * Returns the current QueryResults object being iterated
     * @return QueryResults
     */
    public function getCurrentResultsSet()
    {
        return $this->currentResultsSet;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $results = $this->currentResultsSet->getResults();

        return $results[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->currentResultsSet->getTotalSize();
    }

}
