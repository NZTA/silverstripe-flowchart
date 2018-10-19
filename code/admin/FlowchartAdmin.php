<?php
namespace ChTombleson\Flowchart\Admins;

use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

class FlowchartAdmin extends ModelAdmin implements PermissionProvider
{
    private static $managed_models = [
        Flowchart::class,
    ];

    private static $url_segment = 'flowcharts';

    private static $menu_title = 'Flowcharts';

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

    public function canView($member = null)
    {
        return Permission::checkMember($member, ['VIEW_FLOWCHART']);
    }

    public function canCreate($member = null)
    {
        return Permission::checkMember($member, ['EDIT_FLOWCHART']);
    }

    public function canEdit($member = null)
    {
        return Permission::checkMember($member, ['EDIT_FLOWCHART']);
    }
}
