<?php
/************************************************************************
 * Extension Manager/Repository config file for ext "monchengladbach_tolle_seite".
 ************************************************************************/
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Mönchengladbach Tolle Seite',
    'description' => 'Base extension for project "monchengladbach_tolle_seite"',
    'category' => 'extension',
    'constraints' => array(
        'depends' => array(
            'typo3' => '7.6.0-8.99.99'
        ),
        'conflicts' => array(
        ),
    ),
    'autoload' => array(
        'psr-4' => array(
            'Wfp2GmbHCoKG\\MonchengladbachTolleSeite\\' => 'Classes'
        ),
    ),
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Benjamin Kott',
    'author_email' => 'benjamin.kott@outlook.com',
    'author_company' => 'wfp:2 GmbH &amp; Co. KG',
    'version' => '1.0.0',
);
