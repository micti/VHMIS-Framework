<?php

namespace Vhmis\Security;

class Hash
{
    /**
     * Đoạn mã bí mật mặc định thêm vào ở trước
     *
     * @var string
     */
    protected $preText = '';

    /**
     * Đoạn mã bí mật mặc định thêm vào ở sau
     *
     * @var string
     */
    protected $suffixText = '';

    public function __construct($preText = '', $suffixText = '')
    {
        $this->preText = $preText;

        $this->suffixText = $suffixText;
    }

    public function setPreText($preText)
    {
        $this->preText = $preText;
        return $this;
    }

    public function setSuffixText($suffixText)
    {
        $this->suffixText = $suffixText;
        return $this;
    }

    /**
     * Hash theo md5
     *
     * @param string $text
     * @param string $preText
     * @param string $suffixText
     * @return string
     */
    public function md5($text, $preText = '', $suffixText = '')
    {
        if ($preText === '') {
            $preText = $this->preText;
        }

        if ($suffixText === '') {
            $suffixText = $this->suffixText;
        }

        return md5($preText . $text . $suffixText);
    }

    /**
     * Hash theo sha1
     *
     * @param string $text
     * @param string $preText
     * @param string $suffixText
     * @return string
     */
    public function sha1($text, $preText = '', $suffixText = '')
    {
        if ($preText === '') {
            $preText = $this->preText;
        }

        if ($suffixText === '') {
            $suffixText = $this->suffixText;
        }

        return sha1($preText . $text . $suffixText);
    }
}
