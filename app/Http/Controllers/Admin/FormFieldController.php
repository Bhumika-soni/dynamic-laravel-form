<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormField;
use App\Models\Form;

class FormFieldController extends Controller
{
   
    public function index($formId)
    {
        $form = Form::with('fields')->where('id', $formId)->first();
        return view('admin.forms.fields.index', compact('form'));
    }

   
    public function create($formId)
    {
        $form = Form::where('id', $formId)->first();
        return view('admin.forms.fields.create', compact('form'));
    }

   
    public function store(Request $request, $formId)
    {
        $request->validate([
            'label' => 'required',
            'type' => 'required',
            'is_required' => 'boolean',
            'options' => 'nullable'
        ]);
        
        FormField::create([
            'form_id' => $formId,
            'label' => $request->label,
            'type' => $request->type,
            'is_required' => $request->is_required ? true : false,
            'options' => in_array($request->type, ['radio', 'checkbox', 'dropdown']) ? $request->options : null,
        ]);
        
        return redirect()->route('admin.forms.show', $formId)->with('success', 'Field added successfully!');
    }

   
    public function show($formId, $id)
    {
        $field = FormField::where('id', $id)->where('form_id', $formId)->first();
        return view('admin.forms.fields.show', compact('field'));
    }

   
    public function edit($formId, $id)
    {
        $form = Form::where('id', $formId)->first();
        $field = FormField::where('id', $id)->where('form_id', $formId)->first();
        return view('admin.forms.fields.edit', compact('form', 'field'));
    }

   
    public function update(Request $request, $formId, $id)
    {
        $request->validate([
            'label' => 'required',
            'type' => 'required',
            'is_required' => 'boolean',
            'options' => 'nullable'
        ]);
        
        $field = FormField::where('id', $id)->where('form_id', $formId)->first();
        
        if ($field) {
            $field->update([
                'label' => $request->label,
                'type' => $request->type,
                'is_required' => $request->is_required ? true : false,
                'options' => in_array($request->type, ['radio', 'checkbox', 'dropdown']) ? $request->options : null,
            ]);
        }
        
        return redirect()->route('admin.forms.show', $formId)->with('success', 'Field updated successfully!');
    }

   
    public function destroy($formId, $id)
    {
        $field = FormField::where('id', $id)->where('form_id', $formId)->first();
        if ($field) {
            $field->delete();
        }
        return redirect()->route('admin.forms.show', $formId)->with('success', 'Field deleted successfully!');
    }
    
    
    public function bulkSaveFields($formId, $fieldsData)
    {
        $existingFieldIds = collect($fieldsData)->pluck('id')->filter()->toArray();
        
        FormField::where('form_id', $formId)
                ->whereNotIn('id', $existingFieldIds)
                ->delete();
        
        foreach ($fieldsData as $fieldData) {
            if (!empty($fieldData['id'])) {
                $field = FormField::where('id', $fieldData['id'])->first();
                if ($field) {
                    $field->update([
                        'label' => $fieldData['label'],
                        'type' => $fieldData['type'],
                        'is_required' => isset($fieldData['is_required']),
                        'options' => in_array($fieldData['type'], ['radio', 'checkbox', 'dropdown']) ? $fieldData['options'] : null,
                    ]);
                }
            } else {
                FormField::create([
                    'form_id' => $formId,
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'is_required' => isset($fieldData['is_required']),
                    'options' => in_array($fieldData['type'], ['radio', 'checkbox', 'dropdown']) ? $fieldData['options'] : null,
                ]);
            }
        }
        
        return true;
    }
}
