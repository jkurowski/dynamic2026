<?php namespace App\Repositories\Board;

use App\Models\Stage;
use App\Repositories\BaseRepository;

class StageRepository extends BaseRepository implements StageRepositoryInterface
{
    protected $model;

    public function __construct(Stage $model)
    {
        parent::__construct($model);
    }

    public function createStage(array $attributes)
    {

        $new_model = $this->model->create($attributes);
        $last_model_position = $this->model->where('id', $attributes['current_stage_id'])->max('sort');
        $new_model->update(['sort' => $last_model_position + 1]);

        return [
            'success' => true,
            'action' => 'created',
            'name' => $new_model->name,
            'current_stage_id' => $attributes['current_stage_id'],
            'id' => $new_model->id
        ];
    }

    public function updateStage(array $attributes, $model): array
    {
        $model->update($attributes);
        $new_model = $model->refresh();

        return [
            'success' => true,
            'action' => 'updated',
            'name' => $new_model->name,
            'id' => $new_model->id
        ];
    }

}
