<?php namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class BaseRepository implements EloquentRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function pluckGroup(int $group_id, array $attributes): Collection
    {
        return $this->model->where('group_id', $group_id)->get($attributes);
    }

    public function allSort($order): Collection
    {
        return $this->model->orderBy('sort', $order)->get();
    }

    public function allSortBy($field, $order): Collection
    {
        return $this->model->orderBy($field, $order)->get();
    }

    public function allSortByWhere($whereField, $whereCondition, $field, $order): Collection
    {
        return $this->model->where($whereField, $whereCondition)->orderBy($field, $order)->get();
    }

    public function idDesc(): Collection
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function allParent(int $parent_id): Collection
    {
        return $this->model->where('parent_id', $parent_id)->whereUserId(auth()->id())->orderBy('type', 'DESC')->get();
    }

    public function allParentNull(): Collection
    {
        return $this->model->whereNull('parent_id')->whereUserId(auth()->id())->orderBy('type', 'DESC')->get();
    }

    public function find($id): ?Model
    {
        if (null == $entry = $this->model->find($id)) {
            throw new ModelNotFoundException("Entry not found");
        }
        return $entry;
    }

    public function findBySlug($slug): ?Model
    {
        if (null == $entry = $this->model->where('slug', '=', $slug)->first()) {
            throw new ModelNotFoundException("Entry not found");
        }
        return $entry;
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes, $updatedModel)
    {
        return $updatedModel->update($attributes);
    }

    public function delete($id): int
    {
        Session::flash('success', 'Wpis usunięty');
        return $this->model->destroy($id);
    }

    public function updateOrder(array $recordsArray)
    {
        $listingCounter = 1;
        foreach ($recordsArray as $recordIDValue) {
            if($recordIDValue > 0) {
                $entry = $this->model->find($recordIDValue);
                $entry->sort = $listingCounter;
                $entry->save();
                $listingCounter = $listingCounter + 1;
            }
        }
    }
    public function updateOrderWithStage(array $itemsArray, int $stage_id)
    {
        $listingCounter = 1;
        foreach ($itemsArray as $item) {
            $entry = $this->model->find($item);
            $entry->sort = $listingCounter;
            $entry->stage_id = $stage_id;
            $entry->save();
            $listingCounter = $listingCounter + 1;
        }
    }
}
