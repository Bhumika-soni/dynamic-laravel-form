@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Form: {{ $form->name }}</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.forms.update', $form->id) }}" method="POST" class="space-y-6" x-data="{
        fields: {{ json_encode($form->fields) ?: '[]' }},
        addField() {
            this.fields.push({ label: '', type: 'text', is_required: false, options: '' });
        },
        removeField(index) {
            if (this.fields.length > 1) {
                this.fields.splice(index, 1);
            }
        }
    }">
        @csrf
        @method('PUT')

        <div class="p-4 border rounded-md bg-white shadow-sm">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Form Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $form->name) }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>
            
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                <textarea name="description" id="description" rows="2" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $form->description) }}</textarea>
            </div>
        </div>

        <div class="p-4 border rounded-md bg-white shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium">Form Fields</h2>
                <button type="button" @click="addField" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                    + Add Field
                </button>
            </div>
            
            <template x-if="fields.length === 0">
                <p class="text-gray-500 py-4 text-center italic">No fields added yet. Add your first field using the button above.</p>
            </template>

            <template x-for="(field, index) in fields" :key="index">
                <div class="mb-6 p-4 border rounded-md bg-gray-50">
                    <input type="hidden" :name="'fields['+index+'][id]'" :value="field.id || ''">
                    
                    <div class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Label</label>
                            <input type="text" x-model="field.label" :name="'fields['+index+'][label]'" class="w-full px-3 py-2 border rounded-md" required>
                        </div>
                        
                        <div class="w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Type</label>
                            <select x-model="field.type" :name="'fields['+index+'][type]'" class="w-full px-3 py-2 border rounded-md">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="radio">Radio Buttons</option>
                                <option value="checkbox">Checkboxes</option>
                                <option value="dropdown">Dropdown</option>
                                <option value="date">Date</option>
                                <option value="time">Time</option>
                                <option value="email">Email</option>
                                <option value="number">Number</option>
                                <option value="url">URL</option>
                                <option value="tel">Tel</option>
                                <option value="file">File</option>
                            </select>
                        </div>
                        
                        <div class="w-32 flex items-end pb-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" x-model="field.is_required" :name="'fields['+index+'][is_required]'" class="mr-2">
                                <span class="text-sm">Required</span>
                            </label>
                        </div>
                    </div>
                    
                    <div x-show="['radio', 'checkbox', 'dropdown'].includes(field.type)" class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Options (comma separated)</label>
                        <input type="text" x-model="field.options" :name="'fields['+index+'][options]'" class="w-full px-3 py-2 border rounded-md" placeholder="Option 1, Option 2, Option 3">
                    </div>
                    
                    <div class="mt-3 flex justify-end">
                        <button type="button" @click="removeField(index)" 
                                x-show="fields.length > 1"
                                class="text-red-600 hover:text-red-800">
                            Remove Field
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.forms.show', $form->id) }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit"
                    class="ml-3 px-6 py-3 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
                Update Form
            </button>
        </div>
    </form>
</div>
@endsection