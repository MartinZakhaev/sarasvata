<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::with('categories')
                ->select(['items.id', 'items.item_name', 'items.stock', 'items.file_path', 'categories.category_name'])
                ->leftJoin('category_item', 'items.id', '=', 'category_item.item_id')
                ->leftJoin('categories', 'category_item.category_id', '=', 'categories.id')
                ->groupBy('items.id', 'items.item_name', 'items.stock', 'items.file_path', 'categories.category_name')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <span class="details-control cursor-pointer"
                    data-id="' . $row->id . '">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-square-rounded-plus-filled" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                            fill="currentColor" stroke-width="0" />
                    </svg>
                    </span>
                    <a href="javascript:void(0)" 
                    class="edit btn btn-success btn-sm" 
                    data-id="' . $row->id . '">
                    Edit
                    </a> 
                    <a href="javascript:void(0)" 
                    class="delete btn btn-danger btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modal-danger" 
                    data-id="' . $row->id . '">
                    Delete
                    </a>
                    ';
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
            'file' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        // Get the uploaded file
        $file = $request->file('file');

        // Generate a timestamp for the filename
        $timestamp = now()->timestamp;

        // Generate a unique filename with timestamp
        $filename = $timestamp . '_' . $file->getClientOriginalName();

        // Move the file to the public directory
        $file->move(public_path('image/items'), $filename);

        $filePath = 'image/items/' . $filename;

        $item = Item::create([
            'item_name' => $request->input('name'),
            'stock' => $request->input('stock'),
            'file_path' => $filePath,
        ]);

        $item->categories()->attach($request->input('category'));

        return response()->json(['message' => 'Item created successfully', 'item' => $item]);
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
        $item = Item::with(['categories' => function ($query) {
            $query->select('categories.id', 'categories.category_name');
        }])
            ->select(['items.id', 'items.item_name', 'items.stock'])
            ->leftJoin('category_item', 'items.id', '=', 'category_item.item_id')
            ->leftJoin('categories', 'category_item.category_id', '=', 'categories.id')
            ->where('items.id', $id)
            ->groupBy('items.id', 'items.item_name', 'items.stock', 'categories.category_name')
            ->first();
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
        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('product.item.index')->with('error', 'Item not found');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|numeric|min:0',
        ]);

        $item->item_name = $validatedData['name'];
        $item->stock = $validatedData['stock'];
        $item->save();

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
