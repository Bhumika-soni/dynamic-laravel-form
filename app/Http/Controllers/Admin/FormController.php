<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormAnswer;
use App\Http\Controllers\Admin\FormFieldController;

class FormController extends Controller
{
    protected $formFieldController;

    public function __construct(FormFieldController $formFieldController)
    {
        $this->formFieldController = $formFieldController;
    }

    public function index()
    {
        $forms = Form::all();
        return view('admin.forms.index', compact('forms'));
    }
    
    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,date,time,email,number,url,tel,file',
        ]);

        $form = Form::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $this->formFieldController->bulkSaveFields($form->id, $request->fields);

        return redirect()->route('admin.forms.show', $form)->with('success', 'Form created successfully!');
    }

    public function show($id)
    {
        $form = Form::with('fields')->where('id', $id)->first();
        return view('admin.forms.show', compact('form'));
    }
   
    public function edit($id)
    {
        $form = Form::where('id', $id)->first();
        return view('admin.forms.edit', compact('form'));
    }

  
    public function update(Request $request, $id)
    {
        $form = Form::where('id', $id)->first();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'array',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,date,time,email,number,url,tel,file',
        ]);

        $form->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $this->formFieldController->bulkSaveFields($form->id, $request->fields);

        return redirect()->route('admin.forms.show', $form)->with('success', 'Form updated successfully!');
    }

  
    public function destroy($id)
    {
        $form = Form::where('id', $id)->first();
        $form->delete();
        return redirect()->route('admin.forms.index')->with('success', 'Form deleted successfully.');
    }

    public function showPublic($id)
    {
        $form = Form::with('fields')->where('id', $id)->first();

        
        if (!$form) {
            return redirect('/')->with('error', 'The requested form does not exist.');
        }
        
        return view('forms.public', compact('form'));
    }
}
