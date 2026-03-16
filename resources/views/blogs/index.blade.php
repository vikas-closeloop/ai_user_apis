<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">Blog Management</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage blog posts for your users.
                        </p>
                    </div>
                    <a href="{{ route('blogs.create') }}"
                       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create blog
                    </a>
                </div>
            </div>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-sm text-green-800 px-4 py-3 rounded-md shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($blogs->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Title
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Author
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 w-48">
                                        Created
                                    </th>
                                    <th class="px-3 py-2 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-64">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($blogs as $blog)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <a href="{{ route('blogs.show', $blog) }}"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                {{ $blog->title }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-700">
                                            {{ $blog->user?->name ?? '—' }}
                                        </td>
                                        <td class="px-3 py-2">
                                            @if ($blog->status === 'published')
                                                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">
                                                    Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-500">
                                            {{ $blog->created_at?->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('blogs.show', $blog) }}"
                                                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                                    View
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
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $blogs->onEachSide(1)->links() }}
                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            No blogs found.
                            <a href="{{ route('blogs.create') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Create your first blog post.
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

