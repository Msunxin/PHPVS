<?php

namespace PHPVS;

class Sanitizer extends Rule
{
    /**
     * @param $callback
     * @return Rule
     */
    public static function callback($callback) {
        return self::instance()->setup(
            self::SANITIZER_CALLBACK,
            array(
                $callback
            )
        );
    }

    /**
     * [int]整数
     *
     * @return Rule
     */
    public static function int() {
        return self::instance()->setup(
            self::SANITIZER_NORMAL,
            array(
                FILTER_SANITIZE_NUMBER_INT,
            )
        );
    }

    /**
     * 非特殊字符
     *
     * @return Rule
     */
    public static function attr() {
        return self::instance()->setup(
            self::SANITIZER_NORMAL,
            array(FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
    }

    /**
     * url字符
     *
     * @return Rule
     */
    public static function url() {
        return self::instance()->setup(
            self::SANITIZER_NORMAL,
            array(FILTER_SANITIZE_URL)
        );
    }

    /**
     * 无html标签
     *
     * @return Rule
     */
    public static function text() {
        return self::instance()->setup(
            self::SANITIZER_NORMAL,
            array(FILTER_SANITIZE_STRING)
        );
    }

    /**
     * 任意字符
     *
     * @return Rule
     */
    public static function raw() {
        return self::instance()->setup(
            self::SANITIZER_NORMAL,
            array(FILTER_UNSAFE_RAW)
        );
    }
}