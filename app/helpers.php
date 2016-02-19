<?php
/**
 * global helper functions
 */

use Storage;
use Log;

/**
 * Check if directory exists in app storage
 * 
 * @param  String $dir 
 * @return Bool 
 */
protected function exists($dir)
{
    $parts = explode('/', $dir);
    $parts = array_values(
        array_filter($parts, function($part) { return strlen($part) > 0; })
    );

    if( count($parts) > 2 ) {
        Log::warning('_hasDirectory() only supports two level recursion');
        return false;
    }

    if( in_array($parts[0], Storage::disk('local')->directories('/')) ) {
        if( count($parts) === 1) return true;
        else {
            return in_array($parts[0] . '/' . $parts[1], Storage::disk('local')->directories('/' . $parts[0]));
        }
    }

    return false;
}