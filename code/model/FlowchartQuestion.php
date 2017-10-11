<?php

class FlowchartQuestion extends DataObject
{
    private static $db = [
        'Content' => 'HTMLText',
        'Info' => 'HTMLText',
        'Answer' => 'HTMLText',
        'QuestionHeading' => 'Varchar(255)', // The question heading, e.g. Question
        'AnswerHeading' => 'Varchar(255)', // The answer heading, e.g. Final answer
        'AnswerImageAfterContent' => 'Boolean'
    ];

    private static $has_one = [
        'Flowchart' => 'Flowchart',
        'AnswerImage' => 'Image'
    ];

    private static $many_many = [
        'Responses' => 'FlowchartResponse'
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'ContentSummary' => 'Question summary',
        'InfoSummary' => 'Description summary',
        'AnswerHeading' => 'Answer heading',
        'AnswerSummary' => 'Answer summary'
    ];

    private static $flowcharts_asset_folder = 'flowcharts';

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

    public function Title()
    {
        return $this->getTitle();
    }

    public function getTitle()
    {
        return sprintf('%d - %s', $this->ID, $this->ContentSummary());
    }

    public function ContentSummary()
    {
        return strip_tags($this->Content);
    }

    public function InfoSummary()
    {
        return strip_tags($this->Info);
    }

    public function AnswerSummary()
    {
        return strip_tags($this->Answer);
    }

    public function FolderName()
    {
        return static::$flowcharts_asset_folder;
    }

    public function Link($action = 'show')
    {
        if ($this->FlowchartID && $this->Flowchart()->getCurrentPage()) {
            return $this->Flowchart()->getCurrentPage()->Link();
        }
        return null;
    }

    protected function getSearchableFields()
    {
        return implode(self::$searchable_fields, ',');
    }

    public function canView($member = null)
    {
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }

    public function canEdit($member = null)
    {
        return (Permission::checkMember($member, array('VIEW_FLOWCHART')));
    }

    public function hasAnswer()
    {
        return !empty($this->Answer);
    }
}
