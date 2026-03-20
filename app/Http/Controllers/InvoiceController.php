<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Services\InvoiceHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $invoices = Invoice::with(['customer', 'user', 'items.item'])
            ->latest()
            ->paginate(10);

        return response()->json($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $data = $request->validated();

        $invoice = DB::transaction(function () use ($data) {
            $invoice = Invoice::create([
                'invoice_number' => InvoiceHelper::generateInvoiceNumber(),
                'customer_id' => $data['customer_id'],
                'user_id' => auth()->id(),
                'issue_date' => $data['issue_date'],
                'due_date' => $data['due_date'],
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $invoiceRow) {
                $item = Item::query()
                    ->lockForUpdate()
                    ->findOrFail($invoiceRow['item_id']);

                if (! $item->is_active) {
                    abort(422, "Item '{$item->name}' is inactive.");
                }

                if ($item->stock_quantity < $invoiceRow['quantity']) {
                    abort(422, "Insufficient stock for item '{$item->name}'. Available stock: {$item->stock_quantity}.");
                }

                $amount = $item->unit_price * $invoiceRow['quantity'];

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item->id,
                    'description' => $item->description ?: $item->name,
                    'unit_price' => $item->unit_price,
                    'quantity' => $invoiceRow['quantity'],
                    'amount' => $amount,
                ]);

                $item->decrement('stock_quantity', $invoiceRow['quantity']);

                $totalAmount += $amount;
            }

            $invoice->update([
                'total_amount' => $totalAmount,
            ]);

            return $invoice->load(['customer', 'user', 'items.item']);
        });

        return response()->json($invoice, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load(['customer', 'user', 'items.item']);

        return response()->json($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        DB::transaction(function () use ($invoice) {
            $invoice->load('items.item');

            foreach ($invoice->items as $invoiceItem) {
                $invoiceItem->item?->increment('stock_quantity', $invoiceItem->quantity);
            }

            $invoice->delete();
        });

        return response()->json([
            'message' => 'Invoice deleted successfully.',
        ]);
    }
}
