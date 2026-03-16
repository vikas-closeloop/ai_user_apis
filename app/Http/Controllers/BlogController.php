<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::with('user')
            ->latest()
            ->paginate(10);

        return view('blogs.index', compact('blogs'));
    }

    public function create(): View
    {
        return view('blogs.create');
    }

    public function store(BlogRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $data['user_id'] = Auth::id();

        $blog = Blog::create($data);

        return redirect()
            ->route('blogs.show', $blog)
            ->with('status', 'Blog created successfully.');
    }

    public function show(Blog $blog): View
    {
        $blog->loadMissing('user');

        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog): View
    {
        return view('blogs.edit', compact('blog'));
    }

    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()
            ->route('blogs.show', $blog)
            ->with('status', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()
            ->route('blogs.index')
            ->with('status', 'Blog deleted successfully.');
    }
}

