<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    private static $image;
    private static $imageName;
    private static $imageUrl;
    private static $directory;
    private static $category;

    public static function getImageUrl($request)
    {
        self::$image             =$request->file('image');
        self::$imageName         =time().''.self::$image->getClientOriginalName();
        self::$directory         ='category-image/';
        self::$image->move(self::$directory,self::$imageName);
        self::$imageUrl          =self::$directory.self::$imageName;
        return self::$imageUrl;
    }
    public static function newCategory($request)
    {
        self::$category              =new Category();
        self::$category->name        =$request->name;
        self::$category->slug=Str::slug($request->name);
        self::$category->description =$request->description;
        if ($request->file('image'))
        {
            self::$category->image  =self::getImageUrl($request);
        }
        if ($request->status==1)
        {
            self::$category->status=1;
        }
        else
        {
            self::$category->status=2;
        }
        self::$category->save();
    }
    public static function updateCategory($request,$id)
    {
        self::$category             =Category::find($id);
        if ($request->file('image'))
        {
            if(file_exists(self::$category->image))
            {
                unlink(self::$category->image);
            }
            self::$imageUrl        =self::getImageUrl($request);

        }
        else
        {
            self::$imageUrl        =self::$category->image;
        }
        self::$category->name      =$request->name;
        self::$category->slug=Str::slug($request->name);
        self::$category->description=$request->description;
        if ($request->file('image'))
        {
            self::$category->image  =self::$imageUrl;
        }
        if ($request->status==1)
        {
            self::$category->status   =1;
        }
        else
        {
            self::$category->status   =2;
        }
        self::$category->save();
    }
}
