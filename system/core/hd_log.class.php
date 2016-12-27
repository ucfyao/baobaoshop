<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class hd_log {

    const EMERG     = 'EMERG';
    const ALERT     = 'ALERT';
    const CRIT      = 'CRIT';
    const ERR       = 'ERR';
    const WARN      = 'WARN';
    const NOTICE    = 'NOTIC';
    const INFO      = 'INFO';
    const DEBUG     = 'DEBUG';
    const SQL       = 'SQL';
    
	static function record($info,$level = '',$record = '') {
        return true;
	}

    static function write($message) {
        return true;
    }

}