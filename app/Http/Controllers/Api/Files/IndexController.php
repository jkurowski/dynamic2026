<?php

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//CMS

class IndexController extends Controller
{

    use FileTrait;


    public function index()
    {
        // $all_files = File::where('extension', '!=', null)->where('user_id', '=', Auth::user()->id)->get()->toArray();
        $all_files = File::where('extension', '!=', null)->get()->toArray();
        $all_files = array_map(function ($file) {
            return $this->addUrlToFile($file);
        }, $all_files);

        return response()->json(['data' => $all_files], 200);
    }

    public function addUrlToFile($file)
    {

        $file['url'] = $this->getFilePublicPath($file['file']);
        return $file;
    }

    public function search(Request $request)
    {

        $validatedData = $request->validate([
            'query' => 'required|string|max:255',
        ]);


        $query = $validatedData['query'];
        // $results = File::where('name', 'LIKE', "%{$query}%")->where('user_id', '=', Auth::user()->id)->get()->toArray();
        $results = File::where('name', 'LIKE', "%{$query}%")->get()->toArray();
        $results = array_map(function ($file) {
            return $this->addUrlToFile($file);
        }, $results);


        return response()->json(['data' => $results]);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Not implemented yet'], 501);
    }

    public function show(Request $request, int $id)
    {
        $file = File::where('id', $id)->where('user_id', '=', Auth::user()->id)->first();

        if ($file) {
            $file = $file->toArray();
            $file = $this->addUrlToFile($file);
            return response()->json(['data' => $file], 200);
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }
}
