<?php

namespace Jetea\Queue\Drivers;

interface QueueInterface
{
    /**
     * 立即分发到队列
     * Push a new job onto the queue.
     *
     * @param  string  $payload
     * @param  string  $queue
     * @param  int     $ttr Time To Run: retry_after 允许worker执行的最大秒数
     *
     * @return int
     */
    public function push($queue, $payload, $ttr);

    /**
     * 延时分发到队列
     *
     * @param int $delay 延迟ready的秒数，在这段时间job为delayed状态。
     * @param $queue
     * @param $payload
     * @param int $ttr
     *
     * @return int
     */
    public function later($delay, $queue, $payload, $ttr);

    /**
     * @param $queue
     *
     * @return null|\Jetea\Queue\Drivers\Jobs\JobInterface
     */
    public function pop($queue);
}
