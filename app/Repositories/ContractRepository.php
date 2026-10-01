<?php namespace App\Repositories;

use App\Models\Contract;

class ContractRepository extends BaseRepository
{
    protected $model;

    public function __construct(Contract $model)
    {
        parent::__construct($model);
    }
}
