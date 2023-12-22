<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $data = Post::get();

        return response()->json(['data'=>$data ]);
    }

    public function store (Request $request){

        $Post = new Post();
        $Post->title= $request->input('title');
        $Post->body = $request->input('body');
        $Post->save();

        return response()->json(['Post' => $Post, 'message' => 'Successfully Create', 'status'=> true]);
        
    }
}
