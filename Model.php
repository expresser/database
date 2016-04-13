<?php namespace Expresser\Database;

use Expresser\Support\Facades\DB;

abstract class Model extends \Expresser\Support\Model {

  protected $primaryKey = 'id';

  protected $table;

  public function getKeyName() {

    return $this->primaryKey;
  }

  public function getQualifiedKeyName() {

    return $this->getTable() . '.' . $this->getKeyName();
  }

  public function getTable() {

    return $this->table;
  }

  public function setTable() {

    $this->table = $table;

    return $this;
  }

  public function newQuery() {

    return (new Query(DB::table($this->getTable())))->setModel($this);
  }
}
