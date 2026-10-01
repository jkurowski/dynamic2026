<?php namespace App\Repositories\Client;

interface FileRepositoryInterface
{
    public function updateClientFileDesc(array $attributes, $model);
    public function removeClientFileDesc($model);
}
