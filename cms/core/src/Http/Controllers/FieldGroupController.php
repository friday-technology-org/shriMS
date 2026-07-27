<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\FieldGroup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FieldGroupController extends Controller
{
    public function index()
    {
        $fieldGroups = FieldGroup::latest()->paginate(20);
        return view('cms-core::field-groups.index', compact('fieldGroups'));
    }

    public function create()
    {
        return view('cms-core::field-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location_rules' => 'nullable|array',
            'is_active' => 'nullable',
            'fields' => 'nullable|array',
        ]);

        // Standardize location_rules format, e.g. [['param' => 'post_type', 'operator' => '==', 'value' => 'page']]
        $locationRules = $validated['location_rules'] ?? [['param' => 'post_type', 'operator' => '==', 'value' => 'post']];

        $group = FieldGroup::create([
            'title' => $validated['title'],
            'location_rules' => $locationRules,
            'is_active' => $request->has('is_active'),
        ]);

        if (isset($validated['fields'])) {
            foreach ($validated['fields'] as $index => $field) {
                // Ignore empty fields
                if (empty($field['label']) || empty($field['name'])) {
                    continue;
                }
                
                $group->fields()->create([
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'type' => $field['type'] ?? 'text',
                    'instructions' => $field['instructions'] ?? '',
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('cms.field-groups.index')->with('success', 'Field Group created.');
    }

    public function edit(FieldGroup $fieldGroup)
    {
        $fieldGroup->load('fields');
        return view('cms-core::field-groups.edit', compact('fieldGroup'));
    }

    public function update(Request $request, FieldGroup $fieldGroup)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location_rules' => 'nullable|array',
            'is_active' => 'nullable',
            'fields' => 'nullable|array',
        ]);

        $locationRules = $validated['location_rules'] ?? [['param' => 'post_type', 'operator' => '==', 'value' => 'post']];

        $fieldGroup->update([
            'title' => $validated['title'],
            'location_rules' => $locationRules,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync fields: recreate them to handle sorting/deletions easily
        $fieldGroup->fields()->delete();
        if (isset($validated['fields'])) {
            foreach ($validated['fields'] as $index => $field) {
                if (empty($field['label']) || empty($field['name'])) {
                    continue;
                }

                $fieldGroup->fields()->create([
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'type' => $field['type'] ?? 'text',
                    'instructions' => $field['instructions'] ?? '',
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('cms.field-groups.index')->with('success', 'Field Group updated.');
    }

    public function destroy(FieldGroup $fieldGroup)
    {
        $fieldGroup->delete();
        return redirect()->route('cms.field-groups.index')->with('success', 'Field Group deleted.');
    }
}
