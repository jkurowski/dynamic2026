<?php namespace App\Repositories\Board;

use App\Models\Task;
use App\Repositories\BaseRepository;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function createTask(array $attributes): array
    {
        $new_model = $this->model->create($attributes);
        $last_model_position = $this->model->where('stage_id', $attributes['stage_id'])->max('sort');
        $new_model->update(['sort' => $last_model_position + 1]);

        return [
            'success' => true,
            'action' => 'created',
            'id' => $new_model->id,
            'name' => $new_model->name,
            'client_id' => $new_model->client?$new_model->client->name:'',
            'stage_id' => $new_model->stage_id,
            'created_at' => $new_model->created_at->diffForHumans()
        ];
    }

    public function updateTask(array $attributes, $model): array
    {
        $model->update($attributes);
        $new_model = $model->refresh();

        return [
            'success' => true,
            'action' => 'updated',
            'id' => $new_model->id,
            'name' => $new_model->name,
            'client_id' => $new_model->client?$new_model->client->name:'',
            'stage_id' => $new_model->stage_id,
            'created_at' => $new_model->created_at->diffForHumans()
        ];
    }

}
