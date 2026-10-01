<?php

namespace App\Http\Controllers\Admin\Crm\Board;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//CMS
use App\Http\Requests\BoardFormRequest;
use App\Models\Board;

class IndexController extends Controller
{
    public function index()
    {
        return view('admin.crm.board.index', ['list' => Board::all()]);
    }

    public function show(int $id)
    {
        $board = Board::whereId($id)->with('stages.tasks.client')->get();
        return view('admin.crm.board.show', ['board' => $board]);
    }

    public function create()
    {
        return view('admin.crm.board.form', [
            'cardTitle' => 'Dodaj tablice',
            'backButton' => route('admin.crm.board.index')
        ])->with('entry', Board::make());
    }

    public function store(BoardFormRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();
        Board::create($validatedData);

        return redirect(route('admin.crm.board.index'))->with('success', 'Nowa tablica utworzona');
    }

    public function edit(int $id)
    {
        return view('admin.crm.board.form', [
            'entry' => Board::find($id),
            'cardTitle' => 'Edytuj tablice',
            'backButton' => route('admin.crm.board.index')
        ]);
    }

    public function update(BoardFormRequest $request, int $id)
    {
        $board = Board::find($id);
        $board->update($request->validated());

        return redirect(route('admin.crm.board.index'))->with('success', 'Tablica zaktualizowana');
    }

    public function destroy(int $id)
    {
        Board::find($id)->delete($id);
        return response()->json('Deleted');
    }
}
