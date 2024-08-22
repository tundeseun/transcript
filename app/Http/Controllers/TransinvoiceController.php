<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transinvoice; 
use App\Models\Cart;
use App\Models\RequestType;
use App\Models\NewRecord;
use App\Models\PrevApp;
use Illuminate\Support\Facades\Auth;


class TransinvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $matric = $user->matric;

        $prevApp = PrevApp::where('matric', $matric)->first();
        if (!$prevApp) {
            return response()->json(['error' => 'User not found in prev_app table.'], 404);
        }
        $newRecord = NewRecord::find($prevApp->user_id);

        if (!$newRecord) {
            return response()->json(['error' => 'User not found in new table.'], 404);
        }
        $fullName = $newRecord->Surname . ' ' . $newRecord->Other_names;
       
        $invoices = Transinvoice::where('appno', $matric)->orderby('id','desc')->get();
        
        return view('invoices.index', compact('invoices','fullName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Transinvoice::create($request->all());
        $user = Auth::user();
        $matric = $user->matric;
        Cart::where('matric', $matric)->delete();
        return redirect()->route('transinvoice.index')->with('success', 'Checkout successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $matric = $user->matric;

        $prevApp = PrevApp::where('matric', $matric)->first();
        if (!$prevApp) {
            return response()->json(['error' => 'User not found in prev_app table.'], 404);
        }
        $newRecord = NewRecord::find($prevApp->user_id);

        if (!$newRecord) {
            return response()->json(['error' => 'User not found in new table.'], 404);
        }
        $fullName = $newRecord->Surname . ' ' . $newRecord->Other_names;
    $invoice = Transinvoice::findOrFail($id);

        return view('invoices.invoice', compact('invoice','fullName'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $matric = $user->matric;

        $prevApp = PrevApp::where('matric', $matric)->first();
        if (!$prevApp) {
            return response()->json(['error' => 'User not found in prev_app table.'], 404);
        }
        $newRecord = NewRecord::find($prevApp->user_id);

        if (!$newRecord) {
            return response()->json(['error' => 'User not found in new table.'], 404);
        }
        $fullName = $newRecord->Surname . ' ' . $newRecord->Other_names;
        $invoice = Transinvoice::findOrFail($id);
        $requests = RequestType::all();
        return view('invoices.edit', compact('invoice','requests','fullName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $invoice = Transinvoice::findOrFail($id);

    $transactions = $request->input('transactions', []);

    $purposes = [];
    $amounts = [];
    $copies = [];
    $total = 0;

    foreach ($transactions as $transaction) {
        $purpose = $transaction['purpose'] ?? $transaction['original_purpose'];
        $amount = $transaction['amount'] ?? $transaction['original_amount'];
        $copy = $transaction['copy'] ?? $transaction['original_copy'];
        $total += $transaction['total'];

        $purposes[] = $purpose;
        $amounts[] = $amount;
        $copies[] = $copy;
    }

    // Concatenate the values
    $concatenatedPurposes = implode(',', $purposes);
    $concatenatedAmounts = implode(',', $amounts);
    $concatenatedCopies = implode(',', $copies);

    // Update the invoice record
    $invoice->update([
        'purpose' => $concatenatedPurposes . ',',
        'dy' => $concatenatedAmounts . ',',
        'mth' => $concatenatedCopies . ',',
        'amount_charge' => $total
    ]);

    return redirect()->route('transinvoice.index')->with('success', 'Invoice updated successfully.');
}

  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
