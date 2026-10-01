<?php namespace App\Repositories\Board;

interface TaskRepositoryInterface
{
    public function createTask(array $attributes);
    public function updateTask(array $attributes, $model);
}
