<?php

namespace Application;

class Flash implements \Nerd\Design\Initializable
{
    private static $alert;
    private static $flash;

    public static function __initialize()
    {
        static::$alert = <<<ALERT

<div class="alert alert-block alert-%s">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>%s</strong> %s
</div>

ALERT;
    }

    public static function info($data)
    {
        if ($data === null) {
            return '';
        }

        return sprintf(static::$alert, 'info', 'Info!', static::parse($data));
    }

    public static function error($data)
    {
        if ($data === null) {
            return '';
        }

        return sprintf(static::$alert, 'error', 'Error!', static::parse($data));
    }

    public static function warning($data)
    {
        if ($data === null) {
            return '';
        }

        return sprintf(static::$alert, 'warning', 'Warning!', static::parse($data));
    }

    public static function success($data)
    {
        if ($data === null) {
            return '';
        }

        return sprintf(static::$alert, 'success', 'Success!', static::parse($data));
    }

    private static function parse($data)
    {
        if (is_array($data)) {
            // Assumed array
            $out = '<ul>';

            foreach ($data as $field => $errors) {
                foreach ($errors as $error) {
                    $out .= "<li>$error</li>";
                }
            }

            return $out.'</ul>';
        }

        return $data;
    }
}
