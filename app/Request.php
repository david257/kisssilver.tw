<?php
namespace app;

// 應用請求對象類
class Request extends \think\Request
{
    protected $filter = ['trim'];
}
