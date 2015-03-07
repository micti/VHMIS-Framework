<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils;

/**
 * File funtions
 */
class File
{

    /**
     * Move uploaded file.
     * 
     * @param string $temp
     * @param string $path
     * @param string $filename
     * @param boolean $fix
     * 
     * @return bool
     */
    static public function moveUploadedFile($temp, $path, $filename, $fix = true)
    {
        $destination = $path . D_SPEC . $filename;

        if ($fix) {
            if (file_exists($destination)) {
                $destination = $path . D_SPEC . time() . '_' . $filename;
            }
        }

        return move_uploaded_file($temp, $destination);
    }

    /**
     * Get file mine type.
     * 
     * @param string $file
     * 
     * @return string
     */
    static public function getFileType($file)
    {
        $finfo = new \finfo;
        $type = $finfo->file($file, FILEINFO_MIME_TYPE);
        return $type;
    }
    
    /**
     * Get file extension.
     * 
     * @param string $file
     * 
     * @return string
     */
    static public function getFileExt($file)
    {
        $part = explode('.', $file);
        
        if(count($part) === 1) {
            return '';
        }
        
        return strtolower(end($part));
    }
}
