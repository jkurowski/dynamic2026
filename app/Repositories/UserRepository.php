<?php namespace App\Repositories;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getRoles()
    {
        return Role::pluck('name', 'name')->all();
    }
}
