<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class CategoryController extends Controller
{
    private $categories;
    private $category;
    public function index()
    {
        return view('admin.category.index');
    }
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);
        Category::newCategory($request);
        Alert::Success('Category Create Successfully');
        return redirect()->back();
    }
    public function manage()
    {
        $this->categories=Category::orderBy('id','desc')->get();
        return view('admin.category.manage',['categories'=>$this->categories]);
    }
    public function edit($id)
    {
        $this->category=Category::find($id);
        return view('admin.category.edit',['category'=>$this->category]);
    }
    public function update(Request $request,$id)
    {
        Category::updateCategory($request,$id);
        Alert::Success('Category Update Successfully');
        return redirect()->route('category.manage');
    }
    public function delete($id)
    {
        $this->category=Category::find($id);
        if (file_exists($this->category->image))
        {
            unlink($this->category->image);
        }
        $this->category->delete();
        Alert::Success('Category Delete Successfully');
        return redirect()->route('category.manage');
    }
}
