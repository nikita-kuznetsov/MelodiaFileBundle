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

Step 3: Configure dependencies of the Bundle
------------------------------------------------

```yaml
# app/config/config.yml

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

# Common configuration for all Melodia API bundles

nelmio_api_doc: ~

jms_serializer:
    metadata:
        auto_detection: true
    property_naming:
        separator:  ~
        lower_case: false

fos_rest:
    param_fetcher_listener: force
    view:
        view_response_listener: force
        jsonp_handler: ~
    serializer:
        serialize_null: true
    routing_loader:
        default_format: json
        include_format: false
    format_listener:
      rules:
        - { path: '^/api', priorities: ['json'] }
        - { path: '^/', priorities: ['html'] }

stof_doctrine_extensions:
    orm:
        default:
            softdeleteable: true
```

Step 4: Import API router
-------------------------

```yaml
# app/config/routing.yml

melodia_file_api_:
    resource: "@MelodiaFileBundle/Resources/config/routing/api.yml"
    prefix: /api
```
