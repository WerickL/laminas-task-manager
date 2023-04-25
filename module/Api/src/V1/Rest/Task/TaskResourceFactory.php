<?php
namespace Api\V1\Rest\Task;

class TaskResourceFactory
{
    public function __invoke($services)
    {
        return new TaskResource();
    }
}
