<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\NullableBoolean;
use Illuminate\Support\Carbon;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $casts = [
        'is_current' => NullableBoolean::class,
    ];
    
    protected $fillable = ['is_current'];
    
    public function org() {
        return $this->belongsTo(Org::class);
    }
    
    public function person() {
        return $this->belongsTo(Person::class);
    }
    
    /**
     * Return a Carbon date object based on (but NOT equal to) the position's available start date info.
     * Used for comparing start & end date or checking whether to confirm is_current status.
     *
     * @param  Mixed  $positionOrRequest
     * @return \Illuminate\Support\Carbon
     */
    public static function getStartDate($positionOrRequest) {
        $minYear = get_min_year();
        $startDate = new Carbon();
        $startDate->setDateTime($minYear, 1, 1, 0, 0, 0);
        if (intval($positionOrRequest->start_year)) $startDate->year = intval($positionOrRequest->start_year);
        if (intval($positionOrRequest->start_month)) $startDate->month = intval($positionOrRequest->start_month);
        if (intval($positionOrRequest->start_day)) $startDate->day = intval($positionOrRequest->start_day);
        return $startDate;
    }
    
    /**
     * Return a Carbon date object based on (but NOT equal to) the position's available end date info.
     * Used for comparing start & end date or checking whether to confirm is_current status.
     *
     * @param  Mixed  $positionOrRequest
     * @return \Illuminate\Support\Carbon
     */
    public static function getEndDate($positionOrRequest) {
        $maxYear = get_max_year();
        $endDate = new Carbon();
        $endDate->setDateTime($maxYear, 12, 31, 23, 59, 59);
        if (intval($positionOrRequest->end_year)) $endDate->year = intval($positionOrRequest->end_year);
        if (intval($positionOrRequest->end_month)) $endDate->month = intval($positionOrRequest->end_month);
        if (intval($positionOrRequest->end_day)) $endDate->day = intval($positionOrRequest->end_day);
        return $endDate;
    }
    
    public function getStartDateAttribute() {
        return $this->getStartDate($this);
    }
    
    public function getEndDateAttribute() {
        return $this->getEndDate($this);
    }

    public function getStartDateStrAttribute() {
        $str = null;
        if ($this->start_year) {
            $str = $this->start_year;
            if ($this->start_month) {
                $str .= '/'.$this->start_month;
                if ($this->start_day) {
                    $str .= '/'.$this->start_day;
                }
            }
        }
        return $str;
    }

    public function getEndDateStrAttribute() {
        $str = null;
        if ($this->end_year) {
            $str = $this->end_year;
            if ($this->end_month) {
                $str .= '/'.$this->end_month;
                if ($this->end_day) {
                    $str .= '/'.$this->end_day;
                }
            }
        }
        return $str;
    }
}
