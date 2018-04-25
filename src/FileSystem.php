<?php

namespace MifestaFileSystem;

/**
 * Class FileSystem
 * @package MifestaFileSystem
 */
class FileSystem
{
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
        return mkdir($new_directory_name, 0777, true);
    }
}
