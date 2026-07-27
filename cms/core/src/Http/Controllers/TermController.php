<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Taxonomy;
use Cms\Core\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class TermController extends Controller
{
    public function index(Taxonomy $taxonomy)
    {
        $terms = $taxonomy->terms()->with('parent')->latest()->paginate(20);
        
        // If hierarchical, we might want to pass parent terms for the dropdown
        $parentTerms = $taxonomy->hierarchical ? $taxonomy->terms()->whereNull('parent_id')->get() : [];

        return view('cms-core::terms.index', compact('taxonomy', 'terms', 'parentTerms'));
    }

    public function store(Request $request, Taxonomy $taxonomy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:terms,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure unique slug per taxonomy or globally
        // For simplicity, using Str::slug and rely on HasSlug if present. 
        // Wait, Term model doesn't have HasSlug right now. Let's make sure it's unique.
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Term::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        $term = new Term($validated);
        $taxonomy->terms()->save($term);

        return redirect()->route('cms.terms.index', $taxonomy->id)->with('success', 'Term created successfully.');
    }

    public function edit(Taxonomy $taxonomy, Term $term)
    {
        $parentTerms = $taxonomy->hierarchical ? $taxonomy->terms()->whereNull('parent_id')->where('id', '!=', $term->id)->get() : [];
        return view('cms-core::terms.edit', compact('taxonomy', 'term', 'parentTerms'));
    }

    public function update(Request $request, Taxonomy $taxonomy, Term $term)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:terms,slug,' . $term->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:terms,id',
        ]);

        $term->update($validated);

        return redirect()->route('cms.terms.index', $taxonomy->id)->with('success', 'Term updated successfully.');
    }

    public function destroy(Taxonomy $taxonomy, Term $term)
    {
        $term->delete();
        return redirect()->route('cms.terms.index', $taxonomy->id)->with('success', 'Term deleted.');
    }
}
