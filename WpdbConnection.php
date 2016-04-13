<?php namespace Expresser\Database;

class WpdbConnection extends \Illuminate\Database\MySqlConnection {

  protected $wpdb;

  public function __construct() {

    global $wpdb;

    $this->wpdb = $wpdb;

    parent::__construct($this->wpdb->dbh->get_pdo(), $this->wpdb->dbname, $this->wpdb->prefix);
  }

  public function selectFromWriteConnection($query, $bindings = []) {

    return parent::select($query, $bindings, false);
  }

  public function select($query, $bindings = []) {

    return $this->statement($query, $bindings);
  }

  public function insert($query, $bindings = []) {

    return $this->statement($query, $bindings);
  }

  public function update($query, $bindings = []) {

    return $this->affectingStatement($query, $bindings);
  }

  public function delete($query, $bindings = []) {

    return $this->affectingStatement($query, $bindings);
  }

  public function statement($query, $bindings = []) {

    return $this->run($query, $bindings, function ($me, $query, $bindings) {

      if (count($bindings) > 0) $query = $me->wpdb->prepare($query, $bindings);

      $this->wpdb->query($query);

      return $this->wpdb->last_result;
    });
  }

  public function affectingStatement($query, $bindings = []) {

    return $this->run($query, $bindings, function ($me, $query, $bindings) {

      if (count($bindings) > 0) $query = $me->wpdb->prepare($query, $bindings);

      return $this->wpdb->query($query);;
    });
  }

  public function unprepared($query) {

		return $this->run($query, [], function($me, $query) {

			return $this->statement($query);
		});
	}

  protected function getDefaultQueryGrammar() {

		return $this->withTablePrefix(new WpdbGrammar);
	}
}
