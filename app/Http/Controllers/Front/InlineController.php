<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inline;
use Illuminate\Http\Request;

class InlineController extends Controller
{
    public function show(Inline $inline)
    {
        if($inline)
        {
            return $inline;
        } else {
            return response()->json([
                'error' => 'Brak wpisu w bazie',
            ]);
        }
    }

    public function update(Request $request, Inline $inline)
    {
        if ($request->ajax()) {

            if($request->get('locale')) {
                app()->setLocale($request->get('locale'));
            }

            if ($request->hasFile('file')) {
                $inline->upload($request->file_alt, $request->file('file'), $request->obrazek_width, $request->obrazek_height);
            }

            $inline->fill($request->except(['file']));
            $inline->save();

            return response()->json([
                'status' => 'success',
                'item' => $inline->id,
                'items' =>  array_filter($inline->toArray())
            ]);
        }
    }
}
