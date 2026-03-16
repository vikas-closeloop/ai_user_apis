<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-start justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">{{ $blog->title }}</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            By {{ $blog->user?->name ?? 'Unknown' }}
                            <span class="mx-1">•</span>
                            <span class="text-xs">
                                {{ $blog->created_at?->format('Y-m-d H:i') }}
                            </span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('blogs.index') }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            Back to list
                        </a>
                        <a href="{{ route('blogs.edit', $blog) }}"
                           class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-slate-700">
                            Edit
                        </a>
                        <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this blog?')"
                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                @if (session('status'))
                    <div class="px-6 pt-4">
                        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <div class="px-6 pb-6 pt-4 space-y-4">
                    <div>
                        <span class="text-xs text-gray-500">Status:</span>
                        @if ($blog->status === 'published')
                            <span class="ml-2 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">
                                Published
                            </span>
                        @else
                            <span class="ml-2 inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">
                                Draft
                            </span>
                        @endif
                    </div>

                    @if ($blog->featured_image)
                        <div>
                            <img
                                src="{{ asset('storage/'.$blog->featured_image) }}"
                                alt="Featured image"
                                class="max-w-full rounded-md border border-gray-200"
                            >
                        </div>
                    @endif

                    <div>
                        <p class="mb-2 text-xs font-medium text-gray-500 uppercase tracking-wide">Content</p>
                        <div class="prose prose-sm max-w-none text-gray-800 whitespace-pre-wrap">
                            {{ $blog->content }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

