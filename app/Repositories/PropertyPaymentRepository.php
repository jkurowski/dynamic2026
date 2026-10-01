<?php namespace App\Repositories;

use App\Models\PropertyPayment;

class PropertyPaymentRepository extends BaseRepository
{
    protected $model;

    public function __construct(PropertyPayment $model)
    {
        parent::__construct($model);
    }
}
