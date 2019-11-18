<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Gate;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\Http\Requests\StoreComment;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;




class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        return PostResource::collection(
            Post::orderBy('created_at', 'desc')
                ->paginate()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request, Post $post)
    {

        $post = Post::create([
            'user_id' => auth('api')->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'picture' => $request->picture,
        ]);
        if ($request->hasFile('picture')) {
            $imageExtension = $request->picture->getClientOriginalExtension();
            $imageName = $post->title . '.' . $imageExtension;
            $post->picture = $imageName;
            $request->picture->move('public/image/', $imageName);
        }
        return new PostResource($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $post = Post::where('user_id', auth('api')->id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit()
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, Post $post)
    {
        $post->update([
            'user_id' => auth('api')->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'picture' => $request->picture,
        ]);
        if ($request->hasFile('picture')) {
            $imageExtension = $request->picture->getClientOriginalExtension();
            $imageName = $post->title . '.' . $imageExtension;
            $post->picture = $imageName;
            $request->picture->move('public/image/', $imageName);
        }
        $post->save();
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function delete(Post $post)
    {
        $post->delete();
        if ($post->delete()) {
            return new PostResource($post);
        }
    }

    public function comments(Post $post)
    {
        return CommentResource::collection(
            $post->comments()->with('user')->latest()->paginate()
        );
    }

    public function storeComment(StoreComment $request, Post $post)
    {
        $comment = $post->comments()->create([

            'post_id' => $request->post_id,
            'user_id' => auth('api')->user()->id,
            'content' => $request->content,
        ]);

        return new CommentResource($comment);
    }
}
