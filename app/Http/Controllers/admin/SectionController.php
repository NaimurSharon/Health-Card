<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use App\Models\Page;
use App\Models\Section;
use App\Models\SectionMapping;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function create(Edition $edition, Page $page)
    {
        $otherPages = $edition->pages()->where('id', '!=', $page->id)->get();
        $sections = $page->sections()->with('mappings.targetPage')->get();
        
        return view('admin.sections.create', compact('edition', 'page', 'otherPages', 'sections'));
    }

    public function store(Request $request, Edition $edition, Page $page)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'coordinates' => 'required|json',
            'target_page_id' => 'required|exists:pages,id'
        ]);

        // Create section
        $section = $page->sections()->create([
            'edition_id' => $edition->id,
            'name' => $validated['name'],
            'coordinates' => $validated['coordinates']
        ]);

        // Create mapping
        $section->mappings()->create([
            'target_page_id' => $validated['target_page_id']
        ]);

        return redirect()->back()->with('success', 'Section mapped successfully!');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->back()->with('success', 'Section deleted successfully!');
    }
}