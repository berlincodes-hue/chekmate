<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('tasks.report') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Download PDF Report') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Task Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Task Summary') }}</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Total Tasks') }}</span>
                                <span class="text-gray-900 font-medium">{{ auth()->user()->tasks->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Completed Tasks') }}</span>
                                <span class="text-green-600 font-medium">{{ auth()->user()->tasks->where('completed', true)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('Pending Tasks') }}</span>
                                <span class="text-indigo-600 font-medium">{{ auth()->user()->tasks->where('completed', false)->count() }}</span>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    {{ __('View All Tasks') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Categories') }}</h3>
                        <div class="space-y-4">
                            @forelse(auth()->user()->categories as $category)
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color ?? '#6B7280' }}"></div>
                                        <span class="text-gray-600">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-gray-900 font-medium">{{ $category->tasks->count() }} tasks</span>
                                </div>
                            @empty
                                <p class="text-gray-500">{{ __('No categories created yet.') }}</p>
                            @endforelse
                            <div class="mt-6">
                                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    {{ __('Manage Categories') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Tasks') }}</h3>
                        <div class="space-y-4">
                            @forelse(auth()->user()->tasks()->latest()->take(5)->get() as $task)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                               {{ $task->completed ? 'checked' : '' }}
                                               onchange="toggleTaskComplete({{ $task->id }})">
                                        <div>
                                            <h4 class="font-medium {{ $task->completed ? 'line-through text-gray-500' : 'text-gray-900' }}">
                                                {{ $task->title }}
                                            </h4>
                                            @if($task->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                      style="background-color: {{ $task->category->color ?? '#E5E7EB' }}40; color: {{ $task->category->color ?? '#374151' }}">
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($task->due_date)
                                        <span class="text-sm text-gray-500">
                                            Due: {{ $task->due_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500">{{ __('No tasks created yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleTaskComplete(taskId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/tasks/' + taskId + '/toggle-complete';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    @endpush
</x-app-layout> 