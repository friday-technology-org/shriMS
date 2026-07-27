<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')->latest()->paginate(20);
        return view('cms-core::posts.index', compact('posts'));
    }

    public function create()
    {
        $fieldGroups = \Cms\Core\Models\FieldGroup::where('is_active', true)
            ->whereJsonContains('location_rules', ['param' => 'post_type', 'operator' => '==', 'value' => 'post'])
            ->with('fields')
            ->orderBy('sort_order')
            ->get();
            
        return view('cms-core::posts.create', compact('fieldGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'content'            => 'nullable|string',
            'excerpt'            => 'nullable|string',
            'status'             => 'required|in:draft,published,trashed',
            'post_type'          => 'nullable|string',
            'featured_image_id'  => 'nullable|integer|exists:media,id',
        ]);

        $post = new Post($validated);
        $post->author_id = auth()->id();
        
        if (!isset($validated['post_type'])) {
            $post->post_type = 'post';
        }

        if ($post->status === 'published') {
            $post->published_at = now();
        }

        $post->save();

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $post->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $post->terms()->sync($request->input('terms'));
        }

        return redirect()->route('cms.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $fieldGroups = \Cms\Core\Models\FieldGroup::where('is_active', true)
            ->whereJsonContains('location_rules', ['param' => 'post_type', 'operator' => '==', 'value' => 'post'])
            ->with('fields')
            ->orderBy('sort_order')
            ->get();
            
        return view('cms-core::posts.edit', compact('post', 'fieldGroups'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'content'           => 'nullable|string',
            'excerpt'           => 'nullable|string',
            'status'            => 'required|in:draft,published,trashed',
            'featured_image_id' => 'nullable|integer|exists:media,id',
        ]);

        if ($post->status !== 'published' && $validated['status'] === 'published') {
            $post->published_at = now();
        }

        $post->update($validated);

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $post->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $post->terms()->sync($request->input('terms'));
        } else {
            $post->terms()->detach();
        }

        return redirect()->route('cms.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('cms.posts.index')->with('success', 'Post moved to trash.');
    }
}
