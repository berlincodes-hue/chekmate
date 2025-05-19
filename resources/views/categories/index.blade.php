<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            <div class="flex items-center space-x-4">
                <form action="{{ route('categories.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="rounded-l-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Search categories...">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700">
                        Search
                    </button>
                </form>
                <a href="{{ route('categories.create') }}" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Create Category
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($categories as $category)
                            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $category->name }}
                                    </h3>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('categories.edit', $category) }}" 
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $category->tasks_count ?? 0 }} tasks
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-4">
                                <p class="text-gray-500 dark:text-gray-400">No categories found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 