<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class CleanupDriveRegistration extends Model {
 protected $fillable=['cleanup_drive_id','volunteer_id','registered_at','attendance_status','verification_method','verified_at','evidence_submitted_at','penalty_processed_at','points_awarded','points_deducted'];
 protected $casts=['registered_at'=>'datetime','verified_at'=>'datetime','evidence_submitted_at'=>'datetime','penalty_processed_at'=>'datetime'];
 public function drive():BelongsTo{return $this->belongsTo(CleanupDrive::class,'cleanup_drive_id');}
 public function volunteer():BelongsTo{return $this->belongsTo(User::class,'volunteer_id');}
 public function evidence():HasMany{return $this->hasMany(CleanupDriveEvidence::class,'registration_id');}
}
