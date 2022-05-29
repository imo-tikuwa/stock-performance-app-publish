<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use DateTime;
use DateTimeZone;
use Exception;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Psr\Log\LogLevel;

/**
 * CreateSbiDailyRecord command.
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 * @property \App\Model\Table\CalendarsTable $Calendars
 * @property \App\Model\Table\ConfigsTable $Configs
 * @property \App\Model\Table\DailyRecordsTable $DailyRecords
 */
class CreateSbiDailyRecordCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $this->Accounts = $this->fetchTable('Accounts');
        $this->Calendars = $this->fetchTable('Calendars');
        $this->Configs = $this->fetchTable('Configs');
        $this->DailyRecords = $this->fetchTable('DailyRecords');

        /** @var \App\Model\Entity\Account[] $accounts */
        $accounts = $this->Accounts->find()->toArray();
        if (empty($accounts)) {
            throw new Exception('account not exists.');
        }
        $choices = [];
        foreach ($accounts as $account) {
            $choices[] = (string)$account->id;
        }

        $parser = parent::buildOptionParser($parser);
        $parser->addArgument('account_id', [
            'required' => true,
            'choices' => $choices,
            'help' => 'Prease input account_id.',
        ]);
        $parser->addOption('debug', [
            'boolean' => true,
            'help' => 'Debug mode.',
        ]);

        return $parser;
    }

    /**
     * 何らかのエラーがあったときの共通エラーメッセージ
     *
     * @var string
     */
    private static $abort_common_message = "\nStop the CreateSbiDailyRecordCommand process.";

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->success('Start the CreateSbiDailyRecordCommand process.');

        // 口座ID
        $account_id = $args->getArgument('account_id');

        // 日付
        // 実行したタイミングが日本時間で午前0～9時のとき前日分のデータとして処理する
        // また、営業日チェックも実施する
        $day = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
        if ((int)$day->format('G') <= 9) {
            $day->modify('-1 day');
            $io->out('Since it is before 9 am, it will be processed as the previous day.');
        }
        $day = $day->format('Y-m-d');
        if ($this->Calendars->find()->where(['day' => $day, 'is_holiday' => false])->count() !== 1) {
            $io->abort("{$day} is not a business day." . self::$abort_common_message);
        }

        // 設定を取得、ChromeDriverのパスについてチェック、問題なければ環境変数にChromeDriverのパスを設定する
        try {
            $config = $this->Configs->get(1);
            if (is_null($config->chromedriver_path) || $config->chromedriver_path === '') {
                $io->abort('ChromeDriver path not set.' . self::$abort_common_message);
            } elseif (!file_exists($config->chromedriver_path)) {
                $io->abort('ChromeDriver path is incorrect.' . self::$abort_common_message);
            }
            putenv('webdriver.chrome.driver=' . $config->chromedriver_path);
        } catch (InvalidPrimaryKeyException $e) {
            $io->abort('InvalidPrimaryKeyException was thrown. The configs were not found.' . self::$abort_common_message);
        }

        // SBI証券のログインID/PWを取得、確認
        $sbi_login_id = getenv('SBI_LOGIN_ID');
        $sbi_login_pw = getenv('SBI_LOGIN_PW');
        if ($sbi_login_id === false || $sbi_login_id === '' || $sbi_login_pw === false || $sbi_login_pw === '') {
            $io->abort('Environment variable (SBI_LOGIN_ID, SBI_LOGIN_PW) is not set or empty.' . self::$abort_common_message);
        }
        $io->info('1. Confirmed that the parameters required for processing are complete.');

        // Chromeを起動
        $chrome_options = new ChromeOptions();
        $chrome_options->addArguments([
            '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36',
            '--guest',
        ])->setExperimentalOption('excludeSwitches', ['enable-logging', 'enable-automation']);
        if (!$args->getOption('debug')) {
            $chrome_options->addArguments(['--headless']);
        }
        $driver = ChromeDriver::start(DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $chrome_options));
        $io->info('2. Chrome started successfully.');

        // SBI証券にログインして保有資産の合計金額を取得する
        $trial_num = 0;
        $record = null;
        while ($trial_num < 3) {
            $trial_num++;
            $driver->get('https://site2.sbisec.co.jp/ETGate/?_ControlID=WPLETacR001Control');
            try {
                // ログインセッションが存在しない場合ログイン画面が表示されるのでログイン情報を入力してログインボタンをクリック
                $driver->findElement(WebDriverBy::cssSelector("input[name='user_id']"))->sendKeys($sbi_login_id);
                $driver->findElement(WebDriverBy::cssSelector("input[name='user_password']"))->sendKeys($sbi_login_pw);
                $driver->findElement(WebDriverBy::cssSelector("input[name='ACT_login']"))->click();
            } catch (NoSuchElementException $e) {
                // 口座管理画面が開けた場合 = ログイン情報の入力欄が見つからずにNoSuchElementExceptionがスローされる
                // 口座管理画面から目的の値を取得する
                try {
                    $tr_selector_path = 'body > div:nth-child(1) > table > tbody > tr > td:nth-child(1) > table > tbody > tr:nth-child(2) > td > table:nth-child(1) > tbody > tr > td > form > table:nth-child(3) > tbody > tr:nth-child(1) > td:nth-child(2) > table:nth-child(19) > tbody > tr > td:nth-child(1) > table:nth-child(7) > tbody > tr:nth-child(8)';
                    foreach ($driver->findElements(WebDriverBy::cssSelector($tr_selector_path)) as $element) {
                        if ($element->findElement(WebDriverBy::cssSelector('td:nth-child(1)'))->getText() == '計') {
                            $record = $element->findElement(WebDriverBy::cssSelector('td:nth-child(2) > div > b'))->getText();
                            if (strlen($record) > 0) {
                                $record = (int)str_replace(',', '', $record);
                                break 2;
                            }
                        }
                    }
                } catch (NoSuchElementException $e) {
                    $io->abort('Failed to get the "total" line from the SBI account management screen.' . self::$abort_common_message);
                }
            }
        }
        $driver->close();
        if (is_null($record)) {
            $io->abort('Failed to get the total amount of SBI account management screen.' . self::$abort_common_message);
        }
        $io->info('3. Data acquisition from SBI was completed successfully.');

        // データ登録 or 更新
        $entity = $this->DailyRecords->find()->where(['account_id' => $account_id, 'day' => $day])->first();
        if (is_null($entity)) {
            $entity = $this->DailyRecords->newEmptyEntity();
        }
        assert($entity instanceof \App\Model\Entity\DailyRecord);
        $entity = $this->DailyRecords->patchEntity($entity, [
            'account_id' => $account_id,
            'day' => $day,
            'record' => $record,
        ])
        ->setDirty('record', true);
        if ($entity->hasErrors()) {
            $this->log(implode("\n", $entity->getErrorMessages()), LogLevel::ERROR);
            $io->abort('Something Error. Please check 「cli-error.log」.' . self::$abort_common_message);
        }
        $result = $this->DailyRecords->save($entity);
        $io->info('4. The Daily Records data update is complete.');

        // 実行結果出力
        $io->helper('Table')->output([
            [
                'target day',
                'account_id',
                'record',
                'save status',
            ],
            [
                $day,
                "<text-right>{$account_id}</text-right>",
                "<text-right>{$record}</text-right>",
                $result instanceof \App\Model\Entity\DailyRecord ? 'true' : 'false',
            ],
        ]);
        $io->success('The process of CreateSbiDailyRecordCommand is completed succesfully.');
    }
}
