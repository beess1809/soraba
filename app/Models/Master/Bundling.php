<?php

namespace App\Models\Master;

use App\Helpers\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bundling extends Model
{
    use HasFactory, SoftDeletes, RecordSignature;

}
