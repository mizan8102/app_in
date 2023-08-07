<?php

namespace App\Models\UserMenu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{
    use HasFactory;
    protected $table="vms_menu";
    protected $primaryKey="id";
    protected $fillable=['id', 'menu_name', 'menu_name_bn', 'menu_parent_id', 'is_active', 'is_child', 'sub_menu_id', 'icon_name', 'route_name', 'seq_number', 'is_newtab', 'created_at', 'updated_at', 'created_by', 'updated_by'];

}
