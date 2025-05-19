<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="color" :value="__('Color')" />
                            <div class="mt-1 flex items-center gap-2">
                                <x-text-input id="color" name="color" type="color" class="h-10 w-20 p-1 rounded-md border-gray-300" :value="old('color', '#6B7280')" />
                                <span class="text-sm text-gray-500">Choose a color for your category</span>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('color')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Category') }}</x-primary-button>
                            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 