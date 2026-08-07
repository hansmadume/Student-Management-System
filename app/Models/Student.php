<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'birthday',
        'gender',
        'email',
        'phone',
        'department_id',
        'course',
        'course_id',
        'year_level',
        'photo',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function enrolledCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}