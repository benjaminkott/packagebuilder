# Sitepackage

A Sitepackage is a extension for TYPO3 that contains all relevant configurations 
for a website. In this article, you'll learn how to write a powerful 
**Sitepackage** that can be used to provide themes, templates, configuration and
handle dependencies for your **TYPO3** website. You'll learn shortcuts, clever 
ways to extend templates and how to reuse template code.

> In the wild you will find different names for this technique to bundle 
> configuration like **Base Extension**, **Template Extension** or 
> **Site Foundation**.

## Basic Knowledge

Prior to 2014 it was considered as best practice to store configuration for
TYPO3 websites directly in the database or in the userspace most known as
**fileadmin**. Even if was possible before to use a **Sitepackage** it was only
officially introduced as best practive in 2014 with the release of TYPO3 6.2 and
the complete rework of the 
[Introduction Package](https://extensions.typo3.org/extension/introduction/).

Storing the configuration in the database or in the userspace had several 
downsides and was not compatible with modern **continous integration** and 
**deployment** workflows. A quick overview of common problems a **Sitepackage**
solves.

1. **Configuration files indexed by the File Abstraction Layer.**  
   All files you upload into the fileadmin are automatically indexed from the
   file abstraction layer if not disabled. The only way FAL recognizes file
   changes or deletions is if they are done using the backend or the API
   directly. Files that are changed outside can cause fatal error, if this file
   is referenced and deleted for example via SFTP.
2. **Fileadmin is userspace.**  
   Configuration files within the fileadmin are usually edited and maintained
   by an editor who mostly does not have the knowledge to do it properly. It´s
   better to protect the editor from accidentally breaking a part of the
   internet.
3. **Configurations always should be under version control.**  
   Having the configuration files in the userspace is a strong indicator for a
   missing version control.
4. **Dependencies were not considered.**  
   Without dependency management you have no idea which extensions and which 
   version is needed to maintain a running website. If your website needs for
   example the Extensions **Bootstrap Package**, **News** and **Seo Basics** 

## Dependencies
The Extension Manager is your friend.

For example sitepackage extension could require Bootstrap Package, News & Seo 
Basics in the newest available version on installation.

Your TypoScriptSetup and Constants are savedin the template record.
The setup and constant field of a template record is the last possibility to 
override the TypoScript configuration of your TYPO3 page within that record.


Template Record  
Static template sorting.

Instead of putting your setup and constants directly in the template record or 
include it there its more easy to make use of Static Templates.


Your configuration is @!?#&%§ public accessible.  
Most users that put their configuration files in the fileadmin folder, forget to
protect it from external access.

Want so see how not to do it?  
Bing `fileadmin setup.txt` or `fileadmin setup.ts`

You don´t want your configuration to be public accessible!  
NEVER. EVER. EVERERERERER!

## Comparison ##

|                                              | Fileadmin | Sitepackage |
| -------------------------------------------- | --------- | ----------- |
| Config files editable through file module    | x         |             |
| Config files not accessible by editors       |           | x           |
| Config files are protected *                 |           | x           |
| Autoload PageTS                              |           | x           |
| Autoload TypoScript                          |           | x           |
| Static TypoScript Template                   |           | x           |
| Dependency Management                        |           | x           |
| Distrubution through TER possible            |           | x           |
| Deployment through Extension Manager         |           | x           |
| Clean Version Control possible               |           | x           |
   
## Structure   

|                                  | Description                         |
| -------------------------------- | ----------------------------------- |
| ![folder] Classes                | Controller, ViewHelpers             |
| ![folder] Configuration          | TypoScript, PageTS, TCA             |
| ![folder] Documentation          | Manual in reStructuredText format   |
| ![folder] Initialisation         | Data / Assets for Fileadmin         |
| ![folder] Resources              | Templates, Images, CSS, JS, ...     |
| ![file] ext_conf_template.txt    | Extension Manager Configuration     |
| ![file] ext_emconf.php           | Extension Configuration             |
| ![file] ext_icon.png             | Extension Icon                      |
| ![file] ext_localconf.php        | Executed in FE and BE               |
| ![file] ext_tables.php           | Executed in BE                      |
| ![file] ext_tables.sql           | Database Schema                     |

### Classes

|                                  | Description                         |
| -------------------------------- | ----------------------------------- |
| ![folder] Controller             | MVC Controller                      |
| ![folder] Domain                 |                                     |
| -- ![folder] Model               | MVC Domain Model                    |
| ---- ![folder] Repository        | Data Repositorys                    |
| ![folder] Hook                   | Core Manipulation Scripts           |
| ![folder] ViewHelpers            | Custom Fluid ViewHelper             |

### Configuration

|                                  | Description                         |
| -------------------------------- | ----------------------------------- |
| ![folder] PageTS                 | PageTS for your Website             | 
| -- ![file] RTE.txt               |                                     |
| -- ![file] TCEFORM.txt           |                                     |
| ![folder] TCA                    | TCA Definition for your own tables  |
| -- ![folder] Overrides           | TCA Overrides for existing tables   |
| ---- ![file] tt_content.php      |                                     |
| -- ![file] tx_myext_record.php   |                                     |
| ![folder] TypoScript             | TypoScript Static Template          |
| -- ![file] setup.txt             |                                     |
| -- ![file] constants.txt         |                                     |
| ![file] .htaccess                | Protect your stuff!                 |

### Documentation

docs.typo3.org/typo3cms/CoreApiReference/ExtensionArchitecture/Documentation/Index.html

Call for help!  
documentation@typo3.org  
twitter.com/T3docTeam

Documentation is hard and we are all really bad in this. It helps you to 
remember and others to understand what and how things work.

### Initialization

|                                  | Description                                                                        |
| -------------------------------- | ---------------------------------------------------------------------------------- |
| ![folder] Files                  | Files added here, they will be copied to fileadmin/extension/ during installation  | 
| -- ![folder] Images              |                                                                                    |
| ---- ![file] Example_1.png       |                                                                                    |
| ---- ![file] Example_2.jpg       |                                                                                    |
| ---- ![file] Example_3.txt       |                                                                                    |
| ![file] data.t3d                 | Export of your database it will be imported at page root level during installation |

### Resources

|                                  | Description                          |
| -------------------------------- | ------------------------------------ |
| ![folder] Private                | Private protected files              |
| -- ![folder] Languages           | XLIFF/XML files for localized labels |
| -- ![folder] Layouts             | Main layouts for the views           |
| -- ![folder] Partials            | Partial templates for repetitive use |
| -- ![folder] Templates           | Templates for the views              |
| -- ![file] .htaccess             | Protect your stuff!                  |
| ![folder] Public                 | Public accessible files              |
| -- ![folder] Css                 | Any CSS file used by the extension   |
| -- ![folder] Images              | Any images used by the extension     |
| -- ![folder] JavaScript          | Any JS file used by the extension    |

## Minimal Setup

### Example Package ###
Just enough to get the party starting.

1. Extension
2. Dependency to Fluid Styled Content
3. Basic TypoScript
4. Basic PageTS
5. Fluid Template
6. CSS file
7. JavaScript file

typo3conf/ext/example_package  
Extension Directory -> Your Extension

Head to the directory and create a folder with a name of your choice like
`example_package`. This will be the container of your sitepackage and also 
your extension key.

| Name                       | Description                     |
| -------------------------- | ------------------------------- |
| ![folder] Configuration    | TypoScript, PageTS, TCA         |
| ![folder] Resources        | Templates, Images, CSS, JS, ... |
| ![file] ext_emconf.php     | Extension Manager Configuration |
| ![file] ext_icon.png       | Extension Icon                  |
| ![file] ext_localconf.php  | Executed in FE and BE           |
| ![file] ext_tables.php     | Executed in BE                  |


### ./ext_emconf.php ###

Definition of extension properties.

Title, category, dependencies, conflicts etc. Used by the Extension Manager. 
If this file is not present the EM will not find the extension.

```php
<?php

// File:
// ./ext_emconf.php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Example Package',
    'description' => '',
    'category' => 'templates',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'John Doe',
    'author_email' => 'john.doe@example.com',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.7.99',
            'fluid_styled_content' => '8.7.0-8.7.99',
        ],
        'conflicts' => [
            'fluidpages' => '*',
            'themes' => '*',
        ],
    ],
];
```

Title  
A title for your extension.

Category  
Type of category the extension should be listed in. Use template for general
usage and distribution for a listing in the distributions section.

Dependencies  
Additional Extensions, for example like a news extension or a specific core
version.

Conflicts  
Known issues with other extensions can be placed here to avoid that these
extension are running parallel in your system.

![file] ext_icon.png
![folder] typo3conf / ![folder] ext / ![folder] example_package

### ./ext_localconf.php ###

```php
<?php

// File:
// ./ext_localconf.php

defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'
    . $_EXTKEY . '/Configuration/PageTS/TCEFORM.txt">'
);
```

You should really know what you are doing before adding global PageTS.
After adding its set for all Websites in your TYPO3 instance.

### ./ext_tables.php ###

```php
<?php

// File:
// ./ext_tables.php

defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    // Extension Key
    $_EXTKEY,
     // Path to setup.typoscript and constants.typoscript
    'Configuration/TypoScript',
    // Title in the selector box
    'Example Package'
);
```

### ./Configuration ###

|                                  | Description                          |
| -------------------------------- | ------------------------------------ |
| ![folder] PageTS                 |                                      |
| -- ![file] TCEFORM.txt           | Basic PageTS                         |
| ![folder] TypoScript             |                                      |
| -- ![file] setup.txt             | Basic Setup                          |
| -- ![file] constants.txt         | Basic Constants                      |

### ./Configuration/PageTS/TCEFORM.txt ###

```typoscript
## TCEFORM
TCEFORM {
    pages {
        layout.disabled = 1
    }
    tt_content {
        // NOBODY wants or should edit this
        // really, let them disappear
        table_bgColor.disabled = 1
        table_border.disabled = 1
        table_cellspacing.disabled = 1
        table_cellpadding.disabled = 1
        pi_flexform.table.sDEF {
            acctables_nostyles.disabled = 1
            acctables_tableclass.disabled = 1
        }
    }
}
```

You should really know what you are doing before adding global PageTS like in 
this example. After adding its set for all Websites in your TYPO3 instance.

See ext_localconf.php as example how to add PageTS globally.

### ./Configuration/TypoScript/constants.typoscript ###

```typoscript
## DEPENDENCIES
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fluid_styled_content/Configuration/constants.txt">

## PAGE
page {
    template {
        # cat=example package: advanced/100/100; type=string; label=Layout Root Path: Path to layouts
        layoutRootPath = EXT:example_package/Resources/Private/Layouts/
        # cat=example package: advanced/100/110; type=string; label=Partial Root Path: Path to partials
        partialRootPath = EXT:example_package/Resources/Private/Partials/
        # cat=example package: advanced/100/120; type=string; label=Template Root Path: Path to templates
        templateRootPath = EXT:example_package/Resources/Private/Templates/
    }
}
```

Use TypoScript Constants and configuration for the Constant Editor to have your 
Sitepackage ready for multisite usage or to easily make adjustments to your 
website.

### ./Configuration/TypoScript/setup.typoscript ###

Load the needed TypoScript from dependant extensions to avoid sorting of static 
tempates in the template record.

To avoid unnecessary work we are depending `fluid_styled_content` for the 
content rendering in this example.

```typoscript
## File:
## ./Configuration/TypoScript/setup.typoscript

## DEPENDENCIES
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fluid_styled_content/Configuration/setup.txt">
```

Content selection for use in Fluid cObject ViewHelper with data pass-through.

```typoscript
## File:
## ./Configuration/TypoScript/setup.typoscript

## CONTENT SELECTION
lib.dynamicContent = COA
lib.dynamicContent {
    5 = LOAD_REGISTER
    5 {
        colPos.cObject = TEXT
        colPos.cObject {
            field = colPos
            ifEmpty.cObject = TEXT
            ifEmpty.cObject {
                value.current = 1
                ifEmpty = 0
            }
        }
    }
    20 = CONTENT
    20 {
        table = tt_content
        select {
            includeRecordsWithoutDefaultTranslation = 1
            orderBy = sorting
            where = {#colPos}={register:colPos}
            where.insertData = 1
        }
    }
    90 = RESTORE_REGISTER
}
```

```html
<!-- How to use lib.dynamicContent in your fluidtemplate -->
<f:cObject typoscriptObjectPath="lib.dynamicContent" data="{colPos: '0'}" />
```

Page-Templates based on Fluid for super easy templating.

BackendLayouts used to select the Template.

Include CSS and JavaScript files with the EXT: notation from the extension directory.

```typoscript
## File:
## ./Configuration/TypoScript/setup.typoscript

## PAGE
page = PAGE
page {
    typeNum = 0
    10 = FLUIDTEMPLATE
    10 {
        file.stdWrap.cObject = CASE
        file.stdWrap.cObject {
            key.data = levelfield:-1, backend_layout_next_level, slide
            key.override.field = backend_layout
            default = TEXT
            default.value = {$page.template.templateRootPath}Default.html
            default.insertData = 1
        }
        partialRootPath = {$page.template.partialRootPath}
        layoutRootPath = {$page.template.layoutRootPath}
    }
    includeCSS {
        main = EXT:example_package/Resources/Public/Css/main.css
    }
    includeJSFooterlibs {
        main = EXT:example_package/Resources/Public/JavaScript/main.js
    }
}
```

### ./Resources ###

|                                  | Description                          |
| -------------------------------- | ------------------------------------ |
| ![folder] Private                |                                      |
| -- ![folder] Layouts             |                                      |
| ---- ![file] Default.html        | Default Layout                       |
| -- ![folder] Templates           |                                      |
| ---- ![file] Default.html        | Default Template                     |
| ![folder] Public                 |                                      |
| -- ![folder] Css                 |                                      |
| ---- ![file] main.css            | Basic CSS                            |
| -- ![folder] JavaScript          |                                      |
| ---- ![file] main.js             | Basic JavaScript                     |

### ./Resources/Private/Layouts/Default.html ###

Render the Section

```html
<!-- File: ./Resources/Private/Layouts/Default.html -->
<f:render section="Main" />
```

### ./Resources/Private/Templates/Default.html ###

```html
<!-- File: ./Resources/Private/Templates/Default.html -->
<f:layout name="Default" />
<f:section name="Main">
    HELLO WORLD
    <f:cObject typoscriptObjectPath="lib.dynamicContent" data="{colPos: '0'}" />
</f:section>
```

### ./Configuration/Public/Css/main.css ###

```css
/* FILE: ./Resources/Public/Css/main.css */
body {
    background-color: #ffffff;
    color: #333333;
}
```

### ./Configuration/Public/JavaScript/main.js ###

```javascript
/* FILE: ./Resources/Public/JavaScript/main.js */
console.log('I LOVE TYPO3');
```

That is everything you need!
A minimum configuration with 11 files in total.

## Resources

- [Bootstrap Package](https://github.com/benjaminkott/bootstrap_package)
- [Distribution Management](https://wiki.typo3.org/Blueprints/DistributionManagement)
- [Extension Architecture](https://docs.typo3.org/typo3cms/CoreApiReference/ExtensionArchitecture/Index.html)


[file]: https://www.sitepackagebuilder.com/bundles/sitepackagegenerator/images/file.svg
[folder]: https://www.sitepackagebuilder.com/bundles/sitepackagegenerator/images/folder.svg
