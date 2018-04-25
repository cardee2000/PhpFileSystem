<?php

namespace MifestaFileSystem;

/**
 * Class Filesystem
 * @package MifestaFileSystem
 */
class FileSystem
{
    /**
     * Get file stat
     * @param string $filename
     * @return array|bool
     */
    public function get_stat($filename)
    {
        $filename = $this->normalize($filename);
        if (file_exists($filename)) {
            return stat($filename);
        } else {
            return false;
        }
    }

    /**
     * Get file content
     * @param string $filename
     * @return string|false
     */
    public function get_content($filename)
    {
        $filename = $this->normalize($filename);
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            return false;
        }
    }

    /**
     * Get file content
     * @param string $filename
     * @param mixed $data
     * @return string|false
     */
    public function put_content($filename, $data)
    {
        $filename = $this->normalize($filename);
        return file_put_contents($filename, $data);
    }

    /**
     * Remove BOM
     * @param string $string
     * @return string
     */
    public function remove_bom($string)
    {
        if (is_string($string) && (substr($string, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf))) {
            $string = substr($string, 3);
        }
        return $string;
    }

    /**
     * Normalize the given path. On Windows servers backslash will be replaced
     * with slash. Removes unnecessary double slashes and double dots. Removes
     * last slash if it exists. Examples:
     * normalize("C:\\any\\path\\") returns "C:/any/path"
     * normalize("/your/path/..//home/") returns "/your/home"
     * @param string $path
     * @return string
     */
    public function normalize($path)
    {
        $classOS = new OS();
        // Backslash to slash convert
        if ($classOS->is_windows()) {
            $path = preg_replace('/([^\\\])\\\+([^\\\])/s', '$1/$2', $path);
            if (substr($path, -1) == '\\') {
                $path = substr($path, 0, -1);
            }
            if (substr($path, 0, 1) == '\\') {
                $path = '/' . substr($path, 1);
            }
        }
        $path = preg_replace('/\/+/s', '/', $path);
        $path = '/' . $path;
        if (substr($path, -1) != '/') {
            $path .= '/';
        }
        $expr = '/\/([^\/]{1}|[^\.\/]{2}|[^\/]{3,})\/\.\.\//s';
        while (preg_match($expr, $path)) $path = preg_replace($expr, '/', $path);
        $path = substr($path, 0, -1);
        $path = substr($path, 1);
        return $path;
    }

    /**
     * Create directory
     * @param string $directory_name
     * @return bool
     * @throws \Exception
     */
    public function create_directory($directory_name = '')
    {
        $new_directory_name = rtrim($this->normalize($directory_name), '/');
        if (file_exists($new_directory_name)) {
            return false;
        } else {
            return mkdir($new_directory_name . '/', 0777, true);
        }
    }
}
