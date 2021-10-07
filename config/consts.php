<?php
/** サイト名 */
const SITE_NAME = 'StockPerformance';

/** サイト名(短) */
const SITE_NAME_SHORT = 'SP';

/** 管理者のアカウントID(権限チェック不要で全ての機能にアクセス可能) */
const SUPER_USER_ID = 1;

/** Read権限 */
const ROLE_READ = 'READ';

/** Write権限 */
const ROLE_WRITE = 'WRITE';

/** Delete権限 */
const ROLE_DELETE = 'DELETE';

/** CsvExport権限 */
const ROLE_CSV_EXPORT = 'CSV_EXPORT';

/** CsvImport権限 */
const ROLE_CSV_IMPORT = 'CSV_IMPORT';

/** ExcelExport権限 */
const ROLE_EXCEL_EXPORT = 'EXCEL_EXPORT';

/** ExcelImport権限 */
const ROLE_EXCEL_IMPORT = 'EXCEL_IMPORT';

/** indexアクション */
const ACTION_INDEX = 'index';

/** viewアクション */
const ACTION_VIEW = 'view';

/** addアクション */
const ACTION_ADD = 'add';

/** editアクション */
const ACTION_EDIT = 'edit';

/** deleteアクション */
const ACTION_DELETE = 'delete';

/** fileUploadアクション */
const ACTION_FILE_UPLOAD = 'fileUpload';

/** fileDeleteアクション */
const ACTION_FILE_DELETE = 'fileDelete';

/** csvExportアクション */
const ACTION_CSV_EXPORT = 'csvExport';

/** csvImportアクション */
const ACTION_CSV_IMPORT = 'csvImport';

/** excelExportアクション */
const ACTION_EXCEL_EXPORT = 'excelExport';

/** excelImportアクション */
const ACTION_EXCEL_IMPORT = 'excelImport';

/** 権限エラーメッセージ */
const MESSAGE_AUTH_ERROR = '権限エラーが発生しました';

/** エクセルファイル(.xlsx)のContent-Type */
const EXCEL_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

/** Google Authenticatorのシークレットキーの長さ */
const GOOGLE_AUTHENTICATOR_SECRET_KEY_LEN = 32;

/** 認証コードのinput名 */
const GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME = 'secret';

/** 月ごと表示モードON */
const DISPLAY_ONLY_MONTH_ON = '01';

/** 初期資産額表示ON */
const DISPLAY_INIT_RECORD_ON = '01';