<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $user = User::findorfail($post->user_id);
            $formattedPost = [
                'id' => $post->id,
                'description' => $post->description,
                'image' => asset('storage/post_images/' . basename($post->image)),
                'user' => '',
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ];
    
            $formattedPosts[] = $formattedPost;
        }
    
        // Update the image URL in the pagination data
        $paginationData = $posts->toArray();
        $paginationData['data'] = $formattedPosts;
    
        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => 'required|string|max:255',
                'image' => 'required|file|image|mimes:jpeg,png,jpg|max:5000',
            ]
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $userId = Auth::id();

        $post = new Post();
        $post->user_id = $userId;
        $post->description = $request->description;
        $imagePath = $request->file('image')->store('public/post_images');
        $post->image = $imagePath;

        $post->save();
        return response('Post Created Succesfully!', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        $formattedResponse = [
            'id' => $post->id,
            'description' => $post->description,
            'image' => asset('storage/post_images/' . basename($post->image)),
            'user_id' => $post->user_id,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];

        return Response::json($formattedResponse, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
