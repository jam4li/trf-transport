<?php
namespace Trust\Templates;

use Trust\TrustWarrantyPlugin as Trust;

class LocalQueries {
    protected $utils;

    public function __construct() {
        $this->utils = Trust::instance()->utils();
    }

    public function make_query(string $type, string $dest) {
        return $this->utils->make_query($type, $dest);
    }
}