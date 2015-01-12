<?php

namespace Cloudstash\DispatchBox;

class EventDispatcher
{
    protected $events = null;

    /**
     * @param $trigger
     */
    protected function initTriggerGroup($trigger)
    {
        if (!is_array($this->events)) {
            $this->events = [];
        }

        if (!isset($this->events[$trigger])) {
            $this->events[$trigger] = [];
        }
    }

    /**
     * @param string $trigger
     * @return array
     */
    protected function getTriggerGroup($trigger)
    {
        $this->initTriggerGroup($trigger);

        return $this->events[$trigger];
    }

    /**
     * @param string $trigger
     * @param callable $handler
     * @return bool
     * @throws InternalException
     */
    public function register($trigger, $handler)
    {
        if (!is_callable($handler)) {
            throw new InternalException('Handler is not callable');
        }

        $this->initTriggerGroup($trigger);
        $this->events[$trigger][] = $handler;

        return true;
    }

    /**
     * @param string $trigger
     * @param array $arguments
     * @return bool|mixed
     */
    public function fire($trigger, array $arguments = [])
    {
        $group = $this->getTriggerGroup($trigger);

        $count = (int) count($group);
        $return = ($count == 1);

        foreach ($group as $i => $handler) {
            if ($return) {
                return call_user_func_array($handler, $arguments);
            }

            call_user_func_array($handler, $arguments);
        }

        return true;
    }
}