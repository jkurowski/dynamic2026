<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ZeroOrExists implements Rule
{
    private $table;
    private $column;

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        return $value === 0 || DB::table($this->table)->where($this->column, $value)->exists();
    }

    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}