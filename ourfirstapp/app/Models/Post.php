<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id'); // A blog post (Post.php) belongs to a user (User.php). Laravel can do the SQL statement lookup for 'user_id'
    }
}
