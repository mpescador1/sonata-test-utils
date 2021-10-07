

# SonataTestUtils

Library providing traits for testing SonataAdminBundle. Provided in the tray:

    - SonataAdminFormTraitTest for testing creation and editing forms
entities.
    - SonataAdminMenuTraitTest for testing the vertical menu.
    - SonataAdminFlashMessagesTraitTest for testing popup messages.
    - SonataAdminActionsTraitTest for testing items in a tab "Actions"
    - SonataAdminListBatchActionsTraitTest for testing common actions on
entity list page.
    - SonataAdminTabTraitTest for testing tabs.

To run the tests, you first need to install the dependencies via composer

`docker-compose run php composer install`

After running the tests

`docker-compose run php vendor/bin/phpunit tests/`
