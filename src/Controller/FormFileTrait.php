<?php
namespace App\Controller;

use Cake\Core\Exception\CakeException;
use Cake\Utility\Inflector;
use Intervention\Image\ImageManagerStatic;

/**
 * FormFileTrait
 *
 * ファイル項目に関するアクションをまとめたTrait
 * 表側でbootstrap-fileinput(https://github.com/kartik-v/bootstrap-fileinput)
 * を使用しているのでそちらの仕様に合わせたjsonを返す
 */
trait FormFileTrait
{
    /**
     * Ajaxファイルアップロード処理
     * @param string $input_name input[type=file]の要素名
     * @return static
     */
    public function fileUpload($input_name = null)
    {
        $response = $this->getResponse();
        $response_data = [];
        try {
            $this->viewBuilder()->disableAutoLayout();
            $this->autoRender = false;
            if (!$this->getRequest()->is(['post'])) {
                throw new CakeException("不正なリクエストです。Invalid Request.");
            } elseif (is_null($input_name)) {
                throw new CakeException("プログラムエラーが発生しました。Invalid Request.");
            }

            // App.uploadedFilesAsObjectsの値によってオブジェクトと配列の条件分岐が必要
            $file = $this->getRequest()->getData($input_name);
            if (null !== _code('App.uploadedFilesAsObjects') && _code('App.uploadedFilesAsObjects') === false) {
                $org_name = $file['name'];
                $file_size = $file['size'];
                $tmp_name = $file['tmp_name'];
            } else {
                /** @var \Laminas\Diactoros\UploadedFile $file */
                $org_name = $file->getClientFilename();
                $file_size = $file->getSize();
                $tmp_name = $file->getStream()->getMetadata('uri');
            }

            if (empty($org_name) || empty($tmp_name)) {
                throw new CakeException("プログラムエラーが発生しました。Empty File.");
            }

            $extension = pathinfo($org_name, PATHINFO_EXTENSION);
            if (empty($extension)) {
                throw new CakeException("プログラムエラーが発生しました。Invalid Extension.");
            }
            $extension = strtolower($extension);

            // 拡張子チェック
            $allow_file_extensions = _code("FileUploadOptions.{$this->name}.{$input_name}.allow_file_extensions", null);
            if (!is_null($allow_file_extensions) && !in_array($extension, $allow_file_extensions, true)) {
                throw new CakeException("アップロードされたファイルの拡張子が許可されてません。Invalid Extension.");
            }

            // ファイルアップロード
            $new_file_key = sha1(uniqid(rand()));
            $cur_name = $new_file_key . "." . $extension;
            $upload_to = UPLOAD_FILE_BASE_DIR . DS . Inflector::underscore($this->name) . DS . $cur_name;
            if (!rename($tmp_name, $upload_to)) {
                throw new CakeException("ファイルのアップロードに失敗しました。Upload Failed.");
            }

            // アップロードされたファイルが画像かつ、サムネイル生成のオプションが有効なときサムネ生成
            if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif'], true) && _code("FileUploadOptions.{$this->name}.{$input_name}.create_thumbnail", false)) {
                $file_upload_options = _code("FileUploadOptions.{$this->name}.{$input_name}");
                $thumbnail_width = (isset($file_upload_options['thumbnail_width']) && is_numeric($file_upload_options['thumbnail_width'])) ? $file_upload_options['thumbnail_width'] : null;
                $thumbnail_height = (isset($file_upload_options['thumbnail_height']) && is_numeric($file_upload_options['thumbnail_height'])) ? $file_upload_options['thumbnail_height'] : null;
                $thumbnail_aspect_ratio_keep = (isset($file_upload_options['thumbnail_aspect_ratio_keep']) && $file_upload_options['thumbnail_aspect_ratio_keep'] === true) ? true : false;
                $thumbnail_quality = (isset($file_upload_options['thumbnail_quality']) && is_numeric($file_upload_options['thumbnail_quality'])) ? $file_upload_options['thumbnail_quality'] : 90;
                $thumb_to = UPLOAD_FILE_BASE_DIR . DS . Inflector::underscore($this->name) . DS . $new_file_key . "_thumb." . $extension;
                if ($thumbnail_aspect_ratio_keep) {
                    ImageManagerStatic::make($upload_to)->resize($thumbnail_width, $thumbnail_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumb_to, $thumbnail_quality);
                } else {
                    ImageManagerStatic::make($upload_to)->resize($thumbnail_width, $thumbnail_height)->save($thumb_to, $thumbnail_quality);
                }
            }

            $delete_action = "";
            $prefix = $this->getRequest()->getParam('prefix');
            if (!empty($prefix)) {
                $delete_action .= '/' . Inflector::underscore($prefix);
            }
            $delete_action .= '/' . Inflector::underscore($this->name) . '/file-delete/' . $input_name;

            $url = $this->getRequest()->is('ssl') ? 'https://' : 'http://';
            $url .= is_null($this->getRequest()->host()) ? 'localhost' : $this->getRequest()->host();
            $port = $this->getRequest()->port();
            if (!is_null($port) && $port != 80 && $port != 443) {
                $url .= ':' . $port;
            }

            $response_data += [
                'initialPreview' => [
                    $url . '/' . UPLOAD_FILE_BASE_DIR_NAME . '/' . Inflector::underscore($this->name) . '/' . $cur_name
                ],
                'initialPreviewConfig' => [
                    0 => [
                        'caption' => $org_name,
                        'size' => $file_size,
                        'url' => $delete_action,
                        'key' => $cur_name,
                    ],
                ],
                'append' => true,
                'org_name' => $org_name,
                'cur_name' => $cur_name,
                'size' => $file_size,
                'delete_url' => $delete_action,
                'key' => $cur_name,
            ];
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $response_data['error'] = $e->getMessage();
            $response = $response->withStatus(400);
        }

        return $response->withType('json')->withStringBody(json_encode($response_data));
    }

    /**
     *  Ajaxファイル削除処理
     *
     * ※実際には削除しないので注意
     * 今のところbootstrap-fileinputプラグイン用の削除完了ステータスを返すだけ
     *
     * @param string $input_name input[type=file]の要素名
     * @throws Exception
     * @return static
     */
    public function fileDelete($input_name = null)
    {
        $response = $this->getResponse();
        $response_data = [];
        try {
            $this->viewBuilder()->disableAutoLayout();
            $this->autoRender = false;

            if (!$this->getRequest()->is(['post', 'delete'])) {
                throw new CakeException("不正なリクエストです。Invalid Request.");
            }

            $key = $this->getRequest()->getData('key');
            if (is_null($key)) {
                $this->log('削除対象のファイルキーが存在しません');
                throw new CakeException("プログラムエラーが発生しました。");
            }

            if (!file_exists(UPLOAD_FILE_BASE_DIR . DS . Inflector::underscore($this->name) . DS . $key)) {
                $this->log('削除対象の実ファイルが存在しません');
                throw new CakeException("プログラムエラーが発生しました。");
            }

            $response_data['status'] = true;
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $response_data['error'] = $e->getMessage();
            $response = $response->withStatus(400);
        }

        return $response->withType('json')->withStringBody(json_encode($response_data));
    }
}
