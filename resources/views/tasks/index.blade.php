<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <div class="flex items-center space-x-4">
                <form action="{{ route('tasks.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="rounded-l-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Search tasks...">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700">
                        Search
                    </button>
                </form>
                <a href="{{ route('tasks.create') }}" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Create Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filter Section -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Filter Tasks</h3>
                        <form action="{{ route('tasks.index') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">All</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">All</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                                    <select name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">All</option>
                                        <option value="today" {{ request('due_date') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="tomorrow" {{ request('due_date') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                                        <option value="this_week" {{ request('due_date') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                    </select>
                                </div>
                                <div class="flex items-end space-x-2">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('tasks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tasks List -->
                    <div class="space-y-4">
                        @forelse($tasks as $task)
                            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <form action="{{ route('tasks.toggle-complete', $task) }}" method="POST" class="flex items-center">
                                            @csrf
                                            <input type="checkbox" 
                                                {{ $task->completed ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </form>
                                        <div>
                                            <h3 class="text-lg font-semibold {{ $task->completed ? 'line-through text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-100' }}">
                                                {{ $task->title }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $task->description }}</p>
                                            <div class="mt-2 flex items-center space-x-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->completed ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                    {{ $task->completed ? 'Completed' : 'Pending' }}
                                                </span>
                                                @if($task->category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}">
                                                        {{ $task->category->name }}
                                                    </span>
                                                @endif
                                                @if($task->due_date)
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        Due: {{ $task->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('tasks.edit', $task) }}" 
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                onclick="return confirm('Are you sure you want to delete this task?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500 dark:text-gray-400">No tasks found.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 