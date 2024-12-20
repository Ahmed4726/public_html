<?php

namespace Guzzle\Service;

use Guzzle\Common\AbstractHasDispatcher;

/**
 * Apply a callback to the contents of a {@see ResourceIterator}
 */
class ResourceIteratorApplyBatched extends AbstractHasDispatcher
{
    /**
     * @var function|array
     */
    protected $callback;

    /**
     * @var ResourceIterator
     */
    protected $iterator;

    /**
     * @var integer Total number of sent batches
     */
    protected $batches = 0;

    /**
     * @var int Total number of iterated resources
     */
    protected $iterated = 0;

    /**
     * {@inheritdoc}
     */
    public static function getAllEvents()
    {
        return array(
            // About to send a batch of requests to the callback
            'iterator_batch.before_batch',
            // Finished sending a batch of requests to the callback
            'iterator_batch.after_batch'
        );
    }

    /**
     * Constructor
     *
     * @param ResourceIterator $iterator Resource iterator to apply a callback to
     * @param array|function $callback Callback method accepting the resource
     *      iterator and an array of the iterator's current resources
     */
    public function __construct(ResourceIterator $iterator, $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }

    /**
     * Apply the callback to the contents of the resource iterator
     *
     * Calling this method dispatches four events:
     *
     *   # before_apply -- Before adding a resource to a batch.  Context is the resource
     *   # after_apply -- After adding the resource to a batch.  Context is the resource
     *   # before_batch -- Before a batch request is sent if one is sent.  Context is an array of resources
     *   # after_batch -- After a batch request is sent if one is sent.  Context is an array of resources
     *
     * @return integer Returns the number of resources iterated
     */
    public function apply($perBatch = 20)
    {
        if ($this->iterated == 0) {
            $batched = array();
            foreach ($this->iterator as $resource) {
                $batched[] = $resource;
                if (count($batched) >= $perBatch) {
                    $this->applyBatch($batched);
                    $batched = array();
                }
                $this->iterated++;
            }

            if (count($batched)) {
                $this->applyBatch($batched);
            }
        }

        return $this->iterated;
    }

    /**
     * Get the total number of batches sent
     *
     * @return int
     */
    public function getBatchCount()
    {
        return $this->batches;
    }

    /**
     * Get the total number of iterated resources
     *
     * @return int
     */
    public function getIteratedCount()
    {
        return $this->iterated;
    }

    /**
     * Apply the callback to a collection of resources
     *
     * @param array $batch
     */
    private function applyBatch(array $batch)
    {
        $this->batches++;

        $this->dispatch('iterator_batch.before_batch', array(
            'iterator' => $this,
            'batch'    => $batch
        ));

        call_user_func_array($this->callback, array(
            $this->iterator, $batch
        ));

        $this->dispatch('iterator_batch.after_batch', array(
            'iterator' => $this,
            'batch'    => $batch
        ));
    }
}