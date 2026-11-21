<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CleanupDriveEvidence extends Model {
 protected $fillable=['registration_id','photo_path','uploaded_at']; protected $casts=['uploaded_at'=>'datetime'];
 public function registration():BelongsTo{return $this->belongsTo(CleanupDriveRegistration::class,'registration_id');}
}
