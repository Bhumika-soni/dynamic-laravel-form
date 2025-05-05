<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormAnswer;
use Illuminate\Support\Facades\Storage;

class FormAnswerController extends Controller
{

    public function index()
    {
        $answers = FormAnswer::with('form')->latest()->get();
        return view('admin.form_answers.index', compact('answers'));
    }

    public function store(Request $request, $id)
    {
        $form = Form::with('fields')->where('id', $id)->first();
        
        if (!$form) {
            return redirect()->back()->with('error', 'Form not found');
        }
        
        if ($form->fields && $form->fields->count() > 0) {
            foreach ($form->fields as $field) {
                $fieldName = "field_{$field->id}";
                
                if ($field->type === 'file' && $request->hasFile($fieldName)) {
                    $file = $request->file($fieldName);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    
                    $filePath = $file->storeAs('form_uploads', $fileName, 'public');
                    
                    FormAnswer::create([
                        'form_id' => $form->id,
                        'form_field_id' => $field->id,
                        'answer' => $filePath,
                    ]);
                }
                elseif ($request->has($fieldName)) {
                    if (is_array($request->input($fieldName))) {
                        $answer = implode(', ', $request->input($fieldName));
                    } else {
                        $answer = $request->input($fieldName);
                    }
                    
                    FormAnswer::create([
                        'form_id' => $form->id,
                        'form_field_id' => $field->id,
                        'answer' => $answer,
                    ]);
                }
            }
        }
        
        return view('forms.thankyou');
    }

    public function show($id)
    {
        $answer = FormAnswer::with('form.fields')->where('id', $id)->first();
        if (!$answer) {
            return redirect()->back()->with('error', 'Answer not found');
        }
        return view('admin.form_answers.show', compact('answer'));
    }

    public function destroy($id)
    {
        $answer = FormAnswer::find($id);
        if ($answer) {
            $field = $answer->formField;
            if ($field && $field->type === 'file' && $answer->answer) {
                Storage::disk('public')->delete($answer->answer);
            }
            
            $answer->delete();
            return redirect()->route('admin.form-answers.index')->with('success', 'Form answer deleted successfully.');
        }
        return redirect()->route('admin.form-answers.index')->with('error', 'Answer not found');
    }
}
