<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::select('*');
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
                    </a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('products.category.index');
    }

    public function getAllCategory()
    {
        $categories = Category::all();
        return response()->json(['data' => $categories]);
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
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }

        $category = Category::create(['category_name' => $request->input('name')]);

        $notification = array(
            'message' => 'Category created successfully',
            'type' => 'success',
        );

        return response()->json(['notification' => $notification, 'category' => $category]);
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
        $category = Category::find($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        $category->category_name = $request->input('name');
        $category->save();

        $notification = array(
            'message' => 'Category updated successfully',
            'type' => 'success',
        );

        return response()->json(['notification' => $notification, 'category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        $category->delete();

        $notification = array(
            'message' => 'Category deleted successfully',
            'type' => 'success',
        );

        return response()->json(['notification' => $notification]);
    }
}
