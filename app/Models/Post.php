<?php

namespace App\Models;

use App\Models\AppModel;
use App\Modules\ContentManager\Models\Articles;
use App\Traits\AppTrait;
use Dimsav\Translatable\Translatable;
use Lang;

/**
 * Class Country
 * @package App
 * @version October 17, 2018, 7:29 am UTC
 *
 * @property int id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property int post_author
 * @property string post_content
 * @property string post_title
 * @property string post_excerpt
 * @property string post_status
 * @property string comment_status
 * @property string post_password
 * @property string post_name
 * @property int post_parent
 * @property string guid
 * @property int menu_order
 * @property string menu_group
 * @property string post_type
 * @property int post_hit
 * @property string post_mime_type
 */
class Post extends Articles
{
    use Translatable, AppTrait;

    public $fillable = [
        'created_at',
        'updated_at',
        'post_author',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'post_password',
        'post_name',
        'post_parent',
        'guid',
        'menu_order',
        'menu_group',
        'post_type',
        'post_hit',
        'post_mime_type',
    ];

    public $translatedAttributes = [
        'post_name',
        'post_title',
        'post_content',
        'post_excerpt',
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
        'post_name' => 'string',
        'post_title' => 'string',
        'post_content' => 'string',
        'post_excerpt' => 'string',
        'post_author' => 'integer',
        'post_status' => 'string',
        'comment_status' => 'string',
        'post_password' => 'string',
        'post_parent' => 'integer',
        'guid' => 'string',
        'menu_order' => 'integer',
        'menu_group' => 'string',
        'post_type' => 'string',
        'post_hit' => 'integer',
        'post_mime_type' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    const POST_TYPE_POST = 'post';
    const POST_TYPE_ABOUT = 'about';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'post_author');
    }

    const STATUS_PUBLISH = 'publish';
    const STATUS_DRAFT = 'draft';

    /**
     * static enum: Model::function()
     *
     * @access static
     * @param string|null $value
     * @return array|string|null
     */
    public static function statuses($value = null) {
        $options = array(
            static::STATUS_PUBLISH => Lang::get('strings.published'),
            static::STATUS_DRAFT => Lang::get('strings.draft'),
        );
        return static::enum($value, $options);
    }

    /**
     * Get default selected align
     * @return string
     */
    public static function getDefaultStatus()
    {
        return static::STATUS_DRAFT;
    }

    /**
     * Fire events when create, update, delete teams
     * The "booting" method of the model.
     * @link https://stackoverflow.com/a/38685534
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // When delete record
        self::deleted(function ($model) {
            // Detach reference tags
            /*$model->detachTags($model);*/
        });

    }

    /**
     * Detach the tags in a post
     *
     * @return int
     */
    public function detachTags()
    {
        /*// Remove old references
        $deleted = ReferenceTag::where('model', static::class)
            ->where('reference_id', $this->id)
            ->delete();

        return $deleted;*/
    }

}
