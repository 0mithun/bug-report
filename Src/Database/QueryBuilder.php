<?php
namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exception\NotFoundException;

abstract class QueryBuilder {
    protected $connection;
    protected $table;
    protected $statement;
    protected $fields;
    protected $placeholders;
    protected $bindings;
    protected $operation = self::DML_TYPE_SELECT;

    const OPERATORS = ['=', '<', '<=', '>', '>=', '<>'];
    const PLACEHOLDER = '?';
    const COLUMN = '*';
    const DML_TYPE_SELECT = 'SELECT';
    const DML_TYPE_INSERT = 'INSERT';
    const DML_TYPE_UPDATE = 'UPDATE';
    const DML_TYPE_DELETE = 'DELETE';

    use Query;

    public function __construct( DatabaseConnectionInterface $connection ) {
        $this->connection = $connection->getConnection();
    }

    public function table( $name ) {
        $this->table = $name;

        return $this;
    }

    public function where( $column, $operator = self::OPERATORS[0], $value = null ) {
        //

        if ( !in_array( $operator, self::OPERATORS ) ) {
            if ( $value == null ) {
                $value = $operator;
                $operator = self::OPERATORS[0];
            } else {
                throw new NotFoundException( 'Operator is not valid', ['operator' => $operator] );
            }
        }

        $this->parseWhere( [$column => $value], $operator );
        $query = $this->prepare( $this->getQuery( $this->operation ) );

        $this->statement = $this->execute( $query );

        return $this;
    }

    private function parseWhere( array $conditions, string $operator ) {
        foreach ( $conditions as $column => $value ) {
            $this->placeholders[] = sprintf( "%s %s %s", $column, $operator, self::PLACEHOLDER );
            $this->bindings[] = $value;
        }

        return $this;
    }

    public function select( string $fields = self::COLUMN ) {
        $this->operation = self::DML_TYPE_SELECT;
        $this->fields = $fields;

        return $this;
    }

    public function create( array $data ) {
        $this->fields = '`' . implode( '`, `', array_keys( $data ) ) . '`';
        foreach ( $data as $value ) {
            $this->placeholders[] = self::PLACEHOLDER;
            $this->bindings[] = $value;
        }
        $query = $this->prepare( $this->getQuery( self::DML_TYPE_INSERT ) );
        $this->statement = $this->execute( $query );

        return $this->lastInsertedId();
    }

    public function update( array $data ) {
        $this->operation = self::DML_TYPE_UPDATE;
        $this->fields = [];

        foreach ( $data as $column => $value ) {
            $this->fields[] = sprintf( "%s%s%s", $column, self::OPERATORS[0], "'$value'" );
        }
    }

    public function delete() {
        $this->operation = self::DML_TYPE_DELETE;

        return $this;
    }

    public function raw( string $query ) {
        $query = $this->prepare( $query );
        $this->statement = $this->execute( $query );

        return $this;
    }

    public function find( $id ) {
        return $this->where( 'id', $id )->first();
    }

    public function findOneBy( string $field, $value ) {
        return $this->where( $field, $value )->first();
    }

    public function first() {
        return $this->count() ? $this->get()[0] : '';
    }

    abstract public function get();
    abstract public function count();
    abstract public function lastInsertedId();
    abstract public function prepare( $query );
    abstract public function execute( $statement );
    abstract public function fetchInto( $className );
}
