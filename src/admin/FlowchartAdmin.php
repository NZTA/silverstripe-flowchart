<?php
namespace ChTombleson\Flowchart\Admins;

use SilverStripe\Security\Member;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Security\Permission;
use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\Security\PermissionProvider;

class FlowchartAdmin extends ModelAdmin implements PermissionProvider
{
    /**
     * @var array
     */
    private static $managed_models = [
        Flowchart::class,
    ];

    /**
     * @var string
     */
    private static $url_segment = 'flowcharts';

    /**
     * @var string
     */
    private static $menu_title = 'Flowcharts';

    /**
     * @return array
     */
    public function providePermissions()
    {
        return array(
            'VIEW_FLOWCHART' => array(
                'name' => _t('Comment.PERMISSION_CREATE_DESCRIPTION', 'Can view and export Flowcharts'),
                'category' => _t('Permissions.CONTENT_CATEGORY', 'Content Permissions'),
                'help' => _t(
                    'Comment.PERMISSION_CREATE_HELP',
                    'Permission required to view and export Flowcharts from the CMS.'
                )
            ),
            'EDIT_FLOWCHART' => array(
                'name' => _t('Comment.PERMISSION_CREATE_DESCRIPTION', 'Can edit and create Flowcharts'),
                'category' => _t('Permissions.CONTENT_CATEGORY', 'Content Permissions'),
                'help' => _t(
                    'Comment.PERMISSION_CREATE_HELP',
                    'Permission required to edit and create Flowcharts from the CMS.'
                )
            ),
        );
    }

    /**
     * @inheritdoc
     */
    public function canView($member = null)
    {
        return Permission::checkMember($member, ['VIEW_FLOWCHART']);
    }

    /**
     * @inheritdoc
     */
    public function canCreate($member = null)
    {
        return Permission::checkMember($member, ['EDIT_FLOWCHART']);
    }

    /**
     * @inheritdoc
     */
    public function canEdit($member = null)
    {
        return Permission::checkMember($member, ['EDIT_FLOWCHART']);
    }
}
