<?php

namespace App\Models;

use App\Models\AppModel;
use Dimsav\Translatable\Translatable;
use Helper;
use Lang;

/**
 * Class Banner
 * @package App
 * @version January 3, 2019, 6:40 pm +07
 *
 * @property int id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property string photo Slide Image. The recommended size is 1920x875.
 * @property string title_1
 * @property string title_2
 * @property string button_1 Title of button 1.
 * @property string button_2 Title of button 2.
 * @property string link_1 Link of button 1.
 * @property string link_2 Link of button 2.
 * @property int align Allow values: -1, 0, 1. Where: -1: left, 0: center, 1: right.
 * @property int order Order when show..
 * @property string description Description for slider.
 */
class Banner extends AppModel
{
    use Translatable;

    public $table = 'banners';

    public $fillable = [
        'created_at',
        'updated_at',
        'photo',
        'title_1',
        'title_2',
        'button_1',
        'button_2',
        'link_1',
        'link_2',
        'align',
        'order',
        'description'
    ];

    public $translatedAttributes = [
        'title_1',
        'title_2',
        'button_1',
        'button_2',
        'link_1',
        'link_2',
        'description',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    // (optionaly)
    // protected $with = ['translations'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'photo' => 'string',
        'title_1' => 'string',
        'title_2' => 'string',
        'button_1' => 'string',
        'button_2' => 'string',
        'link_1' => 'string',
        'link_2' => 'string',
        'align' => 'integer',
        'order' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    const ALIGN_LEFT = -1;
    const ALIGN_CENTER = 0;
    const ALIGN_RIGHT = 1;

    /**
     * static enum: Model::function()
     *
     * @access static
     * @param integer|null $value
     * @return array|string
     */
    public static function aligns($value = null)
    {
        $options = array(
            static::ALIGN_LEFT => Lang::get('strings.banner.align_left'),
            static::ALIGN_CENTER => Lang::get('strings.banner.align_center'),
            static::ALIGN_RIGHT => Lang::get('strings.banner.align_right'),
        );
        return static::enum($value, $options);
    }

    /**
     * Get default selected align
     * @return int
     */
    public static function getDefaultAlign()
    {
        return static::ALIGN_CENTER;
    }

    /**
     * Get the user's photo attribute.
     *
     * @param string $value
     * @return string
     */
    public function getPhotoAttribute($value) {
        if (empty($value)) {
            // Default photo
            $value = \Config::get('common.default_banner_image');
        }

        return Helper::getLinkFromDataSource($value);
    }

    /**
     * Set the user's photo attribute.
     *
     * @param string $value
     */
    public function setPhotoAttribute($value)
    {
        $this->attributes['photo'] = Helper::getRelativeResource($value);
    }

}
