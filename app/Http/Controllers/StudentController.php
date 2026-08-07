<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of active students with search, filters, and pagination.
     */
    public function index(Request $request)
    {
        $students = $this->studentQuery($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $search = $request->input('search', '');
        $filters = $this->filters();
        $currentFilters = $this->currentFilters($request);
        $mode = 'active';

        return view('students.index', compact('students', 'search', 'filters', 'currentFilters', 'mode'));
    }

    /**
     * Display soft-deleted students in the recycle bin.
     */
    public function trash(Request $request)
    {
        $students = $this->studentQuery($request, true)
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $search = $request->input('search', '');
        $filters = $this->filters();
        $currentFilters = $this->currentFilters($request);
        $mode = 'trash';

        return view('students.index', compact('students', 'search', 'filters', 'currentFilters', 'mode'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $departments = Department::with(['courses' => function ($query) {
            $query->orderBy('course_name');
        }])->orderBy('name')->get();
        $courses = Course::with('department')->orderBy('course_name')->get();

        return view('students.create', compact('courses', 'departments'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|unique:students',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birthday' => 'required|date',
            'gender' => 'required',
            'email' => 'required|email|unique:students',
            'phone' => 'required',
            'department_id' => 'required|exists:departments,id',
            'course' => 'required',
            'course_id' => 'nullable|exists:courses,id',
            'year_level' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    /**
     * Display the specified student profile.
     */
    public function show(Student $student)
    {
        $student->load(['department', 'enrolledCourse.department']);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $departments = Department::with(['courses' => function ($query) {
            $query->orderBy('course_name');
        }])->orderBy('name')->get();
        $courses = Course::with('department')->orderBy('course_name')->get();

        return view('students.edit', compact('student', 'courses', 'departments'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $student->id,
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birthday' => 'required|date',
            'gender' => 'required',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required',
            'department_id' => 'required|exists:departments,id',
            'course' => 'required',
            'course_id' => 'nullable|exists:courses,id',
            'year_level' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }

            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Soft delete the specified student and move it to the recycle bin.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student moved to recycle bin successfully.');
    }

    /**
     * Restore a soft-deleted student from the recycle bin.
     */
    public function restore($student)
    {
        $student = Student::onlyTrashed()->findOrFail($student);
        $student->restore();

        return redirect()->route('students.trash')
            ->with('success', 'Student restored successfully.');
    }

    /**
     * Permanently delete a student from the recycle bin.
     */
    public function forceDelete($student)
    {
        $student = Student::onlyTrashed()->findOrFail($student);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->forceDelete();

        return redirect()->route('students.trash')
            ->with('success', 'Student permanently deleted successfully.');
    }

    private function studentQuery(Request $request, bool $trashed = false)
    {
        $query = Student::with(['department', 'enrolledCourse.department']);

        if ($trashed) {
            $query->onlyTrashed();
        }

        return $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->where('gender', $request->input('gender'));
            })
            ->when($request->filled('year_level'), function ($query) use ($request) {
                $query->where('year_level', $request->input('year_level'));
            })
            ->when($request->filled('course_id'), function ($query) use ($request) {
                $query->where('course_id', $request->input('course_id'));
            });
    }

    private function filters(): array
    {
        return [
            'courses' => Course::with('department')->orderBy('course_name')->get()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->course_name,
                    'department' => optional($course->department)->name,
                ];
            })->values(),
            'genders' => Student::withTrashed()
                ->select('gender')
                ->whereNotNull('gender')
                ->distinct()
                ->orderBy('gender')
                ->pluck('gender')
                ->values(),
            'yearLevels' => Student::withTrashed()
                ->select('year_level')
                ->whereNotNull('year_level')
                ->distinct()
                ->orderBy('year_level')
                ->pluck('year_level')
                ->values(),
        ];
    }

    private function currentFilters(Request $request): array
    {
        return [
            'search' => $request->input('search', ''),
            'gender' => $request->input('gender', ''),
            'year_level' => $request->input('year_level', ''),
            'course_id' => $request->input('course_id', ''),
        ];
    }
}