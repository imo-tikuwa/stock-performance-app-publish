# stock-performance-app-publish

## このリポジトリについて、できること
 - 日々の株資産の推移を記録、確認するために作成したツールです
   - 口座の資産額を1日1回記録することでその金額の推移をチャート表示します
   - 口座への入出金でチャートが崩れるのを防止する機能があります
   - 複数の口座を登録することができます(チャートは1本にまとまります)
 - PHP8、MySQL5.7以上が必要です
 - composerが必要です

![image](https://user-images.githubusercontent.com/48991931/136362481-2c875d54-9c25-4966-bd78-70686d3459ed.png)

## セットアップ手順
以下はWindowsPCにおける手順になります。
1. リポジトリをクローン
```
git clone https://github.com/imo-tikuwa/stock-performance-app-publish.git
cd stock-performance-app-publish
```

2. composerでPHPのライブラリをインストール
```
composer install
```

3. アプリケーションで使用するデータベースを作成
   - MySQLクライアントで以下実行
```
CREATE DATABASE [DB名] CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER ON [DB名].* TO '[DBユーザー]'@'localhost' IDENTIFIED BY '[DBパスワード]';
FLUSH PRIVILEGES;
```

4. configディレクトリに.envファイルを作成
   - DATABASE_NAME、DATABASE_USER、DATABASE_PASSはそれぞれ手順3と同じものを入力  
   - DATABASE_PORT、SECURITY_SALTは必要に応じて修正
```
#!/usr/bin/env bash

export APP_NAME="StockPerformance"
export DEBUG="false"
export APP_ENCODING="UTF-8"
export APP_DEFAULT_LOCALE="ja_JP"
export APP_DEFAULT_TIMEZONE="Asia/Tokyo"
export SECURITY_SALT="__SALT__"

export DATABASE_HOST="localhost"
export DATABASE_NAME="[DB名]"
export DATABASE_PORT="3306"
export DATABASE_USER="[DBユーザー]"
export DATABASE_PASS="[DBパスワード]"

export SBI_LOGIN_ID=""
export SBI_LOGIN_PW=""
```

5. マイグレート&シード実行、管理者アカウント生成コマンド実行
   - 便宜上ログインアカウントとしてメールアドレスを入力しますがメールを送信することはありません
```
bin\cake.bat migrations migrate
bin\cake.bat execute_all_migrations_and_seeds
bin\cake.bat recreate_admin [メールアドレス] [ログインパスワード]
```

6. ビルトインサーバーを起動して動作確認
```
bin\cake.bat server

起動後ブラウザで http://localhost:8765/admin にアクセス
手順5で入力した[メールアドレス]と[ログインパスワード]をログインIDとパスワードに入力してログインできればOK
```

## 使い方
1. まず、初回のみ[口座](http://localhost:8765/admin/accounts)を登録
2. 日々の中で[入出金](http://localhost:8765/admin/deposits)があれば登録
3. 日々の資産について[資産記録](http://localhost:8765/admin/daily-records)で登録。
   - 口座が1件以上登録してある必要あり
4. [分析](http://localhost:8765/admin/display)で日々の資産をチャート表示します。
   - 口座が1件以上登録してある必要あり
   - [設定](http://localhost:8765/admin/configs/edit)で分析画面に一覧表示する項目やチャートの色を設定することができます

## その他（SBI証券の資産を資産記録に登録するコマンドについて）
 - 前提として以下の作業が必要です。
   - config/.envのSBI_LOGIN_IDとSBI_LOGIN_PWを設定
     - もしビルトインサーバーが起動中の場合は再起動の必要あり
   - [設定](http://localhost:8765/admin/configs/edit)の「ChromeDriverのパス」にchromedriver.exeのフルパスを入力

 - 上記設定を済ませた状態で以下のコマンドを実行
   - [account_id]には[口座](http://localhost:8765/admin/accounts)で登録したSBI証券のIDを入力
```
bin\cake.bat create_sbi_daily_record [account_id]
```

## その他（営業日カレンダーについて）
 - セットアップ手順を実行した時点で2021年と2022年の営業日データは入っています
 - 2023年以降は以下のコマンドでカレンダーのデータを最新化する必要があります
   - [year]には4桁の年を入力します
   - 祝日が決まるのがいつなのかわからないので現在年もしくは現在年+1の入力制限があります 
```
bin\cake.bat create_calendars [year]
```
