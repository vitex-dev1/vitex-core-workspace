<?php

namespace App\Models;

use App\Models\AppModel;

/**
 * Class BannerTranslation
 * @package App\Entities
 * @version October 17, 2018, 7:29 am UTC
 */
class BannerTranslation extends AppModel
{
    public $timestamps = false;

    protected $fillable = [
        'title_1',
        'title_2',
        'button_1',
        'button_2',
        'link_1',
        'link_2',
        'description',
    ];

}
