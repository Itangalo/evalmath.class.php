<?php

use PHPUnit\Framework\TestCase;


class ExpressionTest extends TestCase
{
    public function arrayTest($array)
    {
        $expr = new Expression();
        for ($i = 0; $i < count($array); $i++) {
            $result = $expr->evaluate($array[$i]);
            $this->assertEquals($result, eval("return " . $array[$i] . ";"));
        }
    }
    /*
    public function testTest() {
        $expression = '2+2*2-2/2 >= 2*2+-2/2*2';
        $expr = new Expression();
        echo json_encode($expr->evaluate($expression)) ? "true" : "false";
    }
    */
    // -------------------------------------------------------------------------
    public function testIntegers()
    {
        $ints = array("100", "3124123", (string)PHP_INT_MAX, "-1000");
        $expr = new Expression();
        for ($i = 0; $i < count($ints); $i++) {
            $result = $expr->evaluate($ints[$i]);
            $this->assertEquals($result, intval($ints[$i]));
        }

    }

    // -------------------------------------------------------------------------
    public function testFloats()
    {
        $ints = array("10.10", "0.01", ".1", "1.", "-100.100", "1.10e2", "-0.10e10");
        $expr = new Expression();
        for ($i = 0; $i < count($ints); $i++) {
            $result = $expr->evaluate($ints[$i]);
            $this->assertEquals($result, floatval($ints[$i]));
        }
    }

    // -------------------------------------------------------------------------
    public function testAritmeticOperators()
    {
        $expressions = array("20+20", "-20+20", "-0.1+0.1", ".1+.1", "1.+1.",
            "0.1+(-0.1)", "20*20", "-20*20", "20*(-20)", "1.*1.",
            ".1*.1", "20-20", "-20-20", "20/20", "-20/20", "10%20",
            "10%9", "20%9");
        $this->arrayTest($expressions);
        try {
            $expr = new Expression();
            $expr->evaluate('10/0');
            $this->assertTrue(false); // will fail if evaluate don't throw exception
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

    // -------------------------------------------------------------------------
    public function testSemicolon()
    {
        $expr = new Expression();
        $result = $expr->evaluate("10+10;");
        $this->assertEquals($result, "20");
    }

    // -------------------------------------------------------------------------
    public function testBooleanComparators()
    {
        $expressions = array("10 == 10", "10 == 20", "0.1 == 0.1", "0.1 == 0.2",
            "10 != 10", "20 != 10", "0.1 != 0.1", "0.1 != 0.2",
            "10 < 10", "20 < 10", "10 < 20", "0.1 < 0.2",
            "0.2 < 0.1", "0.1 < 0.1", "10 > 10", "20 > 10",
            "10 > 20", "0.1 > 0.2", "0.2 > 0.1", "0.1 > 0.1",
            "10 <= 10", "20 <= 10", "10 <= 20", "0.1 <= 0.2",
            "0.2 <= 0.1", "0.1 <= 0.1", "10 >= 10", "20 >= 10",
            "10 >= 20", "0.1 >= 0.2", "0.2 >= 0.1", "0.1 >= 0.1");
        $this->arrayTest($expressions);
    }

    // -------------------------------------------------------------------------
    public function testBooleanOperators()
    {
        $expressions = array("10 == 10 && 10 == 10", "10 != 10 && 10 != 10",
            "10 == 20 && 10 == 10", "10 == 10 && 10 == 20",
            "0.1 == 0.1 && 0.1 == 0.1", "0.1 == 0.2 && 0.1 == 0.1",
            "0.1 == 0.1 && 0.1 == 0.2", "10 == 10 || 10 == 10",
            "10 == 20 || 10 == 10", "10 == 10 || 10 == 20",
            "0.1 == 0.1 || 0.1 == 0.1", "0.1 == 0.2 || 0.1 == 0.1",
            "0.1 == 0.1 || 0.1 == 0.2");
        $this->arrayTest($expressions);
        $expressions = array('("foo" == "foo") && "a" || "b"' => "a",
            '("foo" == "bar") && "a" || "b"' => "b");
        $expr = new Expression();
        foreach ($expressions as $expression => $value) {
            $result = $expr->evaluate($expression);
            $this->assertEquals($result, $value);
        }
    }
    // -------------------------------------------------------------------------
    public function testPriorityOperands() {
        $data = [
            '2+2*2' => 6,
            '2-2+2*2+2/2*-1+2' => 5,
            '2+1 > 2+2' => false,
            '2+1 < 2+2' => true,
            '2+2*2-2/2 >= 2*2+-2/2*2' => true,
        ];
        $expr = new Expression();
        foreach ($data as $formula => $result) {
            $this->assertEquals($expr->evaluate($formula), $result);
        }
    }
    // -------------------------------------------------------------------------
    public function testKeywords()
    {
        $expressions = array("1 == true", "true == true", "false == false",
            "false != true", "null == null", "null != true");
        $expr = new Expression();
        foreach ($expressions as $expression) {
            $result = $expr->evaluate($expression);
            $this->assertTrue((bool)$result);
        }
        $expressions = array("foo = true" => true, "foo = false" => false, "foo = null" => null);
        foreach ($expressions as $expression => $value) {
            $expr = new Expression();
            $expr->evaluate($expression);
            $result = $expr->evaluate("foo");
            $this->assertEquals($result, $value);
        }

    }

    // -------------------------------------------------------------------------
    public function testNegation()
    {
        $expressions = array("!(10 == 10)", "!1", "!0");
        $this->arrayTest($expressions);
    }

    // -------------------------------------------------------------------------
    public function testStrings()
    {
        $expressions = array('"foo" == "foo"', '"foo\\"bar" == "foo\\"bar"',
            '"f\\"oo" != "f\\"oo"', '"foo\\"" != "foo\\"bar"',
            "'foo\"bar' == 'foo\"bar'", "'foo' == 'foo'",
            "'foo\\'foo' != 'foo'", '"foo\\\\" == "foo\\\\"',
            "'foo\\\\' == 'foo\\\\'");
        $this->arrayTest($expressions);
        $expressions = array('"foo" + "bar"' => 'foobar', "'foo' + 'bar'" => 'foobar',
            '"foo\\"bar" + "baz"' => "foo\"barbaz");
        $expr = new Expression();
        foreach ($expressions as $expression => $value) {
            $result = $expr->evaluate($expression);
            $this->assertEquals($result, $value);
        }
        $result = $expr->evaluate('"foo\"ba\\\\\\"r" =~ /foo/');
        $this->assertEquals((boolean)$result, true);
    }

    // -------------------------------------------------------------------------
    public function testMatchers() {
        $expressions = array('"Foobar" =~ /([fo]+)/i' => 'Foo',
                             '"foobar" =~ /([0-9]+)/' => null,
                             '"1020" =~ /([0-9]+)/'=> '1020',
                             '"1020" =~ /([a-z]+)/' => null);

        foreach ($expressions as $expression => $group) {
            $expr = new Expression();
            $result = $expr->evaluate($expression);
            if ($group == null) {
                $this->assertEquals((boolean)$result, false);
            }
            if ($group != null) {
                $this->assertEquals($expr->evaluate('$1'), $group);
            }
        }
    }

    // -------------------------------------------------------------------------
    public function testVariableAssignment()
    {
        $expressions = array('foo = "bar"' => array('var' => 'foo', 'value' => 'bar'),
            'foo = 10' => array('var' => 'foo', 'value' => 10),
            'foo = 0.1' => array('var' => 'foo', 'value' => 0.1),
            'foo = 10 == 10' => array('var' => 'foo', 'value' => 1),
            'foo = 10 != 10' => array('var' => 'foo', 'value' => 0),
            'foo = "foo" =~ "/[fo]+/"' => array('var' => 'foo', 'value' => 1),
            'foo = 10 + 10' => array('var' => 'foo', 'value' => 20));
        foreach ($expressions as $expression => $object) {
            $expr = new Expression();
            $expr->evaluate($expression);
            $this->assertEquals($expr->evaluate($object['var']), $object['value']);
        }
    }
    // -------------------------------------------------------------------------
    public function testVariables() {
        $expr = new Expression();
        $expr->v += [
            'f_price' => 500,
            'f_width' => 500,
            'f_turndown_0_2_f_count' => 2,
            'f_length_metal_f_length' => 1400
        ];
        $formula = 'f_price*(f_width+f_turndown_0_2_f_count*10)*f_length_metal_f_length/1000000';
        $this->assertEquals($expr->evaluate($formula), 364);
    }
    // -------------------------------------------------------------------------
    public function testJSON()
    {
        $expressions = array(
            array("foo" => "bar"),
            array('foo\\"bar' => "baz"),
            array("foo}" => "bar"),
            array(10, 20, 30, 40),
            array(10, "]", 30),
            array(10, array("foo" => "bar"), 30)
        );
        $expr = new Expression();
        foreach ($expressions as $expression) {
            $json = json_encode($expression);
            $result = $expr->evaluate($json);
            $this->assertEquals(json_encode($result), $json);
        }
        $expressions = array(
            '{"foo":"bar"} == {"foo":"bar"}' => true,
            '{"foo2":"bar2"} == {"foo": "bar"}' => false,
            '{"f}o2":"ba{r2"} != {"foo": "bar"}' => true,
            '[10,20] != [20,30]' => true
        );
        foreach ($expressions as $expression => $value) {
            $result = $expr->evaluate($expression);
            $this->assertEquals((bool)$result, $value);
        }
        $expressions = array(
            '{"foo":"bar"}["foo"]' => "bar",
            '[10,20][0]' => 10
        );
        foreach ($expressions as $expression => $value) {
            $result = $expr->evaluate($expression);
            $this->assertEquals($result, $value);
        }
        $expr->evaluate('foo = {"foo": "bar"}');
        $result = $expr->evaluate('foo["foo"]');
        $this->assertEquals($result, 'bar');
    }

    // -------------------------------------------------------------------------
    public function testCustomFunctions()
    {
        $functions = array('square(x) = x*x' => array(
            'square(10)' => 100,
            'square(10) == 100' => 1
        ),
            'string() = "foo"' => array(
                'string() =~ "/[fo]+/"' => 1,
                'string() == "foo"' => 1,
                'string() != "bar"' => 1
            ),
            'number(x) = x =~ "/^[0-9]+$/"' => array(
                'number("10")' => 1,
                'number("10foo")' => 0
            ),
            'logic(x, y) = x == "foo" || x == "bar"' => array(
                'logic( "foo", 1 )' => 1,
                'logic("bar", 1)' => 1,
                'logic("lorem", 1)' => 0
            ));
        foreach ($functions as $function => $object) {
            $expr = new Expression();
            $expr->evaluate($function);
            foreach ($object as $fn => $value) {
                $this->assertEquals($expr->evaluate($fn), $value);
            }
        }
    }

    // -------------------------------------------------------------------------
    public function testCustomClosures()
    {
        $expr = new Expression();
        $expr->functions['even'] = function ($a) {
            return $a % 2 == 0;
        };
        $values = array(10 => true, 20 => true, 1 => false, 3 => false, 4 => true);
        foreach ($values as $number => $value) {
            $this->assertEquals((bool)$expr->evaluate("even($number)"), $value);
        }
    }

    // -------------------------------------------------------------------------
    public function testBug_1()
    {
        $expr = new Expression();
        $expr->v += [
            'f_price' => 500,
            'f_width' => 500,
            'f_turndown_0_2_f_count' => 2,
            'f_length_metal_f_length' => 1400
        ];
        $formula = 'f_price*(f_width+f_turndown_0_2_f_count*10)*f_length_metal_f_length/1000000';
        $this->assertEquals($expr->evaluate($formula), 364);
    }
    // */

    public function testFormulaWithBrackets()
    {
        $e = new Expression();
        $e->suppress_errors = true;
        $e->functions = [
            'p1' => function ($p1, $p2, $p3, $p4, $p5) {
                return $p1;
            },
            'p2' => function ($p1, $p2, $p3, $p4, $p5) {
                return $p2;
            },
            'p3' => function ($p1, $p2, $p3, $p4, $p5) {
                return $p3;
            },
            'p4' => function ($p1, $p2, $p3, $p4, $p5) {
                return $p4;
            },
            'p5' => function ($p1, $p2, $p3, $p4, $p5) {
                return $p5;
            },
        ];

        $data = [
            'p1(1, 2, 3, 4, 5)' => 1,
            'p2(1, 2, 3, 4, 5)' => 2,
            'p3(1, 2, 3, 4, 5)' => 3,
            'p4(1, 2, 3, 4, 5)' => 4,
            'p5(1, 2, 3, 4, 5)' => 5,
        ];

        foreach ($data as $formula => $result) {
            $this->assertEquals($e->evaluate($formula), $result);
        }
    }

    public function testFunctionOrderParameters()
    {
        $e = new Expression();
        $e->suppress_errors = true;
        $e->functions = [
            'min' => function ($v1, $v2) {
                return min($v1, $v2);
            },
            'max' => function ($v1, $v2) {
                return max($v1, $v2);
            },
        ];

        $data = [
            'max(2,(2+2)*2)' => 8,
            'max((2),(0))' => 2,
            'max((2+2)*2,(3+2)*2)' => 10,
            'max((4+2)*2,(3+2)*2)' => 12,
            'min(max((2+2)*2,(3+2)*2), 1)' => 1,
            'min(1, max(2,3))' => 1,
            'min(1, max((4+2)*2,(3+2)*2))' => 1,
            'min(max((2+2)*2,(3+2)*2), max((4+2)*2,(3+2)*2))' => 10,
            'max( min((2+2)*2,(3+2)*2), min((4+2)*2,(3+2)*2) )' => 10,
            '1 + max( min(2-(2+2)*2,(3+2)*2), min((4+2)*2,(3+2)*2) ) - 2' => 9,
            'max(1,max(2,max(3,4)))' => 4,
            'max( min(1 + 2, 4), 5)' => 5,
            'min(2,(2+2)*2)' => 2,
        ];

        foreach ($data as $formula => $result) {
            $this->assertEquals($e->evaluate($formula), $result);
        }
    }

}