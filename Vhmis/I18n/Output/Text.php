<?php

namespace Vhmis\I18n\Output;

use \Vhmis\I18n\Resource\Resource as I18nResource;

class Text
{
    /**
     * Locale mặc định
     *
     * @var string
     */
    protected $locale;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        // Locale mặc định
        $this->locale = locale_get_default();
    }

    /**
     * Thiết lập Locale
     *
     * @param string $locale Locale
     */
    public function setLocale($locale = null)
    {
        if (null !== $locale)
            $this->locale = locale;
    }

    public function toList($group)
    {
        $count = count($group);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $group[0];
        }

        $listPattern = I18nResource::listPattern($this->locale);

        if (isset($listPattern[$count])) {
            $position = array();

            for ($i = 0; $i < $count; $i++) {
                $position[] = '{' . $i . '}';
            }

            return str_replace($position, $group, $listPattern[$count]);
        }

        $end = str_replace(array('{0}', '{1}'), array($group[$count - 2], $group[$count - 1]), $listPattern['end']);

        $middle = array();

        for ($i = 1; $i < $count - 2; $i++) {
            $middle[] = $group[$i];
        }

        $middle[] = $end;
        $middleDer = str_replace(array('{0}', '{1}'), '', $listPattern['middle']);
        $middle = implode($middleDer, $middle);

        $text = str_replace(array('{0}', '{1}'), array($group[0], $middle), $listPattern['start']);

        return $text;
    }
}
