<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssueChild extends Model
{
    use HasFactory;
    public $table = "trns03b_issue_child";
    protected $primaryKey = 'id';
    protected $guarded = [];
    /**
     * Get the user associated with the IssueChild
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function itemInfo(): HasOne
    {
        return $this->hasOne(VarItemInfo::class, 'id', 'item_information_id');
    }
}