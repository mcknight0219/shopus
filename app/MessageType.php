<?php

/**
 * An enum to represent message type
 */
class MessageType extends SplEnum 
{
    const __default     = self::TEXT;

    const TEXT          = 1;
    const IMAGE         = 2;
    const VOICE         = 3;
    const VIDEO         = 4;
    const SHORT_VIDEO   = 5;
    const LOCATION      = 6;
    const LINK          = 7;
    const EVENT         = 8;

    const UNKNOWN       = 9;
}