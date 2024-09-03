<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function deletePost(Post $post) {
        if (auth()->user()->id === $post['user_id']) { // If you are the author of the post, then delete the post
            $post->delete();
        }
        return redirect('/');
    }

    public function actuallyUpdatePost(Post $post, Request $request) {
        if (auth()->user()->id !== $post['user_id']) { // If you are not the author of the post, redirect to home page
            return redirect('/');
        }

        $incomingFields = $request->validate([ // Title and body required for each post
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']); // Prevents any malicious html from being stored in the database
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);
        return redirect('/');
    }

    public function showEditScreen(Post $post) { 
        if (auth()->user()->id !== $post['user_id']) { /* If you are not the author of the post, redirect to home page. 
                                                          Can also be done with policy statement/middleware. 
                                                       */
            return redirect('/');
        }

        return view('edit-post', ['post' => $post]); // Laravel will automatically do the database lookup for post
    }

    public function createPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']); // Ensure no malicious htmls are saved on posts
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        Post::create($incomingFields); // Create Post model using: php artisan make:model Post
        return redirect('/');
    }
}
