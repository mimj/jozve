<?php

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
    use NodeTrait;

    protected $fillable = ['unique_name', 'parent_id', 'topic_id', 'title', 'position', 'content'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}
