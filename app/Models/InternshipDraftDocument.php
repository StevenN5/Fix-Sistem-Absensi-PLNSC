<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipDraftDocument extends Model
{
    protected $fillable = [
        'uploaded_by',
        'document_type',
        'library_category',
        'title',
        'description',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
