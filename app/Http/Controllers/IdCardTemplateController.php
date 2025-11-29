<?php

namespace App\Http\Controllers;

use App\Models\IdCardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IdCardTemplateController extends Controller
{
    public function index()
    {
        $templates = IdCardTemplate::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.id-cards.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('backend.id-cards.templates.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,teacher,staff,medical',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'width' => 'required|numeric|min:50|max:200',  // changed from integer to numeric and adjusted min/max
            'height' => 'required|numeric|min:50|max:200', // changed from integer to numeric and adjusted min/max
            'orientation' => 'required|in:portrait,landscape',
            'description' => 'nullable|string',
        ]);


        $templateData = $request->except('background_image');
        
        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $templateData['background_image'] = $this->handleImageUpload($request->file('background_image'), 'id_card_template');
        }

        IdCardTemplate::create($templateData);

        return redirect()->route('admin.id-card-templates.index')
            ->with('success', 'ID Card Template created successfully.');
    }

    public function edit(IdCardTemplate $template)
    {
        return view('backend.id-cards.templates.form', compact('template'));
    }

    public function update(Request $request, IdCardTemplate $template)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,teacher,staff,medical',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'width' => 'required|numeric|min:50|max:200',  // changed from integer to numeric and adjusted min/max
            'height' => 'required|numeric|min:50|max:200', // changed from integer to numeric and adjusted min/max
            'orientation' => 'required|in:portrait,landscape',
            'description' => 'nullable|string',
        ]);

    
    
        $templateData = $request->except('background_image');
        
        // Handle checkbox value for is_active
        $templateData['is_active'] = $request->has('is_active') ? 1 : 0;
        
    
        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old image
            if ($template->background_image) {
                $this->deleteImage($template->background_image);
            }
            
            $templateData['background_image'] = $this->handleImageUpload($request->file('background_image'), 'id_card_template');
        }
    
        $template->update($templateData);
    
    
        return redirect()->route('admin.id-card-templates.index')
            ->with('success', 'ID Card Template updated successfully.');
    }

    public function destroy(IdCardTemplate $template)
    {
        // Delete background image
        if ($template->background_image) {
            $this->deleteImage($template->background_image);
        }

        $template->delete();

        return redirect()->route('admin.id-card-templates.index')
            ->with('success', 'ID Card Template deleted successfully.');
    }

    /**
     * Handle image upload and return relative path
     */
    protected function handleImageUpload($file, $type)
    {
        $baseName = 'id_card_' . $type . '_' . Str::uuid();
        $originalExtension = $file->getClientOriginalExtension();
        $destinationPath = public_path('storage/id-cards/templates');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $originalName = $baseName . '.' . $originalExtension;
        $file->move($destinationPath, $originalName);

        return 'id-cards/templates/' . $originalName;
    }

    /**
     * Delete image from storage
     */
    protected function deleteImage($imagePath)
    {
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($imagePath);
            $imagePath = ltrim($parsedUrl['path'], '/');
            
            if (strpos($imagePath, 'public/') === 0) {
                $imagePath = substr($imagePath, 7);
            }
        }
        
        $fullPath = public_path('storage/' . $imagePath);
        if ($imagePath && file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}