<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsCompanyBranch extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primary_key = 'id';
    protected $table = 'cs_company_branch_unit';
}
