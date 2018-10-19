<?php
namespace ChTombleson\Flowchart\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Security\Permission;
use ChTombleson\Flowchart\Models\FlowchartQuestion;

class FlowchartResponse extends DataObject
{
    private static $db = [
        'Label' => 'Varchar(120)', // Yes, No, Maybe
    ];

    private static $has_one = [
        'PreviousQuestion' => FlowchartQuestion::class,
        'NextQuestion' => FlowchartQuestion::class,
    ];

    private static $belongs_many_many = [
        'Questions' => FlowchartQuestion::class
    ];

    private static $summary_fields = [
        'Label' => 'Label',
        'NextQuestionNice' => 'Linked question'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([ 'Label', 'Questions', 'PreviousQuestionID', 'NextQuestionID' ]);

        $fields->addFieldToTab(
            'Root.Main',
            TextField::create('Label', 'Response label')
                ->setDescription(
                    'The response button label, e.g. Yes, No. This displays on the button linking to the next question.'
                )
        );

        if ($this->isInDB()) {
            $fields->insertAfter(
                'Label',
                DropdownField::create(
                    'NextQuestionID',
                    'Next question',
                    FlowchartQuestion::get()
                        ->exclude('ID', $this->PreviousQuestionID)
                        ->map('ID', 'Title'),
                    $this->NextQuestionID
                )
                    ->setEmptyString('')
                    ->setDescription('Optional, link the response button to the next question')
            );
        } else {
            $fields->insertAfter(
                'Label',
                LiteralField::create(
                    'NextQuestionMessage',
                    'You can link to the next question once the Response label has been saved for the first time'
                )
            );
        }

        return $fields;
    }

    public function Title()
    {
        return $this->getTitle();
    }

    public function getTitle()
    {
        if ($this->NextQuestionID) {
            return $this->Label;
        } else {
            return sprintf('%s - %s', $this->Label, $this->Heading);
        }
    }

    public function getNextQuestionNice()
    {
        if ($this->NextQuestionID) {
            return $this->NextQuestion()->Title();
        } else {
            return 'none';
        }
    }

    public function validate()
    {
        $result = parent::validate();

        if (empty($this->Label)) {
            $result->error('Label is required');
        }

        return $result;
    }

    public function canView($member = null)
    {
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }

    public function canEdit($member = null)
    {
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }
}
