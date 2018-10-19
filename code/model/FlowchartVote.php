<?php
namespace ChTombleson\Flowchart\Models;

use SilverStripe\ORM\DataObject;
use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\Security\Permission;

class FlowchartVote extends DataObject
{
    private static $db = [
        'Value' => 'Int',
        'IP' => 'Varchar(50)'
    ];

    private static $has_one = [
        'Flowchart' => Flowchart::class,
    ];

    private static $summary_fields = [
        'ID',
        'Value'
    ];

    public function canCreate($member = null, $context = array())
    {
        return false;
    }

    public function canDelete($member = null)
    {
        return false;
    }

    public function canEdit($member = null)
    {
        return false;
    }

    public function canView($member = null)
    {
        return Permission::checkMember($member, ['VIEW_FLOWCHART']);
    }
}
