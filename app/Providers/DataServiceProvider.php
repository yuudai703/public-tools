<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;
use App\Models\EraName;
use App\Models\Wareki;
use Carbon\CarbonImmutable;

class DataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        Date::use(CarbonImmutable::class);  // 日付を扱うクラスをCarbonImmutableに変更

        Date::macro('toWareki', function () {  // 日付を扱うクラスにtoWarekiメソッドを追加
            return new Wareki($this);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Wareki::setEraNames();
    }
}
