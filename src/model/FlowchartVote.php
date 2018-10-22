<?php
namespace ChTombleson\Flowchart\Models;

use SilverStripe\ORM\DataObject;
use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\Security\Permission;

class FlowchartVote extends DataObject
{

    /**
     * @var array
     */
    private static $db = [
        'Value' => 'Int',
        'IP' => 'Varchar(50)'
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Flowchart' => Flowchart::class,
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'ID',
        'Value'
    ];

    /**
     * @inheritdoc
     */
    public function canCreate($member = null, $context = array())
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function canDelete($member = null)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function canEdit($member = null)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function canView($member = null)
    {
        return Permission::checkMember($member, ['VIEW_FLOWCHART']);
    }
}
