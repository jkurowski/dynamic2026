<?php

namespace App\Http\Controllers\Admin\Crm\Board;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageFormRequest;
use App\Models\Stage;
use App\Repositories\Board\StageRepository;
use Illuminate\Http\Request;

//CMS

class StageController extends Controller
{
    private $repository;

    public function __construct(StageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function sort(Request $request)
    {
        if (request()->ajax()) {

            if($request->get('items')) {
                $this->repository->updateOrder($request->get('items'));
            }
            return ['success' => true];
        }
    }

    public function form(Request $request)
    {
        if (request()->ajax()) {
            if($request->input('id')){

                $stage = $this->repository->find($request->input('id'));

                return view('admin.crm.modal.board-stage', [
                    'stage' => $stage,
                    'board_id' => $request->input('board_id')
                ])->render();

            } else {
                return view('admin.crm.modal.board-stage',
                    [
                        'stage_id' => $request->input('stage_id'),
                        'board_id' => $request->input('board_id')
                    ])
                    ->with('stage', Stage::make())
                    ->render();
            }
        }
    }

    public function store(StageFormRequest $request)
    {
        if (request()->ajax()) {
            if($request->input('id')) {
                $stage = $this->repository->find($request->input('id'));
                return $this->repository->updateStage($request->validated(), $stage);
            } else {
                return $this->repository->createStage($request->validated());
            }
        }
    }

    public function destroy(Stage $stage)
    {
        if (request()->ajax()) {
            $stage->delete();
            return ['success' => true];
        }
    }
}
