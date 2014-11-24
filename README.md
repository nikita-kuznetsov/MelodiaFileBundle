#MelodiaFileBundle

##Installatioin

Step 1: Download the Bundle
---------------------------

```json
// composer.json
repositories: [
  {
    "type": "vcs",
    "url": "https://github.com/Melodia/MelodiaFileBundle.git"
  }
]
```

```bash
$ composer require "melodia/file-bundle" "dev-master"
```

Step 2: Enable the Bundle and its dependencies
-------------------------

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Melodia\FileBundle\MelodiaFileBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Configure the Bundle and its dependencies
------------------------------------------------

```
knp_gaufrette:
    stream_wrapper: ~
    adapters:
        file_adapter:
            local:
                directory: %kernel.root_dir%/../web/uploads/files
    filesystems:
        files_fs:
            adapter:    file_adapter

vich_uploader:
    db_driver: orm
    gaufrette: true
    storage:   vich_uploader.storage.gaufrette
    mappings:
        file:
            uri_prefix:         /uploads/files
            upload_destination: files_fs
            directory_namer: melodia_file.directory_namer
```

Step 4: Import API router
-------------------------

```
melodia_file_api_:
    resource: "@MelodiaFileBundle/Resources/config/routing/api.yml"
    prefix: /api
```
