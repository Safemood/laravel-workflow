<?php

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    use HasFactory;

    protected $guarded = [];
}
