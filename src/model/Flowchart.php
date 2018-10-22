<?php
namespace ChTombleson\Flowchart\Models;

use SilverStripe\View\SSViewer;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\View\Requirements;
use ChTombleson\Flowchart\Models\FlowchartVote;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Security\Permission;
use SilverStripe\Security\SecurityToken;
use ChTombleson\Flowchart\Models\FlowchartFeedback;
use ChTombleson\Flowchart\Models\FlowchartQuestion;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldExportButton;

class Flowchart extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(100)',
        'VotingDisabled' => 'Boolean',
        'FeedbackDisabled' => 'Boolean',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Questions' => FlowchartQuestion::class,
        'Feedback' => FlowchartFeedback::class,
        'Votes' => FlowchartVote::class,
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title',
        'Shortcode' => 'Shortcode',
    ];

    /**
     * @inheritdoc
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['Feedback', 'Votes']);

        if (!$this->FeedbackDisabled) {
            // Configure the Feedback Gridfield for export
            $feedbackExportButton = new GridFieldExportButton();
            $feedbackExportButton->setExportColumns(['ID', 'Created', 'IP', 'Feedback']);

            $fields->addFieldsToTab(
                'Root.Feedback',
                [
                    GridField::create(
                        'Feedback',
                        'Feedback',
                        $this->Feedback(),
                        GridFieldConfig_Base::create()
                            ->addComponents(
                                $feedbackExportButton
                            )
                    ),
                ]
            );
        }

        if (!$this->VotingDisabled) {
            // Configure the Voting Gridfield for export
            $votingExportButton = new GridFieldExportButton();
            $votingExportButton->setExportColumns(['ID', 'Created', 'IP', 'Value']);

            $fields->addFieldsToTab(
                'Root.Votes',
                [
                    GridField::create(
                        'Vote',
                        'Vote',
                        $this->Votes(),
                        GridFieldConfig_Base::create()
                            ->addComponents(
                                $votingExportButton
                            )
                    ),
                ]
            );

            $fields->addFieldsToTab(
                'Root.Main',
                [
                    ReadonlyField::create('TotalVotes', 'Total Votes', $this->Votes()->count()),
                    ReadonlyField::Create('AvergageVote', 'Average Vote', $this->averageVote()),
                ]
            );
        }

        if ($this->ID) {
            $fields->addFieldToTab(
                'Root.Main',
                ReadonlyField::create('ShortCode', 'Shortcode', $this->getShortcode())
            );
        }

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function canCreate($member = null, $context = array())
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

    /**
     * @return string
     */
    public function getShortcode()
    {
        return '[flowchart id="' . $this->ID . '"]';
    }

    /**
     * @return integer
     */
    public function averageVote()
    {
        $total = 0;

        foreach ($this->Votes() as $vote) {
            $total += $vote->Value;
        }

        if ($total == 0) {
            return 0;
        }

        return $total / $this->Votes()->count();
    }

    /**
     * @return string
     */
    public static function shortcodeHandler($arguments, $content = null, $parser = null, $tagName)
    {
        if (!isset($arguments['id'])) {
            return null;
        }

        $flowchart = Flowchart::get()->filter('ID', $arguments['id'])->first();

        if (!$flowchart) {
            return null;
        }

        Requirements::css('chtombleson/silverstripe-flowchart:css/flowchart.css');

        // Requirements::javascript(FRAMEWORK_DIR . '/thirdparty/jquery/jquery.min.js');
        Requirements::javascript('chtombleson/silverstripe-flowchart:js/flowchart.js');

        // Security tokens for form
        $securityToken = new SecurityToken('Flowchart_' . $flowchart->ID);

        return SSViewer::execute_template(
            'Flowchart',
            ArrayData::create([
                'Flowchart' => $flowchart,
                'SecurityToken' => $securityToken->getValue(),
            ])
        );
    }
}
