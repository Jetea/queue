<?php

namespace Jetea\Queue;

use Jetea\Queue\Drivers\QueueInterface;

class Queue
{
    const TYPE = 'jetea';

    /**
     * @var QueueInterface
     */
    protected $driver;

    /**
     * @var string
     */
    protected $defaultQueue = 'default';

    public function __construct(QueueInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  Job  $job
     *
     * @return mixed
     */
    public function push(Job $job)
    {
        return $this->driver->push(
            $job->queue,
            $this->createPayload($job),
            $job->retry_after
        );
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  int $delay 延迟ready的秒数，在这段时间job为delayed状态。
     * @param  Job  $job
     * @return mixed
     */
    public function later($delay, Job $job)
    {
        return $this->driver->later(
            $delay,
            $job->queue,
            $this->createPayload($job),
            $job->retry_after
        );
    }

    /**
     * @param string $queue
     *
     * @return null|\Jetea\Queue\Drivers\Jobs\JobInterface
     */
    public function pop($queue = '')
    {
        return $this->driver->pop($this->getQueue($queue));
    }

    /**
     * Get the queue or return the default.
     *
     * @param  string|null  $queue
     * @return string
     */
    protected function getQueue($queue)
    {
        return $queue ? $queue : $this->defaultQueue;
    }

    /**
     * Create a payload string from the given job and data.
     *
     * @param  Job $job
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function createPayload(Job $job)
    {
        $payload = json_encode([
            'type'  => self::TYPE, //只有 type 为 jetea 才会自动处理，其他的不处理
            'job'   => serialize(clone $job),
        ]);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Unable to create payload: ' . json_last_error_msg());
        }

        return $payload;
    }
}
