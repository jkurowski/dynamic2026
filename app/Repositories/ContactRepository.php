<?php namespace App\Repositories;

use App\Models\Contact;

class ContactRepository extends BaseRepository
{
    protected $model;

    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }
}
