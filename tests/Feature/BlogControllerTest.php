<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_paginated_blogs(): void
    {
        $user = User::factory()->create();
        $blogs = Blog::factory()->count(12)->for($user)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('blogs.index');
        $response->assertViewHas('blogs', function ($paginator) {
            return $paginator->count() === 10
                && $paginator->total() === 12;
        });
    }

    public function test_create_displays_create_view_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('blogs.create'));

        $response->assertStatus(200);
        $response->assertViewIs('blogs.create');
    }

    public function test_store_creates_blog_without_image(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Test blog title',
            'content' => 'Test blog content',
            'status' => 'draft',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('blogs.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Blog created successfully.');

        $this->assertDatabaseHas('blogs', [
            'title' => 'Test blog title',
            'content' => 'Test blog content',
            'status' => 'draft',
            'user_id' => $user->id,
            'featured_image' => null,
        ]);
    }

    public function test_store_creates_blog_with_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $payload = [
            'title' => 'Blog with image',
            'content' => 'Body with image',
            'status' => 'published',
            'featured_image' => UploadedFile::fake()->image('featured.jpg'),
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('blogs.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Blog created successfully.');

        $blog = Blog::first();
        $this->assertNotNull($blog);
        $this->assertEquals($user->id, $blog->user_id);
        $this->assertEquals('published', $blog->status);
        $this->assertNotNull($blog->featured_image);

        Storage::disk('public')->assertExists($blog->featured_image);
    }

    public function test_show_displays_blog(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('blogs.show', $blog));

        $response->assertStatus(200);
        $response->assertViewIs('blogs.show');
        $response->assertViewHas('blog', function ($viewBlog) use ($blog) {
            return $viewBlog->is($blog);
        });
    }

    public function test_edit_displays_edit_view_for_owner(): void
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->for($user)->create();

        $response = $this
            ->actingAs($user)
            ->get(route('blogs.edit', $blog));

        $response->assertStatus(200);
        $response->assertViewIs('blogs.edit');
        $response->assertViewHas('blog', function ($viewBlog) use ($blog) {
            return $viewBlog->is($blog);
        });
    }

    public function test_update_updates_blog_without_changing_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $blog = Blog::factory()
            ->for($user)
            ->create([
                'title' => 'Old title',
                'content' => 'Old content',
                'featured_image' => 'blogs/old-image.jpg',
                'status' => 'draft',
            ]);

        Storage::disk('public')->put($blog->featured_image, 'dummy');

        $payload = [
            'title' => 'New title',
            'content' => 'New content',
            'status' => 'published',
        ];

        $response = $this
            ->actingAs($user)
            ->put(route('blogs.update', $blog), $payload);

        $response->assertRedirect(route('blogs.show', $blog));
        $response->assertSessionHas('status', 'Blog updated successfully.');

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'New title',
            'content' => 'New content',
            'status' => 'published',
            'featured_image' => 'blogs/old-image.jpg',
        ]);

        Storage::disk('public')->assertExists('blogs/old-image.jpg');
    }

    public function test_update_replaces_featured_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $blog = Blog::factory()
            ->for($user)
            ->create([
                'title' => 'Title',
                'content' => 'Content',
                'featured_image' => 'blogs/old-image.jpg',
                'status' => 'draft',
            ]);

        Storage::disk('public')->put($blog->featured_image, 'old-image-content');

        $payload = [
            'title' => 'Updated title',
            'content' => 'Updated content',
            'status' => 'published',
            'featured_image' => UploadedFile::fake()->image('new-featured.jpg'),
        ];

        $response = $this
            ->actingAs($user)
            ->put(route('blogs.update', $blog), $payload);

        $response->assertRedirect(route('blogs.show', $blog));
        $response->assertSessionHas('status', 'Blog updated successfully.');

        $blog->refresh();

        $this->assertEquals('Updated title', $blog->title);
        $this->assertEquals('Updated content', $blog->content);
        $this->assertEquals('published', $blog->status);
        $this->assertNotNull($blog->featured_image);
        $this->assertNotEquals('blogs/old-image.jpg', $blog->featured_image);

        Storage::disk('public')->assertMissing('blogs/old-image.jpg');
        Storage::disk('public')->assertExists($blog->featured_image);
    }

    public function test_destroy_deletes_blog_and_featured_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $blog = Blog::factory()
            ->for($user)
            ->create([
                'featured_image' => 'blogs/image-to-delete.jpg',
            ]);

        Storage::disk('public')->put($blog->featured_image, 'dummy');

        $response = $this
            ->actingAs($user)
            ->delete(route('blogs.destroy', $blog));

        $response->assertRedirect(route('blogs.index'));
        $response->assertSessionHas('status', 'Blog deleted successfully.');

        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
        Storage::disk('public')->assertMissing('blogs/image-to-delete.jpg');
    }

    public function test_destroy_deletes_blog_without_featured_image(): void
    {
        $user = User::factory()->create();
        Storage::fake('public');

        $blog = Blog::factory()
            ->for($user)
            ->create([
                'featured_image' => null,
            ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('blogs.destroy', $blog));

        $response->assertRedirect(route('blogs.index'));
        $response->assertSessionHas('status', 'Blog deleted successfully.');

        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }
}

