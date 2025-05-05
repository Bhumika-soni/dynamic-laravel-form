@extends('layouts.public')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-6 px-6">
            <h1 class="text-2xl font-bold text-white">{{ $form->name }}</h1>
            @if($form->description)
                <p class="text-blue-100 mt-2">{{ $form->description }}</p>
            @endif
        </div>
        
        <div class="p-6 sm:p-8">
            @if($form->fields->count() > 0)
                <form action="{{ route('forms.submit', $form->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @foreach($form->fields as $field)
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $field->label }}
                                @if($field->is_required) <span class="text-red-500">*</span> @endif
                            </label>
                            
                            @switch($field->type)
                                @case('text')
                                    <input type="text" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        placeholder="Enter {{ strtolower($field->label) }}"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('textarea')
                                    <textarea name="field_{{ $field->id }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        rows="3" 
                                        placeholder="Enter {{ strtolower($field->label) }}"
                                        {{ $field->is_required ? 'required' : '' }}>{{ old('field_' . $field->id) }}</textarea>
                                    @break
                                
                                @case('radio')
                                    @if($field->options)
                                        <div class="space-y-2 mt-1">
                                            @foreach(explode(',', $field->options) as $option)
                                                <div class="flex items-center">
                                                    <input type="radio" 
                                                        id="field_{{ $field->id }}_{{ Str::slug(trim($option)) }}"
                                                        name="field_{{ $field->id }}" 
                                                        value="{{ trim($option) }}" 
                                                        {{ old('field_' . $field->id) == trim($option) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                    <label for="field_{{ $field->id }}_{{ Str::slug(trim($option)) }}" class="ml-2 text-gray-700">{{ trim($option) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break
                                
                                @case('checkbox')
                                    @if($field->options)
                                        <div class="space-y-2 mt-1">
                                            @foreach(explode(',', $field->options) as $option)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                        id="field_{{ $field->id }}_{{ Str::slug(trim($option)) }}"
                                                        name="field_{{ $field->id }}[]" 
                                                        value="{{ trim($option) }}" 
                                                        {{ is_array(old('field_' . $field->id)) && in_array(trim($option), old('field_' . $field->id)) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="field_{{ $field->id }}_{{ Str::slug(trim($option)) }}" class="ml-2 text-gray-700">{{ trim($option) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @break
                                
                                @case('dropdown')
                                    <select name="field_{{ $field->id }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $field->is_required ? 'required' : '' }}>
                                        <option value="">Select an option</option>
                                        @if($field->options)
                                            @foreach(explode(',', $field->options) as $option)
                                                <option value="{{ trim($option) }}" {{ old('field_' . $field->id) == trim($option) ? 'selected' : '' }}>
                                                    {{ trim($option) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break
                                
                                @case('date')
                                    <input type="date" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('time')
                                    <input type="time" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('email')
                                    <input type="email" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        placeholder="Enter email address"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('number')
                                    <input type="number" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        placeholder="Enter a number"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('url')
                                    <input type="url" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        placeholder="Enter a URL"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('tel')
                                    <input type="tel" name="field_{{ $field->id }}" 
                                        value="{{ old('field_' . $field->id) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        placeholder="Enter phone number"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                                
                                @case('file')
                                    <input type="file" name="field_{{ $field->id }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    @break
                            @endswitch
                        </div>
                    @endforeach
                    
                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-md hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Submit Form
                        </button>
                    </div>
                </form>
            @else
                <div class="py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No form fields available</h3>
                    <p class="mt-1 text-sm text-gray-500">This form is not yet configured properly.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
