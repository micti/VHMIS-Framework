<?php

class Vhmis_Locale
{
    public static function loadLocale($lang, $region = '')
    {
        $locale = $lang . ($region == '' ? '' : '_' . $region);

        $data = Vhmis_Configure::get('Locale.' . $locale);
        if($data == null || $data == '' || $data === false)
        {
            include VHMIS_CORE_PATH . D_SPEC . 'Locale' . D_SPEC . 'Data' .  D_SPEC . $locale . '.php';
            Vhmis_Configure::set('Locale.' . $locale, $localeData);
            return $localeData;
        }
        else
        {
            return $data;
        }
    }

    public static function dateFormat($date, $format, $lang = '', $region = '')
    {
        if($lang == '' && $region == '')
        {
            $locale = Vhmis_Configure::get('Locale');
            $data = Vhmis_Locale::loadLocale($locale);
        }
        else
        {
            $data = Vhmis_Locale::loadLocale($lang, $region);
        }

        if($data == null) return '';

        $date = self::_praseDateToVhmisDateArray($date);

        if($date == null) return '';

        $pattern = array(
            '{EEEE}',
            '{eee}',
            '{EE}',
            '{e}',
            '{dd}',
            '{d}',
            '{MMMM}',
            '{MMM}',
            '{MM}',
            '{M}',
            '{YYYY}',
            '{yyyy}',
            '{yy}'
        );

        /*$convert = array('d' => 'dd'  , 'D' => 'EE'  , 'j' => 'd'   , 'l' => 'EEEE', 'N' => 'eee' , 'S' => 'SS'  ,
                         'w' => 'e'   , 'z' => 'D'   , 'W' => 'ww'  , 'F' => 'MMMM', 'm' => 'MM'  , 'M' => 'MMM' ,
                         'n' => 'M'   , 't' => 'ddd' , 'L' => 'l'   , 'o' => 'YYYY', 'Y' => 'yyyy', 'y' => 'yy'  ,
                         'a' => 'a'   , 'A' => 'a'   , 'B' => 'B'   , 'g' => 'h'   , 'G' => 'H'   , 'h' => 'hh'  ,
                         'H' => 'HH'  , 'i' => 'mm'  , 's' => 'ss'  , 'e' => 'zzzz', 'I' => 'I'   , 'O' => 'Z'   ,
                         'P' => 'ZZZZ', 'T' => 'z'   , 'Z' => 'X'   , 'c' => 'yyyy-MM-ddTHH:mm:ssZZZZ',
                         'r' => 'r'   , 'U' => 'U');*/

        $replace = array(
            $data['dates']['calendar']['gregorian']['days']['formatFull'][$date['wdayAbbr']],
            $date['wday'],
            $data['dates']['calendar']['gregorian']['days']['formatAbbr'][$date['wdayAbbr']],
            ($date['wday'] - 1),
            $date['day'],
            (int) $date['day'],
            $data['dates']['calendar']['gregorian']['months']['formatFull'][(int) $date['month']],
            $data['dates']['calendar']['gregorian']['months']['formatAbbr'][(int) $date['month']],
            $date['month'],
            (int) $date['month'],
            $date['wyear'],
            $date['year'],
            $date['year'][2] . $date['year'][3]
        );

        if(isset($data['dates']['calendar']['gregorian']['dateFormats'][$format]))
        {
            return str_replace($pattern, $replace, $data['dates']['calendar']['gregorian']['dateFormats'][$format]);
        }
        if(isset($data['dates']['calendar']['gregorian']['otherFormats'][$format]))
        {
            return str_replace($pattern, $replace, $data['dates']['calendar']['gregorian']['otherFormats'][$format]);
        }
        else
        {
            return str_replace($pattern, $replace, $format);
        }
    }

    public static function getWeekday($wday, $full = true, $lang = '', $region = '')
    {
        if($lang == '' && $region == '')
        {
            $locale = Vhmis_Configure::get('Locale');
            $data = Vhmis_Locale::loadLocale($locale);
        }
        else
        {
            $data = Vhmis_Locale::loadLocale($lang, $region);
        }

        $format = $full ? 'formatFull' : 'formatAbbr';

        return $data['dates']['calendar']['gregorian']['days'][$format][$wday];
    }

    public static function getField($field, $lang = '', $region = '')
    {
        if($lang == '' && $region == '')
        {
            $locale = Vhmis_Configure::get('Locale');
            $data = Vhmis_Locale::loadLocale($locale);
        }
        else
        {
            $data = Vhmis_Locale::loadLocale($lang, $region);
        }

        return $data['dates']['calendar']['gregorian']['fields'][$field];
    }

    protected function _praseDateToVhmisDateArray($date)
    {
        if(is_array($date)) return $date;

        if(is_string($date) || is_numeric($date))
        {
            $obj = new Vhmis_Date();
            if(!$obj->time($date))
            {
                return null;
            }
            else
            {
                return $obj->toArray();
            }
        }

        return null;
    }
}