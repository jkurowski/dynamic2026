<?php

namespace App\Http\Controllers\Admin\Sql;

use App\Http\Controllers\Controller;
use App\Services\SqlPliki;
use Illuminate\Http\Request;

/**
 * Zmiany w bazie z database/sql/*.sql uruchamiane z panelu (serwer bez dostępu do konsoli).
 * Tylko rola Administrator. To samo z konsoli: php artisan sql:wykonaj
 */
class IndexController extends Controller
{
    public function __construct(private SqlPliki $sql)
    {
        $this->middleware('role:Administrator');
    }

    public function index()
    {
        return view('admin.sql.index', ['pliki' => $this->sql->lista()]);
    }

    /** Wszystkie niewykonane po kolei (stop na pierwszym błędzie) albo jeden plik (`plik`) */
    public function wykonaj(Request $request)
    {
        $pliki = $request->filled('plik') ? [$request->input('plik')] : $this->sql->oczekujace();
        $kto = auth()->user()->name . ' ' . auth()->user()->surname;
        $wykonane = [];

        foreach ($pliki as $plik) {
            try {
                $ile = $this->sql->wykonaj($plik, trim($kto));
                $wykonane[] = "{$plik} ({$ile} zapytań)";
            } catch (\Throwable $e) {
                return back()
                    ->with('wykonane', $wykonane)
                    ->with('blad', $e->getMessage());
            }
        }

        return back()->with('wykonane', $wykonane);
    }

    /** Plik puszczony wcześniej ręcznie (konsola mysql, phpMyAdmin) - tylko zapis, bez uruchamiania */
    public function oznacz(Request $request)
    {
        $request->validate(['plik' => 'required|string']);

        try {
            $this->sql->oznacz($request->input('plik'), trim(auth()->user()->name . ' ' . auth()->user()->surname) . ' (oznaczone)');
        } catch (\InvalidArgumentException $e) {
            return back()->with('blad', $e->getMessage());
        }

        return back()->with('wykonane', [$request->input('plik') . ' - oznaczony jako wykonany, bez uruchamiania']);
    }
}
