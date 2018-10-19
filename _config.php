<?php

use ChTombleson\Flowchart\Models\Flowchart;
use SilverStripe\View\Parsers\ShortcodeParser;

ShortcodeParser::get('default')->register(
    'flowchart',
    [Flowchart::class, 'shortcodeHandler']
);
