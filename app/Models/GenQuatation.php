<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Dept;
use DateTime;
class GenQuatation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "gen_quatations";
    protected $guarded=['id'];
    
    public static function get_quatation_no($dept_id,$customer_id, $created_at,$quat_cat=0)
    {
        $max= self::withTrashed()
            ->where('dept_id',$dept_id)
            ->where('customer_id',$customer_id)
            ->whereDate('created_at', $created_at)
            ->where('quat_cat',$quat_cat)
            ->max('number');
        
        $dept=Dept::find($dept_id);
        //dd($dept_id);
        $symbol=$dept?$dept->symbol:"X";
        
        $number= $max?$max+1:1;
        $created_atDT=new DateTime($created_at);
        $created_atYMD=$created_atDT->format('ymd');
        //dd($quate_dateYMD->format('ymd'));
        //記号　＋　取引先ID3桁　＋　日付（YYYYMMDD) + 連番
        $code = $symbol . sprintf('%04d',$customer_id) .'-'. $created_atYMD .sprintf('%03d',$number);
        return array('number'=>$number,'quatation_no'=>$code);
        
        
    }
}
