<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\FrozenDate;
use DateInterval;
use DateTime;

/**
 * CreateCalendars command.
 *
 * @property \App\Model\Table\CalendarsTable $Calendars
 */
class CreateCalendarsCommand extends Command
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
        $parser = parent::buildOptionParser($parser);

        $datetime = new DateTime();
        $parser->addArgument('year', [
            'required' => true,
            'choices' => [
                $datetime->format('Y'),
                $datetime->add(new DateInterval('P1Y'))->format('Y')
            ],
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->Calendars = $this->fetchTable('Calendars');

        // 対象年
        $year = $args->getArgument('year');

        // 対象年のデータがある場合は終了
        $result = $this->Calendars->findByYear($year);
        if (!empty($result)) {
            $io->abort("{$year} business days already exist.");
        }

        // 日本の祝日を取得。レスポンスが200番以外の場合は終了
        $ch = curl_init("https://holidays-jp.github.io/api/v1/{$year}/date.json");
        assert($ch !== false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $holidays = curl_exec($ch);
        $result = curl_getinfo($ch);
        if ($result['http_code'] !== 200) {
            $io->abort('holidays not found.');
        }
        curl_close($ch);
        assert(is_string($holidays));
        $holidays = json_decode($holidays, true);

        // 1日1日からループして12月31日までの営業日データを登録する
        $start_datetime = new DateTime("{$year}-01-01");
        $end_datetime = new DateTime("{$year}-12-31");
        $insert_count = 0;
        while ((int)($start_datetime->diff($end_datetime)->format('%R%a')) >= 0) {
            $current_ymd = $start_datetime->format('Y-m-d');
            $day_of_week_num = $start_datetime->format('w');
            $entity = $this->Calendars->newEmptyEntity();
            if (array_key_exists($current_ymd, $holidays)) {
                $entity->is_holiday = true;
                $entity->holiday_name = $holidays[$current_ymd];
            } elseif ($day_of_week_num === '0' || $day_of_week_num === '6' || $current_ymd === "{$year}-01-01" || $current_ymd === "{$year}-12-31") {
                $entity->is_holiday = true;
            }
            $entity->day = FrozenDate::parse($current_ymd);
            $this->Calendars->save($entity);
            $start_datetime->add(new DateInterval('P1D'));
            $insert_count++;
        }

        $io->success("registered {$insert_count} days.");
    }
}
