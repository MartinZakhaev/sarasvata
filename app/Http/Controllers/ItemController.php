<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::with(['categories', 'itemFiles'])
                ->select(['items.id', 'items.item_name', 'items.stock', 'categories.category_name'])
                ->leftJoin('category_item', 'items.id', '=', 'category_item.item_id')
                ->leftJoin('categories', 'category_item.category_id', '=', 'categories.id')
                ->groupBy('items.id', 'items.item_name', 'items.stock', 'categories.category_name')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <button class="btn btn-outline-info btn-sm">
                    <span class="details-control cursor-pointer"
                    data-id="' . $row->id . '">
                    Details
                    </span>
                    </button>
                    <a href="javascript:void(0)" 
                    class="edit btn btn-outline-success btn-sm" 
                    data-id="' . $row->id . '">
                    Edit
                    </a> 
                    <a href="javascript:void(0)" 
                    class="delete btn btn-outline-danger btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modal-danger" 
                    data-id="' . $row->id . '">
                    Delete
                    </a>
                    ';
                    return $actionBtn;
                })
                ->addColumn('file_paths', function ($row) {
                    $filePaths = '';
                    foreach ($row->itemFiles as $file) {
                        $filePaths .= '<a href="' . asset($file->file_path) . '">' . $file->file_path . '</a><br>';
                    }
                    return $filePaths;
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
            'files.*' => 'required|image|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $item = Item::create([
            'item_name' => $request->input('name'),
            'stock' => $request->input('stock'),
        ]);

        $item->categories()->attach($request->input('category'));

        $uploadedFiles = [];
        foreach ($request->file('files') as $file) {
            $timestamp = now()->timestamp;
            $filename = $timestamp . '_' . $file->getClientOriginalName();
            $file->move(public_path('image/items'), $filename);
            $filePath = 'image/items/' . $filename;

            $uploadedFile = new ItemFile([
                'file_path' => $filePath,
            ]);

            $item->itemFiles()->save($uploadedFile);

            $uploadedFiles[] = $uploadedFile;
        }

        $notification = array(
            'message' => 'Item created successfully',
            'type' => 'success',
        );

        return response()->json(['notification' => $notification, 'item' => $item]);
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

        $notification = array(
            'message' => 'Item updated successfully',
            'type' => 'success',
        );

        return response()->json(['notification' => $notification, 'item' => $item]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        foreach ($item->itemFiles as $file) {
            File::delete($file->file_path);
        }

        $item->categories()->detach();

        $item->delete();

        $notification = array(
            'message' => 'Item deleted successfully',
            'type' => 'success',
        );

        return response()->json(['notification' => $notification]);
    }
}
