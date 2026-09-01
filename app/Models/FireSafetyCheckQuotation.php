<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Dept;
use DateTime;
class FireSafetyCheckQuotation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "firesafetycheck_quotations";
    protected $guarded=['id'];
    
    public static function get_quotation_no($customer_id, $created_at)
    {
        $max= self::withTrashed()
            ->where('customer_id',$customer_id)
            ->whereDate('created_at', $created_at)
            ->max('number');
        //dd($dept_id);oooooooo
        $symbol=config('firesafetycheck_quotation.default_symbol');
        
        $number= $max?$max+1:1;
        $created_atDT=new DateTime($created_at);
        $created_atYMD=$created_atDT->format('ymd');
        //dd($quote_dateYMD->format('ymd'));
        //記号　＋　取引先ID3桁　＋　日付（YYYYMMDD) + 連番
        $code = $symbol . sprintf('%04d',$customer_id) .'-'. $created_atYMD .sprintf('%03d',$number);
        return array('number'=>$number,'quotation_no'=>$code);
    }
}
