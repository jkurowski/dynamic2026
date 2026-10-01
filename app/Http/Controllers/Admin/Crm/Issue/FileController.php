<?php

namespace App\Http\Controllers\Admin\Crm\Issue;

use App\Http\Controllers\Controller;
use App\Models\IssueFile;
use Illuminate\Http\Request;

// CMS
use App\Repositories\IssueRepository;
use App\Services\IssueService;
use App\Models\Issue;

class FileController extends Controller
{
    private IssueRepository $repository;
    private IssueService $service;

    public function __construct(IssueRepository $repository, IssueService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function upload(Request $request, Issue $issue)
    {
        if (request()->ajax()) {

            if ($request->hasFile('qqfile')) {
                $upload = $this->service->upload($issue, $request->file('qqfile'));
            }
            return response()->json([
                'success' => true,
                'file' => $upload
            ]);
        }
    }

    public function destroy(Issue $issue, IssueFile $issueFile)
    {
        if (request()->ajax()) {
            $issueFile->delete();
            return ['success' => true];
        }
    }

}
