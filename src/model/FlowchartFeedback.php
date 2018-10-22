<?php
namespace ChTombleson\Flowchart\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class FlowchartFeedback extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'IP' => 'Varchar(50)',
        'Feedback' => 'Text',
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
        'Feedback'
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
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }
}
