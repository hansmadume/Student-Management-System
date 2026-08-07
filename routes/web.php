<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalStudents = Student::count();
    $maleStudents = Student::where('gender', 'Male')->count();
    $femaleStudents = Student::where('gender', 'Female')->count();
    $totalCourses = Course::count();
    $totalDepartments = Department::count();
    $newStudentsThisMonth = Student::where('created_at', '>=', now()->startOfMonth())->count();
    $graduatedStudents = Student::where('year_level', 'Graduated')
        ->orWhere('year_level', 'like', '%graduate%')
        ->count();

    $courses = Course::with('department')->withCount('students')->orderBy('course_name')->get();

    $departments = Department::with(['courses' => function ($query) {
        $query->withCount('students');
    }])->orderBy('name')->get();

    $studentsPerCourse = $courses->map(function ($course) {
        return [
            'label' => $course->course_name,
            'value' => $course->students_count,
        ];
    })->values();

    $studentsByGender = Student::all()
        ->groupBy('gender')
        ->map(function ($students, $gender) {
            return [
                'label' => $gender ?: 'Unspecified',
                'value' => $students->count(),
            ];
        })
        ->values();

    $studentsPerDepartment = $departments->map(function ($department) {
        return [
            'label' => $department->name,
            'value' => $department->courses->sum('students_count'),
        ];
    })->values();

    $recentStudents = Student::with('enrolledCourse')->latest()->take(5)->get()->map(function ($student) {
        return [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'fullName' => trim($student->first_name . ' ' . $student->last_name),
            'courseName' => optional($student->enrolledCourse)->course_name ?? $student->course,
            'createdAt' => optional($student->created_at)->format('M d, Y'),
        ];
    });

    $stats = [
        ['label' => 'Total Students', 'value' => $totalStudents, 'accent' => 'blue'],
        ['label' => 'Male Students', 'value' => $maleStudents, 'accent' => 'indigo'],
        ['label' => 'Female Students', 'value' => $femaleStudents, 'accent' => 'pink'],
        ['label' => 'Courses', 'value' => $totalCourses, 'accent' => 'emerald'],
        ['label' => 'Departments', 'value' => $totalDepartments, 'accent' => 'amber'],
        ['label' => 'New Students This Month', 'value' => $newStudentsThisMonth, 'accent' => 'cyan'],
        ['label' => 'Graduated Students', 'value' => $graduatedStudents, 'accent' => 'purple'],
    ];

    $props = [
        'stats' => $stats,
        'charts' => [
            'studentsPerCourse' => [
                'title' => 'Students per Course',
                'items' => $studentsPerCourse,
            ],
            'studentsByGender' => [
                'title' => 'Students by Gender',
                'items' => $studentsByGender,
            ],
            'studentsPerDepartment' => [
                'title' => 'Students per Department',
                'items' => $studentsPerDepartment,
            ],
        ],
        'recentStudents' => $recentStudents,
        'studentsUrl' => route('students.index'),
        'departmentsUrl' => route('departments.index'),
    ];

    return view('dashboard', compact('props'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'update', 'destroy']);

Route::get('students/trash', [StudentController::class, 'trash'])->name('students.trash');
Route::patch('students/{student}/restore', [StudentController::class, 'restore'])->name('students.restore');
Route::delete('students/{student}/force-delete', [StudentController::class, 'forceDelete'])->name('students.force-delete');
Route::resource('students', StudentController::class);

require __DIR__.'/auth.php';