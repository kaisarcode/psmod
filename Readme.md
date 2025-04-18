# PrestaShop Module

## Description
This is a generic PrestaShop module. It is designed to be compatible with PrestaShop versions 1.7 and 8.x. The module provides a basic structure for developers to extend and customize according to their needs.

## Features
- Compatible with PrestaShop 1.7 and 8.x.
- Includes example front and admin controllers.
- Provides a dummy class for database interactions.
- Includes hooks for front-end and back-office integration.

## Installation
1. Clone or download the module into the `modules` directory of your PrestaShop installation.
2. Ensure the folder name is `psmod`.
3. Run the following command to generate the `vendor` folder and autoload files:
   ```bash
   composer dump-autoload
   ```
4. Log in to your PrestaShop back office.
5. Navigate to `Modules > Module Manager`.
6. Search for `PrestaShop Module` and click `Install`.

## Customizing the Module Name
To rename this module from 'PSMod' to your desired name, follow these steps:

1. **Rename Files and Folders**:
   - Rename the main module folder from `psmod` to your desired name (e.g., `mymodule`)
   - Rename these files:
     - `psmod.php` to `mymodule.php`
     - `classes/PsModDummy.php` to `classes/MyModuleDummy.php`
     - `controllers/admin/AdminPsModConfiguration.php` to `controllers/admin/AdminMyModuleConfiguration.php`
     - `src/PsModTools.php` to `src/MyModuleTools.php`

2. **Update File Contents**:
   - In `composer.json`:
     - Change `"name": "kaisarcode/psmod"` to `"kaisarcode/mymodule"`
     - Update namespace from `"KaisarCode\\PsMod\\"` to `"KaisarCode\\MyModule\\"`
   
   - In your renamed main module file:
     - Change the class name from `PsMod` to `MyModule`
     - Update `$this->name = 'psmod'` to `$this->name = 'mymodule'`
     - Update configuration keys from `PSMOD_` to `MYMODULE_`
   
   - In class files:
     - Update class names (e.g., `PsModDummy` to `MyModuleDummy`)
     - Update database table names and prefixes in SQL files
     - Update namespaces and class references
   
   - In config files:
     - Update module name in `config.xml` and `config_es.xml`
     - Update paths in controllers and templates

Remember to follow PrestaShop's naming conventions:
- Use CamelCase for class names
- Use lowercase for module folder and main file names
- Keep configuration keys in UPPERCASE with underscores

After renaming, run:
```bash
composer dump-autoload
```

## Development
### File Structure
- **`psmod.php`**: Main module file.
- **`config.xml` and `config_es.xml`**: Configuration files for the module.
- **`classes/`**: Contains example model classes like `PsModExampleModelClass` that show how to work with PrestaShop's ORM.
- **`controllers/`**: Includes front and admin controllers.
- **`src/`**: Contains example utility classes like `PsModExampleClass`. Shows how to use namespaces and custom classes.
- **`views/`**: Templates for admin and front-end views.
- **`sql/`**: SQL scripts for installation and uninstallation.

### Adding a New Feature
1. Create a new controller in the `controllers/front` or `controllers/admin` directory.
2. Add the necessary routes in the `hookModuleRoutes` method in `psmod.php`.
3. Create or update templates in the `views/templates` directory.
4. Register any new hooks in the `regHooks` method in `psmod.php`.

### Database Changes
1. Add your database schema changes in `sql/install.php`.
2. Update the `PsModDummy` class or create a new class in the `classes/` directory.

## Uninstallation
1. Log in to your PrestaShop back office.
2. Navigate to `Modules > Module Manager`.
3. Search for `PrestaShop Module` and click `Uninstall`.
4. Optionally, delete the module folder from the `modules` directory.

## Support
For any issues or questions, please contact KaisarCode at `support@kaisarcode.com`.

## License
This module is licensed under the Academic Free License (AFL 3.0).
