<?php

namespace App\Models;

use App\Models\IssueChild;
use App\Models\PProgramMaster;
use App\Models\ItemMasterModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IssueMaster extends Model
{
    use HasFactory;

    public $table = "trns03a_issue_master";

    protected $primaryKey = 'id';

    protected $guarded = [];
    /**
     * Get all of the comments for the IssueMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issueChild(): HasMany
    {
        return $this->hasMany(IssueChild::class, 'issue_master_id', 'id');
    }
    /**
     * Get the user that owns the IssueMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /**
     * Get the user that owns the IssueMaster
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function indentMaster(): BelongsTo
    {
        return $this->belongsTo(ItemMasterModel::class, 'indent_master_id', 'id');
    }
    public function store():HasOne{
        return $this->hasOne(CsCompanyStoreLocation::class,'id','delivery_to');
    }
}
