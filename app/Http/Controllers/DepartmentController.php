<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display the department management page.
     */
    public function index()
    {
        $departments = Department::withCount('courses')
            ->orderBy('name')
            ->get()
            ->map(function ($department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'department_head' => $department->department_head,
                    'description' => $department->description,
                    'courses_count' => $department->courses_count,
                    'updateUrl' => route('departments.update', $department),
                    'deleteUrl' => route('departments.destroy', $department),
                ];
            });

        $props = [
            'departments' => $departments,
            'storeUrl' => route('departments.store'),
            'dashboardUrl' => route('dashboard'),
            'csrf' => csrf_token(),
            'success' => session('success') ?? '',
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ];

        return view('departments.index', compact('props'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $data = $this->validateDepartment($request);

        Department::create($data);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Update the selected department.
     */
    public function update(Request $request, Department $department)
    {
        $data = $this->validateDepartment($request, $department);

        $department->update($data);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Delete the selected department.
     */
    public function destroy(Department $department)
    {
        if ($department->courses()->exists()) {
            return redirect()->route('departments.index')
                ->withErrors(['delete' => 'Cannot delete a department that still has courses.']);
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    private function validateDepartment(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->ignore($department?->id),
            ],
            'department_head' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}