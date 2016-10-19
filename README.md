yii2-expression
==================

Вычисление математических и логических выражений

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
      $e = new \optimistex\expression\ExpressionCore();
      
      // базовые вычисления
      $result = $e->evaluate('2+2');
      
      // поддержка: порядок операций; круглые скобки; отрицание; встроенные функции
      $result = $e->evaluate('-8(5/2)^2*(1-sqrt(4))-8');
      
      // поддержка логических выражений
      $result = $e->evaluate('10 < 20 || 20 > 30 && 10 == 10');
      
      // поддержка сравнения строки с регулярным выражением (регулярные выражения должныбыть такими же как в PHP)
      $result = $e->evaluate('"foo,bar" =~ "/^([fo]+),(bar)$/"');
      
      // Предыдущий вызов создаст переменные $0 c общим результатом сравнения и $1, $2 с результатами групп
      $result = $e->evaluate('$2');
      
      // Создание собственных переменных
      $e->evaluate('a = e^(ln(pi))');
      // или функций
      $e->evaluate('f(x,y) = x^2 + y^2 - 2x*y + 1');
      // и их использование
      $result = $e->evaluate('3*f(42,a)');
      
      // Создание внешних функций
      $e->functions['foo'] = function() {
        return "foo";
      };
      // и их использование
      $result = $e->evaluate('foo()');
    ?>
```

## Описание

Используйте класс Expression когда вам нужно вычислять математические или логические выражения из ненадежных источников. 
Вы можете определить свои собственные переменные и функции, которые хранятся в объекте. Попробуйте, это весело!

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

    **Copyright 2005, Miles Kaufmann**
    **Copyright 2016, Jakub Jankiewicz**

Version 2.0

# Лицензия

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
