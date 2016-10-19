yii2-expression
==================

Вычисление математических выражений от **Miles Kaufmann**

# Установка

```
    $ php composer.phar require optimistex/yii2-expression
```

# Возможности

Данное расширение предоставляет класс **Expression** для обработки математических выражений.

Класс может принимать математическое выражение в текстовой строке и обработать его путем замены значений переменных и вычисление результатов математических функций и операций.

Он поддерживает неявное умножение, многомерные функции и вложенные функции.

Он может быть использован для проверки выражений из ненадежных источников. Это обеспечивает надежную проверку ошибок и вычисление только ограниченного набора функций.

Он может быть использован для создания графиков из выражений в виде формул.

# Примеры использования

## Синтаксис

```php
    <?php
      $m = new \optimistex\expression\Expression();
      
      // базовые вычисления:
      $result = $m->evaluate('2+2');
      
      // поддержка: порядок операций; круглые скобки; отрицание; встроенные функции
      $result = $m->evaluate('-8(5/2)^2*(1-sqrt(4))-8');
      
      // создание своих собственных переменных
      $m->evaluate('a = e^(ln(pi))');
      
      // или своих функций
      $m->evaluate('f(x,y) = x^2 + y^2 - 2x*y + 1');
      
      // а затем их использование
      $result = $m->evaluate('3*f(42,a)');
    ?>
```

## Описание

Используйте класс Expression когда вам нужно вычислять математические выражения из ненадежных источников. Вы можете определить свои собственные переменные и функции, которые хранятся в объекте. Попробуйте, это весело!

## Методы

$m->evalute($expr)
    
    Вычисляет выражение и возвращает результат. В случае возникновения ошибки,
    выдает предупреждение и возвращает ложь. 
    Если $ехрг является функцией, то возвращает истину в случае успеха.
    
$m->e($expr)
    
    Это синоним для $m->evaluate().
    
$m->vars()
    
    Возвращает ассоциированный массив всех пользовательских переменных и их значений.
        
$m->funcs()
    
    Возвращает массив всех пользовательских функций.

## Параметры

$m->suppress_errors

    Подавление ошибок.
    Установите true для отключения предупреждений при вычислении выражений.

$m->last_error

    Если последнее вычисление не удалась, то содержит строку с описанием ошибки. 
    (Полезно, когда включено подавление ошибок $m->suppress_errors).


# Информация об авторах

**Copyright 2005, Miles Kaufmann.**

Version 1.0

Оригинальный класс расположен тут: http://www.phpclasses.org/browse/file/11680.html, cred to Miles Kaufmann

Этот репозиторий был клонирован по двум причинам:

1. Чтобы разрешить загрузку кода без входа в phpclasses.org.
2. Чтобы добавить небольшие улучшения в коде.
# NAME
    Expression - safely evaluate math and boolean expressions
    
# SYNOPSIS
    <?
      include('expression.php');
      $e = new Expression();
      // basic evaluation:
      $result = $e->evaluate('2+2');
      // supports: order of operation; parentheses; negation; built-in functions
      $result = $e->evaluate('-8(5/2)^2*(1-sqrt(4))-8');
      // support of booleans
      $result = $e->evaluate('10 < 20 || 20 > 30 && 10 == 10');
      // support for strings and match (regexes need to be like the ones from php)
      $result = $e->evaluate('"foo,bar" =~ "/^([fo]+),(bar)$/"');
      // previous call will create $0 for whole match match and $1,$2 for groups
      $result = $e->evaluate('$2');
      // create your own variables
      $e->evaluate('a = e^(ln(pi))');
      // or functions
      $e->evaluate('f(x,y) = x^2 + y^2 - 2x*y + 1');
      // and then use them
      $result = $e->evaluate('3*f(42,a)');
      // create external functions
      $e->functions['foo'] = function() {
        return "foo";
      };
      // and use it
      $result = $e->evaluate('foo()');
    ?>
      
# DESCRIPTION
    Use the Expression class when you want to evaluate mathematical or boolean
    expressions  from untrusted sources.  You can define your own variables and
    functions, which are stored in the object.  Try it, it's fun!
    
    Based on http://www.phpclasses.org/browse/file/11680.html, cred to Miles Kaufmann
    
# METHODS
    $e->evalute($expr)
        Evaluates the expression and returns the result.  If an error occurs,
        prints a warning and returns false.  If $expr is a function assignment,
        returns true on success.
    
    $e->e($expr)
        A synonym for $e->evaluate().
    
    $e->vars()
        Returns an associative array of all user-defined variables and values.
        
    $e->funcs()
        Returns an array of all user-defined functions.

# PARAMETERS
    $e->suppress_errors
        Set to true to turn off warnings when evaluating expressions

    $e->last_error
        If the last evaluation failed, contains a string describing the error.
        (Useful when suppress_errors is on).

# AUTHORS INFORMATION
    Copyright 2005, Miles Kaufmann.
    Copyright 2016, Jakub Jankiewicz

# LICENSE
    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are
    met:
    
    1   Redistributions of source code must retain the above copyright
        notice, this list of conditions and the following disclaimer.
    2.  Redistributions in binary form must reproduce the above copyright
        notice, this list of conditions and the following disclaimer in the
        documentation and/or other materials provided with the distribution.
    3.  The name of the author may not be used to endorse or promote
        products derived from this software without specific prior written
        permission.
    
    THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
    IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT,
    INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
    HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
    STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
    ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.
