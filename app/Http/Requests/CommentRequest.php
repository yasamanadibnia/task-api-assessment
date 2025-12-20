<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'task_id' => ['required', 'exists:tasks,id'],
            'body' => ['required', 'max:500'],
        ];
    }
}
