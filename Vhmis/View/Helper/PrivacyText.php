<?php

namespace Vhmis\View\Helper;

class PrivacyText extends HelperAbstract
{

    /**
     * Format text to protected privacy info
     * 
     * Text can be phone, email
     * 
     * @param string $string
     * @param string $type
     */
    public function __invoke($string, $type = "phone")
    {
        if ($type === 'phone') {
            return $this->privacyPhoneNumber($string);
        }
        
        if ($type === 'email') {
            return $this->privacyEmailAddress($string);
        }
        
        return $string;
    }
    
    protected function privacyPhoneNumber($string)
    {
        $total = strlen($string);
        
        if($total <= 4) {
            return '****';
        }
        
        return substr($string, 0, $total - 4) . '****';
    }
    
    protected function privacyEmailAddress($string)
    {
        $email = explode('@', $string);
        
        $total = strlen($email[0]);
        
        $endOfEmail = '@****';
        if(isset($email[1])) {
            $endOfEmail = '@' . $email[1];
        }
        
        if($total <= 4) {
            return '****' . $endOfEmail;
        }
        
        return $email[0][0] . str_repeat('*', $total - 2) . $email[0][$total - 1] . $endOfEmail;
    }
}
