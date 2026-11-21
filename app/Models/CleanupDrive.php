<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class CleanupDrive extends Model {
 protected $fillable=['title','description','location','start_at','end_at','slots','organizer_id','barangay_id','attendance_code_hash','status'];
 protected $casts=['start_at'=>'datetime','end_at'=>'datetime'];
 public function organizer():BelongsTo{return $this->belongsTo(User::class,'organizer_id');}
 public function registrations():HasMany{return $this->hasMany(CleanupDriveRegistration::class);}
}
