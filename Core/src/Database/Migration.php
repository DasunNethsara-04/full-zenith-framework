<?php

namespace ZenithPHP\Core\Database;

/**
 * Abstract class representing a database migration.
 * 
 * Each migration must define methods for applying (`up`) and reversing (`down`) database changes.
 * 
 * @package ZenithPHP\Core\Database
 */
abstract class Migration
{
    /**
     * Defines the operations to apply the migration.
     * 
     * This method should contain the code to create or alter database structures.
     * 
     * @return void
     */
    abstract public function up();

    /**
     * Defines the operations to revert the migration.
     * 
     * This method should contain the code to drop or undo the changes made in the `up` method.
     * 
     * @return void
     */
    abstract public function down();
}
