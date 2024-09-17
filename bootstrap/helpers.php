<?php

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


function removeAllCharacters($str)
{
    return preg_replace('/[^A-Za-z0-9. -]/', '', $str);
}

function prefix_zeros($number, $min_lenght = 5)
{
    return str_pad($number, $min_lenght, 0, STR_PAD_LEFT);
}


/*Create log via spatie activity log*/
function trail($name, $narration, $subject = null)
{
    //$causer = user() ? null : admin();
    $causer = user();

    $subject = ($subject != null) ? $subject : new \Spatie\Activitylog\Models\Activity();

    ($causer) ? activity($name)->performedOn($subject)->withProperties(['ip' => ip()])->log($narration)->causedBy(user()) : null;
}

if (!function_exists('ip')) {

    function ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip == false) ? '0.0.0.0' : $ip;
        return $ip;

    }

}


if (!function_exists('curl')) {
    function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}


if (!function_exists('user')) {

    function user($key = null)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!is_null($key))
            return isset($user->$key) ? $user->$key : null;


        return $user;
    }
}


function set_session_company_id($company_id = null)
{
    return session(['company_id' => $company_id]);
}


if (!function_exists('admin')) {
    /**
     *  Get currently logged in admin
     *  use: user()->name or user('name')
     *
     * @param null $key
     * @return App\Models\Admin
     */
    function admin($key = null)
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        if (!is_null($key))
            return $admin->$key;

        return $admin;
    }
}

function get_guard()
{
    if (Auth::guard('admin')->check()) {
        return 'admin';
    } elseif (Auth::guard('web')->check()) {
        return 'web';
    }
}

function get_session_user()
{
    if (Auth::guard('admin')->check()) {
        return admin();
    } elseif (Auth::guard('web')->check() || Auth::guard('api')->check()) {
        return user();
    }
}

function guard_class()
{
    return get_class(get_guard());

}

function str_slug_reverse($string, $character = '-')
{
    return ucwords(str_replace($character, ' ', $string));
}

if (!function_exists('array_group_by')) {

    function array_group_by(array $arr, $key): array
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, a float, or a function', E_USER_ERROR);
        }
        $isFunction = !is_string($key) && is_callable($key);
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($arr as $value) {
            $groupKey = null;
            if ($isFunction) {
                $groupKey = $key($value);
            } else if (is_object($value)) {
                $groupKey = $value->{$key};
            } else {
                $groupKey = $value[$key];
            }
            $grouped[$groupKey][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $groupKey => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$groupKey] = call_user_func_array('array_group_by', $params);
            }
        }
        return $grouped;
    }
}

if (!function_exists('mask_phone')) {


    function mask_phone($phone_number, $char = '*')
    {
        //ensure it is a valid email
        $len = strlen($phone_number);

        $str = maskString($phone_number, 6, $len - 2);

        return $str;
    }
}


if (!function_exists('money')) {

    function money($amount, $currency = 'KES', $decimal = 2)
    {
        return $currency . " " . number_format($amount, $decimal);
    }
}


if (!function_exists('maskString')) {

    function maskString($s, $start = 1, $end = null, $char = '*')
    {
        $start = $start - 1;

        $array = str_split($s);

        // $end = strlen($s) < $end ? strlen($s) : $end ?: strlen($s);

        if (strlen($s) < $end) {
            $end = strlen($s);
        }
        if (!$end) {
            $end = strlen($s);
        }

        for ($start; $start < $end; $start++) {
            $array[$start] = $char;
        }
        return join('', $array);
    }
}


if (!function_exists('mask_email')) {

    function mask_email($email, $char = '*')
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            list ($username, $domain) = explode('@', $email);
            $length = strlen($username);

            if ($length > 3)
                return maskString($username, 3, strlen($username), $char) . "@" . $domain;

            return maskString($username, 2, strlen($username), $char) . "@" . $domain;
        }

        return $email;
    }
}


if (!function_exists('acronym')) {

    function acronym($string)
    {
        //first slug string to take care of double spaces
        $words = explode("-", str_slug($string));
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }

        //return uppercase acronym
        return strtoupper($acronym);
    }
}


if (!function_exists('carbon')) {
    /**
     * @param null $date_time
     * @param null $format
     * @return \Carbon\Carbon
     */
    function carbon($date_time = null, $format = null)
    {
        if (!$date_time)
            return \Carbon\Carbon::now();

        if ($date_time && !$format)
            return \Carbon\Carbon::parse($date_time);

        return \Carbon\Carbon::createFromFormat($format, $date_time);
    }
}


if (!function_exists('sanitize')) {

    function sanitize($string, $force_lowercase = false, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;
        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }
}

function format_phone($phone)
{

    $phone = removeAllSpaces($phone);
    $phone = str_replace('+', '', $phone);
    $first_character = (substr($phone, 0, 1));

    if ($first_character == 0) {
        $phone = ltrim($phone, '0');
        $phone = '254' . $phone;
    }

    if ($first_character == 7) {
        $phone = '254' . $phone;
    }

    return $phone;
}


if (!function_exists('status_label')) {

    function status_label($status)
    {
        $status = is_object($status) ? $status->status : $status;
        $status = strtolower($status);

        switch ($status) {
            case in_array($status, ['archived', 'correction', 'pending', 'partially-paid', 'expired']):
                return badge('warning', title_case($status));
            case in_array($status, ['active', 'published', 'paid', 'complete', 'confirmed', 'valid', 'validated', 'live', 'completed', 'resolved']):
                return badge('success', title_case($status));
            case in_array($status, ['rejected', 'blocked', 'banned', 'disable', 'used', 'unpaid']):
                return badge('danger', title_case($status));
            case in_array($status, ['disabled', 'failed']):
                return badge('dark', title_case($status));
            case in_array($status, ['archived']):
                return badge('info', title_case($status));
            default:
                return badge('secondary', title_case($status));
        }
    }
}

if (!function_exists('title_case')) {

    function title_case($string)
    {

        return ucwords($string);
    }
}

if (!function_exists('badge')) {

    function badge($type, $label, $icon = null)
    {
        $icon = $icon ? '<i class="' . $icon . '"></i>' : '';
        return "<span class='badge bg-{$type}'>{$icon} {$label}</span>";
    }
}


if (!function_exists('first_phrase')) {

    function first_phrase($string)
    {

        $split = explode(' ', $string);

        return $split[0];
    }
}


if (!function_exists('truthy_badge')) {
    /**
     * @param $bool
     * @param string $yes_label
     * @param string $no_label
     * @return string
     */
    function truthy_badge($bool, $yes_label = 'Yes', $no_label = 'No')
    {
        if ($bool)
            return '<span class="badge badge-success">' . $yes_label . '</span>';

        return '<span class="badge badge-danger">' . $no_label . '</span>';
    }
}


if (!function_exists('has_expired')) {

    function has_expired($timestamp)
    {
        return Carbon::now()->gt(Carbon::parse($timestamp));
    }

}

if (!function_exists('list_check')) {

    function bad_word_check($name, $bad_words)
    {
        foreach ($bad_words as $bad_word) {
            if (stristr($name, $bad_word)) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('logout_all_guards')) {

    function logout_all_guards()
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) Auth::guard($guard)->logout();
        }
    }
}

if (!function_exists('force_multi_dimensional')) {

    function force_multi_dimensional($array)
    {
        $result = [];
        foreach ($array as $item) {
            $item_detail = array("id" => $item);
            $result = array_merge($result, $item_detail);
        }
        return $result;
    }
}


if (!function_exists('is_change_approver')) {

    function can_approve_change($change_type_id, $business_id, $user_id)
    {
        return \App\Models\ChangeApprovers::where('change_type_id', $change_type_id)->where('business_id', $business_id)->whereJsonContains('approvers->id', strval($user_id))->count();
    }
}


if (!function_exists('has_approved')) {

    function has_approved($approved_by, $user_id = null)
    {
        return in_array($user_id ? $user_id : user('id'), array_values($approved_by));
    }
}


function sanitize_business_name($string)
{
    $string = removeWhiteSpace($string);
    return strtoupper(strtolower($string));
}

function removeWhiteSpace($text)
{
    $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
    $text = preg_replace('/([\s])\1+/', ' ', $text);
    $text = trim($text);
    return $text;
}


function removeAllSpaces($string)
{
    return preg_replace('/\s+/', '', $string);
}

function cleanPhoneNumber($phone_number)
{

    return str_replace('-', '', removeAllSpaces($phone_number));

}


if (!function_exists('randomNDigitNumber')) {

    function randomNDigitNumber($digits)
    {
        $returnString = '';
        while (strlen($returnString) < $digits) {
            $returnString .= mt_rand(0, 9);
        }
        return $returnString;
    }
}

if (!function_exists('rejection_reason_comment')) {


    function rejection_reason_comment($rejection_reason)
    {
        $reasons = config('brs.name_rejection_reasons');

        return $reasons[$rejection_reason];
    }

}


function super_trim($string)
{

    $string = str_replace(array("\r", "\n"), '', $string);
    return preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', "\n", $string));
}

if (!function_exists('is_image_ext')) {

    function is_image_ext($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        return in_array($ext, ['png', 'bmp', 'jpeg', 'jpg']);
    }
}

function time_label($seconds)
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;
    return $hours > 0 ? "$hours hours, $minutes minutes" : ($minutes > 0 ? "$minutes minutes, $seconds seconds" : "$seconds seconds");
}


function extension($filename)
{
    return pathinfo($filename, PATHINFO_EXTENSION);
}

//Behaviour: 50 outputs 50, 52 outputs 55, 50.25 outputs 50
function roundUpToAny($n, $x = 5)
{
    return (round($n) % $x === 0) ? round($n) : round(($n + $x / 2) / $x) * $x;
}


function age_group_level($age_group)
{

    return str_slug_reverse($age_group, '_');

}

function uuid()
{

    $uuid = Ramsey\Uuid\Uuid::uuid4()->toString();

    return $uuid;
}


function add_quotes($str)
{
    return sprintf("'%s'", $str);
}

function url_site_label($url)
{

    $string = str_replace('https://', '', $url);
    $string = str_replace('http://', '', $string);
    return $string;
}

function niceNumberLabel($num)
{

    if ($num > 1000) {

        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int)$x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= '' . $x_parts[$x_count_parts - 1];

        return $x_display;

    }

    return $num;
}


function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}


function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


function linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
{
    // Link attributes
    $attr = '';
    foreach ($attributes as $key => $val) {
        $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
    }

    $links = array();

    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) {
        return '<' . array_push($links, $match[1]) . '>';
    }, $value);

    // Extract text links for each protocol
    foreach ((array)$protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https':
                $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
                    if ($match[1]) $protocol = $match[1];
                    $link = $match[2] ?: $match[3];
                    return '<' . array_push($links, "<a target='_blank' $attr href=\"$protocol://$link\">$link</a>") . '>';
                }, $value);
                break;
            case 'mail':
                $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) {
                    return '<' . array_push($links, "<a target='_blank' $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>';
                }, $value);
                break;
            case 'twitter':
                $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) {
                    return '<' . array_push($links, "<a target='_blank' $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . "\">{$match[0]}</a>") . '>';
                }, $value);
                break;
            default:
                $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
                    return '<' . array_push($links, "<a target='_blank' $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>';
                }, $value);
                break;
        }
    }

    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) {
        return $links[$match[1] - 1];
    }, $value);
}


function set_customer_session($customer)
{
    session(['customer_id' => $customer->id]);
    session(['customer' => $customer]);
}

function customer()
{
    return \App\Models\Customer::find(customer_id());
}

function customer_id()
{
    return session('customer_id');
}


function my_float()
{
    return customer()->balance;
}


function check_call($check, $query, $extra_params = [])
{
    $base_url = config('app.proxy_base_url');

    $headers = [
        'P-Key' => config('app.proxy_key'),
        'Accept' => 'application/json',
    ];



    /*  $allowed_types = ['
          national_id',
          'alien_id',
          'plate',
          'dl',
          'kra',
          'brs'];
    */

    $query = base64_encode($query);
    $url = "$base_url/$check/$query";

    $response = Http::withHeaders($headers)->get($url);
    if ($response->status()) {

        return $response->json();
    }

    return null;


    /* if(is_array($response)){

         return $response;
     }*/

    // return $response;
}


function retainArrayElementsByKeys($array, $keysToRetain)
{
    $keysToRetain = array_flip($keysToRetain);
    return array_intersect_key($array, $keysToRetain);
}

function service_label($service_id)
{
    $services = config('billing.services');
    $service_label = \Illuminate\Support\Arr::get($services,$service_id.'.label');

    return $service_label;
}
