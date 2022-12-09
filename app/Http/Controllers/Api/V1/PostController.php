<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostCollection;
use App\Models\Post;

use Illuminate\Http\Request;
use App\Http\Resources\V1\PostResource;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([new PostCollection(Post::all())], 200);
        //return new PostCollection(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post= new Post($request->all());
        $array=[];
        foreach($request->image as $itemFile){
            $path=$itemFile->store('public/posts');
            $post->image= Storage::url($path);
            array_push($array,'asdasdasdasda');
        }

        $post->save();
        return Response()->json(data:$array,status:200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post)
    {
        $postUpdate = Post::find($post);
        $path = $request->image->store('public/posts');
        $postUpdate->image = $path;
        $postUpdate->name = $request->name;
        $postUpdate->description = $request->description;
        $postUpdate->price = $request->price;
        $postUpdate->numTicket = $request->numTicket;
        $postUpdate->dateGame = $request->dateGame;
        $postUpdate->save();
        return Response()->json(new PostResource($postUpdate), status: 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
