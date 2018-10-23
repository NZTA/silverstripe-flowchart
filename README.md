# Silverstripe Flowchart

A module to create an interactive flowchart.

## Installation

    $ composer require chtombleson/silverstripe-flowchart

For SilverStripe 3 use the 3x branch.

## Usage

Once installed and the dev/build has been run. In the CMS there should be a Flowcharts
section. Where you can create a new Flowchart and add questions.

### Flowchart options

  * Voting Disabled - Disables rating of flowchart from 1 to 5.
  * Feedback Disabled - Disables feedback for the flowchart.

### Question options

  * Question Heading - Optional the default is Question 1 etc.
  * Question Description Content - Optional used for additional info about the question.
  * Answer - The final outcome of the flowchart.

### Responses

Responses are used to link question to other questions.

  * Response Label - For example Yes or No.
  * Next Question - The question to go to if that response is selected.
