<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostCollection;
use App\Models\Post;

use Illuminate\Http\Request;
use App\Http\Resources\V1\PostResource;
use App\Models\Raffle;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

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
        return new PostCollection(Post::all());
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
        if(is_array($request->image)){
            foreach($request->image as $itemFile){
                $path=$itemFile->store('public/posts');
                $post->image= Storage::url($path);

            }
        }else{
            $path=$request->image->store('public/posts');
            $post->image= Storage::url($path);
        }

        $post->save();

        if(is_array($request->image)){
            foreach($request->image as $itemFile){
                $path=$itemFile->store('public/posts');
                $pathInsert=Storage::url($path);
                Raffle::create(['post_id'=>$post->id,'path'=>$pathInsert]);
            }
        }else{
            $path=$request->image->store('public/posts');
            $pathInsert=Storage::url($path);
            Raffle::create(['post_id'=>$post->id,'path'=>$pathInsert]);
        }


        return Response()->json(new PostResource($post),status:200);

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
        if($request->file('image')){
            if(is_array($request->image)){
                foreach($request->image as $itemFile){
                    $path=$itemFile->store('public/posts');
                    $postUpdate->image= Storage::url($path);

                }
            }else{
                $path=$request->image->store('public/posts');
                $postUpdate->image= Storage::url($path);
            }

            if(is_array($request->image)){
                foreach($request->image as $itemFile){
                    $path=$itemFile->store('public/posts');
                    $pathInsert=Storage::url($path);
                    Raffle::create(['post_id'=>$postUpdate->id,'path'=>$pathInsert]);
                }
            }else{
                $path=$request->image->store('public/posts');
                $pathInsert=Storage::url($path);
                Raffle::create(['post_id'=>$postUpdate->id,'path'=>$pathInsert]);
            }
        }

        if(is_array($request->deleteIds) && !isEmpty($request->deleteIds)){
            foreach($request->deleteIds as $delete){
                $data=Raffle::find($delete);
                $path=$data->path;
                $pathDelete=str_replace('storage','public',$path);
                Storage::delete($pathDelete);
                Raffle::destroy($delete);

            }
        }else if (!isEmpty($request->deleteIds)){
            $data=Raffle::find($request->deleteIds);
            $path=$data->path;
            $pathDelete=str_replace('storage','public',$path);
            Storage::delete($pathDelete);
            Raffle::destroy($request->deleteIds);
        }

        $postUpdate->name = $request->name;
        $postUpdate->description = $request->description;
        $postUpdate->price = $request->price;
        $postUpdate->numTicket = $request->numTicket;
        $postUpdate->dateGame = $request->dateGame;
        $postUpdate->save();
        return Response()->json(new PostResource($postUpdate), status: 200);
        //return Response()->json($arrayId, status: 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {

        foreach($post->raffles as $raffle){
            $data=Raffle::find($raffle->id);
            $path=$data->path;
            $pathDelete=str_replace('storage','public',$path);
            Storage::delete($pathDelete);
            Raffle::destroy($raffle->id);
        }

        $post->delete();
    }
}
