<?php 
//RBAC用户权限管理 
class CommonAction extends Action {
//初始化接口	
function _initialize() {
        import('ORG.Util.Cookie');
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            import('ORG.Util.RBAC');
            if (!RBAC::AccessDecision()) {		//获取用户访问信息
                //检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
                   redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                    
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 没有权限提示错误信息
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
        }
    }
/* (non-PHPdoc)
	 * @see Action::index()
	 */public function index() {
		// TODO Auto-generated method stub
		}

 
}




?>