<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Indexer\Mview;

use Magento\Framework\DB\Ddl\Trigger;
use Magento\Framework\Mview\View\Subscription;
use Magento\Framework\Mview\ViewInterface;

class RemainsAggregationSubscription extends Subscription
{
    /**
     * List of columns that can be updated in any subscribed table
     * without creating a new change log entry
     *
     * @var array
     */
    private $ignoredUpdateColumns = [
        'creation_time',
        'update_time',
        'commentary'
    ];

    /**
     * List of columns that can be updated in a specific subscribed table
     * for a specific view without creating a new change log entry
     */
    private $ignoredUpdateColumnsBySubscription = [
        'tsum_remains_aggregate' => [
            'tsum_cf_incomes' => [
                'project_id',
                'cf_item_id'
            ]
        ]
    ];

    /**
     * Rewrite the original by added UNIX_TIMESTAMP converting
     * Build trigger statement for INSERT, UPDATE, DELETE events
     *
     */
    protected function buildStatement(string $event, ViewInterface $view) : string
    {
        $changelog = $view->getChangelog();
        switch ($event) {
            case Trigger::EVENT_INSERT:
                $trigger = "INSERT IGNORE INTO %s (%s) VALUES (UNIX_TIMESTAMP(NEW.%s));";
                break;
            case Trigger::EVENT_UPDATE:
                $tableName = $this->resource->getTableName($this->getTableName());
                $trigger = "INSERT IGNORE INTO %s (%s) VALUES (UNIX_TIMESTAMP(NEW.%s));";
                if ($this->connection->isTableExists($tableName) &&
                    $describe = $this->connection->describeTable($tableName)
                ) {
                    $columnNames = array_column($describe, 'COLUMN_NAME');
                    $ignoredColumnsBySubscription = array_filter(
                        $this->ignoredUpdateColumnsBySubscription[$changelog->getViewId()][$this->getTableName()] ?? []
                    );
                    $ignoredColumns = array_merge(
                        $this->ignoredUpdateColumns,
                        $ignoredColumnsBySubscription
                    );
                    $columnNames = array_diff($columnNames, $ignoredColumns);
                    if ($columnNames) {
                        $columns = [];
                        foreach ($columnNames as $columnName) {
                            $columns[] = sprintf(
                                'NOT(NEW.%1$s <=> OLD.%1$s)',
                                $this->connection->quoteIdentifier($columnName)
                            );
                        }
                        $trigger = sprintf(
                            "IF (%s) THEN %s END IF;",
                            implode(' OR ', $columns),
                            $trigger
                        );
                    }
                }
                break;
            case Trigger::EVENT_DELETE:
                $trigger = "INSERT IGNORE INTO %s (%s) VALUES (UNIX_TIMESTAMP(OLD.%s));";
                break;
            default:
                return '';
        }
        return sprintf(
            $trigger,
            $this->connection->quoteIdentifier($this->resource->getTableName($changelog->getName())),
            $this->connection->quoteIdentifier($changelog->getColumnName()),
            $this->connection->quoteIdentifier($this->getColumnName())
        );
    }
}
