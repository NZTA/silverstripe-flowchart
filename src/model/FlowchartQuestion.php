<?php
namespace ChTombleson\Flowchart\Models;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Security\Permission;
use ChTombleson\Flowchart\Models\FlowchartResponse;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class FlowchartQuestion extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
        'Info' => 'HTMLText',
        'Answer' => 'HTMLText',
        'QuestionHeading' => 'Varchar(255)', // The question heading, e.g. Question
        'AnswerHeading' => 'Varchar(255)', // The answer heading, e.g. Final answer
        'AnswerImageAfterContent' => 'Boolean',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Flowchart' => Flowchart::class,
        'AnswerImage' => Image::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'Responses' => FlowchartResponse::class,
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'ID' => 'ID',
        'ContentSummary' => 'Question summary',
        'InfoSummary' => 'Description summary',
        'AnswerHeading' => 'Answer heading',
        'AnswerSummary' => 'Answer summary'
    ];

    /**
     * @var string
     */
    private static $flowcharts_asset_folder = 'flowcharts';

    /**
     * @inheritdoc
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([
            'FlowchartID',
            'QuestionHeading',
            'Content',
            'Info',
            'AnswerHeading',
            'AnswerImage',
            'AnswerImageAfterContent',
            'Answer'
        ]);

        // Question heading
        $fields->addFieldToTab(
            'Root.Main',
            TextField::create('QuestionHeading', 'Question heading')
                ->setDescription('Optional, question heading, defaults to `Question` if left blank.')
        );

        // Question content
        $fields->addFieldToTab(
            'Root.Main',
            HTMLEditorField::create('Content', 'Question content')
                ->setRows(15)
                ->setDescription('The question content body')
        );

        // Info content
        $fields->addFieldToTab(
            'Root.Main',
            HTMLEditorField::create('Info', 'Question description content')
                ->setRows(15)
                ->setDescription(
                    'More information about the question, revealed via the question mark icon in the question heading.'
                )
        );

        // Answer heading
        $fields->addFieldToTab(
            'Root.Answer',
            TextField::create('AnswerHeading', 'Answer heading')
                ->setDescription('Optional, answer heading, e.g. `Final answer`, defaults to `Answer` if left blank.')
        );

        // Answer Image
        $featureImage = new UploadField('AnswerImage', 'Answer image');
        $featureImage->setAllowedFileCategories('image');
        $featureImage->setDescription(
            'Optional, image to display above the answer content. Accepted image-types: jpg, png, gif.'
        );
        $featureImage->setAllowedMaxFileNumber(1);
        $featureImage->setFolderName($this->FolderName());
        $fields->addFieldToTab('Root.Answer', $featureImage);

        $fields->addFieldToTab(
            'Root.Answer',
            CheckboxField::create('AnswerImageAfterContent', 'Show image after content?')
        );

        // Answer content
        $fields->addFieldToTab(
            'Root.Answer',
            HTMLEditorField::create('Answer', 'Answer content')
                ->setRows(15)
                ->setDescription('Interim or final answer content')
        );

        return $fields;
    }

    /**
     * @return string
     */
    public function Title()
    {
        return $this->getTitle();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return sprintf('%d - %s', $this->ID, $this->getContentSummary());
    }

    /**
     * @return string
     */
    public function getContentSummary()
    {
        return strip_tags($this->Content);
    }

    /**
     * @return string
     */
    public function getInfoSummary()
    {
        return strip_tags($this->Info);
    }

    /**
     * @return string
     */
    public function AnswerSummary()
    {
        return strip_tags($this->Answer);
    }

    /**
     * @return string
     */
    public function FolderName()
    {
        return static::$flowcharts_asset_folder;
    }

    /**
     * @inheritdoc
     */
    public function canView($member = null)
    {
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }

    /**
     * @inheritdoc
     */
    public function canEdit($member = null)
    {
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }

    /**
     * @return boolean
     */
    public function hasAnswer()
    {
        return !empty($this->Answer);
    }
}
