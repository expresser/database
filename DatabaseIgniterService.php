<?php namespace Expresser\Database;

class DatabaseIgniterService extends \Themosis\Core\IgniterService {

  private static $connection;

  public function ignite() {

    $this->app->bind('db', function ($app) {

      return static::$connection ?: static::$connection = new WpdbConnection;
    });
  }
}
