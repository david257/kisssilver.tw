<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\View;

/**
 * 控制器基礎類
 */
abstract class BaseController
{
    /**
     * Request實例
     * @var \think\Request
     */
    protected $request;

    /**
     * 應用實例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批次驗證
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中介軟體
     * @var array
     */
    protected $middleware = [];

    /**
     * 構造方法
     * @access public
     * @param  App  $app  應用物件
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $data["current_domain"] = request()->domain();
        $config = get_setting();
        $setting = $config["setting"];
        $data['sitetitle'] = isset($setting["site"]['title'])?$setting["site"]['title']:'';
        $data['sitekeywords'] = isset($setting["site"]['meta_keywords'])?$setting["site"]['meta_keywords']:'';
        $data['sitedesc'] = isset($setting["site"]['meta_desc'])?$setting["site"]['meta_desc']:'';
        View::assign($data);
    }

    /**
     * 驗證資料
     * @access protected
     * @param  array        $data     數據
     * @param  string|array $validate 驗證器名或者驗證規則陣列
     * @param  array        $message  提示資訊
     * @param  bool         $batch    是否批次驗證
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持場景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批次驗證
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}
