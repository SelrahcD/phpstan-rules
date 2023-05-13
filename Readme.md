# PHPStan rules

A set of useful PHPStan rules


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