<?php

namespace app\lapi\boot;

use lake\module\controller\HomeBase;

use app\lapi\trait\Lapi as LapiTrait;

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
