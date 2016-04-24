<?php

namespace PHPVS;

class Validator extends Rule
{
    /**
     * @param $callback
     * @return Rule
     */
    public static function callback($callback) {
        return self::instance()->setup(
            self::VALIDATOR_CALLBACK,
            array(
                $callback
            )
        );
    }

    /**
     * 非负整数
     *
     * @return Rule
     */
    public static function id() {
        return self::instance()->setup(
            self::VALIDATOR_NORMAL,
            array(
                FILTER_VALIDATE_INT,
                array(
                    'min_range' => 1,
                ),
            )
        );
    }

    /**
     * id，多个可用逗号分隔
     *
     * @return Rule
     */
    public static function ids() {
        return self::instance()->setup(
            self::VALIDATOR_PREG,
            array(
                "~^\\d+(,\\d+)*$~",
            )
        );
    }

    /**
     * 整数
     *
     * @param int $min
     * @param int $max
     * @return Rule
     */
    public static function int($min = -2147483647, $max = 2147483647) {
        return self::instance()->setup(
            self::VALIDATOR_NORMAL,
            array(
                FILTER_VALIDATE_INT,
                array(
                    'min_range' => $min,
                    'max_range' => $max,
                ),
            )
        )->defaulted(0);
    }
}
