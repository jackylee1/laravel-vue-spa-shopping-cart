<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TypeFilter
 *
 * @property int $id
 * @property int $type_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter whereTypeId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter withCacheCooldownSeconds($seconds)
 * @property-read \App\Models\Filter $filter
 */
class TypeFilter extends Model
{
    use Cachable;

    protected $fillable = ['filter_id', 'type_id'];
    public $timestamps = false;

    public function filter() {
        return $this->hasOne('App\Models\Filter', 'id', 'filter_id');
    }
}
