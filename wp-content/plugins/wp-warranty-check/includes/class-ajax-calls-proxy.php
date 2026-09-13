<?php

namespace Trust\INC;

class AJAXCallsProxy {
    public static function handle(string $type) {
        switch($type) {
            case 'warranty':
                return new WarrantyAJAXCalls();
                break;
            case 'validation':
                return new ValidationAJAXCalls();
                break;
            default:
                return;
        }
    }
}