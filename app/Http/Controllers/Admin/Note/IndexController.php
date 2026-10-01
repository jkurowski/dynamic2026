<?php

namespace App\Http\Controllers\Admin\Note;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Note_v2Request;
use App\Models\Note;

// CMS

class IndexController extends Controller
{
    public function store(Note_v2Request $request)
    {
        $modelType = $request->input('model_type');
        $convertedModelType = str_replace('AppModels', 'App\Models\\', $modelType);

        $note = new Note();
        $note->user_id = auth()->id();
        $note->text = $request->input('text');
        $note->pinned = $request->input('pinned', 0);
        $note->model_type = $convertedModelType;
        $note->model_id = $request->input('model_id');
        $note->save();

        return response()->json([
            'success' => true,
            'note' => [
                'id' => $note->id,
                'text' => $note->text,
                'user' => $note->user()->first()->toArray(),
                'created_at' => $note->created_at->diffForHumans()
            ]],
            201);
    }

    public function update(Note_v2Request $request, Note $note)
    {
        $note->update(['text' => $request->input('text')]);
        return [
            'success' => true,
            'note' => [
                'text' => $request->input('text')
            ]
        ];
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(['success' => true]);
    }
}
