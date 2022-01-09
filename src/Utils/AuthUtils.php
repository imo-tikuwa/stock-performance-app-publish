<?php
namespace App\Utils;

use App\Model\Entity\Admin;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Exception;
use Psr\Log\LogLevel;

class AuthUtils
{

    /**
     * Adminsテーブルの管理者判定
     * @param ServerRequest $request リクエスト情報
     * @return bool
     */
    public static function isSuperUser(ServerRequest $request = null)
    {
        if (is_null($request)) {
            return false;
        }
        $super_user = false;
        try {
            $super_user = (SUPER_USER_ID === $request->getSession()->read('Auth.Admin.id'));
        } catch (Exception $e) {
            Log::write(LogLevel::ERROR, $e->getMessage());

            return false;
        }

        return $super_user;
    }

    /**
     *
     * リクエスト先へのアクセスに必要な権限を持っているかチェック
     *
     * @param ServerRequest $request リクエスト情報
     * @param array $properties  権限チェックプロパティ $requestオブジェクト以外の権限チェックをしたいとき
     *                           'controller' => '[コントローラ名]',
     *                           'action' => '[アクション名]'
     *                           を配列でセットする
     *
     * @return bool
     */
    public static function hasRole(ServerRequest $request = null, array $properties = [])
    {
        if (is_null($request)) {
            return false;
        }

        // 管理者は全機能にアクセス可
        if (self::isSuperUser($request)) {
            return true;
        }

        // チェック対象のコントローラとアクションの設定
        $controller = $request->getParam('controller');
        if (isset($properties['controller'])) {
            $controller = $properties['controller'];
        }
        $action = $request->getParam('action');
        if (isset($properties['action'])) {
            $action = $properties['action'];
        }

        $privileges = $request->getSession()->read('Auth.Admin.privilege.' . $controller);
        if (empty($privileges)) {
            return false;
        }

        // 子テーブルの動的なフォーム追加を行うアクションはWRITE権限があるときアクセス可
        if (str_starts_with($action, 'append') && str_ends_with($action, 'Row')) {
            return in_array(ROLE_WRITE, $privileges, true);
        }

        switch ($action) {
            case ACTION_INDEX:
            case ACTION_VIEW:
                $has_role = in_array(ROLE_READ, $privileges, true);
                break;
            case ACTION_ADD:
            case ACTION_EDIT:
            case ACTION_FILE_UPLOAD:
            case ACTION_FILE_DELETE:
                $has_role = in_array(ROLE_WRITE, $privileges, true);
                break;
            case ACTION_DELETE:
                $has_role = in_array(ROLE_DELETE, $privileges, true);
                break;
            case ACTION_CSV_EXPORT:
                $has_role = in_array(ROLE_CSV_EXPORT, $privileges, true);
                break;
            case ACTION_CSV_IMPORT:
                $has_role = in_array(ROLE_CSV_IMPORT, $privileges, true);
                break;
            case ACTION_EXCEL_EXPORT:
                $has_role = in_array(ROLE_EXCEL_EXPORT, $privileges, true);
                break;
            case ACTION_EXCEL_IMPORT:
                $has_role = in_array(ROLE_EXCEL_IMPORT, $privileges, true);
                break;
                // 上記以外のアクションは一律アクセス可能
            default:
                $has_role = true;
        }

        return $has_role;
    }

    /**
     * 二段階認証用のQRコード名を返す
     * アカウントのIDを付加した文字列を返す
     *
     * @param Admin $admin アカウント情報
     * @return string
     */
    public static function getTwoFactorQrName(Admin $admin = null)
    {
        if (is_null($admin)) {
            return '';
        }

        return "admin_id = {$admin->id}";
    }
}
