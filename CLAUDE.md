# Development Guidelines for GenericPharma

## Commands
- Build assets: `npm run dev`, `npm run watch`, `npm run prod`
- Run all tests: `php artisan test` or `./vendor/bin/phpunit`
- Run single test: `php artisan test --filter=TestName`
- Run specific suite: `php artisan test --testsuite=Unit`
- Clear cache: `php artisan cache:clear`
- Run migrations: `php artisan migrate`
- Create model with migration: `php artisan make:model ModelName -m`

## Code Style
- Follow PSR standards with Laravel-specific conventions
- Controllers extend BaseController and implement ControllerInterface
- Models use protected $guarded = ['id'] for mass assignment protection
- Four-space indentation, no trailing commas
- Function/method names use camelCase
- Class names use PascalCase
- Database tables/models: tables are plural, models are singular
- Group imports by PHP core, vendor packages, then application
- Error handling through Laravel's exception system
- Models should declare relationships and type hints
- Use Laravel's validation in controllers or form requests
- Typehint method parameters and return types when possible