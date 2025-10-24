<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function updateInventory(Request $request, Book $book)
    {
        $validated = $request->validate([
            'total_copies' => 'nullable|integer|min:0',
            'available_copies' => 'nullable|integer|min:0',
        ]);

        // Find the inventory record for the specified book
        $inventory = $book->inventory;

        if (!$inventory) {
            return response()->json(['message' => 'Inventory record not found'], 404);
        }
        if (isset($validated['total_copies'])) {
            $inventory->total_copies = $validated['total_copies'];
        }
        if ($inventory->available_copies > $inventory->total_copies) {
            $inventory->available_copies = $inventory->total_copies;
        }

        if (isset($validated['available_copies'])) {
            if ($validated['available_copies'] > $inventory->total_copies) {
                return response()->json(['message' => 'Available copies cannot exceed total copies'], 400);
            }
            $inventory->available_copies = $validated['available_copies'];
        }
        $inventory->save();
        return response()->json(
            [
                'message' => 'Inventory updated successfully',
                'inventory' => $inventory
            ],
            200
        );
    }
}
