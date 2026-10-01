<?php namespace App\Repositories\Board;

interface StageRepositoryInterface
{
    public function createStage(array $attributes);
    public function updateStage(array $attributes, $model);
}
