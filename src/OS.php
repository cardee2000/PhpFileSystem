<?php

namespace MifestaFileSystem;

/**
 * Class OS
 * @package MifestaFileSystem
 */
class OS
{
    /**
     * This OS is Windows
     * @return bool
     */
    public function is_windows()
    {
        return (strtolower(substr(PHP_OS, 0, 3)) == 'win');
    }

    /**
     * Get max size file upload in byte
     * @return int
     */
    public function max_upload_size()
    {
        $values = array($this->parse_size(ini_get('post_max_size')));
        if (($upload_max = $this->parse_size(ini_get('upload_max_filesize'))) && ($upload_max > 0)) {
            $values[] = $upload_max;
        }
        return min($values);
    }

    /**
     * Get the size from the string in bytes
     * @param $size
     * @return float
     */
    public function parse_size($size)
    {
        // Remove the non-unit characters from the size.
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        // Remove the non-numeric characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}
