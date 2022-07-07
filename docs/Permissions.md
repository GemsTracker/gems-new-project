# Permissions

Permission usage amounts to the following:

1. Add permission to the (module) `ConfigProvider`:
```php
    protected function getPermissions(): array
    {
        return [
            'gt.setup',
        ];
    }
```
2. Make sure `AclMiddleware` is added to routes that require permission, and add the permission to the route in `['options']['permission']`.

```php
    [
        'name' => 'setup.reception.index',
        'path' => '/setup/reception/index',
        'middleware' => [
            SecurityHeadersMiddleware::class,
            AclMiddleware::class,
            LegacyController::class,
        ],
        'allowed_methods' => ['GET'],
        'options' => [
            'controller' => \Gems_Default_ReceptionAction::class,
            'action' => 'index',
            'permission' => 'gt.setup',
        ]
    ],
```

3. In the project `ConfigProvider`, give permission to the relevant roles:
```php
    public function getRoles(): array
    {
        return [
            'role-1' => ['p-permission-1', 'p-permission-2', 'gt.setup'],
            'role-2' => ['p-permission-2', 'p-permission-3'],
            'role-3' => ['gt.setup'],
            'nologin' => ['gt.setup', 'p-permission-2'],
        ];
    }
```
