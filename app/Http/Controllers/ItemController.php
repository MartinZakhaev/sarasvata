<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = Item::select('*');
            $data = Item::with('categories') // Eager load categories relationship
                ->select(['items.id', 'items.item_name', 'items.stock', 'categories.category_name'])
                ->leftJoin('category_item', 'items.id', '=', 'category_item.item_id')
                ->leftJoin('categories', 'category_item.category_id', '=', 'categories.id')
                ->groupBy('items.id', 'items.item_name', 'items.stock', 'categories.category_name')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-id="'
                        . $row->id . '">Edit</a> 
                    <a href="javascript:void(0)" 
                    class="delete btn btn-danger btn-sm" 
                    data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="'
                        . $row->id . '">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('products.item.index');
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
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'stock' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $item = Item::create([
            'item_name' => $request->input('name'),
            'stock' => $request->input('stock'),
        ]);

        $item->categories()->attach($request->input('category'));

        return response()->json(['message' => 'Category created successfully', 'category' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $item = Item::find($id);
        // return response()->json($item);

        $item = Item::with(['categories' => function ($query) {
            $query->select('categories.id', 'categories.category_name');
        }])
            ->select(['items.id', 'items.item_name', 'items.stock'])
            ->leftJoin('category_item', 'items.id', '=', 'category_item.item_id')
            ->leftJoin('categories', 'category_item.category_id', '=', 'categories.id')
            ->where('items.id', $id) // Filter by the specific item ID
            ->groupBy('items.id', 'items.item_name', 'items.stock', 'categories.category_name')
            ->first(); // Use 'first' to retrieve a single record
        return response()->json([
            'id' => $item->id,
            'item_name' => $item->item_name,
            'stock' => $item->stock,
            'categories' => $item->categories->map(function ($category) {
                return [
                    'id' => $category->pivot->category_id,
                    'category_name' => $category->category_name
                ];
            })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Retrieve the item
        $item = Item::find($id);

        // Check if the item exists
        if (!$item) {
            return redirect()->route('product.item.index')->with('error', 'Item not found');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure category exists
            'stock' => 'required|numeric|min:0',
        ]);

        // Update item details
        $item->item_name = $validatedData['name'];
        $item->stock = $validatedData['stock'];
        $item->save();

        // Sync item's categories
        $item->categories()->sync([$validatedData['category_id']]);

        return redirect()->route('product.item.index')->with('success', 'Item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        $item->categories()->detach();

        $item->delete();

        return response()->json(['message' => 'Item deleted successfully.']);
    }
}
