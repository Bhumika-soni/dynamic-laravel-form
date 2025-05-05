@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $form->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.forms.index') }}" class="px-3 py-2 text-gray-600 hover:text-gray-900">
                <span class="hidden sm:inline">← Back to Forms</span>
                <span class="sm:hidden">←</span>
            </a>
            <a href="{{ route('admin.forms.edit', $form->id) }}" class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">Edit Form</a>
            <a href="{{ route('admin.forms.fields.index', $form->id) }}" class="px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Manage Fields</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded border border-green-200">
            <div class="flex">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="mb-6 bg-white shadow-sm rounded-md p-6 border border-gray-200">
        <h2 class="text-lg font-medium mb-4">Form Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Name:</p>
                <p class="text-gray-800 font-medium">{{ $form->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Fields:</p>
                <p class="text-gray-800 font-medium">{{ $form->fields->count() }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Description:</p>
                <p class="text-gray-800">{{ $form->description ?: 'No description provided' }}</p>
            </div>
        </div>
    </div>

    @if($form->fields->count() > 0)
        <div class="bg-white shadow-md rounded-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-6">
                <h3 class="text-xl font-bold text-white">{{ $form->name }}</h3>
                @if($form->description)
                    <p class="text-blue-100 mt-2">{{ $form->description }}</p>
                @endif
            </div>
            
            <div class="p-6">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($form->fields as $field)
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $field->label }}
                                    @if($field->is_required) <span class="text-red-500">*</span> @endif
                                </label>
                                
                                @switch($field->type)
                                    @case('text')
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter {{ strtolower($field->label) }}" disabled>
                                        @break
                                    
                                    @case('textarea')
                                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter {{ strtolower($field->label) }}" disabled></textarea>
                                        @break
                                    
                                    @case('radio')
                                        @if($field->options)
                                            <div class="space-y-2">
                                                @foreach(explode(',', $field->options) as $option)
                                                    <div class="flex items-center">
                                                        <input type="radio" name="field_{{ $field->id }}" value="{{ trim($option) }}" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" disabled>
                                                        <label class="ml-2 text-gray-700">{{ trim($option) }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @break
                                    
                                    @case('checkbox')
                                        @if($field->options)
                                            <div class="space-y-2">
                                                @foreach(explode(',', $field->options) as $option)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="field_{{ $field->id }}[]" value="{{ trim($option) }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" disabled>
                                                        <label class="ml-2 text-gray-700">{{ trim($option) }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @break
                                    
                                    @case('dropdown')
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" disabled>
                                            <option value="">Select an option</option>
                                            @if($field->options)
                                                @foreach(explode(',', $field->options) as $option)
                                                    <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @break
                                @endswitch
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="pt-4 flex justify-center col-span-2">
                        <button type="button" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-md hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 mx-auto" disabled>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-6 bg-white p-6 rounded-md shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium mb-2">Share Link</h3>
            <div class="flex flex-col space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Form Link:</p>
                    <div class="flex">
                        <input type="text" value="{{ route('forms.public', $form->id) }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-l-md focus:outline-none" readonly>
                        <button onclick="copyToClipboard('{{ route('forms.public', $form->id) }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            Copy
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    @else
        <div class="bg-yellow-50 p-6 rounded-md border border-yellow-200">
            <div class="flex">
                <svg class="h-6 w-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-yellow-800">This form has no fields yet.</h3>
                    <p class="mt-2 text-yellow-700">
                        Your form needs fields to be functional. 
                        <a href="{{ route('admin.forms.fields.index', $form->id) }}" class="font-medium underline">
                            Add fields now
                        </a> 
                        to make your form usable.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection
