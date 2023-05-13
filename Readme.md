# PHPStan rules

A set of useful PHPStan rules


## Installation

To use this extension, require it in [Composer](https://getcomposer.org/):

```
composer require --dev selrahcd/phpstan-rules
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include extension.neon in your project's PHPStan config:

```
includes:
    - vendor/phpstan/phpstan-mockery/extension.neon
```
</details>



## Rules

## DisallowIsArrayFunctionCall

A rule that prevents from using `\is_array`.


## CountFuncCallUsage

A configurable rule that prevents from using more function calls than we already do.

Configure:
```
parameters:
    watched_funcCalls:
        watched:
            '\is_array': 4
```

Will warn you if you start using more than 4 calls to `\is_array`.
You can add as many functions to watch as you want.


