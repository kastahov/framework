<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\Components\DBAL\Builders;

use Spiral\Components\DBAL\Database;
use Spiral\Components\DBAL\QueryBuilder;
use Spiral\Components\DBAL\QueryCompiler;
use Spiral\Components\DBAL\QueryResult;

class SelectQuery extends AbstractSelectQuery
{
    /**
     * Array of table names data should be fetched from. This list may include aliases (AS) construction,
     * system will automatically resolve them which allows fetching columns from multiples aliased
     * tables even if databases has table prefix.
     *
     * @var array
     */
    protected $fromTables = [];

    /**
     * List of QueryBuilder or SQLFragment object which should be joined to query using UNION or UNION
     * ALL syntax. If query is instance of SelectBuilder it'a all parameters will be automatically
     * merged with query parameters.
     *
     * @var array
     */
    protected $unions = [];

    /**
     * SelectBuilder used to generate SELECT query statements, it can as to directly fetch data from
     * database or as nested select query in other builders.
     *
     * @param Database      $database Parent database.
     * @param QueryCompiler $compiler Driver specific QueryGrammar instance (one per builder).
     * @param array         $from     Initial set of table names.
     * @param array         $columns  Initial set of columns to fetch.
     */
    public function __construct(
        Database $database,
        QueryCompiler $compiler,
        array $from = [],
        array $columns = []
    )
    {
        parent::__construct($database, $compiler);

        $this->fromTables = $from;
        if ($columns)
        {
            $this->columns = $this->fetchIdentifiers($columns);
        }
    }

    /**
     * Set table names SELECT query should be performed for. Table names can be provided with specified
     * alias (AS construction).
     *
     * @param array|string|mixed $tables Array of names, comma separated string or set of parameters.
     * @return static
     */
    public function from($tables)
    {
        $this->fromTables = $this->fetchIdentifiers(func_get_args());

        return $this;
    }

    /**
     * Get query binder parameters. Method can be overloaded to perform some parameters manipulations.
     * SelectBuilder will merge it's own parameters with parameters defined in UNION queries.
     *
     * @return array
     */
    public function getParameters()
    {
        $parameters = parent::getParameters();

        foreach ($this->unions as $union)
        {
            if ($union[0] instanceof QueryBuilder)
            {
                $parameters = array_merge($parameters, $union[0]->getParameters());
            }
        }

        return $parameters;
    }

    /**
     * Get or render SQL statement.
     *
     * @param QueryCompiler $compiler
     * @return string
     */
    public function sqlStatement(QueryCompiler $compiler = null)
    {
        $compiler = !empty($compiler) ? $compiler : $this->compiler;

        return $compiler->select(
            $this->fromTables,
            $this->distinct,
            $this->columns,
            $this->joins,
            $this->whereTokens,
            $this->havingTokens,
            $this->groupBy,
            $this->orderBy,
            $this->limit,
            $this->offset,
            $this->unions
        );
    }
}