<?php 

namespace App\Helpers;

use Carbon\Carbon;
use Config;
use URL, Auth;
use App\Modules\ContentManager\Models\Options;
use DateTime;
use DateTimeZone;
use App\Models\Workspace;

class Helper
{
    private $options;

    public function __construct() {
        $this->options =  Options::all()->toArray();
    }

    public function menu($group = "main-menu")
    {
    	$menu = new Menu($group);        
        return $menu->generateMenu();
    }

    public function compress($source,$destination){
        $com = new Compress($source,$destination);
        return $com->run();
    }

    public function extract($source,$destination){
        $com = new Compress($source,$destination);
        return $com->extract();
    }

    public function widget($class,$option = []){
        $class = "App\\Widgets\\".str_replace(".", "\\", $class);
        $widget = new $class;
        return $widget->test();
    }

    public function taxonomyLink($taxonomy,$link = true){
        $res = [];
        if($link){
            foreach ($taxonomy as $value) {
                $res[] = '<a href="'.url("/category/".$value->slug).'">'.$value->name.'</a>';
            }
        }else{
            foreach ($taxonomy as $value) {
                $res[] = $value->name;
            }
        }
        return implode(",", $res);
    }

    public function bbcode($content){
        $bbcode = new BBCode();
        return $bbcode->toHTML($content);
    }

    public function option($keySearch){
        $result = null;
        foreach ($this->options as $value) {
            if($value['name'] == $keySearch){
                $result = $value['value'];
            }
        }
        return $result;
    }

    public function appTitle($title){
        return ($title == "") ? $this->option("site_title") : $title." - ".$this->option("site_title");
    }

    public function menuList() {
        return '';
    }

    public function recursive_array_search($needle, $haystack) {

        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value OR ( is_array($value) && $this->recursive_array_search($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    /**
     * Check if user has Route or not
     *
     * @param String $routeName
     * @param string $guard
     * @return boolean
     */
    public static function checkUserPermission($routeName, $guard = 'admin') {
        /** @var \App\Models\User $user */
        $user = \Auth::guard($guard)->user();

        // If is super admin
        if ($user->isSuperAdmin()) {
            return true;
        } else {
            // Get workspace from request
            $workspaceId = config('workspace.active');

            if (empty($workspaceId)) {
                // Get default workspace
                $workspace = Helper::getDefaultWorkspace();

                if (!empty($workspace)) {
                    $workspaceId = $workspace->id;
                }
            }

            // Get role from workspace
            $workspaceObject = new \App\Models\WorkspaceObject();
            $role = $workspaceObject->getRole($workspaceId, $user->id);
            $roleId = $role->id;
        }

        // Get role from cache if exist
        $roleCacheName = config('cache.key').$roleId;

        if (cache()->has($roleCacheName)) {
            $rolePermission = cache($roleCacheName);
        } else {
            $rolePermission = !empty($role) ? $role->toArray() : [];
            cache()->forever($roleCacheName, $rolePermission);
        }

        if (!empty($rolePermission)) {
            $user->setRelation('roles', new \App\Models\Role($rolePermission));
        }

        // Check permission by user role (permission)
        $role = $user->roles;

        if (!empty($role) && empty($role->active)) {
            return false;
        }

        if ($user->hasRoute($routeName)) {
            return true;
        }

        return false;
    }

    /**
     * Check current user is admin or not
     *
     * @param string $guard
     * @return bool
     */
    public static function isAdmin($guard = 'admin') {
        /** @var \App\Models\User $user */
        $user = \Auth::guard($guard)->user();

        return (!empty($user) && $user->isAdmin());
    }

    /**
     * Check current user is super admin or not
     *
     * @param string $guard
     * @return bool
     */
    public static function isSuperAdmin($guard = 'admin') {
        /** @var \App\Models\User $user */
        $user = \Auth::guard($guard)->user();

        return (!empty($user) && $user->isSuperAdmin());
    }

    /**
     * Get full resource URL
     *
     * @param string $link
     * @param string $default
     * @param string $path
     * @return string
     */
    public static function getLinkFromDataSource($link, $default = null, $path = null) {
        $baseUrl = URL::to('/') . '/';

        if ($link == null) {
            // Get default avatar if null
            $link = ($default == null) ? '' : $baseUrl . $default;
            return $link;
        }

        $regex = "/^(http|https):\/\//";
        $match = preg_match($regex, $link);

        if (!$match) {
            $link = ($path) ? (trim($path, '/') . '/' . $link) : $link;
            $link = $baseUrl . $link;
        }

        return $link;
    }

    /**
     * @param string $value
     * @return string
     */
    public static function getRelativeResource($value)
    {
        // Validate value
        if (empty($value)) {
            return $value;
        }

        $baseUrl = url('/') . '/';
        $baseUrlLength = strlen($baseUrl);

        return substr($value, $baseUrlLength);
    }
    
    /**
     * Config date format in here
     */
    public static function getDateFormat() {
        return 'd/m/Y';
    }

    /**
     * Config time format in here
     */
    public static function getTimeFormat() {
        return 'H:i';
    }

    /**
     * Config js date format in here
     */
    public static function getJsDateFormat() {
        return 'dd/mm/yyyy';
    }

    /**
     * Config js date format in here
     */
    public static function getJsTimeFormat() {
        return 'HH:MM';
    }

    /**
     * Config js date format in here
     */
    public static function getJsDateTimeFormat() {
        return static::getJsDateFormat() . ' ' . static::getJsTimeFormat();
    }

    /**
     * Format datetime by config
     *
     * @param \Carbon\Carbon|string $datetime
     * @param string $format
     * @return string
     */
    public static function getDatetimeFromFormat($datetime, $format = null, $guard = 'admin') {
        // When empty datetime
        if (empty($datetime)) {
            return null;
        }

        // Convert date string to Carbon
        if (!($datetime instanceof Carbon)) {
            $datetime = new Carbon($datetime);
        }

        // Default format
        if (empty($format)) {
            $format = static::getDateFormat() . ' ' . static::getTimeFormat();
        }

        // When invalid datetime
        if ($datetime->year < 1) {
            return null;
        }

        // Format datetime
        /** @var \App\Models\User $me */
        $me = Auth::guard($guard)->user();
        $timezone = (!empty($me) && !empty($me->timezone)) ? $me->timezone : Config::get('app.timezone');
        $strDate = $datetime->setTimezone($timezone)->format($format);

        return $strDate;
    }

    /**
     * Format date by config
     *
     * @param \Carbon\Carbon|string $datetime
     * @param string $format
     * @return string
     */
    public static function getDateFromFormat($datetime, $format = null, $guard = 'admin') {
        // When empty datetime
        if (empty($datetime)) {
            return null;
        }

        // Convert date string to Carbon
        if (!($datetime instanceof Carbon)) {
            $datetime = new Carbon($datetime);
        }

        // Default format
        if (empty($format)) {
            $format = static::getDateFormat();
        }

        // When invalid datetime
        if ($datetime->year < 1) {
            return null;
        }

        // Format date
        /** @var \App\Models\User $me */
        $me = Auth::guard($guard)->user();
        $timezone = (!empty($me) && !empty($me->timezone)) ? $me->timezone : Config::get('app.timezone');
        $strDate = $datetime->setTimezone($timezone)->format($format);

        return $strDate;
    }

    /**
     * Format time by config
     *
     * @param \Carbon\Carbon|string $datetime
     * @param string $format
     * @return string
     */
    public static function getTimeFromFormat($datetime, $format = null, $guard = 'admin') {
        // When empty datetime
        if (empty($datetime)) {
            return null;
        }

        // Convert date string to Carbon
        if (!($datetime instanceof Carbon)) {
            $datetime = new Carbon($datetime);
        }

        // Default format
        if (empty($format)) {
            $format = static::getTimeFormat();
        }

        // When invalid datetime
        if ($datetime->year < 1) {
            return null;
        }

        // Format time
        /** @var \App\Models\User $me */
        $me = Auth::guard($guard)->user();
        $timezone = (!empty($me) && !empty($me->timezone)) ? $me->timezone : Config::get('app.timezone');
        $strDate = $datetime->setTimezone($timezone)->format($format);

        return $strDate;
    }

    /**
     * Get Active locale language
     *
     * @return array
     */
    public static function getActiveLanguages()
    {
        return Config::get('languages');
    }

    /**
     * Get Active locale language
     *
     * @return array
     */
    public static function getActiveWorkspaces()
    {
        // Read from cache
        if (Config::has('workspace.active_workspaces')) {
            return Config::get('workspace.active_workspaces');
        }

        // Cache from db and return
        /** @var \App\User $user */
        $user = \Auth::guard('admin')->user();

        // If invalid user
        if (empty($user)) {
            return [];
        }

        $workspaceInstance = Workspace::getInstance();
        /** @var \App\Workspace $workspaces */
        $workspaces = Workspace::where('workspaces.active', Workspace::IS_YES);

        if (!$user->isSuperAdmin()) {
            // Filter by workspace permission
            $workspaces->withUser($user->id);
        }

        // list
        $workspaces = $workspaces->pluck('workspaces.name', 'workspaces.' . $workspaceInstance->getKeyName());
        $workspaces = $workspaces->toArray();

        // Cache active workspaces
        Config::set('workspace.active_workspaces', $workspaces);

        return $workspaces;
    }

    /**
     * Get default workspace
     *
     * @return \App\Workspace|null
     */
    public static function getDefaultWorkspace()
    {
        // list from cache
        $workspaces = static::getActiveWorkspaces();
        $workspace = null;
        $workspaceInstance = Workspace::getInstance();

        if (!empty($workspaces)) {
            foreach ($workspaces as $id => $name) {
                // Get first item and break
                $workspace = Workspace::active()
                    ->where($workspaceInstance->getKeyName(), $id)
                    ->first();
                break;
            }
        }

        return $workspace;
    }

    /**
     * Translate by \Dimsav\Translatable\Translatable
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $locale
     * @param string $field
     * @return string|null
     */
    public static function translate($model, $locale, $field)
    {
        // Empty model
        if (empty($model)) {
            return null;
        }

        $translation = $model->translate($locale);

        // Not found locale lang
        if (empty($translation)) {
            return null;
        }

        // Get value by field name
        return $translation->getAttribute($field);
    }

    /**
     * Overwrite trans function in Laravel helpers core
     * Parameter $domain is passed to Translator but never used
     * @link https://github.com/laravel/framework/issues/2249
     *
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    public static function trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        $tmpDomain = $domain . '.';
        $string = $tmpDomain . $id;
        // Call from laravel helpers core
        $result = trans($string, $parameters, $locale);

        // Exception with match string when not found in translate file
        if ($result == $string) {
            // Find match in begin of string
            $find = strpos($result, $tmpDomain);

            if ($find !== false) {
                // Remove domain and group of result
                $result = substr_replace($result, '', $find, strlen($tmpDomain));
            }
        }

        return $result;
    }

    /**
     * Set KCFinder upload dir
     *
     * @param string $dir
     * @return bool
     */
    public static function setKCFinderUploadDir($dir)
    {
        if (!isset($_SESSION['KCFINDER'])) {
            $_SESSION['KCFINDER'] = array();
        }

        $_SESSION['KCFINDER']['uploadURL'] = url($dir);
        $_SESSION['KCFINDER']['uploadDir'] = public_path($dir);

        return true;
    }

    /**
     * Get timezone list
     *
     * @link https://stackoverflow.com/questions/1727077/generating-a-drop-down-list-of-timezones-with-php#answer-40636798
     * @return array
     */
    public function getTimezones()
    {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new DateTime('now', new DateTimeZone('UTC'));

            foreach (DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = '(' . $this->formatGmtOffset($offset) . ') ' . $this->formatTimezoneName($timezone);
            }

            array_multisort($offsets, $timezones);
        }

        return $timezones;
    }

    /**
     * Format GMT offset
     *
     * @param $offset
     * @return string
     */
    public function formatGmtOffset($offset)
    {
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }

    /**
     * Format timezone name
     *
     * @param $name
     * @return mixed
     */
    public function formatTimezoneName($name)
    {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);
        return $name;
    }

    /**
     * @param $orginalTranslations
     * @param $fileName
     * @return array
     */
    public static function getFileJsonLang($orginalTranslations, $fileName)
    {
        $pathFile = 'lang/' . app()->getLocale() . '/' . $fileName;
        $jsonTranslationsString = '';

        if (file_exists(storage_path($pathFile))) {
            $jsonTranslationsString = file_get_contents(storage_path($pathFile));
        }

        if (!in_array(substr($jsonTranslationsString, 0, 1), ['{', '['])) {
            return $orginalTranslations;
        }

        return array_replace_recursive($orginalTranslations, json_decode($jsonTranslationsString, TRUE));
    }

    /**
     * @param       $stringKey
     * @param array $array
     * @param       $result
     */
    public static function getChildNode($stringKey, array $array, &$result)
    {
        foreach ($array as $key => $value) {
            if ($stringKey !== "") {
                $currentKey = $stringKey . "." . $key;
            } else {
                $currentKey = $key;
            }
            if (is_array($value)) {
                Helper::getChildNode($currentKey, $value, $result);
            } else {
                $result[$currentKey] = $value;
            }
        }
    }
}