<?php namespace App\Repositories\Client;

use App\Models\ClientFile;
use App\Repositories\BaseRepository;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    protected $model;

    public function __construct(ClientFile $model)
    {
        parent::__construct($model);
    }

    public function updateClientFileDesc(array $attributes, $model): array
    {
        $model->update($attributes);
        $new_model = $model->refresh();

        return [
            'success' => true,
            'action' => 'updated',
            'description' => $new_model->description,
            'id' => $new_model->id
        ];
    }

    public function removeClientFileDesc($model): array
    {
        $model->description = null;
        $model->save();

        return [
            'success' => true
        ];
    }

}
