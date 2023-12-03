<?php

namespace frontend\access;

use Yii;

class VonageConfigAccess
{
    private static $instance;
    private $currentUser = null;

    // Private constructor to prevent instantiation
    private function __construct()
    {
        $this->currentUser = Yii::$app->user->identity;
    }

    // Public method to get the singleton instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function canViewVenmoHits()
    {
        return true;
    }

    public function canViewAffirmHits()
    {
        return $this->currentUser->id == 8 or $this->currentUser->id == 6 or $this->currentUser->id == 4;
    }
}
