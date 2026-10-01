<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\ArticleFormRequest;
use App\Repositories\ArticleRepository;
use App\Services\ArticleService;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    private ArticleRepository $repository;
    private ArticleService $service;

    public function __construct(ArticleRepository $repository, ArticleService $service)
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.article.index', ['list' => $this->repository->idDesc()]);
    }

    public function create()
    {
        return view('admin.article.form', [
            'cardTitle' => 'Dodaj artykuł',
            'backButton' => route('admin.article.index')
        ])->with('entry', Article::make());
    }

    public function store(ArticleFormRequest $request)
    {
        $validatedData = $request->validated();
        $article = $this->repository->create($validatedData);

        $this->updateArticleFiles($request, $article, 'file', 'upload', false);
        $this->updateArticleFiles($request, $article, 'file_facebook', 'uploadFileFacebook', false);

        return redirect(route('admin.article.index'))->with('success', 'Nowy artykuł dodany');
    }

    public function edit(int $id)
    {
        if(request()->get('lang')) {
            app()->setLocale(request()->get('lang'));
        }

        return view('admin.article.form', [
            'entry' => Article::find($id),
            'cardTitle' => 'Edytuj artykuł',
            'backButton' => route('admin.article.index')
        ]);
    }

    public function update(ArticleFormRequest $request, int $id)
    {
        if(request()->get('lang')) {
            app()->setLocale(request()->get('lang'));
        }

        $article = $this->repository->find($id);
        $this->repository->update($request->validated(), $article);

        $this->updateArticleFiles($request, $article, 'file', 'upload', true);
        $this->updateArticleFiles($request, $article, 'file_facebook', 'uploadFileFacebook', true);

        return redirect(route('admin.article.index'))->with('success', 'Artykuł zaktualizowany');
    }

    private function updateArticleFiles(ArticleFormRequest $request, object $article, string $fileField, string $uploadMethod, bool $delete)
    {
        if ($request->hasFile($fileField)) {
            $this->service->$uploadMethod($request->title, $request->file($fileField), $article, $delete);
        }
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function convert(){
        // Fetch data from the old table
        $oldArticles = DB::connection('old_mysql')->table('news')->get();

        // Define the default locale
        $defaultLocale = 'pl';

        foreach ($oldArticles as $article) {
            $entry = new Article();

            $entry->fill([
                'old_id' => $article->id,
                'file' => $article->plik ?? null,
                'file_webp' => $article->plik_webp ?? null,
                'posted_at' => $article->data,
                'status' => $article->status,
                'file_facebook' => $article->file_facebook ?? null,
                'file_alt' => $article->file_alt ?? null,
                'meta_robots' => $article->meta_robots ?? null,
                'sort' => $article->sort ?? 0,
            ]);

            $entry->setTranslation('title', $defaultLocale, $article->tytul);
            $entry->setTranslation('content_entry', $defaultLocale, $article->wprowadzenie);
            $entry->setTranslation('content', $defaultLocale, $article->tekst);
            $entry->setTranslation('meta_title', $defaultLocale, $article->meta_tytul);
            $entry->setTranslation('meta_description', $defaultLocale, $article->meta_opis);
            $entry->save();
        }

        return redirect(route('admin.article.index'))->with('success', 'Wpisy przetłumaczone');
    }

    public function translate()
    {
        // Define the default locale
        $defaultLocale = 'pl';
        $fallbackLocale = 'en'; // Fallback locale or any other locale you need

        try {
            // Fetch translation records from the old table
            $translations = DB::connection('old_mysql')->table('tlumaczenie_wpisy')
                ->where('module', 'news')
                ->get();

            // Iterate through each translation record
            foreach ($translations as $translation) {
                // Extract old_id and JSON data
                $oldId = $translation->id_wpis;
                $jsonData = json_decode($translation->json, true);

                // Check if JSON data is valid and not empty
                if (is_array($jsonData) && !empty($jsonData)) {
                    // Find the corresponding article in the new database
                    $article = Article::where('old_id', $oldId)->first();

                    if ($article) {
                        // Print article information
                        echo "Article ID: " . $article->id . "<br>";
                        echo "Old ID: " . $oldId . "<br>";

                        // Set and print translations
                        if (isset($jsonData['tytul'])) {
                            echo "Setting translation for 'title' to: " . $jsonData['tytul'] . "<br>";
                            $article->setTranslation('title', $fallbackLocale, $jsonData['tytul']);
                        }
                        if (isset($jsonData['wprowadzenie'])) {
                            echo "Setting translation for 'content_entry' to: " . $jsonData['wprowadzenie'] . "<br>";
                            $article->setTranslation('content_entry', $fallbackLocale, $jsonData['wprowadzenie']);
                        }
                        if (isset($jsonData['tekst'])) {
                            echo "Setting translation for 'content' to: " . htmlspecialchars($jsonData['tekst']) . "<br>";
                            $article->setTranslation('content', $fallbackLocale, $jsonData['tekst']);
                        }
                        echo "<hr>";
                    }
                }
            }

            // Provide feedback
            echo "Translations processing complete.<br>";

        } catch (\Exception $e) {
            // Handle errors
            echo 'Error occurred during translation update: ' . $e->getMessage();
        }
    }
}
