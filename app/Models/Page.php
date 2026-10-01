<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
    use NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active',
        'parent_id',
        'title',
        'title_text',
        'content',
        'content_header',
        'file_header',
        'meta_title',
        'meta_description',
        'meta_robots'
    ];

    public static function mainmenu()
    {
        return view('layouts.partials.menu', [
            'pages' => self::withDepth()->defaultOrder()->get()->toTree()
        ]);
    }

    public static function sidemenu(int $id)
    {
        return view('layouts.partials.sidemenu', [
            'pages' => self::descendantsOf($id)->toTree()
        ]);
    }
}
