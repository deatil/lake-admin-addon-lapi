<?php

namespace app\lapi\contracts;

use lake\module\controller\HomeBase;

use app\lapi\traits\Lapi as LapiTrait;

/*
 * API基础类
 *
 * @create 2020-8-15
 * @author deatil
 */
abstract class Lapi extends HomeBase
{
    use LapiTrait;
}
