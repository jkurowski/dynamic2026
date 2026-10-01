<?php

namespace App\Http\Controllers\Admin\Developro\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//CMS
use App\Models\Investment;
use App\Models\InvestmentPayment as Model;
use App\Http\Requests\InvestPaymentsFormRequest as FormRequest;
use App\Repositories\InvestmentPaymentsRepository as Repository;

class IndexController extends Controller
{

    private Repository $repository;
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Investment $investment)
    {
        $list = $investment->payments;
        return view('admin.developro.payments.index', compact('investment', 'list'));
    }

    public function create(Investment $investment)
    {
        return view('admin.developro.payments.form', [
            'cardTitle' => 'Dodaj wpis',
            'investment' => $investment,
            'backButton' => route('admin.developro.investment.payments', $investment)
        ])->with('entry', Model::make());
    }

    public function store(FormRequest $request, Investment $investment)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.developro.investment.payments', $investment))->with('success', 'Nowy wpis dodany');
    }

    public function calculate(Request $request, Investment $investment)
    {
        // Retrieve the total amount from the request
        $totalAmount = $request->input('amount');

        // Validate the input
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Fetch all InvestmentPayment records for the specified investment
        $investmentPayments = Model::where('investment_id', $investment->id)->get();

        // Extract percentages from the amount field of each InvestmentPayment
        $percentages = $investmentPayments->pluck('amount')->toArray();  // Assuming 'amount' contains the percentage

        // Calculate amounts based on the extracted percentages
        $amounts = array_map(function($percentage) use ($totalAmount) {
            $amount = round(($totalAmount * $percentage) / 100, 2);
            return number_format($amount, 2, ',', '');
        }, $percentages);

        // Return the calculated amounts and percentages as a JSON response
        return response()->json([
            'amounts' => $amounts,
            'percentages' => $percentages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investment $investment, Model $payment)
    {
        return view('admin.developro.payments.form', [
            'entry' => $payment,
            'investment' => $investment,
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route('admin.developro.investment.payments', $investment)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Investment $investment, Model $payment)
    {
        $this->repository->update($request->validated(), $payment);
        return redirect(route('admin.developro.investment.payments', $investment))->with('success', 'Wpis zaktualizowany');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
