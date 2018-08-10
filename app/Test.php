<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * Table.
     *
     * @var string
     */
    protected $table = 'test';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_lot'
    ];

    public function getAllData($request, $start)
    {
        return self::paginate($request->limit ? $request->limit : 1, ['*'], 'page', $start ? $start + 1 : 1);
//        return self::all();
    }

}
