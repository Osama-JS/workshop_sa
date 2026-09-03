<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiFaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_ar',
        'question_en',
        'answer_ar',
        'answer_en',
        'category',
        'keywords',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Localized Question.
     */
    public function getQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'en' && !empty($this->question_en))
            ? $this->question_en
            : $this->question_ar;
    }

    /**
     * Localized Answer.
     */
    public function getAnswerAttribute(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'en' && !empty($this->answer_en))
            ? $this->answer_en
            : $this->answer_ar;
    }
}
